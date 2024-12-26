<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Auction;
use Carbon\Carbon;
class AuctionCohosenNotification extends Notification
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

        $owner_name = $this->auction->owner_name;
        $car_no = $this->auction->car_no;

        $taksong_wish_at = Carbon::parse($this->auction->taksong_wish_at)->format('Y-m-d');
        $taksong_wish_at_time = Carbon::parse($this->auction->taksong_wish_at)->format('H:i');
        $bank = $this->auction->bank;
        $bank_account = $this->auction->account;
        $final_price = $this->auction->final_price;

        if($this->mode == 'user'){
            $subject = $this->user->name . '님이 ' . $owner_name . '님의 ' . $car_no . ' 차량을 선택하였습니다.';
            $message = $this->user->name . '님이 ' . $owner_name . '님의 ' . $car_no . ' 차량을 선택하였습니다.';
        }else{
            $subject = $owner_name . '님의 ' . $car_no . ' 차량이 선택되었습니다.';
            $message = $owner_name . '님의 ' . $car_no . ' 차량이 선택되었습니다. 추후 탁송 예정입니다.';
            $message .= '탁송 예정일은 ' . $taksong_wish_at . ' ' . $taksong_wish_at_time . ' 입니다.';
            $message .= '탁송 은행은 ' . $bank . ' 이며, 계좌는 ' . $bank_account . ' 입니다.';
            $message .= '최종 경매 가격은 ' . $final_price . ' 원 입니다.'; 
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
