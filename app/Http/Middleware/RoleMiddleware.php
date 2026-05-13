<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user && in_array($user->role, $roles, true)) {
            return $next($request);
        }

        if (! $user) {
            return redirect()->route('login');
        }

        return redirect()
            ->route($this->dashboardRouteFor($user->role))
            ->with('error', 'You are already signed in with a different account type.');
    }

    private function dashboardRouteFor(?string $role): string
    {
        return match ($role) {
            'admin' => 'admin.dashboard',
            'caterer' => 'caterer.dashboard',
            'client' => 'client.dashboard',
            default => 'home',
        };
    }
}
