<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AligoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $auction;
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
        //
    }
}
