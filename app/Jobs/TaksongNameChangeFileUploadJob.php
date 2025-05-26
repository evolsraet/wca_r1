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
        Log::info('[차량 명의이전] 파일 업로드 알림 발송', [
            'name'=> '차량 명의이전 파일 업로드 알림 발송',
            'path'=> __FILE__,
            'line'=> __LINE__,
            'auction' => $auction
        ]);


        $notificationTemplate = NotificationTemplate::getTemplate('TaksongNameChangeFileUploadJob', $auction, ['mail']);
        $user = User::find($this->user);
        $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송


    }
}
