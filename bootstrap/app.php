<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'prevent.back' => \App\Http\Middleware\PreventBackHistory::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request): string {
            $routeName = $request->route()?->getName();

            if (is_string($routeName) && $routeName !== 'caterer.detail' && str_starts_with($routeName, 'caterer.')) {
                return route('caterer.login');
            }

            if (is_string($routeName) && (
                str_starts_with($routeName, 'menu.')
                || str_starts_with($routeName, 'bookings.')
            )) {
                return route('caterer.login');
            }

            // Admin and client both use the regular login page
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
