<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
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
    }
}
