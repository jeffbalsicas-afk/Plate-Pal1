<?php

namespace App\Http\Controllers;

use App\Mail\NewBookingNotification;
use App\Models\Booking;
use App\Models\Message;
use App\Models\Package;
use App\Models\Review;
use App\Models\SavedCaterer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $activeBookings    = Booking::where('user_id', $user->id)->whereNull('client_viewed_at')->count();
        $completedEvents   = Booking::where('user_id', $user->id)->where('status', 'completed')->count();
        $unreadMessages    = Message::where('user_id', $user->id)->where('is_read', false)->where('sender', 'caterer')->count();
        $savedCaterersCount = SavedCaterer::where('user_id', $user->id)->count();
        $statusCounts = ['all' => Booking::where('user_id', $user->id)->count()];

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

        $savedCatererIds = SavedCaterer::where('user_id', $user->id)->pluck('caterer_id')->toArray();

        $featuredCaterers = User::where('role', 'caterer')
            ->where('approval_status', 'approved')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('rating')
            ->limit(3)
            ->get()
            ->map(fn ($caterer) => [
                'id'       => $caterer->id,
                'name'     => $caterer->business_name ?? $caterer->name,
                'location' => $caterer->barangay ?? 'Tagum City',
                'cuisine'  => $caterer->cuisine ?? 'Filipino Cuisine',
                'rating'   => $caterer->rating ?? 4.5,
                'reviews'  => $caterer->reviews_count ?? 0,
                'price'    => 'PHP ' . ($caterer->price_min ?? 300) . '-' . ($caterer->price_max ?? 500) . '/head',
                'image'    => $caterer->profile_image_url ?? '/assets/Pusit.png',
                'is_saved' => in_array($caterer->id, $savedCatererIds),
            ]);

        return response()
            ->view('client.dashboard', compact(
                'user',
                'activeBookings',
                'completedEvents',
                'unreadMessages',
                'savedCaterersCount',
                'upcomingBookings',
                'recentMessages',
                'featuredCaterers',
                'statusCounts'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function browse()
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

        $activeBookings = Booking::where('user_id', $user->id)->whereNull('client_viewed_at')->count();
        $completedEvents = Booking::where('user_id', $user->id)->where('status', 'completed')->count();
        $unreadMessages = Message::where('user_id', $user->id)->where('is_read', false)->where('sender', 'caterer')->count();
        $statusCounts = ['all' => Booking::where('user_id', $user->id)->count()];

        $query = User::where('role', 'caterer')
            ->where('approval_status', 'approved')
            ->where('is_active', true);

        $this->applyCatererFilters($query);
        $this->applyCatererSort($query);

        $caterers = $query->paginate(12)->withQueryString();
        $savedCatererIds = SavedCaterer::where('user_id', $user->id)->pluck('caterer_id')->toArray();
        
        [$cuisines, $barangays] = $this->browseFilterOptions();

        return view('client.browse', compact(
            'user',
            'initials',
            'caterers',
            'savedCatererIds',
            'cuisines',
            'barangays',
            'activeBookings',
            'completedEvents',
            'unreadMessages',
            'statusCounts'
        ));
    }

    public function browsePubic()
    {
        try {
            $query = User::where('role', 'caterer')
                ->where('approval_status', 'approved')
                ->where('is_active', true);

            $this->applyCatererFilters($query);
            $this->applyCatererSort($query);

            $caterers = $query
                ->paginate(12)
                ->withQueryString();
            [$cuisines, $barangays] = $this->browseFilterOptions();
        } catch (Throwable) {
            $caterers = $this->fallbackPublicCaterers();
            [$cuisines, $barangays] = $this->fallbackFilterOptions();
        }

        return response()
            ->view('client.browse-public', compact('caterers', 'cuisines', 'barangays'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    private function applyCatererFilters($query): void
    {
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('cuisine', 'like', "%$search%")
                    ->orWhere('barangay', 'like', "%$search%");
            });
        }

        if (request('cuisine')) {
            $query->where('cuisine', request('cuisine'));
        }

        if (request('barangay')) {
            $query->where('barangay', request('barangay'));
        }

        if (request('rating')) {
            $query->where('rating', '>=', (float) request('rating'));
        }

        $priceRange = $this->priceRange();

        if ($priceRange) {
            [$minPrice, $maxPrice] = $priceRange;
            $query->where(function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price_min', [$minPrice, $maxPrice])
                    ->orWhereBetween('price_max', [$minPrice, $maxPrice])
                    ->orWhere(function ($q2) use ($minPrice, $maxPrice) {
                        $q2->where('price_min', '<=', $minPrice)
                            ->where('price_max', '>=', $maxPrice);
                    });
            });
        }
    }

    private function applyCatererSort($query): void
    {
        match (request('sort', 'rating_high')) {
            'rating_low' => $query->orderBy('rating'),
            'price_low' => $query->orderBy('price_min')->orderByDesc('rating'),
            'price_high' => $query->orderByDesc('price_max')->orderByDesc('rating'),
            default => $query->orderByDesc('rating'),
        };
    }

    private function browseFilterOptions(): array
    {
        $baseQuery = User::where('role', 'caterer')
            ->where('approval_status', 'approved')
            ->where('is_active', true);

        return [
            (clone $baseQuery)->whereNotNull('cuisine')->distinct()->orderBy('cuisine')->pluck('cuisine'),
            (clone $baseQuery)->whereNotNull('barangay')->distinct()->orderBy('barangay')->pluck('barangay'),
        ];
    }

    private function priceRange(): ?array
    {
        $priceRange = request('price_range');

        if (! $priceRange) {
            return null;
        }

        $parts = explode('-', $priceRange);

        if (count($parts) !== 2) {
            return null;
        }

        return [(int) $parts[0], (int) $parts[1]];
    }

    private function fallbackPublicCaterers(): LengthAwarePaginator
    {
        $search = strtolower((string) request('search', ''));
        $barangay = (string) request('barangay', '');
        $cuisine = (string) request('cuisine', '');
        $rating = request('rating') ? (float) request('rating') : null;
        $priceRange = $this->priceRange();

        $items = $this->fallbackCatererData()
            ->filter(function ($caterer) use ($search, $barangay, $cuisine, $rating, $priceRange) {
                if ($barangay !== '' && $caterer['barangay'] !== $barangay) {
                    return false;
                }

                if ($cuisine !== '' && $caterer['cuisine'] !== $cuisine) {
                    return false;
                }

                if ($rating !== null && $caterer['rating'] < $rating) {
                    return false;
                }

                if ($priceRange) {
                    [$minPrice, $maxPrice] = $priceRange;
                    $overlaps = ($caterer['price_min'] >= $minPrice && $caterer['price_min'] <= $maxPrice)
                        || ($caterer['price_max'] >= $minPrice && $caterer['price_max'] <= $maxPrice)
                        || ($caterer['price_min'] <= $minPrice && $caterer['price_max'] >= $maxPrice);

                    if (! $overlaps) {
                        return false;
                    }
                }

                if ($search === '') {
                    return true;
                }

                return str_contains(strtolower($caterer['business_name']), $search)
                    || str_contains(strtolower($caterer['name']), $search)
                    || str_contains(strtolower($caterer['cuisine']), $search)
                    || str_contains(strtolower($caterer['barangay']), $search);
            });

        $items = match (request('sort', 'rating_high')) {
            'rating_low' => $items->sortBy('rating'),
            'price_low' => $items->sortBy('price_min'),
            'price_high' => $items->sortByDesc('price_max'),
            default => $items->sortByDesc('rating'),
        };

        $items = $items
            ->values()
            ->map(fn ($caterer) => (object) $caterer);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    private function fallbackFilterOptions(): array
    {
        $items = $this->fallbackCatererData();

        return [
            $items->pluck('cuisine')->filter()->unique()->sort()->values(),
            $items->pluck('barangay')->filter()->unique()->sort()->values(),
        ];
    }

    private function fallbackCatererData()
    {
        return collect([
            [
                'id' => null,
                'business_name' => "Lola Maria's Kitchen",
                'name' => 'Lola Maria',
                'barangay' => 'Magugpo Poblacion',
                'cuisine' => 'Authentic Tagum Native Chicken',
                'rating' => 4.8,
                'reviews_count' => 127,
                'price_min' => 300,
                'price_max' => 500,
                'min_guest' => 20,
                'max_guest' => 120,
                'profile_image' => '/assets/Pusit.png',
                'is_featured' => true,
            ],
            [
                'id' => null,
                'business_name' => 'Kusina ni Aling Nena',
                'name' => 'Aling Nena',
                'barangay' => 'Apokon',
                'cuisine' => 'Mindanao Fusion Cuisine',
                'rating' => 4.9,
                'reviews_count' => 98,
                'price_min' => 400,
                'price_max' => 600,
                'min_guest' => 15,
                'max_guest' => 100,
                'profile_image' => '/assets/Nena.png',
                'is_featured' => true,
            ],
            [
                'id' => null,
                'business_name' => 'TasteBuds Catering',
                'name' => 'TasteBuds Team',
                'barangay' => 'Visayan Village',
                'cuisine' => 'Party Packages & Event Buffets',
                'rating' => 4.7,
                'reviews_count' => 156,
                'price_min' => 350,
                'price_max' => 550,
                'min_guest' => 25,
                'max_guest' => 150,
                'profile_image' => '/assets/Buds.png',
                'is_featured' => true,
            ],
            [
                'id' => null,
                'business_name' => 'Bahay Kubo Kitchen',
                'name' => 'Bahay Kubo',
                'barangay' => 'Mankilam',
                'cuisine' => 'Organic Farm-to-Table Dishes',
                'rating' => 4.6,
                'reviews_count' => 73,
                'price_min' => 380,
                'price_max' => 520,
                'min_guest' => 20,
                'max_guest' => 90,
                'profile_image' => '/assets/Kubo.png',
                'is_featured' => true,
            ],
        ])
            ->values();
    }

    public function bookings()
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));
        $selectedStatus = request('status', 'all');
        $allowedStatuses = ['all', 'confirmed', 'pending', 'completed', 'cancelled'];

        if (! in_array($selectedStatus, $allowedStatuses, true)) {
            $selectedStatus = 'all';
        }

        // Mark all bookings as viewed when user visits this page
        Booking::where('user_id', $user->id)
            ->whereNull('client_viewed_at')
            ->update(['client_viewed_at' => now()]);

        $baseQuery = Booking::with(['caterer', 'package'])->where('user_id', $user->id);

        $statusCounts = [
            'all' => (clone $baseQuery)->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        $bookings = (clone $baseQuery)
            ->when($selectedStatus !== 'all', fn ($query) => $query->where('status', $selectedStatus))
            ->orderByRaw("case when status = 'pending' then 0 when status = 'confirmed' then 1 when status = 'completed' then 2 else 3 end")
            ->orderBy('event_date')
            ->get();

        // After marking as viewed, count should be 0
        $activeBookings = Booking::where('user_id', $user->id)->whereNull('client_viewed_at')->count();
            
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
                'activeBookings',
                'unreadMessages'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function bookingDetails(Booking $booking)
    {
        $user = auth()->user();

        abort_unless($booking->user_id === $user->id, 403);

        $booking->load(['caterer', 'package', 'bookingItems']);
        $existingReview = Review::where('booking_id', $booking->id)->first();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

        $statusCounts = [
            'all' => Booking::where('user_id', $user->id)->count(),
            'confirmed' => Booking::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'pending' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        $activeBookings = Booking::where('user_id', $user->id)->whereNull('client_viewed_at')->count();
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
                'activeBookings',
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
                'title' => null,
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

        $validated = $request->validate([
            'event_title' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'guests' => ['required', 'integer', 'min:1'],
            'client_budget' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'package_id' => [
                'nullable',
                'integer',
                Rule::exists('packages', 'id')->where(fn ($query) => $query
                    ->where('caterer_id', $caterer->id)
                    ->where('status', 'live')),
            ],
            'menu_items' => ['nullable', 'array'],
            'menu_items.*' => ['integer', 'exists:menu_items,id'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['integer', 'exists:menu_items,id'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ]);

        $package = isset($validated['package_id'])
            ? Package::where('caterer_id', $caterer->id)->where('status', 'live')->find($validated['package_id'])
            : null;

        // Package min_guests is now just a guideline - caterer can accept any booking
        
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'caterer_id' => $caterer->id,
            'package_id' => $package?->id,
            'package_name' => $package?->name,
            'package_price' => $package?->price,
            'event_title' => $validated['event_title'],
            'event_date' => $validated['event_date'],
            'guests' => $validated['guests'],
            'client_budget' => $validated['client_budget'] ?? null,
            'special_requests' => $validated['special_requests'] ?? null,
            'status' => 'pending',
        ]);

        // Store selected menu items
        if (!empty($validated['menu_items'])) {
            $menuItems = \App\Models\MenuItem::whereIn('id', $validated['menu_items'])->get();
            foreach ($menuItems as $item) {
                \App\Models\BookingItem::create([
                    'booking_id' => $booking->id,
                    'menu_item_id' => $item->id,
                    'item_name' => $item->name,
                    'item_type' => 'menu_item',
                    'item_price' => $item->price,
                    'quantity' => 1,
                ]);
            }
        }

        // Store selected add-ons
        if (!empty($validated['addons'])) {
            $addons = \App\Models\MenuItem::whereIn('id', $validated['addons'])->get();
            foreach ($addons as $addon) {
                \App\Models\BookingItem::create([
                    'booking_id' => $booking->id,
                    'menu_item_id' => $addon->id,
                    'item_name' => $addon->name,
                    'item_type' => 'addon',
                    'item_price' => $addon->price,
                    'quantity' => 1,
                ]);
            }
        }

        $booking->load(['user', 'caterer', 'package']);
        Mail::to($caterer->email)->send(new NewBookingNotification($booking));

        // Redirect back to caterer detail page to show success modal
        return redirect()
            ->route('caterer.detail', $caterer->id)
            ->with('booking_success', 'Booking request sent. The caterer can now review your event details.');
    }

    public function savedCaterers()
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

        $activeBookings = Booking::where('user_id', $user->id)->whereNull('client_viewed_at')->count();
        $completedEvents = Booking::where('user_id', $user->id)->where('status', 'completed')->count();
        $unreadMessages = Message::where('user_id', $user->id)->where('is_read', false)->where('sender', 'caterer')->count();
        $statusCounts = ['all' => Booking::where('user_id', $user->id)->count()];

        $savedCaterers = SavedCaterer::with('caterer')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return response()
            ->view('client.saved-caterers', compact(
                'user',
                'initials',
                'savedCaterers',
                'activeBookings',
                'completedEvents',
                'unreadMessages',
                'statusCounts'
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

        $activeBookings = Booking::where('user_id', $user->id)->whereNull('client_viewed_at')->count();
        $completedEvents = Booking::where('user_id', $user->id)->where('status', 'completed')->count();
        $unreadMessages = Message::where('user_id', $user->id)->where('is_read', false)->where('sender', 'caterer')->count();
        $savedCaterersCount = SavedCaterer::where('user_id', $user->id)->count();
        $statusCounts = ['all' => Booking::where('user_id', $user->id)->count()];

        $reviews = Review::with('caterer')
            ->where('client_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('client.reviews', compact(
            'user',
            'initials',
            'reviews',
            'activeBookings',
            'completedEvents',
            'unreadMessages',
            'savedCaterersCount',
            'statusCounts'
        ));
    }

    public function editProfile()
    {
        $user = auth()->user();
        $initials = strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1, 1) : ''));

        $activeBookings = Booking::where('user_id', $user->id)->whereNull('client_viewed_at')->count();
        $unreadMessages = Message::where('user_id', $user->id)->where('is_read', false)->where('sender', 'caterer')->count();
        $statusCounts = ['all' => Booking::where('user_id', $user->id)->count()];

        return response()
            ->view('client.profile', compact('user', 'initials', 'activeBookings', 'unreadMessages', 'statusCounts'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
            'phone' => $this->normalizePhone($request->input('phone')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'regex:/^\+639\d{9}$/', Rule::unique('users', 'phone')->ignore($user->id)],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ], [
            'phone.regex' => 'Phone number must be in format +639XXXXXXXXX (10 digits starting with 9)',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && str_starts_with($user->profile_image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile_image));
            }

            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = '/storage/' . $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    private function normalizePhone(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        // Remove all non-digit characters except +
        $phone = preg_replace('/[^\d+]/', '', (string) $phone);

        // If it starts with 0, replace with +63
        if (str_starts_with($phone, '0')) {
            return '+63' . substr($phone, 1);
        }

        // If it starts with 63, add +
        if (str_starts_with($phone, '63')) {
            return '+' . $phone;
        }

        // If it already starts with +63, return as is
        if (str_starts_with($phone, '+63')) {
            return $phone;
        }

        // Otherwise, assume it's a local number and add +63
        return '+63' . preg_replace('/\D/', '', $phone);
    }
}
