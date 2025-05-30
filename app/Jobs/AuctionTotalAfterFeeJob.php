<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionTotalAfterFeeNotification;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AuctionsNotification;
class AuctionTotalAfterFeeJob implements ShouldQueue
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

        $notificationTemplate = NotificationTemplate::getTemplate('AuctionTotalAfterFeeJob', $this->auction, ['mail']);
        $user = User::find($this->user);
        $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

    }
}
