<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the login credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Redirect to intended page or dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Authentication failed, redirect back with error
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function formRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate the registration data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // Will be automatically hashed by User model
        ]);

        // Log the user in
        Auth::login($user);

        // Redirect to dashboard
        return redirect()->route('dashboard');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function datatable()
    {
        return view('admin.datatable');
    }
}
