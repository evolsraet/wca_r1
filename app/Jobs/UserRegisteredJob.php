<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\WelcomeNotification;
use App\Notifications\AligoNotification;

class UserRegisteredJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 이메일 전송
        $this->user->notify(new WelcomeNotification());

        // 알리고 알림톡 전송
        $this->user->notify(new AligoNotification([
            'tpl_data' => [
                'tpl_code' => env('SMS_TPL_CODE'),
                'receiver_1' => $this->user->phone,
                'subject_1' => '회원가입을 축하합니다.',
                'message_1' => "안녕하세요. 위카옥션에 회원가입을 축하드립니다.",
            ],
        ]));
    }
}
