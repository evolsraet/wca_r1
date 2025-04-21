<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\UaerDealerStatusNotification;
use Illuminate\Support\Facades\Log;
use App\Notifications\AligoNotification;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AuctionsNotification;

class UaerDealerStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $status;
    public function __construct($user, $status)
    {
        $this->user = $user;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = [
            'user' => $this->user,
            'status' => $this->status,
        ];

        $notificationTemplate = NotificationTemplate::getTemplate('userStatus', $data, ['mail']);
        $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail']));

    }
}
