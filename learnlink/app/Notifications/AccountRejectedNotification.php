<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRejectedNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update on your LearnLink account request')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('After review, your LearnLink account request was not approved.')
            ->line('If you believe this decision was made in error, please contact LearnLink support.');
    }
}
