<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Log;


class UserRegisteredListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {

        //
        $user = $event->user;
        if ($user) {
            $this->succese($event);
        }


        // 사용자에게 알림 보내기
        // $event->user->notify(new WelcomeNotification());
        // Notification::send($user, new WelcomeNotification());
    }

    public function succese(UserRegistered $event)
    {
        Log::info('[회원가입] 알림이 성공적으로 전송되었습니다!', [
            'name'=> '회원가입 알림 전송 성공',
            'path'=> __FILE__,
            'line'=> __LINE__,
            'user_id' => $event->user->id,
            'email' => $event->user->email
        ]);
    }

}
