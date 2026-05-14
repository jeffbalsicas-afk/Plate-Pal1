<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->catererUser();
        $displayName = $user->business_name ?? $user->name;
        $initials = $user->initials;
        $selectedTab = $request->query('tab', 'all');
        $allowedTabs = ['all', 'public', 'hidden', 'featured'];
        $search = trim((string) $request->query('search', ''));

        if (! in_array($selectedTab, $allowedTabs, true)) {
            $selectedTab = 'all';
        }

        $baseQuery = Review::with(['client', 'booking'])
            ->forCaterer($user->id);

        $reviewCounts = [
            'all' => (clone $baseQuery)->count(),
            'public' => (clone $baseQuery)->where('status', 'public')->count(),
            'hidden' => (clone $baseQuery)->where('status', 'hidden')->count(),
            'featured' => (clone $baseQuery)->where('is_featured', true)->count(),
        ];

        $ratingCounts = [];
        for ($rating = 5; $rating >= 1; $rating--) {
            $ratingCounts[$rating] = (clone $baseQuery)->where('rating', $rating)->count();
        }

        $averageRating = (clone $baseQuery)->avg('rating') ?? 0;

        $reviews = (clone $baseQuery)
            ->when($selectedTab === 'public', fn ($query) => $query->where('status', 'public'))
            ->when($selectedTab === 'hidden', fn ($query) => $query->where('status', 'hidden'))
            ->when($selectedTab === 'featured', fn ($query) => $query->where('is_featured', true))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('reviewer_name', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%")
                        ->orWhere('package_name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('reviewed_at')
            ->latest()
            ->get();

        $unreadMessages = Message::where('caterer_id', $user->id)
            ->where('is_read', false)
            ->where('sender', 'client')
            ->count();

        return response()
            ->view('caterer.reviews', compact(
                'user',
                'displayName',
                'initials',
                'selectedTab',
                'search',
                'reviews',
                'reviewCounts',
                'ratingCounts',
                'averageRating',
                'unreadMessages'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function updateAutoFeature(Request $request)
    {
        $user = $this->catererUser();
        $enabled = $request->boolean('auto_feature_reviews');

        DB::transaction(function () use ($user, $enabled) {
            $user->forceFill(['auto_feature_reviews' => $enabled])->save();

            if ($enabled) {
                Review::forCaterer($user->id)
                    ->where('rating', 5)
                    ->where('status', 'public')
                    ->update([
                        'is_featured' => true,
                        'is_auto_featured' => true,
                    ]);

                return;
            }

            Review::forCaterer($user->id)
                ->where('is_auto_featured', true)
                ->update([
                    'is_featured' => false,
                    'is_auto_featured' => false,
                ]);
        });

        $this->refreshRating($user->id);

        return back()->with('success', 'Auto-feature setting updated.');
    }

    public function updateVisibility(Request $request, Review $review)
    {
        $user = $this->catererUser();
        $this->authorizeReview($review, $user->id);

        $validated = $request->validate([
            'status' => ['required', 'in:public,hidden'],
        ]);

        $updates = ['status' => $validated['status']];

        if ($validated['status'] === 'hidden') {
            $updates['is_featured'] = false;
            $updates['is_auto_featured'] = false;
        } elseif ($user->auto_feature_reviews && $review->rating === 5) {
            $updates['is_featured'] = true;
            $updates['is_auto_featured'] = true;
        }

        $review->update($updates);
        $this->refreshRating($user->id);

        return back()->with('success', $validated['status'] === 'public' ? 'Review is now public.' : 'Review has been hidden.');
    }

    public function updateFeatured(Request $request, Review $review)
    {
        $user = $this->catererUser();
        $this->authorizeReview($review, $user->id);

        $validated = $request->validate([
            'is_featured' => ['required', 'boolean'],
        ]);

        abort_if($review->status !== 'public' && $validated['is_featured'], 422, 'Only public reviews can be featured.');

        $review->update([
            'is_featured' => (bool) $validated['is_featured'],
            'is_auto_featured' => false,
        ]);

        return back()->with('success', $validated['is_featured'] ? 'Review added to featured list.' : 'Review removed from featured list.');
    }

    public function reply(Request $request, Review $review)
    {
        $user = $this->catererUser();
        $this->authorizeReview($review, $user->id);

        $validated = $request->validate([
            'caterer_reply' => ['required', 'string', 'max:1000'],
        ]);

        $review->update([
            'caterer_reply' => $validated['caterer_reply'],
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Reply saved.');
    }

    public function report(Request $request, Review $review)
    {
        $user = $this->catererUser();
        $this->authorizeReview($review, $user->id);

        $validated = $request->validate([
            'report_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $review->update([
            'reported_at' => now(),
            'report_reason' => $validated['report_reason'] ?? 'Reported by caterer',
        ]);

        return back()->with('success', 'Review reported for admin review.');
    }

    private function catererUser()
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'caterer', 403);

        return $user;
    }

    private function authorizeReview(Review $review, int $catererId): void
    {
        abort_unless($review->caterer_id === $catererId, 403);
    }

    private function refreshRating(int $catererId): void
    {
        $rating = Review::forCaterer($catererId)->public()->avg('rating') ?? 0;
        $count = Review::forCaterer($catererId)->public()->count();

        auth()->user()->forceFill([
            'rating' => round($rating, 2),
            'reviews_count' => $count,
        ])->save();
    }
}
