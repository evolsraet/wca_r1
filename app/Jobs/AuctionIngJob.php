<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionIngNotification;
use App\Notifications\AligoService;
use App\Models\Auction;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AligoNotification;
use Illuminate\Support\Facades\Log;
class AuctionIngJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $auction;
    protected $finalAt;
    public function __construct($user_id, $auction, $finalAt)
    {
        $this->user = $user_id;
        $this->auction = $auction;
        $this->finalAt = $finalAt;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $baseUrl = config('app.url');

        // 0000-00-00(일) 00시
        $auction = Auction::find($this->auction);

        // Log::info('경매 상태 변경 알림 시간 확인', ['finalAt' => $this->finalAt]);

        $weekdays = ['일', '월', '화', '수', '목', '금', '토'];
        $weekday = $weekdays[date('w', strtotime($this->finalAt))];
        $finalAtTrans = date('Y-m-d', strtotime($this->finalAt)) . "($weekday) " . date('H시', strtotime($this->finalAt));

        $data = [
            'title' => '경매 상태가 변경되었습니다.',
            'data' => Auction::find($this->auction),
            'status' => '경매진행',
            'status2' => $finalAtTrans,
            'link' => $baseUrl.'/auction/'.$this->auction
        ];

        $sendMessage = NotificationTemplate::basicTemplate($data);

        // 이메일 알림 
        $user = User::find($this->user);
        $user->notify(new AuctionIngNotification($user, $sendMessage));

        // 알리고 알림톡 전송
        // $user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $user->phone,
        //         'subject_1' => $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message4'].'<br>'.$sendMessage['message5'].'<br>바로가기'.$baseUrl.'/auction/'.$this->auction,
        //     ]
        // ]));
    }
}
