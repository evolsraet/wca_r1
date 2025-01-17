<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Auction;
class AuctionIngNotification extends Notification
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

        // $auction = Auction::find($this->auction);
        // $owner = $auction->owner_name;
        // $car_no = $auction->car_no;

        return (new MailMessage)
                    ->subject($this->data['title'])
                    ->line($this->data['title'])
                    ->line($this->data['message1'])
                    ->line($this->data['message2'])
                    ->line($this->data['message3'])
                    ->line($this->data['message4'])
                    ->line($this->data['message5'])
                    ->action('바로가기', url($this->data['link']['url']));
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
