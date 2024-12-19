<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\AuctionBidStatusNotification;
use App\Models\User;
class AuctionBidStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $status;
    public function __construct($user, $status)
    {
        $this->user = User::find($user);
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // dd($this->user, $this->status);
        // 이메일 전송
        $this->user->notify(new AuctionBidStatusNotification($this->user, $this->status));
    }
}
