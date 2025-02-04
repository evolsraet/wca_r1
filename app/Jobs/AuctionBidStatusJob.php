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
                'link' => $baseUrl.'/auction/'.$this->auction->id
            ];

            $sendMessage2 = NotificationTemplate::basicTemplate($data2);
            $this->user->notify(new AuctionBidStatusNotification($sendMessage2, 'bid'));

            // 알리고 알림톡 전송
            // 고객 (입칠시마다)
            // $this->user->notify(new AligoNotification([
            //     'tpl_data' => [
            //         'tpl_code' => env('SMS_TPL_CODE'),
            //         'receiver_1' => $this->user->phone,
            //         'subject_1' => $sendMessage['title'],
            //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message6'].'<br>'.$sendMessage['message7'].'<br><br>바로가기'.$baseUrl.'/auction/'.$this->auction->id,
            //     ]
            // ]));

        } else if($this->status == 'wait'){

            $data3 = [
                'title' => '경매 상태가 선택대기로 변경되었습니다.',
                'data' => $this->auction,
                'status' => '선택대기',
                'link' => $baseUrl.'/auction/'.$this->auction->id
            ];

            $sendMessage3 = NotificationTemplate::basicTemplate($data3);

            // Log::info('경매 입찰 상태 내용 wait', $sendMessage3);

            $this->user->notify(new AuctionBidStatusNotification($sendMessage3, 'wait'));
        
            // 알리고 알림톡 전송
            // $this->user->notify(new AligoNotification([
            //     'tpl_data' => [
            //         'tpl_code' => env('SMS_TPL_CODE'),
            //         'receiver_1' => $this->user->phone,
            //         'subject_1' => $sendMessage3['title'],
            //         'message_1' => $sendMessage3['message1'].'<br>'.$sendMessage3['message2'].'<br>'.$sendMessage3['message3'].'<br>'.$sendMessage3['message4'].'<br><br>바로가기'.$baseUrl.'/auction/'.$this->auction->id,
            //     ]
            // ]));
        } else if($this->status == 'cancel'){

            $data3 = [
                'title' => '경매 상태가 취소상태로 변경되었습니다.',
                'data' => $this->auction,
                'status' => '취소',
                'link' => $baseUrl.'/auction/'.$this->auction->id
            ];

            $sendMessage3 = NotificationTemplate::basicTemplate($data3);

            // Log::info('경매 입찰 상태 내용 wait', $sendMessage3);

            $this->user->notify(new AuctionBidStatusNotification($sendMessage3, 'wait'));
        
            // 알리고 알림톡 전송
            // $this->user->notify(new AligoNotification([
            //     'tpl_data' => [
            //         'tpl_code' => env('SMS_TPL_CODE'),
            //         'receiver_1' => $this->user->phone,
            //         'subject_1' => $sendMessage3['title'],
            //         'message_1' => $sendMessage3['message1'].'<br>'.$sendMessage3['message2'].'<br>'.$sendMessage3['message3'].'<br>'.$sendMessage3['message4'].'<br><br>바로가기'.$baseUrl.'/auction/'.$this->auction->id,
            //     ]
            // ]));
        } else if($this->status == 'reauction'){

            $data4 = [
                'title' => '경매 상태가 재경매로 변경되었습니다.',
                'data' => $this->auction,
                'status' => '재경매',
                'link' => $baseUrl.'/auction/'.$this->auction->id
            ];

            $sendMessage4 = NotificationTemplate::basicTemplate($data4);
            $this->user->notify(new AuctionBidStatusNotification($sendMessage4, 'reauction'));

            // 알리고 알림톡 전송
            // $this->user->notify(new AligoNotification([
            //     'tpl_data' => [
            //         'tpl_code' => env('SMS_TPL_CODE'),
            //         'receiver_1' => $this->user->phone,
            //         'subject_1' => $sendMessage4['title'],
            //         'message_1' => $sendMessage4['message1'].'<br>'.$sendMessage4['message2'].'<br>'.$sendMessage4['message3'].'<br>'.$sendMessage4['message4'].'<br><br>바로가기'.$baseUrl.'/auction/'.$this->auction->id,
            //     ]
            // ]));
        }

    }
}
