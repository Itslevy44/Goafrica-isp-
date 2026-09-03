<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SupportTicketReplyNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly SupportTicket $ticket) {}

    public function via(object $notifiable): array
    {
        // Registered users get both email + dashboard notification
        // Guest (anonymous notifiable) gets email only
        return $notifiable instanceof \App\Models\User
            ? ['mail', 'database']
            : ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Re: {$this->ticket->subject} — goAfrica Connect Support")
            ->greeting("Hello {$this->ticket->name},")
            ->line("We've replied to your support ticket.")
            ->line("**Your original message:**")
            ->line($this->ticket->message)
            ->line("---")
            ->line("**Our response:**")
            ->line($this->ticket->admin_reply)
            ->action('View on Dashboard', url('/dashboard/notifications'))
            ->salutation('— goAfrica Connect Support Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'      => "Support reply: {$this->ticket->subject}",
            'message'    => $this->ticket->admin_reply,
            'type'       => 'info',
            'action_url' => '/dashboard/notifications',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
