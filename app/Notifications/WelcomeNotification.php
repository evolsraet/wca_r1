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
    public function __construct()
    {
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
        try {
            return (new MailMessage)
                ->subject('위카옥션 회원가입을 환영합니다!')
                ->line('위카옥션 회원가입을 환영합니다!')
                ->action('위카옥션', url('/'));
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
