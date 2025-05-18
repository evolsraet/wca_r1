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

        if($this->type == 'user'){

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionTotalDepositJobUser', $this->auction, ['mail']);
            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송
            
        }else if($this->type == 'dealer'){

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionTotalDepositJobDealer', $this->auction, ['mail']);
            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

        }else if($this->type == 'taksong'){

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionTotalDepositJobTaksong', $this->auction, ['mail']);
            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

        }


    }
}

