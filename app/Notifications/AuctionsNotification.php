<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\AligoChannel;
use Illuminate\Support\Facades\Log;
class AuctionsNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user;
    protected $data;
    protected $via;

    public function __construct($user, $data, $via=['mail','aligo'])
    {
        $this->user = $user;
        $this->data = $data;
        $this->via = $via;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if($this->via == ['aligo']){
            return [AligoChannel::class];
        }else{
            return $this->via;
        }
    }

    /**
     * 이메일 전송.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $sendMessage = $this->data;
        Log::info('sendMessage', $sendMessage);

        // $sendMessage['message'] 의 값을 \n 을 기분으로 배열로 변환
        $normalized = str_replace('\\n', "\n", $sendMessage['message']);
        $messageArray = explode("\n",$normalized);

        Log::info('messageArray', [$messageArray]);

        // line 에 $messageArray 의 값을 추가
        $mailMessage = new MailMessage();
        $mailMessage->subject($sendMessage['title']);
        $mailMessage->greeting('');
        
        foreach ($messageArray as $message) {
            $mailMessage->line($message);
        }
        // action 에 $sendMessage['link'] 의 값을 추가
        if(isset($sendMessage['link'])){
            $mailMessage->action($sendMessage['link']['text'], $sendMessage['link']['url']);
        }
        $mailMessage->salutation('');
    
        return $mailMessage;
    }

    /**
     * 알리고 알림톡 전송.
     */ 
    public function toAligo(object $notifiable)
    {
        Log::info('AligoNotification toAligo', ['phone' => $notifiable->phone]);
        return [
            'phone' => $notifiable->phone, // 예: 수신자 전화번호
            'tpl_data' => $this->data['tpl_data'],
        ];
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
