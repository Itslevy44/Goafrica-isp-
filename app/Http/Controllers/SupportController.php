<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\User;
use App\Notifications\SupportTicketReplyNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Public: Submit a support ticket from the landing page contact form.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:3000',
        ]);

        // Link to a registered user if the email matches
        $user = User::where('email', $validated['email'])->first();

        SupportTicket::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'user_id' => $user?->id,
            'status'  => 'open',
        ]);

        return back()->with('contact_success', 'Your message has been sent. We\'ll get back to you shortly.');
    }
}
