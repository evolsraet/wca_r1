<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReviewSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $data;
    protected $via;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $data, $via=['mail'])
    {
        $this->user = $user;
        $this->data = $data;
        $this->via = $via;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        


        
    }
}
