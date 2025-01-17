<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\Templates\NotificationTemplate;
use App\Models\User;
use App\Notifications\AuctionAfterFeeDonNotification;
use App\Notifications\AligoNotification;

class AuctionAfterFeeDonJob implements ShouldQueue
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

        $data = [
            'title' => '경매가 완료되었습니다.',
            'message' => '수수료를 입금해 주세요!',
            'data' => $this->auction,
            'status10' => $this->auction->final_price,
            'status11' => $this->auction->final_price,
            'status12' => $this->auction->final_price,
            'status13' => $this->auction->final_price,
            'status14' => '123-1234-1234',
            'status15' => '예금주',
            'status16' => '2025-01-16(수) 00시',
            'footerMsg' => '* 수수료 미입금시 클레임이 불가합니다.'
        ];

        $sendMessage = NotificationTemplate::basicTemplate($data);

        // 이메일 전송
        $user = User::find($this->user);
        $user->notify(new AuctionAfterFeeDonNotification($sendMessage));

        // 알림톡 전송
        // $user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' =>  $sendMessage['title'],
        //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message13'].'<br>'.$sendMessage['message14'].'<br>'.$sendMessage['message15'].'<br>'.$sendMessage['message16'].'<br>'.$sendMessage['message17'].'<br>'.$sendMessage['message18'].'<br>'.$sendMessage['message19'].'<br><br>바로가기'.$sendMessage['footerMsg'],
        //     ]
        // ]));


    }
}
