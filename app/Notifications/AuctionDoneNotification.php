<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Auction;
use Illuminate\Support\Facades\Log;

class AuctionDoneNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user;
    protected $auction;
    protected $mode;
    public function __construct($user, $auction, $mode)
    {
        $this->user = $user;
        $this->auction = Auction::find($auction);
        $this->mode = $mode;
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

        $auction = $this->auction;

        $owner_name = $auction->owner_name;
        $car_no = $auction->car_no;
        Log::info('경매 완료 알림', ['auction' => $auction]);

        // dd($this->mode);

        if($this->mode == 'user'){
            $subject = $owner_name . '님의 ' . $car_no . ' 차량이 경매 완료되었습니다.';
            $message = $owner_name . '님의 ' . $car_no . ' 차량이 경매가 완료되었습니다.';
        }else{
            $subject = $owner_name . '님의 ' . $car_no . ' 차량이 경매 완료되었습니다.';
            $message = $owner_name . '님의 ' . $car_no . ' 차량이 경매가 낙찰되어 결매완료되었습니다.';
        }

        return (new MailMessage)
                    ->subject($subject)
                    ->line($message);
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
