<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionIngNotification;
use App\Notifications\AligoService;
use App\Models\Auction;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AligoNotification;
use Illuminate\Support\Facades\Log;
use App\Notifications\AuctionsNotification;
class AuctionIngJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $auction;
    protected $finalAt;
    public function __construct($user_id, $auction, $finalAt)
    {
        $this->user = $user_id;
        $this->auction = $auction;
        $this->finalAt = $finalAt;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $auction = Auction::find($this->auction);

        $auction->final_at = $this->finalAt;

        $notificationTemplate = NotificationTemplate::getTemplate('AuctionIngJob', $auction, ['mail']);
        $user = User::find($this->user);
        $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

    }
}
