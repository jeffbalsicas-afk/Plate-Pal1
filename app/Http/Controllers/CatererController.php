<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use App\Models\Package;
use App\Models\MenuItem;
use App\Models\Review;
use Illuminate\Http\Request;

class CatererController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $user = auth()->user();

        $totalBookings    = Booking::where('caterer_id', $user->id)->count();
        $pendingBookings  = Booking::where('caterer_id', $user->id)->where('status', 'pending')->count();
        $unreadMessages   = Message::where('caterer_id', $user->id)->where('is_read', false)->where('sender', 'client')->count();
        $avgRating        = $user->rating ?? 0;

        $upcomingBookings = Booking::with('user')
            ->where('caterer_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('event_date')
            ->take(3)
            ->get();

        $recentMessages   = Message::with('user')
            ->where('caterer_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        $currentMonth  = now()->month;
        $currentYear   = now()->year;
        $prevMonth     = now()->subMonth()->month;
        $prevYear      = now()->subMonth()->year;

        $currentMonthTotal  = Booking::where('caterer_id', $user->id)
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

        $currentWeekly  = $this->weeklyBookings($user->id, $currentMonth, $currentYear);
        $previousWeekly = $this->weeklyBookings($user->id, $prevMonth, $prevYear);

        $packages = Package::forCaterer($user->id)->get();
        $topPackages = $packages->map(function ($pkg) use ($user) {
            $bookingCount = Booking::where('caterer_id', $user->id)
                ->where('status', 'completed')
                ->count();
            return [
                'name' => $pkg->name,
                'bookings' => $bookingCount,
                'revenue' => '₱0',
                'satisfaction' => '0%',
                'rank' => match(true) {
                    $bookingCount >= 10 => '🥇 Best Seller',
                    $bookingCount >= 5 => '🥈 Runner Up',
                    default => '🥉 Top 3'
                }
            ];
        })->take(3);

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

        $baseQuery = Booking::with('user')->where('caterer_id', $catererId);

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

    public function menuAndPricing()
    {
        $user = auth()->user();
        $catererId = $user->id;
        $displayName = $user->business_name ?? $user->name;
        $initials = $user->initials;
        $perPage = 10;

        $packages = Package::forCaterer($catererId)
            ->when(request('search'), fn ($q) => $q->where('name', 'like', '%' . request('search') . '%'))
            ->when(request('sort'), fn ($q) => match(request('sort')) {
                'price_asc' => $q->orderBy('price'),
                'price_desc' => $q->orderByDesc('price'),
                'newest' => $q->orderByDesc('created_at'),
                'oldest' => $q->orderBy('created_at'),
                default => $q->orderByDesc('created_at')
            }, fn ($q) => $q->orderByDesc('created_at'))
            ->paginate($perPage, ['*'], 'packages_page');

        $menuItems = MenuItem::forCaterer($catererId)
            ->menuItems()
            ->when(request('search'), fn ($q) => $q->where('name', 'like', '%' . request('search') . '%'))
            ->when(request('sort'), fn ($q) => match(request('sort')) {
                'price_asc' => $q->orderBy('price'),
                'price_desc' => $q->orderByDesc('price'),
                'newest' => $q->orderByDesc('created_at'),
                'oldest' => $q->orderBy('created_at'),
                default => $q->orderByDesc('created_at')
            }, fn ($q) => $q->orderByDesc('created_at'))
            ->paginate($perPage, ['*'], 'items_page');

        $addOns = MenuItem::forCaterer($catererId)
            ->addOns()
            ->when(request('search'), fn ($q) => $q->where('name', 'like', '%' . request('search') . '%'))
            ->when(request('sort'), fn ($q) => match(request('sort')) {
                'price_asc' => $q->orderBy('price'),
                'price_desc' => $q->orderByDesc('price'),
                'newest' => $q->orderByDesc('created_at'),
                'oldest' => $q->orderBy('created_at'),
                default => $q->orderByDesc('created_at')
            }, fn ($q) => $q->orderByDesc('created_at'))
            ->paginate($perPage, ['*'], 'addons_page');

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
                'pendingBookings',
                'unreadMessages'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    // Profile edit
    public function editProfile()
    {
        return view('caterer.profile', ['user' => auth()->user()]);
    }

    // Profile update
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'barangay'      => ['required', 'string'],
            'phone'         => ['required', 'string'],
            'description'   => ['nullable', 'string'],
            'cuisine'       => ['required', 'string'],
            'price_min'     => ['required', 'string'],
            'price_max'     => ['required', 'string'],
            'min_guest'     => ['required', 'string'],
            'max_guest'     => ['required', 'string'],
            'profile_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['business_name', 'barangay', 'phone', 'cuisine', 'description', 'price_min', 'price_max', 'min_guest', 'max_guest']);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('caterers', 'public');
            $data['profile_image'] = '/storage/' . $path;
        }

        $data['approval_status'] = 'pending';
        $data['is_verified'] = false;
        $data['rejection_reason'] = null;

        $user->update($data);

        return redirect()->route('caterer.profile')->with('success', 'Profile submitted! Your details are pending admin approval.');
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

        $packages = Package::forCaterer($caterer->id)->where('status', 'live')->get();
        $menuItems = MenuItem::forCaterer($caterer->id)->menuItems()->where('status', 'live')->get();
        $addOns = MenuItem::forCaterer($caterer->id)->addOns()->where('status', 'live')->get();
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

        return response()
            ->view('caterer.detail', compact('caterer', 'packages', 'menuItems', 'addOns', 'publicReviews', 'reviewsCount', 'averageRating', 'user', 'initials'))
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

    public function earnings()
    {
        $user = auth()->user();
        $displayName = $user->business_name ?? $user->name;
        $initials = $user->initials;

        $totalEarnings = Booking::where('caterer_id', $user->id)
            ->where('status', 'completed')
            ->sum('guests') * 400;

        $monthlyEarnings = Booking::where('caterer_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('event_date', now()->month)
            ->whereYear('event_date', now()->year)
            ->sum('guests') * 400;

        $completedBookings = Booking::where('caterer_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $pendingBookings = Booking::where('caterer_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $unreadMessages = Message::where('caterer_id', $user->id)
            ->where('is_read', false)
            ->where('sender', 'client')
            ->count();

        $earningsHistory = Booking::where('caterer_id', $user->id)
            ->where('status', 'completed')
            ->with('user')
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
}
