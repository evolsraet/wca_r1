<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\AligoChannel;
use Illuminate\Support\Facades\Log;

class AligoNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['aligo'];
        return [AligoChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toAligo($notifiable)
    {
        Log::info('AligoNotification toAligo', ['message' => $this->data['message'], 'phone' => $notifiable->phone]);
        return [
            'message' => $this->data['message'],
            'phone' => $notifiable->phone, // 예: 수신자 전화번호
            'tpl_data' => $this->data['tpl_data'],
        ];
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
