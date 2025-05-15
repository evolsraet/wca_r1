<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OwnershipNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $auctionId;
    protected $userId;
    protected $bidUserId;

    public function __construct($auctionId, $userId, $bidUserId)
    {
        $this->auctionId = $auctionId;
        $this->userId = $userId;
        $this->bidUserId = $bidUserId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //


    }
}
