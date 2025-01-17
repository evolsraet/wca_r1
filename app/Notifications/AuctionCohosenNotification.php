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

        return (new MailMessage)
                    ->subject($this->sendMessage['title'])
                    ->line($this->sendMessage['title'])
                    ->line($this->sendMessage['message'])
                    ->line($this->sendMessage['message1'])
                    ->line($this->sendMessage['message2'])
                    ->line($this->sendMessage['message3'])
                    ->line($this->sendMessage['message7'])
                    ->when(!empty($this->sendMessage['link']['url']), function ($mailMessage) {
                        return $mailMessage->action('바로가기', $this->sendMessage['link']['url']);
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
