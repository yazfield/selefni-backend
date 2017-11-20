<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivationEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(User $notifiable)
    {
        // TODO: setup lang files and email views
        return (new MailMessage)->greeting('Hello,')
            ->subject('Selefni Acount Activation')
            ->line('Welcome to Selefni app, you shall never forget what you have borrowed or lent.')
            ->line('Thank you for using our application!')
            ->action('Activate your account', 'https://www.selefni.com')
            ->line(sprintf('Or use this activation code: %d', $notifiable->activation_code));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [//
        ];
    }
}
