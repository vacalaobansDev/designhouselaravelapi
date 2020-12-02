<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;


class ResetPassword extends Notification
{

    public function toMail($notifiable)
    {
        $url = url(config( 'app.client_url' ).'/password/reset/'.$this->token) .'?email='.urlencode( $notifiable->email );
        return (new MailMessage)
                    ->line('Your are receiving this email is because you are received a password reset request for you account.')
                    ->action('Reset Password', $url )
                    ->line('If you din not request a password reset, no further action is required!');
    }

}
