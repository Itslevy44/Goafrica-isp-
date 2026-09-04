<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\User;
use App\Notifications\NewSupportTicketNotification;
use Illuminate\Http\Request;

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

        $ticket = SupportTicket::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'user_id' => $user?->id,
            'status'  => 'open',
        ]);

        // Notify all super admins via dashboard notification
        $superAdmins = User::where('role', 'super_admin')->whereNull('tenant_id')->get();
        foreach ($superAdmins as $admin) {
            try {
                $admin->notify(new NewSupportTicketNotification($ticket));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to notify super admin of new ticket: ' . $e->getMessage());
            }
        }

        return back()->with('contact_success', "Your message has been sent. We'll get back to you shortly.");
    }
}
