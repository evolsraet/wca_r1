<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Auction;
use Illuminate\Support\Facades\Log;
use App\Notifications\TaksongNameChangeFileUploadNotification;
use App\Models\User;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AligoNotification;
use App\Notifications\AuctionsNotification;


class TaksongNameChangeFileUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $auctionId;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $auctionId)
    {
        $this->user = $user;
        $this->auctionId = $auctionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $auction = Auction::find($this->auctionId);
        Log::info('TaksongNameChangeFileUploadJob', ['auction' => $auction]);


        $notificationTemplate = NotificationTemplate::getTemplate('TaksongNameChangeFileUploadJob', $auction, ['mail']);
        $user = User::find($this->user);
        $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송



        // 유저에게 완료 알림
        $notificationTemplate = NotificationTemplate::getTemplate('AuctionDoneJobUser', $auction, ['mail']);
        $user = User::find($this->user);
        $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

    }
}
