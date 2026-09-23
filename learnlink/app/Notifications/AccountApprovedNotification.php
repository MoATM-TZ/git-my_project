<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApprovedNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your LearnLink account is approved')
            ->greeting('Welcome to LearnLink, '.$notifiable->name.'!')
            ->line('Your account has been approved. You can now sign in and access your LearnLink dashboard.')
            ->action('Sign in to LearnLink', route('login'))
            ->line('We look forward to having you in the academic community.');
    }
}
