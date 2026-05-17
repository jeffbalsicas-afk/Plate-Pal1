<?php

namespace App\Http\Controllers;

use App\Mail\PasswordChangedNotification;
use App\Models\Booking;
use App\Models\MenuItem;
use App\Models\Message;
use App\Models\Package;
use App\Models\Review;
use App\Models\SavedCaterer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class CatererController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $user = auth()->user();

        $totalBookings = Booking::where('caterer_id', $user->id)->count();
        $pendingBookings = Booking::where('caterer_id', $user->id)->where('status', 'pending')->count();
        $unreadMessages = Message::where('caterer_id', $user->id)->where('is_read', false)->where('sender', 'client')->count();
        $avgRating = $user->rating ?? 0;

        $upcomingBookings = Booking::with('user')
            ->where('caterer_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(3)
            ->get();

        $recentMessages = Message::with('user')
            ->where('caterer_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $prevMonth = now()->subMonth()->month;
        $prevYear = now()->subMonth()->year;

        $currentMonthTotal = Booking::where('caterer_id', $user->id)
            ->whereMonth('event_date', $currentMonth)
            ->whereYear('event_date', $currentYear)
            ->count();

        $previousMonthTotal = Booking::where('caterer_id', $user->id)
            ->whereMonth('event_date', $prevMonth)
            ->whereYear('event_date', $prevYear)
            ->count();

        $growth = $previousMonthTotal > 0
            ? round((($currentMonthTotal - $previousMonthTotal) / $previousMonthTotal) * 100, 1)
            : ($currentMonthTotal > 0 ? 100 : 0);

        $currentWeekly = $this->weeklyBookings($user->id, $currentMonth, $currentYear);
        $previousWeekly = $this->weeklyBookings($user->id, $prevMonth, $prevYear);

        $packages = Package::forCaterer($user->id)->get();

        // Get all completed bookings in May with final_price set
        $mayBookings = Booking::with('caterer')
            ->where('caterer_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('final_price')
            ->whereMonth('event_date', 5)
            ->whereYear('event_date', now()->year)
            ->get();

        $topPackages = $packages->map(function ($pkg) use ($user, $mayBookings) {
            // Filter bookings for this package
            $packageBookings = $mayBookings->where('package_id', $pkg->id);

            $bookingsCount = $packageBookings->count();
            $revenue = $packageBookings->sum('final_price');

            return [
                'name' => $pkg->name,
                'bookings' => $bookingsCount,
                'revenue' => 'PHP '.number_format($revenue, 0),
                'satisfaction' => number_format($user->rating ?? 0, 1),
                'rank' => $bookingsCount > 0 ? '#'.($bookingsCount >= 5 ? '1' : ($bookingsCount >= 3 ? '2' : '3')) : 'New',
            ];
        });

        // Add "Custom Bookings" for bookings without a package
        $customBookings = $mayBookings->whereNull('package_id');
        $customCount = $customBookings->count();
        $customRevenue = $customBookings->sum('final_price');

        if ($customCount > 0) {
            $topPackages->push([
                'name' => 'Custom Bookings',
                'bookings' => $customCount,
                'revenue' => 'PHP '.number_format($customRevenue, 0),
                'satisfaction' => number_format($user->rating ?? 0, 1),
                'rank' => $customCount >= 5 ? '#1' : ($customCount >= 3 ? '#2' : '#3'),
            ]);
        }

        $topPackages = $topPackages->sortByDesc('bookings')->take(3);

        return response()
            ->view('caterer.dashboard', compact(
                'user',
                'totalBookings',
                'pendingBookings',
                'unreadMessages',
                'avgRating',
                'upcomingBookings',
                'recentMessages',
                'currentMonthTotal',
                'previousMonthTotal',
                'growth',
                'currentWeekly',
                'previousWeekly',
                'topPackages'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function bookings()
    {
        $user = auth()->user();
        $catererId = $user->id;
        $displayName = $user->business_name ?? $user->name;
        $initials = $user->initials;
        $selectedStatus = request('status', 'all');
        $allowedStatuses = ['all', 'pending', 'confirmed', 'completed', 'cancelled'];

        if (! in_array($selectedStatus, $allowedStatuses, true)) {
            $selectedStatus = 'all';
        }

        $baseQuery = Booking::with(['user', 'package', 'bookingItems'])->where('caterer_id', $catererId);

        $statusCounts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        $bookings = (clone $baseQuery)
            ->when($selectedStatus !== 'all', fn ($query) => $query->where('status', $selectedStatus))
            ->orderByRaw("case when status = 'pending' then 0 when status = 'confirmed' then 1 when status = 'completed' then 2 else 3 end")
            ->orderBy('event_date')
            ->get();

        $nextBooking = (clone $baseQuery)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date')
            ->first();

        $totalGuests = (clone $baseQuery)
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('guests');

        $unreadMessages = Message::where('caterer_id', $catererId)
            ->where('is_read', false)
            ->where('sender', 'client')
            ->count();

        return response()
            ->view('caterer.bookings', compact(
                'user',
                'displayName',
                'initials',
                'bookings',
                'selectedStatus',
                'statusCounts',
                'nextBooking',
                'totalGuests',
                'unreadMessages'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function menuAndPricing(Request $request)
    {
        $user = auth()->user();
        $catererId = $user->id;
        $displayName = $user->business_name ?? $user->name;
        $initials = $user->initials;
        $perPage = 10;
        $activeTab = in_array($request->query('tab'), ['packages', 'items', 'addons'], true)
            ? $request->query('tab')
            : 'packages';
        $search = trim((string) $request->query('search', ''));
        $sort = in_array($request->query('sort'), ['newest', 'oldest', 'price_asc', 'price_desc'], true)
            ? $request->query('sort')
            : 'newest';
        $category = in_array($request->query('category'), ['main', 'side', 'dessert', 'beverage'], true)
            ? $request->query('category')
            : null;

        $applySearch = function ($query) use ($search) {
            return $search === ''
                ? $query
                : $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
        };

        $applySort = function ($query) use ($sort) {
            return match ($sort) {
                'oldest' => $query->reorder()->orderBy('created_at'),
                'price_asc' => $query->reorder()->orderBy('price')->orderByDesc('created_at'),
                'price_desc' => $query->reorder()->orderByDesc('price')->orderByDesc('created_at'),
                default => $query->reorder()->orderByDesc('created_at'),
            };
        };

        $packages = Package::forCaterer($catererId)
            ->when($activeTab === 'packages', fn ($query) => $applySort($applySearch($query)), fn ($query) => $query->reorder()->orderByDesc('created_at'))
            ->paginate($perPage, ['*'], 'packages_page')
            ->withQueryString();

        $menuItems = MenuItem::forCaterer($catererId)
            ->menuItems()
            ->when($activeTab === 'items', function ($query) use ($applySearch, $applySort, $category) {
                $query = $applySearch($query);

                if ($category) {
                    $query->where('category', $category);
                }

                return $applySort($query);
            }, fn ($query) => $query->reorder()->orderByDesc('created_at'))
            ->paginate($perPage, ['*'], 'items_page')
            ->withQueryString();

        $addOns = MenuItem::forCaterer($catererId)
            ->addOns()
            ->when($activeTab === 'addons', fn ($query) => $applySort($applySearch($query)), fn ($query) => $query->reorder()->orderByDesc('created_at'))
            ->paginate($perPage, ['*'], 'addons_page')
            ->withQueryString();

        $menuSummary = [
            'packages' => Package::forCaterer($catererId)->count(),
            'items' => MenuItem::forCaterer($catererId)->menuItems()->count(),
            'addons' => MenuItem::forCaterer($catererId)->addOns()->count(),
        ];

        $pendingBookings = Booking::where('caterer_id', $catererId)
            ->where('status', 'pending')
            ->count();

        $unreadMessages = Message::where('caterer_id', $catererId)
            ->where('is_read', false)
            ->where('sender', 'client')
            ->count();

        return response()
            ->view('caterer.menu-pricing', compact(
                'user',
                'displayName',
                'initials',
                'packages',
                'menuItems',
                'addOns',
                'menuSummary',
                'pendingBookings',
                'unreadMessages',
                'activeTab',
                'search',
                'sort',
                'category'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    // Profile edit
    public function editProfile()
    {
        $user = auth()->user();
        $pendingBookings = Booking::where('caterer_id', $user->id)->where('status', 'pending')->count();
        $unreadMessages = Message::where('caterer_id', $user->id)->where('is_read', false)->where('sender', 'client')->count();

        return view('caterer.profile-manage', compact('user', 'pendingBookings', 'unreadMessages'));
    }

    // Profile update
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $formType = $request->input('form_type', 'basic');

        if ($formType === 'password') {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'confirmed', PasswordRule::defaults()],
            ]);

            $user->update([
                'password' => $validated['password'],
            ]);

            Mail::to($user->email)->send(new PasswordChangedNotification($user));

            return redirect()->to(route('caterer.profile').'#security')->with('success', 'Password changed successfully.');
        }

        if ($formType === 'basic') {
            $request->merge([
                'phone' => $this->normalizePhone($request->input('phone')),
            ]);

            $request->validate([
                'business_name' => ['required', 'string', 'max:255'],
                'barangay' => ['required', 'string'],
                'phone' => [
                    'required',
                    'string',
                    'regex:/^\+639\d{9}$/',
                    Rule::unique('users', 'phone')->ignore($user->id),
                    function ($attribute, $value, $fail) use ($user) {
                        $phoneExists = User::whereKeyNot($user->id)
                            ->whereNotNull('phone')
                            ->pluck('phone')
                            ->contains(fn ($phone) => $this->normalizePhone($phone) === $value);

                        if ($phoneExists) {
                            $fail('The phone has already been taken.');
                        }
                    },
                ],
                'description' => ['nullable', 'string', 'max:2000'],
                'cuisine' => ['required', 'string', 'max:255'],
                'price_min' => ['required', 'numeric', 'min:0'],
                'price_max' => ['required', 'numeric', 'gte:price_min'],
                'min_guest' => ['required', 'integer', 'min:1'],
                'max_guest' => ['required', 'integer', 'gte:min_guest'],
                'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            ], [
                'phone.regex' => 'Phone number must be in format +639XXXXXXXXX (10 digits starting with 9)',
            ]);

            $data = $this->pendingReviewData($request->only([
                'business_name',
                'barangay',
                'phone',
                'cuisine',
                'description',
                'price_min',
                'price_max',
                'min_guest',
                'max_guest',
            ]));

            if ($request->hasFile('profile_image')) {
                if ($user->profile_image && str_starts_with($user->profile_image, '/storage/')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile_image));
                }
                $path = $request->file('profile_image')->store('caterers', 'public');
                $data['profile_image'] = '/storage/'.$path;
            }

            $user->update($data);

            return redirect()->route('caterer.profile')->with('success', 'Basic information submitted for admin approval.');
        }

        if ($formType === 'about') {
            $request->validate([
                'our_story' => ['nullable', 'string', 'max:5000'],
                'what_makes_special' => ['nullable', 'string', 'max:5000'],
                'services_offered' => ['nullable', 'array'],
                'services_offered.*' => ['string', 'max:255'],
            ]);

            $user->update($this->pendingReviewData([
                'our_story' => $request->filled('our_story') ? $request->input('our_story') : null,
                'what_makes_special' => $request->filled('what_makes_special') ? $request->input('what_makes_special') : null,
                'services_offered' => $this->arrayValue($request->input('services_offered', [])),
            ]));

            return redirect()->to(route('caterer.profile').'#about')->with('success', 'About section submitted for admin approval.');
        }

        if ($formType === 'gallery') {
            $request->validate([
                'gallery_images' => ['nullable', 'array'],
                'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ]);

            $existingImages = $this->arrayValue($user->gallery_images);

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $path = $image->store('gallery', 'public');
                    $existingImages[] = '/storage/'.$path;
                }
            }

            $user->update($this->pendingReviewData(['gallery_images' => array_values($existingImages)]));

            return redirect()->to(route('caterer.profile').'#gallery')->with('success', 'Gallery changes submitted for admin approval.');
        }

        return redirect()->route('caterer.profile');
    }

    public function deleteGalleryImage($index)
    {
        $user = auth()->user();
        $galleryImages = $this->arrayValue($user->gallery_images);

        if (! isset($galleryImages[$index])) {
            return redirect()->to(route('caterer.profile').'#gallery')->with('success', 'Image was already removed.');
        }

        $imagePath = $galleryImages[$index];
        if (str_starts_with($imagePath, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $imagePath));
        }

        unset($galleryImages[$index]);

        $user->update($this->pendingReviewData(['gallery_images' => array_values($galleryImages)]));

        return redirect()->to(route('caterer.profile').'#gallery')->with('success', 'Gallery changes submitted for admin approval.');
    }

    public function show($id)
    {
        if (auth()->check() && auth()->user()->role === 'caterer') {
            abort(403);
        }

        $caterer = User::where('role', 'caterer')
            ->where(function ($query) {
                $authId = auth()->id();
                $query->where('approval_status', 'approved')
                    ->orWhere(function ($q) use ($authId) {
                        $q->where('approval_status', 'pending')
                            ->where('id', $authId);
                    })
                    ->orWhere(function ($q) use ($authId) {
                        $q->where('approval_status', 'rejected')
                            ->where('id', $authId);
                    });
            })
            ->where('is_active', true)
            ->findOrFail($id);

        $packages = Package::forCaterer($caterer->id)->get();
        $menuItems = MenuItem::forCaterer($caterer->id)->menuItems()->get();
        $addOns = MenuItem::forCaterer($caterer->id)->addOns()->get();
        $publicReviews = Review::forCaterer($caterer->id)
            ->public()
            ->orderByDesc('is_featured')
            ->orderByDesc('reviewed_at')
            ->latest()
            ->take(6)
            ->get();
        $reviewsCount = Review::forCaterer($caterer->id)->public()->count();
        $averageRating = Review::forCaterer($caterer->id)->public()->avg('rating') ?? ($caterer->rating ?? 0);
        $user = auth()->user();
        $initials = $user?->initials;

        $savedCatererIds = [];
        $activeBookings = 0;
        $unreadMessages = 0;
        $statusCounts = ['all' => 0];
        if ($user && $user->role === 'client') {
            $savedCatererIds = SavedCaterer::where('user_id', $user->id)->pluck('caterer_id')->toArray();
            $activeBookings = Booking::where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed'])->count();
            $unreadMessages = Message::where('user_id', $user->id)->where('is_read', false)->where('sender', 'caterer')->count();
            $statusCounts = ['all' => Booking::where('user_id', $user->id)->count()];
        }

        $view = match ($user?->role) {
            null => 'caterer.detail-guest',
            'client' => 'client.caterer-detail',
            default => 'caterer.detail',
        };

        return response()
            ->view($view, compact('caterer', 'packages', 'menuItems', 'addOns', 'publicReviews', 'reviewsCount', 'averageRating', 'user', 'initials', 'savedCatererIds', 'activeBookings', 'unreadMessages', 'statusCounts'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    private function weeklyBookings($catererId, $month, $year): array
    {
        $weeks = [0, 0, 0, 0];
        $bookings = Booking::where('caterer_id', $catererId)
            ->whereMonth('event_date', $month)
            ->whereYear('event_date', $year)
            ->get();

        foreach ($bookings as $booking) {
            $week = min((int) ceil($booking->event_date->day / 7), 4) - 1;
            $weeks[$week]++;
        }

        return $weeks;
    }

    private function pendingReviewData(array $data = []): array
    {
        return array_merge($data, [
            'approval_status' => 'pending',
            'is_verified' => false,
            'rejection_reason' => null,
        ]);
    }

    public function earnings()
    {
        $user = auth()->user();
        $displayName = $user->business_name ?? $user->name;
        $initials = $user->initials;

        // Only count completed bookings with final_price set
        $completedBookings = Booking::with('caterer')
            ->where('caterer_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('final_price')
            ->get();

        $totalEarnings = $completedBookings->sum('final_price');

        $monthlyBookings = Booking::with('caterer')
            ->where('caterer_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('final_price')
            ->whereMonth('event_date', now()->month)
            ->whereYear('event_date', now()->year)
            ->get();

        $monthlyEarnings = $monthlyBookings->sum('final_price');

        $completedBookings = $completedBookings->count();

        $pendingBookings = Booking::where('caterer_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $unreadMessages = Message::where('caterer_id', $user->id)
            ->where('is_read', false)
            ->where('sender', 'client')
            ->count();

        // Only show completed bookings with final_price in history
        $earningsHistory = Booking::with(['user', 'package', 'caterer'])
            ->where('caterer_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('final_price')
            ->latest()
            ->paginate(15);

        return response()
            ->view('caterer.earnings', compact(
                'user',
                'displayName',
                'initials',
                'totalEarnings',
                'monthlyEarnings',
                'completedBookings',
                'pendingBookings',
                'unreadMessages',
                'earningsHistory'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    private function normalizePhone(?string $phone): string
    {
        // Remove all non-digit characters except +
        $phone = preg_replace('/[^\d+]/', '', (string) $phone);

        // If it starts with 0, replace with +63
        if (str_starts_with($phone, '0')) {
            return '+63'.substr($phone, 1);
        }

        // If it starts with 63, add +
        if (str_starts_with($phone, '63')) {
            return '+'.$phone;
        }

        // If it already starts with +63, return as is
        if (str_starts_with($phone, '+63')) {
            return $phone;
        }

        // Otherwise, assume it's a local number and add +63
        return '+63'.preg_replace('/\D/', '', $phone);
    }

    private function arrayValue(mixed $value): array
    {
        for ($i = 0; $i < 2; $i++) {
            if (is_array($value)) {
                return array_values(array_filter($value, fn ($item) => $item !== null && $item !== ''));
            }

            if (! is_string($value) || $value === '') {
                return [];
            }

            $decoded = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }

            $value = $decoded;
        }

        return is_array($value) ? array_values($value) : [];
    }
}
