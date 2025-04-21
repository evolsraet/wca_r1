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
use App\Notifications\AuctionsNotification;

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

        // $data = [
        //     'title' => '경매가 완료되었습니다.',
        //     'message' => '수수료를 입금해 주세요!',
        //     'data' => $this->auction,
        //     'status10' => $this->auction->final_price,
        //     'status11' => $this->auction->final_price,
        //     'status12' => $this->auction->final_price,
        //     'status13' => $this->auction->final_price,
        //     'status14' => '123-1234-1234',
        //     'status15' => '예금주',
        //     'status16' => '2025-01-16(수) 00시',
        //     'footerMsg' => '* 수수료 미입금시 클레임이 불가합니다.'
        // ];

        // $sendMessage = NotificationTemplate::basicTemplate($data);

        // // 이메일 전송
        // $user = User::find($this->user);
        // $user->notify(new AuctionAfterFeeDonNotification($sendMessage));


        $notificationTemplate6 = NotificationTemplate::getTemplate('AuctionDoneJobDealer', $this->auction, ['mail']);
        $user = User::find($this->user);
        $this->user->notify(new AuctionsNotification($user, $notificationTemplate6, ['mail']));


    }
}
