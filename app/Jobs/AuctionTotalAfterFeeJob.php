<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionTotalAfterFeeNotification;
use App\Notifications\Templates\NotificationTemplate;

class AuctionTotalAfterFeeJob implements ShouldQueue
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
        $data = [
            'title' => '수수료 입금확인 되었습니다.',
            'message' => "수수료 입금확인 되었습니다. 위카옥션을 이용해주셔서 감사합니다.",
            'data' => $this->auction
        ];

        $sendMessage = NotificationTemplate::basicTemplate($data);

        $user = User::find($this->user);
        $user->notify(new AuctionTotalAfterFeeNotification($user, $sendMessage));

        // 알리고 알림톡 알림
        // $this->user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' =>  $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'],
        //     ]
        // ]));

    }
}
