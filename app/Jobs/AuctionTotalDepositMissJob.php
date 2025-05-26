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
use App\Notifications\AuctionsNotification;
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

        $notificationTemplate = NotificationTemplate::getTemplate('AuctionTotalDepositMissJob', $this->auction, ['mail']);
        $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        Log::info('[경매] 입찰 보증금 미납 알림', [
            'name'=> '경매 입찰 보증금 미납 알림',
            'path'=> __FILE__,
            'line'=> __LINE__,
            'notificationTemplate' => $notificationTemplate
        ]);


    }
}

