<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionTotalDepositMissNotification;
use App\Notifications\Templates\NotificationTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\AligoNotification;

class AuctionTotalDepositMissJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user;
    public $auction;
    public function __construct($user, $auction)
    {
        $this->user = $user;
        $this->auction = $auction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $formattedDate = Carbon::now()->format('Y-m-d');

        $data = [
            'title' => '차량대금 입금이 확인 되지 않았습니다.',
            'message' => "미입금시 경매가 취소됩니다 😅",
            'data' => $this->auction,
            'status4' => $this->auction['final_price'],
            'status5' => $this->auction['bank'].' '.$this->auction['account'],
            'status6' => $this->auction['taksong_wish_at'],
        ];

        $sendMessage = NotificationTemplate::basicTemplate($data);

        Log::info('AuctionTotalDepositMissJob', $sendMessage);
        // 이메일 전송
        $this->user->notify(new AuctionTotalDepositMissNotification($sendMessage));

        // 알리고 알림톡 알림
        // $this->user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' =>  $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message7'].'<br>'.$sendMessage['message8'].'<br>'.$sendMessage['message9'],
        //     ]
        // ]));

    }
}

