<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class UserResetPasswordNotification extends ResetPasswordNotification
{
    use Queueable;

    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(env('APP_URL') . '/resetPasswordLogin/' . $this->data);
        return (new MailMessage)
            ->line('귀하의 계정에 대한 비밀번호 재설정 요청이 접수되었기 때문에 이 이메일을 보내드립니다.')
            ->action('비밀번호 재설정', $resetUrl)
            ->line('만약 비밀번호 재설정을 요청하지 않았다면 이 이메일을 무시하세요.');
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
