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
class AuctionStartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $auction;
    protected $user;
    protected $data;

    public function __construct($auction, $user, $data)
    {
        $this->auction = $auction;
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //이메일 전송
        // Log::info('AuctionStartJob', ['auction' => $this->auction, 'user' => $this->user]);
        $this->user->notify(new AuctionStartNotification($this->auction, $this->user, $this->data));

        // 알리고 알림톡 전송
        $this->user->notify(new AligoNotification([
            'tpl_data' => [
                'tpl_code' => env('SMS_TPL_CODE'),
                'receiver_1' => $this->user->phone,
                'subject_1' => $this->user->name.'님의 차량이 등록신청 완료되었습니다.',
                'message_1' => $this->user->name.'님의 차량이 등록신청 완료되었습니다.',
            ],
        ]));

    }
}
