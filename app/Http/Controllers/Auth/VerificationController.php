<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class VerificationController extends Controller
{
    // -------------------------------------------------------------------------
    // Show notice page for already-authenticated unverified users
    // -------------------------------------------------------------------------
    public function show(Request $request)
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.verify');
    }

    // -------------------------------------------------------------------------
    // Public pending page — shown when user is logged out but needs to verify.
    // (Reached after a blocked login attempt)
    // -------------------------------------------------------------------------
    public function pending(Request $request)
    {
        // If already verified and authenticated, go to dashboard
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.verify-pending');
    }

    // -------------------------------------------------------------------------
    // Verify the email — works WITHOUT being authenticated (works from any device)
    // -------------------------------------------------------------------------
    public function verify(Request $request, $id, $hash)
    {
        // Find user by ID
        $user = User::findOrFail($id);

        // Validate signed URL
        if (! URL::hasValidSignature($request)) {
            return redirect()->route('dashboard.login')
                ->withErrors(['email' => 'This verification link is invalid or has expired. Please log in and request a new one.']);
        }

        // Verify hash matches email
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('dashboard.login')
                ->withErrors(['email' => 'This verification link is not valid for this account.']);
        }

        // Already verified
        if ($user->hasVerifiedEmail()) {
            if (! Auth::check()) {
                Auth::login($user);
            }
            return redirect()->route('dashboard.index')
                ->with('success', '✅ Your email is already verified. Welcome back!');
        }

        // Mark as verified
        $user->email_verified_at = Carbon::now();
        $user->save();

        event(new Verified($user));

        // Auto-login so they land directly on the dashboard
        Auth::login($user);

        return redirect()->route('dashboard.index')
            ->with('success', '✅ Email verified successfully! Welcome to GoAfrica Connect.');
    }

    // -------------------------------------------------------------------------
    // Resend verification email for authenticated users (from dashboard banner)
    // -------------------------------------------------------------------------
    public function resend(Request $request)
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard.index');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'A fresh verification link has been sent to your email address.');
    }

    // -------------------------------------------------------------------------
    // Resend verification email for unauthenticated users on the pending page.
    // User enters their email address to receive a new link.
    // -------------------------------------------------------------------------
    public function resendPending(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            // Don't reveal whether the email exists for security
            return back()->with('message', 'If that email exists in our system, a verification link has been sent.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard.login')
                ->with('success', 'Your email is already verified. Please log in.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('message', 'Verification email sent to ' . $request->email . '. Please check your inbox and spam folder.');
    }
}
