<?php

namespace App\Providers;

use App\Models\Booking;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RedirectIfAuthenticated::redirectUsing(function () {
            $user = Auth::user();
            if ($user && $user->role === 'caterer') {
                return route('caterer.dashboard');
            }
            if ($user && $user->role === 'admin') {
                return route('admin.dashboard');
            }

            return route('client.dashboard');
        });

        View::composer('client.partials.sidebar', function ($view) {
            $user = Auth::user();

            if (! $user || $user->role !== 'client') {
                return;
            }

            // Only set activeBookings if it hasn't been set by the controller
            if (! $view->offsetExists('activeBookings')) {
                $view->with('activeBookings', Booking::where('user_id', $user->id)
                    ->whereNull('client_viewed_at')
                    ->count());
            }
        });
    }
}
