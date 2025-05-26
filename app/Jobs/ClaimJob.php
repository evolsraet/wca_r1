<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AuctionsNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ClaimJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $userId;
    protected $data;
    protected $type;

    public function __construct($userId, $data, $type)
    {
        $this->userId = $userId;
        $this->data = $data;
        $this->type = $type;
    }

    public function handle(): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            Log::warning("[클레임] 알림 전송 실패: 유저 없음. ID: {$this->userId}", [
                'name'=> '클레임 알림 전송 실패',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'userId' => $this->userId
            ]);
            return; // 유저 없으면 처리 중단
        }

        if ($this->type === 'admin') {
            $notificationTemplate = NotificationTemplate::getTemplate(
                'ClaimAdminJob',
                $this->data,
                ['mail']
            );

            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail']));
        }

        if ($this->type === 'adminUpdate') {
            $notificationTemplate = NotificationTemplate::getTemplate(
                'ClaimAdminUpdateJob',
                $this->data,
                ['mail']
            );

            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail']));
        }

        if($this->type === 'comment'){
            $notificationTemplate = NotificationTemplate::getTemplate(
                'ClaimCommentJob',
                $this->data,
                ['mail']
            );

            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail']));
        }

        if($this->type === 'comment_update'){
            $notificationTemplate = NotificationTemplate::getTemplate(
                'ClaimCommentUpdateJob',
                $this->data,
                ['mail']
            );

            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail']));
        }

    }
}
