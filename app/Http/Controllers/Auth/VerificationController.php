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
    /**
     * Show the verification notice.
     */
    public function show(Request $request)
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * This is a custom implementation that does NOT require the user to be
     * already authenticated, so the link works when opened on any device
     * (e.g. phone) that is not logged in.
     */
    public function verify(Request $request, $id, $hash)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Verify the signed URL is valid and belongs to this user
        if (! URL::hasValidSignature($request)) {
            abort(403, 'This verification link is invalid or has expired.');
        }

        // Check that the hash matches
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'This verification link is not valid for this account.');
        }

        // Already verified - just redirect
        if ($user->hasVerifiedEmail()) {
            // Log the user in if they aren't
            if (! Auth::check()) {
                Auth::login($user);
            }
            return redirect()->route('dashboard.index')->with('success', 'Your email was already verified. Welcome!');
        }

        // Mark as verified
        $user->email_verified_at = Carbon::now();
        $user->save();

        event(new Verified($user));

        // Log the user in automatically so they land on the dashboard
        Auth::login($user);

        return redirect()->route('dashboard.index')
                         ->with('success', '✅ Email verified successfully! Welcome to GoAfrica Connect.');
    }

    /**
     * Resend the verification notification.
     */
    public function resend(Request $request)
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard.index');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'A fresh verification link has been sent to your email.');
    }
}
