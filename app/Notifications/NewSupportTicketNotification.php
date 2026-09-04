<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewSupportTicketNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly SupportTicket $ticket) {}

    public function via(object $notifiable): array
    {
        return ['database']; // super admin gets dashboard notification only (no email spam)
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'      => "New support ticket: {$this->ticket->subject}",
            'message'    => "From {$this->ticket->name} ({$this->ticket->email}) — " . \Illuminate\Support\Str::limit($this->ticket->message, 80),
            'type'       => 'info',
            'action_url' => '/super/tickets/' . $this->ticket->id,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
