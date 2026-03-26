<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApiResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = config('app.frontend_reset_url')
            . '?token=' . $this->token
            . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Reset Password')
            ->view('email-template.forgot-password', [
                'resetUrl' => $resetUrl,
                'email' => $notifiable->email,
                'expire' => config('auth.passwords.users.expire'),
            ]);
    }
}
