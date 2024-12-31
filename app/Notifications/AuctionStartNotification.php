<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
// use Illuminate\Support\Facades\Log;
class AuctionStartNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $auction;
    protected $user;
    protected $data;
    public function __construct($auction, $user, $data)
    {
        $this->auction = $auction;
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

        $data = json_decode($this->data);

        // Log::info('AuctionStartNotification', ['data' => $this->data]);

        $ownerName = $data->owner_name; // 소유자 이름
        $car_no = $data->car_no;

        return (new MailMessage)
                    ->subject($ownerName . '님의 ' . $car_no . ' 차량이 경매 등록신청 완료되었습니다.')
                    ->line($ownerName . '님의 ' . $car_no . ' 차량이 경매 등록신청 완료되었습니다.');
                    // ->action('등록신청', url('/'))
                    // ->line('감사합니다!');
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
