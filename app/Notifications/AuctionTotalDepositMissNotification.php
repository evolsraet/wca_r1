<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
class AuctionTotalDepositMissNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $sendMessage;
    public function __construct($sendMessage)
    {
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
        Log::info('AuctionTotalDepositMissNotification', [
            'name'=> 'AuctionTotalDepositMissNotification',
            'path'=> __FILE__,
            'line'=> __LINE__,
            'sendMessage' => $this->sendMessage
        ]);
        return (new MailMessage)
                    ->subject($this->sendMessage['title'])
                    ->line($this->sendMessage['title'])
                    ->line($this->sendMessage['message'])
                    ->line($this->sendMessage['message1'])
                    ->line($this->sendMessage['message2'])
                    ->line($this->sendMessage['message3'])
                    ->line($this->sendMessage['message7'])
                    ->line($this->sendMessage['message8'])
                    ->line($this->sendMessage['message9']);
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
