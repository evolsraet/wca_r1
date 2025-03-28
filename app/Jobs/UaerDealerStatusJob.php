<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\UaerDealerStatusNotification;
use Illuminate\Support\Facades\Log;
use App\Notifications\AligoNotification;
use App\Notifications\Templates\NotificationTemplate;

class UaerDealerStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $status;
    public function __construct($user, $status)
    {
        $this->user = $user;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = [
            'user' => $this->user,
            'status' => $this->status,
        ];

        $sendMessage = NotificationTemplate::userStatusTemplate($data);

        // Log::info('딜러 승인 결과 알림', ['sendMessage' => $sendMessage]);

        // 이메일 전송
        $this->user->notify(new UaerDealerStatusNotification($this->user, $sendMessage));



        // 알림톡 전송 ( tpl_code 부여하여 적용 필요 )
        // $this->user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' => $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'],
        //     ]
        // ]));

    }
}
