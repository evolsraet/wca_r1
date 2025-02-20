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
            $data = [
                'title' => '딜러님의 입찰이 선택되었습니다.',
                'message' => '금액을 다시 한번 확인하시고, 탁송정보를 입력해주세요! 미입력시 경매 절차가 진행되지않아요 😅',
                'data' => $auction,
                'status4' => $auction->final_price,
                'link' => $baseUrl.'/auction/'.$auction->unique_number
            ];
    
            $sendMessage = NotificationTemplate::basicTemplate($data);
    
            Log::info('경매 선택 알림', ['status' => $auction, 'mode' => $this->mode, 'user' => $this->user]);
            
            // 이메일 전송
            $user = User::find($this->user);
            $user->notify(new AuctionCohosenNotification($user, $this->auction, $this->mode, $sendMessage));
            

            // 알리고 알림톡 알림
            // $user->notify(new AligoNotification([
            //     'tpl_data' => [
            //         'tpl_code' => env('SMS_TPL_CODE'),
            //         'receiver_1' => $this->user->phone,
            //         'subject_1' =>  $sendMessage['title'],
            //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message7'].'<br><br>바로가기'.$baseUrl.'/auction/'.$this->auction->id,
            //     ]
            // ]));

        }else{

            $data = [
                'title' => '딜러님의 입찰이 선택되었습니다.',
                'data' => $auction,
                'link' => $baseUrl.'/auction/'.$auction->unique_number
            ];
    
            $sendMessage = NotificationTemplate::basicTemplate($data);
            
            // 이메일 전송
            $user = User::find($this->user);
            $user->notify(new AuctionCohosenNotification($user, $this->auction, $this->mode, $sendMessage));


            // 알리고 알림톡 알림
            // $user->notify(new AligoNotification([
            //     'tpl_data' => [
            //         'tpl_code' => env('SMS_TPL_CODE'),
            //         'receiver_1' => $this->user->phone,
            //         'subject_1' =>  $sendMessage['title'],
            //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message7'].'<br><br>바로가기'.$baseUrl.'/auction/'.$this->auction->id,
            //     ]
            // ]));

            // 고객 알림 
            $data1 = [
                'title' => '고객님의 차량 경매가 완료되었습니다.',
                'message' => '탁송정보를 입력해주세요! 미입력시 경매 절차가 진행되지않아요 😅',
                'data' => $auction
            ];

            $sendMessage1 = NotificationTemplate::basicTemplate($data1);

            $user = User::find($auction->user_id);
            $user->notify(new AuctionCohosenNotification($user, $this->auction, $this->mode, $sendMessage1));


            // 알리고 알림톡 알림
            // $user->notify(new AligoNotification([
            //     'tpl_data' => [
            //         'tpl_code' => env('SMS_TPL_CODE'),
            //         'receiver_1' => $this->user->phone,
            //         'subject_1' =>  $sendMessage['title'],
            //         'message_1' => $sendMessage1['message1'].'<br>'.$sendMessage1['message2'].'<br>'.$sendMessage1['message3'].'<br>'.$sendMessage1['message7'].'<br><br>바로가기'.$baseUrl.'/auction/'.$this->auction->id,
            //     ]
            // ]));

        }

    }
}
