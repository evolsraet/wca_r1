<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\AuctionTotalDepositNotification;
use App\Notifications\Templates\NotificationTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
class AuctionTotalDepositJob implements ShouldQueue
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
        $baseUrl = config('app.url');

        $formattedDate = Carbon::parse($this->auction['taksong_wish_at'])->format('Y-m-d(D) H시');

        $data = [
            'title' => '차량대금 입금확인 되어 탁송이 진행됩니다.',
            'message' => "탁송은 '위카탁송'에서 진행되며 별도의 안내 문자가 발송됩니다.",
            'data' => $this->auction,
            'status7' => $formattedDate, // 탁송예정일
            'status8' => '('.$this->auction['addr_post'].')'.$this->auction['addr'].' '.$this->auction['addr2'], // 탁송출발지
        ];

        $sendMessage = NotificationTemplate::basicTemplate($data);

        $user = User::find($this->user);
        $user->notify(new AuctionTotalDepositNotification($user, $sendMessage));

        // 알리고 알림톡 알림
        // $this->user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' =>  $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message10'].'<br>'.$sendMessage['message11'],
        //     ]
        // ]));


    }
}

