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
use App\Models\User;
use App\Notifications\AuctionsNotification;

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

        Log::info('UserRegisteredJob 실행되었습니다!....', ['item' => $this->item]);
        
        $item = $this->item;
        $name = $item->name;

        // dd($item);

        $data = [
            'user' => $item,
            'name' => $name,
            'title' => '회원가입 알림',
            'message' => '회원가입 알림',
        ];

        // 회원가입 메시지 템플릿
        $notificationTemplate = NotificationTemplate::getTemplate('welcome', $item, ['mail']);


        // 운영자 회원에게 알림 보내기 추가 / id 가 1, 2 인 회원에게 알림 보내기
        $adminUsers = User::whereIn('id', [1, 2])->get();
        foreach ($adminUsers as $adminUser) {
            // $adminUser->notify(new WelcomeNotification($this->item, $sendMessage));
            $adminUser->notify(new AuctionsNotification($this->item, $notificationTemplate, ['mail']));
            
        }
        // 이메일 전송
        // $this->item->notify(new WelcomeNotification($this->item, $sendMessage));
        $this->item->notify(new AuctionsNotification($this->item, $notificationTemplate, ['mail']));

    }
}
