<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionExpiryNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $daysLeft,
        public readonly string $tenantName
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->daysLeft <= 0
            ? "⚠️ Your goAfrica Connect subscription has expired"
            : "⏰ Your subscription expires in {$this->daysLeft} day" . ($this->daysLeft === 1 ? '' : 's');

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},");

        if ($this->daysLeft <= 0) {
            $mail->line("Your **{$this->tenantName}** subscription on goAfrica Connect has **expired**.")
                 ->line("Your dashboard is currently locked. Renew now to restore access and keep your hotspot running.")
                 ->action('Renew Now — KES 500', url('/dashboard/subscribe'));
        } elseif ($this->daysLeft <= 3) {
            $mail->line("Your **{$this->tenantName}** subscription expires in **{$this->daysLeft} day" . ($this->daysLeft === 1 ? '' : 's') . "**.")
                 ->line("Renew now to avoid any interruption to your hotspot billing service.")
                 ->action('Renew Subscription', url('/dashboard/subscribe'));
        } else {
            $mail->line("Your **{$this->tenantName}** subscription will expire in **{$this->daysLeft} days**.")
                 ->line("Plan ahead and renew your subscription to keep everything running smoothly.")
                 ->action('View Subscription', url('/dashboard/subscribe'));
        }

        return $mail
            ->line('Thank you for using goAfrica Connect.')
            ->salutation('— The goAfrica Connect Team');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'     => $this->daysLeft <= 0
                ? 'Subscription Expired'
                : "Subscription expires in {$this->daysLeft} day" . ($this->daysLeft === 1 ? '' : 's'),
            'message'   => $this->daysLeft <= 0
                ? 'Your dashboard is locked. Renew now to restore access.'
                : 'Renew your subscription to avoid service interruption.',
            'type'      => $this->daysLeft <= 0 ? 'danger' : ($this->daysLeft <= 3 ? 'warning' : 'info'),
            'action_url'=> '/dashboard/subscribe',
            'days_left' => $this->daysLeft,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
