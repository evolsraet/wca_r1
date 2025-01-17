<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
class AuctionBidStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $data;
    protected $case;
    public function __construct($data, $case)
    {
        $this->data = $data;
        $this->case = $case;
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
        // 데이터 요소가 배열인지 확인하고 문자열로 변환
        $title = is_array($this->data['title']) ? implode(' ', $this->data['title']) : $this->data['title'];
        $message1 = is_array($this->data['message1']) ? implode(' ', $this->data['message1']) : $this->data['message1'];
        $message2 = is_array($this->data['message2']) ? implode(' ', $this->data['message2']) : $this->data['message2'];
        // 필요한 경우 다른 메시지도 동일하게 처리

        switch ($this->case) {
            case 'ask':
                return (new MailMessage)
                    ->subject($title)
                    ->line($title)
                    ->line($message1)
                    ->line($message2)
                    ->line($this->data['message3'])
                    ->line($this->data['message4'])
                    ->line($this->data['message5']);

            case 'bid':
                return (new MailMessage)
                    ->subject($title)
                    ->line($title)
                    ->line($message1)
                    ->line($message2)
                    ->line($this->data['message3'])
                    ->line($this->data['message6'])
                    ->line($this->data['message7'])
                    ->action('바로가기', url($this->data['link']['url']));

            case 'wait':
            case 'reauction':
                return (new MailMessage)
                    ->subject($title)
                    ->line($title)
                    ->line($message1)
                    ->line($message2)
                    ->line($this->data['message3'])
                    ->line($this->data['message4'])
                    ->action('바로가기', url($this->data['link']['url']));

            // 다른 상태 케이스 추가 가능
        }
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
