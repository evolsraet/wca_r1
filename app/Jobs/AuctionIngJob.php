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

class AuctionIngJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $auction;
    public function __construct($user_id, $auction)
    {
        $this->user = $user_id;
        $this->auction = $auction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 이메일 알림 
        $user = User::find($this->user);
        $user->notify(new AuctionIngNotification($user, $this->auction));

        // 문자 알림
        
    }
}
