<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use App\Notifications\SupportTicketReplyNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'open');

        $query = SupportTicket::with('user')->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $tickets    = $query->paginate(20)->withQueryString();
        $openCount  = SupportTicket::where('status', 'open')->count();
        $totalCount = SupportTicket::count();

        return view('super.tickets.index', compact('tickets', 'status', 'openCount', 'totalCount'));
    }

    public function show(SupportTicket $ticket)
    {
        return view('super.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|max:5000',
            'status'      => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->update([
            'admin_reply' => $validated['admin_reply'],
            'status'      => $validated['status'],
            'replied_at'  => now(),
        ]);

        // Notify — registered users get email + dashboard notification
        // Guests (not in users table) get email only via AnonymousNotifiable
        if ($ticket->user) {
            $ticket->user->notify(new SupportTicketReplyNotification($ticket));
        } else {
            Notification::route('mail', $ticket->email)
                ->notify(new SupportTicketReplyNotification($ticket));
        }

        return back()->with('success', "Reply sent to {$ticket->email}.");
    }

    public function destroy(SupportTicket $ticket)
    {
        $ticket->delete();
        return back()->with('success', 'Ticket deleted.');
    }
}
