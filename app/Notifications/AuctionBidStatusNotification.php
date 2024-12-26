<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Auction;
use Illuminate\Support\Facades\Log;
use App\Models\User;
class AuctionBidStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user;
    protected $status;
    protected $auction;
    protected $result;
    protected $bid_user;
    public function __construct($user, $status, $auction, $result)
    {
        $this->user = $user;
        $this->status = $status;
        $this->auction = $auction;
        $this->result = $result;
        $this->bid_user = User::find($this->result);
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

        Log::info('경매 입찰 상태 알림', ['user' => $this->user, 'status' => $this->status, 'auction' => $this->auction]);

        $bid_user_name = $this->bid_user->name;
        $ownerName = $this->auction->owner_name; // 소유자 이름
        $car_no = $this->auction->car_no;

        if($this->status == 'ask'){
            $message = $bid_user_name . '님이 ' . $ownerName . '님의 ' . $car_no . ' 차량에 입찰 신청하였습니다.';
        }else if($this->status == 'reauction'){
            $message = $ownerName . '님의 ' . $car_no . ' 차량이 재경매 신청하였습니다.';
        }else{
            $message = $bid_user_name . '님이 ' . $ownerName . '님의 ' . $car_no . ' 차량에 입찰 취소하였습니다.';
        }
        return (new MailMessage)
                    ->subject($message)
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
