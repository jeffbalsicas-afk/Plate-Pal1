<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalUsers = User::where('role', 'client')->count();
        $totalCaterers = User::where('role', 'caterer')->where('approval_status', 'approved')->count();
        $totalBookings = Booking::count();

        // Calculate total revenue using estimated_total attribute
        $completedBookingsList = Booking::where('status', 'completed')->with('caterer')->get();
        $totalRevenue = $completedBookingsList->sum(function ($booking) {
            return $booking->estimated_total;
        });

        $pendingCaterers = User::where('role', 'caterer')->where('approval_status', 'pending')->get();
        $approvedCaterers = User::where('role', 'caterer')->where('approval_status', 'approved')->latest()->take(5)->get();
        $pendingPackages = collect();
        $pendingMenuItems = collect();
        $recentUsers = User::where('role', 'client')->latest()->take(5)->get();
        $recentBookings = Booking::with(['user', 'caterer'])->latest()->take(5)->get();

        return response()
            ->view('admin.dashboard', compact(
                'user',
                'totalUsers',
                'totalCaterers',
                'totalBookings',
                'totalRevenue',
                'pendingCaterers',
                'approvedCaterers',
                'pendingPackages',
                'pendingMenuItems',
                'recentUsers',
                'recentBookings'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function approve(User $user)
    {
        $user->update([
            'approval_status' => 'approved',
            'is_verified' => true,
            'is_active' => true,
        ]);

        return back()->with('success', "{$user->business_name} has been approved.");
    }

    public function reject(Request $request, User $user)
    {
        $request->validate(['rejection_reason' => ['nullable', 'string', 'max:500']]);

        $user->update([
            'approval_status' => 'rejected',
            'is_verified' => false,
            'is_active' => false,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', "{$user->business_name} has been rejected.");
    }

    public function featuredCaterers()
    {
        $user = auth()->user();
        $caterers = User::where('role', 'caterer')
            ->where('approval_status', 'approved')
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->paginate(15);

        return response()
            ->view('admin.featured-caterers', compact('user', 'caterers'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function toggleFeatured(User $caterer)
    {
        abort_unless($caterer->role === 'caterer', 404);

        $caterer->update(['is_featured' => ! $caterer->is_featured]);

        return back()->with('success', "{$caterer->business_name} has been ".($caterer->is_featured ? 'featured' : 'unfeatured').'.');
    }

    public function reports(Request $request)
    {
        $user = auth()->user();
        $catererId = $request->get('caterer_id');
        $selectedCaterer = null;

        // Get all approved caterers for dropdown
        $allCaterers = User::where('role', 'caterer')
            ->where('approval_status', 'approved')
            ->orderBy('business_name')
            ->get();

        if ($catererId) {
            // Filter for specific caterer
            $selectedCaterer = User::where('role', 'caterer')->findOrFail($catererId);

            $totalBookings = Booking::where('caterer_id', $catererId)->count();
            $completedBookings = Booking::where('caterer_id', $catererId)->where('status', 'completed')->count();
            $confirmedBookings = Booking::where('caterer_id', $catererId)->where('status', 'confirmed')->count();
            $pendingBookings = Booking::where('caterer_id', $catererId)->where('status', 'pending')->count();
            $cancelledBookings = Booking::where('caterer_id', $catererId)->where('status', 'cancelled')->count();

            $completedBookingsList = Booking::where('caterer_id', $catererId)->where('status', 'completed')->with('caterer')->get();
            $totalRevenue = $completedBookingsList->sum(function ($booking) {
                return $booking->estimated_total;
            });

            $avgRating = $selectedCaterer->rating ?? 0;
            $topCaterers = collect(); // Empty for single caterer view
        } else {
            // Platform-wide stats
            $totalBookings = Booking::count();
            $completedBookings = Booking::where('status', 'completed')->count();
            $confirmedBookings = Booking::where('status', 'confirmed')->count();
            $pendingBookings = Booking::where('status', 'pending')->count();
            $cancelledBookings = Booking::where('status', 'cancelled')->count();

            $completedBookingsList = Booking::where('status', 'completed')->with('caterer')->get();
            $totalRevenue = $completedBookingsList->sum(function ($booking) {
                return $booking->estimated_total;
            });

            $avgRating = User::where('role', 'caterer')->avg('rating') ?? 0;
            $topCaterers = User::where('role', 'caterer')
                ->withCount('bookings')
                ->orderByDesc('bookings_count')
                ->limit(5)
                ->get();
        }

        return response()
            ->view('admin.reports', compact(
                'user',
                'totalBookings',
                'completedBookings',
                'confirmedBookings',
                'pendingBookings',
                'cancelledBookings',
                'totalRevenue',
                'avgRating',
                'topCaterers',
                'allCaterers',
                'selectedCaterer'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function users()
    {
        $user = auth()->user();
        $allUsers = User::whereIn('role', ['client', 'caterer'])->latest()->paginate(15);
        $totalUsers = User::whereIn('role', ['client', 'caterer'])->count();

        return response()
            ->view('admin.users', compact('user', 'allUsers', 'totalUsers'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function bookings()
    {
        $user = auth()->user();
        $allBookings = Booking::with(['user', 'caterer'])->paginate(15);
        $totalBookings = Booking::count();

        return response()
            ->view('admin.bookings', compact('user', 'allBookings', 'totalBookings'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
