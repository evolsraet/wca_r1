<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\UserResetPasswordNotification;
use App\Notifications\AligoNotification;
class UserResetPasswordLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $data;

    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 이메일 전송
        $this->user->notify(new UserResetPasswordNotification($this->data));

        // 알리고 알림톡 전송
        $this->user->notify(new AligoNotification([
            'tpl_data' => [
                'tpl_code' => env('SMS_TPL_CODE'),
                'receiver_1' => $this->user->phone,
                'subject_1' => '비밀번호 재설정 링크 발송',
                'message_1' => "안녕하세요. 위카옥션에 비밀번호 재설정 링크를 발송합니다.",
            ],
        ]));
    }
}
