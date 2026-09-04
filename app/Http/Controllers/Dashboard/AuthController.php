<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('dashboard.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            event(new Lockout($request));
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();

            // SuperAdmin bypasses email verification
            if ($user->isSuperAdmin()) {
                return redirect()->intended('super');
            }

            // Block login if email is not verified — log them out and redirect to verify page
            if (! $user->hasVerifiedEmail()) {
                // Send a fresh verification email automatically on first blocked login
                $user->sendEmailVerificationNotification();

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('verification.pending')
                    ->with('email', $credentials['email'])
                    ->with('message', 'Please verify your email address. We sent a link to ' . $credentials['email'] . '. Check your inbox (and spam folder).');
            }

            return redirect()->intended('dashboard');
        }

        RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear any bound tenant from the IoC container
        if (app()->has('currentTenant')) {
            app()->forgetInstance('currentTenant');
        }

        return redirect('/dashboard/login');
    }
}
