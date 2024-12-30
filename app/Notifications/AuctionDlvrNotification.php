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
        $taksongWishAt = $this->data['taksongWishAt'];
        $carNo = $this->data['carNo'];
        $carModel = $this->data['carModel'];
        $mobile = $this->data['mobile'];
        $destMobile = $this->data['destMobile'];
        $startAddr = $this->data['startAddr'];
        $destAddr = $this->data['destAddr'];

        return (new MailMessage)
                    ->subject($carNo.'차량이 탁송신청 되었습니다.')
                    ->line($carNo.'차량이 탁송신청 되었습니다.')
                    ->line('탁송 날짜 : '.$taksongWishAt)
                    ->line('출발지 전화번호 : '.$mobile)
                    ->line('출발지 주소 : '.$startAddr)
                    ->line('도착지 전화번호 : '.$destMobile)
                    ->line('도착지 주소 : '.$destAddr);
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
