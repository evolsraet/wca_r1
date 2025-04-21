<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionCancelNotification;
use App\Notifications\AligoNotification;
use Illuminate\Support\Facades\Log;
use App\Models\Auction;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AuctionsNotification;

class AuctionCancelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $auction;
    public function __construct($user_id, $auction_id)
    {
        $this->user = $user_id;
        $this->auction = $auction_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $baseUrl = config('app.url');
        $user = User::find($this->user);
        $auction = Auction::find($this->auction);


        $notificationTemplate = NotificationTemplate::getTemplate('AuctionCancelJob', $auction, ['mail']);
        $user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        // 알림톡 전송
        // $user->notify(new AligoNotification([
        //     'tpl_data' => [
        //         'tpl_code' => env('SMS_TPL_CODE'),
        //         'receiver_1' => $this->user->phone,
        //         'subject_1' => $message,
        //         'message_1' => $message,
        //     ]
        // ]));

    }
}
