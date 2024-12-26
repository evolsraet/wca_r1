<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionDiagNotification;
use App\Notifications\AligoNotification;

class AuctionDiagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $data;    
    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 이메일 전송 
        $user = User::find($this->user);
        $user->notify(new AuctionDiagNotification($user, $this->data));

        // 알리고 알림톡 전송
        // $user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $user->phone,
        //         'subject_1' => $this->data->owner_name . '님의 ' . $this->data->car_no . ' 차량이 진단대기중입니다.',
        //         'message_1' => $this->data->owner_name . '님의 ' . $this->data->car_no . ' 차량이 진단대기중입니다.',
        //     ],
        // ]));
    }
}
