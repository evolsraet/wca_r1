<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionCohosenNotification;
use Illuminate\Support\Facades\Log;
use App\Notifications\AligoNotification;
use App\Models\Auction;
use Carbon\Carbon;
use App\Notifications\Templates\NotificationTemplate;
use App\Notifications\AuctionsNotification;

class AuctionCohosenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $auction;
    protected $mode;
    public function __construct($user_id, $auction_id, $mode)
    {
        $this->user = $user_id;
        $this->auction = $auction_id;
        $this->mode = $mode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $baseUrl = config('app.url');
        $auction = Auction::find($this->auction);

        if($auction->bid_id){

            if($this->mode == 'user'){
                $notificationTemplate = NotificationTemplate::getTemplate('AuctionCohosenJobUser', $auction, ['mail']);
            }else{
                $notificationTemplate = NotificationTemplate::getTemplate('AuctionCohosenJobDealer', $auction, ['mail']);
            }

            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

    
            Log::info('[경매] 선택 알림', [
                'name'=> '경매 선택 알림',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'status' => $auction,
                'mode' => $this->mode,
                'user' => $this->user
            ]);
            

        }else{

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionCohosenJobUser1', $auction, ['mail']);
            $user = User::find($this->user);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송


            $notificationTemplate = NotificationTemplate::getTemplate('AuctionCohosenJobUser2', $auction, ['mail']);
            $user = User::find($auction->user_id);
            $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

        }

    }
}
