<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Auction;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AuctionsNotification;
use Illuminate\Support\Facades\Log;

class NetworkErrorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $context;
    /**
     * Create a new job instance.
     */
    public function __construct($message, $context)
    {
        $this->message = $message;
        $this->context = $context;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find(1);
        $data = Auction::find(1);
    
        $notificationTemplate = NotificationTemplate::getTemplate(
            'NetworkErrorJob',
            $this->context,
            ['mail']
        );

        try {
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail']));

        } catch (\Exception $e) {
            Log::error('[네트워크 오류 알림전송 실패 / '.$data->id.']', ['error' => $e->getMessage()]);
        }
        
    }
}
