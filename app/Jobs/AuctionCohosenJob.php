<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionCohosenNotification;
use Illuminate\Support\Facades\Log;
use App\Notifications\AligoNotification;
use App\Models\Auction;
use Carbon\Carbon;

class AuctionCohosenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $auction;
    protected $mode;
    public function __construct($user_id, $auction_id, $mode)
    {
        $this->user = $user_id;
        $this->auction = $auction_id;
        $this->mode = $mode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 이메일 전송
        $user = User::find($this->user);
        $user->notify(new AuctionCohosenNotification($user, $this->auction, $this->mode));

        Log::info('경매 선택 알림', ['user' => $user, 'auction' => $this->auction]);

        $auction = Auction::find($this->auction);
        $owner_name = $auction->name;
        $car_no = $auction->car_no;
        $taksong_wish_at = Carbon::parse($auction->taksong_wish_at)->format('Y-m-d');
        $taksong_wish_at_time = Carbon::parse($auction->taksong_wish_at)->format('H:i');
        $bank = $auction->bank;
        $bank_account = $auction->account;
        $final_price = $auction->final_price;
        

        Log::info($owner_name.'님의 '.$car_no.'가 선택되었습니다.', ['owner_name' => $owner_name, 'car_no' => $car_no]);

        if($this->mode == 'user'){
            $subject = $this->user->name . '님이 ' . $owner_name . '님의 ' . $car_no . ' 차량을 선택하였습니다.';
            $message = $this->user->name . '님이 ' . $owner_name . '님의 ' . $car_no . ' 차량을 선택하였습니다.';
        }else{
            $subject = $owner_name . '님의 ' . $car_no . ' 차량이 선택되었습니다.';
            $message = $owner_name . '님의 ' . $car_no . ' 차량이 선택되었습니다. 추후 탁송 예정입니다.';
            $message .= '탁송 예정일은 ' . $taksong_wish_at . ' ' . $taksong_wish_at_time . ' 입니다.';
            $message .= '탁송 은행은 ' . $bank . ' 이며, 계좌는 ' . $bank_account . ' 입니다.';
            $message .= '최종 경매 가격은 ' . $final_price . ' 원 입니다.'; 
        }

        // 알림톡 전송
        // $user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' =>  $subject,
        //         'message_1' => $message,
        //     ]
        // ]));
    }
}
