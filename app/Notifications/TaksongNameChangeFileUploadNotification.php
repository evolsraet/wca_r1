<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaksongNameChangeFileUploadNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $user;
    public $sendMessage;
    public function __construct($user, $sendMessage)
    {
        $this->user = $user;
        $this->sendMessage = $sendMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject($this->sendMessage['title'])
                    ->line($this->sendMessage['title'])
                    ->line($this->sendMessage['message'])
                    ->when(($this->sendMessage['link']['url'] !== "/"), function ($mailMessage) {
                        return $mailMessage->action($this->sendMessage['link']['text'], $this->sendMessage['link']['url']);
                    });
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
