<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $item;
    protected $sendMessage;
    public function __construct($item, $sendMessage)
    {
        $this->item = $item;
        $this->sendMessage = $sendMessage;
        //
        // Log::info('WelcomeNotification 생성되었습니다!....');
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
        $title = $this->sendMessage['title'];
        $message1 = $this->sendMessage['message1'];
        try {
            return (new MailMessage)
                ->subject($title)
                ->line($message1)
                ->action('위카옥션', url(path: $this->sendMessage['link']));
        } catch (\Exception $e) {
            Log::channel('mail')->error('메일 전송 실패: ' . $e->getMessage(), [
                'user' => $notifiable->email ?? 'unknown',
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            throw $e; // 예외를 다시 던져서 Laravel이 처리하도록 함
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
