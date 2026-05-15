<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    // --- CLIENT METHODS ---

    public function showLogin()
    {
        return $this->withNoCacheHeaders(response()->view('auth.client-login'));
    }

    public function login(Request $request)
    {
        return $this->attemptRoleLogin(
            $request,
            'client',
            route('client.dashboard'),
            'This account is not registered as a client.',
            $request->input('redirect')
        );
    }

    public function showRegister()
    {
        return view('auth.client-register');
    }

    public function register(Request $request)
    {
        $this->normalizeAuthInput($request);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^\+639\d{9}$/', 'unique:users,phone'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ], [
            'phone.regex' => 'Phone number must be in format +639XXXXXXXXX (10 digits starting with 9)',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'client', // Assigning role
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    // --- CATERER METHODS ---

    public function showCatererLogin()
    {
        return $this->withNoCacheHeaders(response()->view('auth.caterer-login'));
    }

    public function Catererlogin(Request $request)
    {
        return $this->attemptRoleLogin(
            $request,
            'caterer',
            route('caterer.dashboard'),
            'This account is not registered as a caterer.'
        );
    }

    public function showCatererRegister()
    {
        return view('auth.caterer-register');
    }

    public function Catererregister(Request $request)
    {
        $this->normalizeAuthInput($request);

        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^\+639\d{9}$/', 'unique:users,phone'],
            'barangay' => ['required'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ], [
            'phone.regex' => 'Phone number must be in format +639XXXXXXXXX (10 digits starting with 9)',
        ]);

        $user = User::create([
            'business_name' => $request->business_name,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'barangay' => $request->barangay,
            'password' => Hash::make($request->password),
            'role' => 'caterer',
        ]);

        Auth::login($user);

        return redirect()->route('caterer.dashboard');
    }

    // --- ADMIN METHODS ---

    public function showAdminLogin()
    {
        return $this->withNoCacheHeaders(response()->view('auth.admin-login'));
    }

    public function adminLogin(Request $request)
    {
        return $this->attemptRoleLogin(
            $request,
            'admin',
            route('admin.dashboard'),
            'This account is not registered as an admin.'
        );
    }

    public function showForgotPassword(Request $request)
    {
        $role = $this->validatedLoginRole($request->query('role'));

        return view('auth.forgot-password', [
            'loginRoute' => $this->loginRouteForRole($role),
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $this->normalizeEmail($request);

        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if (app()->environment(['local', 'testing'])) {
            $user = User::where('email', $request->email)->firstOrFail();
            $token = PasswordBroker::createToken($user);

            return redirect()
                ->route('password.reset', ['token' => $token, 'email' => $user->email])
                ->with('status', 'Simulation mode: reset link opened for you.');
        }

        $status = PasswordBroker::sendResetLink($request->only('email'));

        return $status === PasswordBroker::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)])->onlyInput('email');
    }

    public function showResetPassword(Request $request, string $token)
    {
        $email = $request->query('email');
        $role = is_string($email)
            ? User::where('email', strtolower(trim($email)))->value('role')
            : null;

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
            'loginRoute' => $this->loginRouteForRole($role),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $this->normalizeEmail($request);

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $loginRoute = $this->loginRouteForRole(User::where('email', $request->email)->value('role'));

        $status = PasswordBroker::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === PasswordBroker::PASSWORD_RESET
            ? redirect()->route($loginRoute)->with('status', __($status))
            : back()->withErrors(['email' => __($status)])->onlyInput('email');
    }

    // --- LOGOUT ---

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear Livewire cache
        if (file_exists(storage_path('framework/cache/livewire-tmp'))) {
            array_map('unlink', glob(storage_path('framework/cache/livewire-tmp/*')));
        }

        return $this->withNoCacheHeaders(redirect()->route('home'));
    }

    private function withNoCacheHeaders($response)
    {
        return $response->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }

    private function normalizeAuthInput(Request $request): void
    {
        $this->normalizeEmail($request);

        if ($request->has('phone')) {
            $request->merge([
                'phone' => $this->normalizePhone($request->input('phone')),
            ]);
        }
    }

    private function normalizeEmail(Request $request): void
    {
        if ($request->has('email')) {
            $request->merge([
                'email' => strtolower(trim((string) $request->input('email'))),
            ]);
        }
    }

    private function normalizePhone(?string $phone): string
    {
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

    private function attemptRoleLogin(
        Request $request,
        string $role,
        string $dashboardUrl,
        string $roleError,
        mixed $redirect = null
    ) {
        $this->normalizeEmail($request);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role !== $role) {
                Auth::logout();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => $roleError])->onlyInput('email');
            }

            $redirect = $this->safeRedirectTarget($redirect);

            if ($redirect && $role === 'client') {
                return redirect()->to($redirect);
            }

            return redirect()->intended($dashboardUrl);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    private function safeRedirectTarget(mixed $redirect): ?string
    {
        if (! is_string($redirect)) {
            return null;
        }

        $redirect = trim($redirect);

        if ($redirect === '') {
            return null;
        }

        if (Str::startsWith($redirect, '/') && ! Str::startsWith($redirect, '//')) {
            return $redirect;
        }

        if (Str::startsWith($redirect, url('/'))) {
            return $redirect;
        }

        return null;
    }

    private function loginRouteForRole(?string $role): string
    {
        return match ($role) {
            'admin' => 'admin.login',
            'caterer' => 'caterer.login',
            default => 'login',
        };
    }

    private function validatedLoginRole(mixed $role): ?string
    {
        return is_string($role) && in_array($role, ['admin', 'caterer', 'client'], true)
            ? $role
            : null;
    }
}
