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
    protected $sendMessage;
    public function __construct($user, $auction, $mode, $sendMessage)
    {
        $this->user = $user;
        $this->auction = Auction::find($auction);
        $this->mode = $mode;
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

        $auction = $this->auction;

        // $owner_name = $auction->owner_name;
        // $car_no = $auction->car_no;

        // // dd($this->mode);

        // if($this->mode == 'user'){
        //     $subject = $owner_name . '님의 ' . $car_no . ' 차량이 경매 완료되었습니다.';
        //     $message = $owner_name . '님의 ' . $car_no . ' 차량이 경매가 완료되었습니다.';
        // }else{
        //     $subject = $owner_name . '님의 ' . $car_no . ' 차량이 경매 완료되었습니다.';
        //     $message = $owner_name . '님의 ' . $car_no . ' 차량이 경매가 낙찰되어 결매완료되었습니다.';
        // }

        return (new MailMessage)
                    ->subject($this->sendMessage['title'])
                    ->line($this->sendMessage['title'])
                    ->line($this->sendMessage['message'])
                    ->line($this->sendMessage['message1'])
                    ->line($this->sendMessage['message2'])
                    ->line($this->sendMessage['message3'])
                    ->when(!empty($this->sendMessage['message12']), function ($mailMessage) {
                        return $mailMessage->line($this->sendMessage['message12']);
                    })
                    ->when(!empty($this->sendMessage['message13']), function ($mailMessage) {
                        return $mailMessage->line($this->sendMessage['message13']);
                    })
                    ->when(!empty($this->sendMessage['message14']), function ($mailMessage) {
                        return $mailMessage->line($this->sendMessage['message14']);
                    })
                    ->when(!empty($this->sendMessage['message15']), function ($mailMessage) {
                        return $mailMessage->line($this->sendMessage['message15']);
                    })
                    ->when(!empty($this->sendMessage['message16']), function ($mailMessage) {
                        return $mailMessage->line($this->sendMessage['message16']);
                    })
                    ->when(!empty($this->sendMessage['message17']), function ($mailMessage) {
                        return $mailMessage->line($this->sendMessage['message17']);
                    })
                    ->when(!empty($this->sendMessage['message18']), function ($mailMessage) {
                        return $mailMessage->line($this->sendMessage['message18']);
                    })
                    ->when(!empty($this->sendMessage['message19']), function ($mailMessage) {
                        return $mailMessage->line($this->sendMessage['message19']);
                    })
                    ->when(!empty($this->sendMessage['link']['url']), function ($mailMessage) {
                        return $mailMessage->action($this->sendMessage['link']['text'], $this->sendMessage['link']['url']);
                    })
                    ->when(!empty($this->sendMessage['footerMsg']), function ($mailMessage) {
                        return $mailMessage->line($this->sendMessage['footerMsg']);
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
