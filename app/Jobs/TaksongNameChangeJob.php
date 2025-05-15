<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Jobs\AuctionDlvrJob;
// use App\Models\TaksongStatusTemp;
use App\Models\User;
use App\Models\Auction;
use App\Notifications\AuctionNameChangeNotification;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AligoNotification;
use App\Notifications\AuctionsNotification;
class TaksongNameChangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $data;
    protected $type;
    public function __construct($user, $data, $type)
    {
        $this->user = $user;
        $this->data = Auction::find($data);
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->taksongNameChange($this->user, $this->data);
    }

    protected function taksongNameChange($user, $data)
    {

        $baseUrl = config('app.url');


        if($this->type == 'user'){
        
            $notificationTemplate = NotificationTemplate::getTemplate('TaksongNameChangeJobUser', $this->data, ['mail']);
            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

        }

        if($this->type == 'dealer'){
        
            $notificationTemplate = NotificationTemplate::getTemplate('TaksongNameChangeJobDealer', $this->data, ['mail']);
            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

        }  

        if($this->type == 'admin'){
            $notificationTemplate = NotificationTemplate::getTemplate('TaksongNameChangeJobAdmin', $this->data, ['mail']);
            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송
        }
        // $sendMessage = NotificationTemplate::basicTemplate($data);

        // $user = User::find($this->user);
        // $user->notify(new AuctionTotalDepositNotification($user, $sendMessage));

    }
}
