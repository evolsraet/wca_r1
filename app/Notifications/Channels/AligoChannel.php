<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Notifications\AligoNotification;
use App\Helpers\SmsAligoHelper;
use App\Services\AligoService;

class AligoChannel
{

    protected $aligoService;

    public function __construct(AligoService $aligoService)
    {
        $this->aligoService = $aligoService;
    }
    public function send($notifiable, Notification $notification)
    {
        // 노티피케이션 데이터 가져오기
        $data = $notifiable;

        // 데이터 전송 로직 구현
        $this->sendAligoNotification($data, $notification, $notifiable);
    }

    protected function sendAligoNotification($data, $notification, $notifiable)
    {
        // 실제 전송 처리 로직 작성
        // 예: HTTP 요청, API 연동 등

        $data = $notification->toAligo($notifiable);
        $this->aligoService->send($data['phone'], $data['message'], $data['tpl_data']);
    }
}
?>