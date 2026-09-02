<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BulkAdminNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $subject,
        public readonly string $body,
        public readonly string $actionText = '',
        public readonly string $actionUrl  = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->subject)
            ->greeting("Hello {$notifiable->name},")
            ->line($this->body);

        if ($this->actionText && $this->actionUrl) {
            $mail->action($this->actionText, $this->actionUrl);
        }

        return $mail
            ->salutation('— goAfrica Connect Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'      => $this->subject,
            'message'    => $this->body,
            'type'       => 'info',
            'action_url' => $this->actionUrl ?: null,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
