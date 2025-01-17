<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\WelcomeNotification;
use App\Notifications\AligoNotification;
use Illuminate\Support\Facades\Log;
use App\Notifications\Templates\NotificationTemplate;
class UserRegisteredJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        // Log::info('UserRegisteredJob 실행되었습니다!....', ['item' => $this->item]);
        
        $item = $this->item;
        $name = $item->name;

        // 회원가입 메시지 템플릿
        $sendMessage = NotificationTemplate::welcomeTemplate($name);

        // 이메일 전송
        $this->item->notify(new WelcomeNotification($this->item, $sendMessage));

        // 알리고 알림톡 전송 ( tpl_code 부여하여 적용 필요 )
        // $this->item->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->item->phone,
        //         'subject_1' => $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'],
        //     ],
        // ]));
    }
}
