<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Notifications\AuctionStartNotification;
use App\Notifications\AligoNotification;
use App\Notifications\Templates\NotificationTemplate;

class AuctionStartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $auction;
    protected $user;
    protected $data;
    protected $isUser;
    public function __construct($auction, $user, $data, $isUser = false)
    {
        $this->auction = $auction;
        $this->user = $user;
        $this->data = $data;
        $this->isUser = $isUser;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $title = '경매 등록신청이 완료 되었습니다.';

        $data = [
            'title' => $title,
            'data' => json_decode($this->data)
        ];

        if($this->isUser){
            $data['footerMsg'] = '진단일정 확인 후 연락드리겠습니다.';
        }

        $sendMessage = NotificationTemplate::basicTemplate($data);

        // Log::info('AuctionStartJob_sendMessage', ['sendMessage' => $sendMessage]);

        //이메일 전송
        // Log::info('AuctionStartJob', ['auction' => $this->auction, 'user' => $this->user]);
        $this->user->notify(new AuctionStartNotification($this->auction, $this->user, data: $sendMessage));

        // 알리고 알림톡 전송 
        // $this->user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' => $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br><br>'.$sendMessage['footerMsg'],
        //     ],
        // ]));

    }
}
