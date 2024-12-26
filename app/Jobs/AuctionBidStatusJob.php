<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\AuctionBidStatusNotification;
use App\Models\User;
use App\Models\Auction;
use App\Notifications\AligoNotification;
use Illuminate\Support\Facades\Log;
class AuctionBidStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $status;
    protected $auction;
    protected $result;
    public function __construct($user, $status, $auction, $result)
    {
        $this->user = User::find($user);
        $this->status = $status;
        $this->auction = Auction::find($auction);
        $this->result = $result;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // dd($this->user, $this->status);
        // 이메일 전송
        $this->user->notify(new AuctionBidStatusNotification($this->user, $this->status, $this->auction, $this->result));

        $ownerName = $this->auction->owner_name . '님의 ' . $this->auction->car_no; // 소유자 이름

        if($this->status == 'ask'){
            $message = $ownerName . '님의 차량이 입찰 신청되었습니다.';
        }else if($this->status == 'reauction'){
            $message = $ownerName . '님의 차량이 재경매 신청되었습니다.';
        }else{
            $message = $ownerName . '님의 차량이 입찰 취소되었습니다.';
        }

        Log::info('경매 입찰 상태  알리고 알림톡 전송알림 내용', ['message' => $message]);

        // 알리고 알림톡 전송
        // $this->user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' => $message,
        //         'message_1' => $message,
        //     ]
        // ]));

    }
}
