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
        // 이메일 전송
        $this->user->notify(new UaerDealerStatusNotification($this->user, $this->status));

        if($this->status == 'ok'){ 
            $message = '딜러 승인이 완료되었습니다.';
        }else{
            $message = '딜러 승인이 거절되었습니다.';
        }

        Log::info('딜러 승인 결과 알림', ['message' => $message]);

        // 알림톡 전송
        $this->user->notify(new AligoNotification([
            'tpl_data' => [
                'tpl_code' => env('SMS_TPL_CODE'),
                'receiver_1' => $this->user->phone,
                'subject_1' =>  '딜러 승인 결과',
                'message_1' => $message,
            ]
        ]));

    }
}
