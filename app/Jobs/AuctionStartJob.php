<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Notifications\AuctionStartNotification;

class AuctionStartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $auction;
    protected $user;

    public function __construct($auction, $user)
    {
        $this->auction = $auction;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //이메일 전송
        // Log::info('AuctionStartJob', ['auction' => $this->auction, 'user' => $this->user]);
        $this->user->notify(new AuctionStartNotification($this->auction, $this->user));
    }
}
