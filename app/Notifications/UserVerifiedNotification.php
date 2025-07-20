<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserVerifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Account Verified Successfully!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your account has been successfully verified.')
            ->action('Go to Dashboard', url('/dashboard'))
            ->line('Thank you for using our Minecraft hosting platform!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Account Verified',
            'message' => 'Your account has been successfully verified. Welcome!',
            'url' => url('/dashboard'),
            'icon' => 'check-circle',
        ];
    }
}
