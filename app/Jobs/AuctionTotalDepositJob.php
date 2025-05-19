<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\AuctionTotalDepositNotification;
use App\Notifications\Templates\NotificationTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use App\Notifications\AuctionsNotification;
class AuctionTotalDepositJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user;
    public $auction;
    public $type;
    public function __construct($user, $auction, $type)
    {
        $this->user = $user;
        $this->auction = $auction;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $baseUrl = config('app.url');
        $type = "";
        switch($this->type){
            case 'user':
                $type = 'AuctionTotalDepositJobUser';
                break;
            case 'dealer':
                $type = 'AuctionTotalDepositJobDealer';
                break;
            case 'taksong':
                $type = 'AuctionTotalDepositJobTaksong';
                break;
        }

        $notificationTemplate = NotificationTemplate::getTemplate($type, $this->auction, ['mail']);
        $user = User::find($this->user);
        $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

    }
}

