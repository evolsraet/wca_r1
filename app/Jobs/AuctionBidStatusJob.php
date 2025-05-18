<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\AuctionBidStatusNotification;
use App\Models\User;
use App\Models\Auction;
use App\Notifications\AligoNotification;
use Illuminate\Support\Facades\Log;
use App\Notifications\Templates\NotificationTemplate;
use App\Models\Bid;
use App\Models\Dealer;
use Illuminate\Support\Facades\URL;
use App\Notifications\AuctionsNotification;
class AuctionBidStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user; // 사용자 아이디
    protected $status; // 상태
    protected $auction; // 경매 아이디
    protected $result; // 딜러 아이디 
    protected $price; // 입찰가격
    public function __construct($user, $status, $auction, $result, $price)
    {
        $this->user = User::find($user);
        $this->status = $status;
        $this->auction = Auction::find($auction);
        $this->result = $result;
        $this->price = $price;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $baseUrl = config('app.url');

        if($this->status == 'ask'){

            // 딜러정보 
            $dealer = User::find($this->result);

            $data2 = [
                'title' => '딜러가 고객님의 차량에 입찰했습니다.',
                'data' => $this->auction,
                'status3' => $dealer['dealer']['company'].' - '.$dealer['dealer']['name'],
                'status4' => $this->price,
                // 'link' => $baseUrl.'/auction/'.$this->auction->unique_number
                'link' => $baseUrl.'/auction/'.$this->auction->hashid
            ];

            $this->auction['dealer'] = $dealer;
            $this->auction['price'] = $this->price;

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobAsk', $this->auction, ['mail']);
            $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        } else if($this->status == 'ing'){

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobIng', $this->auction, ['mail']);
            $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        } else if($this->status == 'wait'){

            // Log::info('경매 상태가 선택대기로 변경되었습니다.', $this->auction);
            $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobWait', $this->auction, ['mail']);
            $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        } else if($this->status == 'cancel'){

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobCancel', $this->auction, ['mail']);
            $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송


        } else if($this->status == 'reauction'){

            $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobReauction', $this->auction, ['mail']);
            $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        } else if($this->status == 'delete'){

            // 딜러정보 
            $dealer = User::find($this->result);
            $this->auction['dealer'] = $dealer;
            
            $notificationTemplate = NotificationTemplate::getTemplate('AuctionBidStatusJobDelete', $this->auction, ['mail']);
            $this->user->notify(new AuctionsNotification($this->user, $notificationTemplate, ['mail'])); // 메일 전송

        }

    }
}
