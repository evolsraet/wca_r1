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

        $auction = Auction::find($this->auction);

        switch($this->mode){
            case 'user':

                $data = [
                    'title' => '고객님의 차량 경매가 완료되었습니다.',
                    'message' => '위카옥션을 이용해주셔서 감사합니다. 후기를 남겨주세요!',
                    'data' => $auction,
                    'status9' => $auction->final_price,
                    'link' => '/view-do/' . $auction->id,
                ];
                
                $sendMessage = NotificationTemplate::basicTemplate($data);
        
                // 이메일 전송
                $user = User::find($this->user);
                $user->notify(new AuctionDoneNotification($user, $this->auction, $this->mode, $sendMessage));

                // 알림톡 전송
                // $user->notify(new AligoNotification([
                //     'tpl_data' => [
                //         'tpl_code' => env('SMS_TPL_CODE'),
                //         'receiver_1' => $this->user->phone,
                //         'subject_1' =>  $sendMessage['title'],
                //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message12'].'<br><br>후기남기기'.$baseUrl.'/view-do/'.$this->auction->id,
                //     ]
                // ]));

            break;

            case 'dealer':

                $data = [
                    'title' => '경매가 완료되었습니다.',
                    'message' => '수수료를 입금해 주세요!',
                    'data' => $auction,
                    'status10' => $auction->final_price,
                    'status11' => $auction->final_price,
                    'status12' => $auction->final_price,
                    'status13' => $auction->final_price,
                    'status14' => '123-1234-1234',
                    'status15' => '예금주',
                    'status16' => '2025-01-16(수) 00시',
                    'footerMsg' => '* 수수료 미입금시 클레임이 불가합니다.'
                ];
        
                $sendMessage = NotificationTemplate::basicTemplate($data);
        
                // 이메일 전송
                $user = User::find($this->user);
                $user->notify(new AuctionDoneNotification($user, $this->auction, $this->mode, $sendMessage));

                // 알림톡 전송
                // $user->notify(new AligoNotification([
                //     'tpl_data' => [
                //         'tpl_code' => env('SMS_TPL_CODE'),
                //         'receiver_1' => $this->user->phone,
                //         'subject_1' =>  $sendMessage['title'],
                //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message13'].'<br>'.$sendMessage['message14'].'<br>'.$sendMessage['message15'].'<br>'.$sendMessage['message16'].'<br>'.$sendMessage['message17'].'<br>'.$sendMessage['message18'].'<br>'.$sendMessage['message19'].'<br><br>바로가기'.$sendMessage['footerMsg'],
                //     ]
                // ]));

            break;
        }

        
    }
}
