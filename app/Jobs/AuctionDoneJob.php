<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Notifications\AuctionDoneNotification;
use App\Notifications\AligoNotification;
use App\Models\Auction;
use App\Notifications\Templates\NotificationTemplate;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Log;
use App\Notifications\AuctionsNotification;
use App\Models\Bid;
class AuctionDoneJob implements ShouldQueue
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

        Log::info('[경매완료] auction_id: ' . $auction->id, ['auction' => $this->auction, 'user' => $this->user, 'mode' => $this->mode]);

        switch($this->mode){
            case 'user':

                $notificationTemplate = NotificationTemplate::getTemplate('AuctionDoneJobUser', $auction, ['mail']);
                $user = User::find($this->user);
                $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

            break;

            case 'dealer':

                $notificationTemplate = NotificationTemplate::getTemplate('AuctionDoneJobDealer', $auction, ['mail']);
                $user = User::find($this->user);
                $user->notify(new AuctionsNotification($user, $notificationTemplate, ['mail'])); // 메일 전송

            break;
        }

        $bid = Bid::where('auction_id', $this->auction->id)->where('user_id', $this->user->id)->first();
        $bid->status = 'done';
        $bid->save();

        
    }
}
