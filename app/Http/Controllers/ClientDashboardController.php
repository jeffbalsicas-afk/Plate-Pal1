<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Message;
use App\Models\Review;
use App\Models\SavedCaterer;
use App\Models\User;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $activeBookings    = Booking::where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed'])->count();
        $completedEvents   = Booking::where('user_id', $user->id)->where('status', 'completed')->count();
        $unreadMessages    = Message::where('user_id', $user->id)->where('is_read', false)->where('sender', 'caterer')->count();

        $upcomingBookings  = Booking::with('caterer')
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('event_date')
            ->take(3)
            ->get();

        $recentMessages    = Message::with('caterer')
            ->where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        $featuredCaterers = User::where('role', 'caterer')
            ->where('approval_status', 'approved')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('rating')
            ->limit(3)
            ->get()
            ->map(fn ($caterer) => [
                'name'     => $caterer->business_name ?? $caterer->name,
                'location' => $caterer->barangay ?? 'Tagum City',
                'cuisine'  => $caterer->cuisine ?? 'Filipino Cuisine',
                'rating'   => $caterer->rating ?? 4.5,
                'reviews'  => $caterer->reviews_count ?? 0,
                'price'    => 'PHP ' . ($caterer->price_min ?? 300) . '-' . ($caterer->price_max ?? 500) . '/head',
                'image'    => $caterer->profile_image ?? '/assets/Pusit.png',
            ]);

        return response()
            ->view('client.dashboard', compact(
                'user',
                'activeBookings',
                'completedEvents',
                'unreadMessages',
                'upcomingBookings',
                'recentMessages',
                'featuredCaterers'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function browse()
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

        $query = User::where('role', 'caterer')
            ->where('approval_status', 'approved')
            ->where('is_active', true);

        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%$search%")
                  ->orWhere('cuisine', 'like', "%$search%")
                  ->orWhere('barangay', 'like', "%$search%");
            });
        }

        if (request('cuisine')) {
            $query->where('cuisine', request('cuisine'));
        }

        if (request('price_min') && request('price_max')) {
            $minPrice = (int) request('price_min');
            $maxPrice = (int) request('price_max');
            $query->whereBetween('price_min', [$minPrice, $maxPrice])
                  ->orWhereBetween('price_max', [$minPrice, $maxPrice]);
        }

        if (request('rating')) {
            $rating = (float) request('rating');
            $query->where('rating', '>=', $rating);
        }

        if (request('barangay')) {
            $query->where('barangay', request('barangay'));
        }

        if (request('sort') === 'rating_high') {
            $query->orderByDesc('rating');
        } elseif (request('sort') === 'rating_low') {
            $query->orderBy('rating');
        } elseif (request('sort') === 'price_low') {
            $query->orderBy('price_min');
        } elseif (request('sort') === 'price_high') {
            $query->orderByDesc('price_max');
        } else {
            $query->orderByDesc('rating');
        }

        $caterers = $query->paginate(12);
        $cuisines = User::where('role', 'caterer')->where('is_active', true)->distinct()->pluck('cuisine');
        $barangays = User::where('role', 'caterer')->where('is_active', true)->distinct()->pluck('barangay');

        return response()
            ->view('client.browse', compact(
                'user',
                'initials',
                'caterers',
                'cuisines',
                'barangays'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function browsePubic()
    {
        $caterers = User::where('role', 'caterer')
            ->where('approval_status', 'approved')
            ->where('is_active', true)
            ->orderByDesc('rating')
            ->paginate(12);

        return response()
            ->view('client.browse-public', compact('caterers'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function bookings()
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
        $selectedStatus = request('status', 'all');
        $allowedStatuses = ['all', 'confirmed', 'pending', 'completed'];

        if (! in_array($selectedStatus, $allowedStatuses, true)) {
            $selectedStatus = 'all';
        }

        $baseQuery = Booking::with('caterer')->where('user_id', $user->id);

        $statusCounts = [
            'all' => (clone $baseQuery)->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
        ];

        $bookings = (clone $baseQuery)
            ->when($selectedStatus !== 'all', fn ($query) => $query->where('status', $selectedStatus))
            ->orderBy('event_date')
            ->get();

        $unreadMessages = Message::where('user_id', $user->id)
            ->where('is_read', false)
            ->where('sender', 'caterer')
            ->count();

        return response()
            ->view('client.bookings', compact(
                'user',
                'initials',
                'bookings',
                'selectedStatus',
                'statusCounts',
                'unreadMessages'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function bookingDetails(Booking $booking)
    {
        $user = auth()->user();

        abort_unless($booking->user_id === $user->id, 403);

        $booking->load('caterer');
        $existingReview = Review::where('booking_id', $booking->id)->first();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

        $statusCounts = [
            'all' => Booking::where('user_id', $user->id)->count(),
            'confirmed' => Booking::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'pending' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        $unreadMessages = Message::where('user_id', $user->id)
            ->where('is_read', false)
            ->where('sender', 'caterer')
            ->count();

        return response()
            ->view('client.booking-details', compact(
                'user',
                'initials',
                'booking',
                'existingReview',
                'statusCounts',
                'unreadMessages'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function storeReview(Request $request, Booking $booking)
    {
        $user = auth()->user();

        abort_unless($booking->user_id === $user->id, 403);
        abort_unless($booking->status === 'completed', 422, 'Only completed bookings can be reviewed.');

        $booking->load('caterer');

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:1000'],
            'package_name' => ['nullable', 'string', 'max:120'],
        ]);

        Review::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'client_id' => $user->id,
                'caterer_id' => $booking->caterer_id,
                'reviewer_name' => $user->name,
                'package_name' => $validated['package_name'] ?? null,
                'title' => $validated['title'],
                'body' => $validated['body'],
                'rating' => $validated['rating'],
                'status' => 'public',
                'is_featured' => $booking->caterer?->auto_feature_reviews && (int) $validated['rating'] === 5,
                'is_auto_featured' => $booking->caterer?->auto_feature_reviews && (int) $validated['rating'] === 5,
                'reviewed_at' => now(),
            ]
        );

        $rating = Review::forCaterer($booking->caterer_id)->public()->avg('rating') ?? 0;
        $count = Review::forCaterer($booking->caterer_id)->public()->count();

        $booking->caterer?->forceFill([
            'rating' => round($rating, 2),
            'reviews_count' => $count,
        ])->save();

        return back()->with('success', 'Review submitted. Thank you for sharing your experience.');
    }

    public function storeBooking(Request $request, User $caterer)
    {
        abort_unless(
            $caterer->role === 'caterer'
            && $caterer->approval_status === 'approved'
            && $caterer->is_active,
            404
        );

        $minGuests = $caterer->min_guest ?? 1;
        $maxGuests = $caterer->max_guest ?? 10000;

        $validated = $request->validate([
            'event_title' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'guests' => ['required', 'integer', 'min:' . $minGuests, 'max:' . $maxGuests],
        ]);

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'caterer_id' => $caterer->id,
            'event_title' => $validated['event_title'],
            'event_date' => $validated['event_date'],
            'guests' => $validated['guests'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('client.bookings.show', $booking)
            ->with('success', 'Booking request sent. The caterer can now review your event details.');
    }

    public function savedCaterers()
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

        $savedCaterers = SavedCaterer::with('caterer')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return response()
            ->view('client.saved-caterers', compact(
                'user',
                'initials',
                'savedCaterers'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function toggleSavedCaterer(User $caterer)
    {
        $user = auth()->user();

        $saved = SavedCaterer::where('user_id', $user->id)
            ->where('caterer_id', $caterer->id)
            ->first();

        if ($saved) {
            $saved->delete();
            return back()->with('success', 'Caterer removed from saved.');
        }

        SavedCaterer::create([
            'user_id' => $user->id,
            'caterer_id' => $caterer->id,
        ]);

        return back()->with('success', 'Caterer saved successfully.');
    }

    public function myReviews()
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

        $reviews = Review::with('caterer')
            ->where('client_id', $user->id)
            ->latest()
            ->paginate(12);

        return response()
            ->view('client.reviews', compact(
                'user',
                'initials',
                'reviews'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function editProfile()
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

        return response()
            ->view('client.profile', compact('user', 'initials'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
