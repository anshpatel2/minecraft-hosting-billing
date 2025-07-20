<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlanPurchasedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $plan;

    public function __construct($plan)
    {
        $this->plan = $plan;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Plan Purchased Successfully!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for purchasing the ' . $this->plan['name'] . ' plan.')
            ->line('Plan Details: ' . $this->plan['description'])
            ->action('View Plan', url('/plans'))
            ->line('Enjoy your new plan!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Plan Purchased',
            'message' => 'You have purchased the ' . $this->plan['name'] . ' plan.',
            'url' => url('/plans'),
            'icon' => 'shopping-cart',
        ];
    }
}
