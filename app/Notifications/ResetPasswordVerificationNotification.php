<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ichtrojan\Otp\Otp;
class ResetPasswordVerificationNotification extends Notification
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    private $otp;

    public function __construct()
    {
        $this->message = 'Use the below codee for resetting your password';
        $this->subject = 'Password Resetting';
        $this->fromEmail = "yaheiaibrahim3@gmail.com";
        $this->mailer = 'smtp';
        $this->otp = new Otp;
    }


    public function via($notifiable): array
    {
        return ['mail'];
    }
    public function toMail($notifiable): MailMessage
    {
        $otp = $this->otp->generate($notifiable->email,'numeric',6,60);
        return (new MailMessage)
            ->mailer('smtp')
            ->subject($this->subject)
            ->greeting('Hello'.$notifiable->name)
            ->line($this->message)
            ->line('code: '. $otp->token);
    }


    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
