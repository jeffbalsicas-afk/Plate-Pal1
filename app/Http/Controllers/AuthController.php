<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // --- CLIENT METHODS ---

    public function showLogin()
    {
        return view('auth.client-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $redirect = $request->input('redirect');
            
            if ($redirect) {
                return redirect($redirect);
            }
            
            if ($user->role === 'caterer') {
                return redirect()->intended(route('caterer.dashboard'));
            }
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('client.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.client-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
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
        return view('auth.caterer-login');
    }

    public function Catererlogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role !== 'caterer') {
                Auth::logout();
                return back()->withErrors(['email' => 'This account is not registered as a caterer.'])->onlyInput('email');
            }
            return redirect()->intended(route('caterer.dashboard'));
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function showCatererRegister()
    {
        return view('auth.caterer-register');
    }

    public function Catererregister(Request $request)
    {
        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required'],
            'barangay' => ['required'],
            'password' => ['required', 'confirmed'],
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

    // --- LOGOUT ---

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
