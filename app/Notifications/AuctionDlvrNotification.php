<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionDlvrNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user;
    protected $data;
    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
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
                    ->subject($this->data['title'])
                    ->line($this->data['title'])
                    ->line($this->data['message'])
                    ->line($this->data['message1'])
                    ->line($this->data['message2'])
                    ->line($this->data['message3'])
                    ->line($this->data['message7'])
                    ->line($this->data['message8'])
                    ->line($this->data['message9'])
                    ->line($this->data['message_1'])
                    ->line($this->data['message_2'])
                    ->line($this->data['message_3'])
                    ->line($this->data['message_4'])
                    ->line($this->data['message_5'])
                    ->line($this->data['message_6'])
                    ->line($this->data['message_7'])
                    ->line($this->data['message_8'])
                    ->line($this->data['message_9'])
                    ->when(($this->data['link']['url'] !== "/"), function ($mailMessage) {
                        return $mailMessage->action($this->data['link']['text'], $this->data['link']['url']);
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
