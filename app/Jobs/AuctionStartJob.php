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
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AuctionsNotification;
use App\Models\Auction;
class AuctionStartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $auction;
    protected $user;
    protected $data;
    protected $isUser;
    public function __construct($auction, $user, $data, $isUser = false)
    {
        $this->auction = Auction::find($auction);
        $this->user = $user;
        $this->data = $data;
        $this->isUser = $isUser;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $data = json_decode($this->data);

        $notificationTemplate = NotificationTemplate::getTemplate('AuctionStartJob', $data, ['mail']);
        $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

    }
}
