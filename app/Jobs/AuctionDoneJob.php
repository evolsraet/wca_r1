<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionDoneNotification;

class AuctionDoneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    public function __construct($user_id)
    {
        $this->user = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->user);
        $user->notify(new AuctionDoneNotification($user));
    }
}
