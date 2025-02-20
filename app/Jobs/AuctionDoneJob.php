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

        switch($this->mode){
            case 'user':

                $data = [
                    'title' => '고객님의 차량 경매가 완료되었습니다.',
                    'message' => '위카옥션을 이용해주셔서 감사합니다. 후기를 남겨주세요!',
                    'data' => $auction,
                    'status9' => $auction->final_price,
                    'link' => $baseUrl.'/view-do/' . $auction->unique_number,
                    'linkTitle' => '후기 남기기'
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

                $dealer = User::find($this->user);
                $auction_id = $this->auction;
                $randomPrefix = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
                $randomSuffix = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
                $moid = $randomPrefix . $auction_id . $randomSuffix; // 앞에8자리 랜덤 + bid_id + 뒤에 랜덤 3자리 조합 
                $VbankExpDate = date('Ymd', strtotime('+1 day'));
                $VbankExpTime = date('His');
                // 나이스페이먼츠 API 가상계좌 번호 발급 
                $data = array(
                    'VbankAccountName'=>$dealer->name, 
                    'VbankExpDate'=>$VbankExpDate, 
                    'VbankExpTime'=>$VbankExpTime, 
                    'Amt'=>$auction->final_price, // $auction->final_price
                    'Moid'=>$moid);

                $account = (new PaymentController())->checkOverPayment($data);

                $VbankExpDateTrans = date('Y-m-d', strtotime($account['data']['VbankExpDate']));
                $VbankExpTimeTrans = date('H:i:s', strtotime($account['data']['VbankExpTime']));
                // 알림 전송 데이터
                $data = [
                    'title' => '경매가 완료되었습니다.',
                    'message' => '수수료를 입금해 주세요!',
                    'data' => $auction,
                    'status10' => $auction->final_price, // 최종 경매가
                    'status11' => $auction->total_fee ? $auction->total_fee : '20000', // 총 입금액 (수수료. 진단비를 자동으로 계산 하는 부분 만들어서 연동 )
                    'status12' => $auction->success_fee ? $auction->success_fee : '20000', // 수수료 
                    'status13' => $auction->diag_fee ? $auction->diag_fee : '20000', // 진단비
                    'status14' => $account['data']['VbankNum'], // 계좌번호 (가상계좌번호 발급)
                    'status15' => $account['data']['VbankAccountName'], // 예금주
                    'status16' => $VbankExpDateTrans.'('.$VbankExpTimeTrans.')', // 입금기한
                    'footerMsg' => '* 수수료 미입금시 클레임이 불가합니다.',
                    //'link' => $baseUrl, // 결제 페이지 링크를 전송? 
                    //'linkTitle' => '수수료결제'
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
                //         'message_1' => $sendMessage['message1'].'<br>'.$sendMessage['message2'].'<br>'.$sendMessage['message3'].'<br>'.$sendMessage['message13'].'<br>'.$sendMessage['message14'].'<br>'.$sendMessage['message15'].'<br>'.$sendMessage['message16'].'<br>'.$sendMessage['message17'].'<br>'.$sendMessage['message18'].'<br>'.$sendMessage['message19'].'<br>'.$sendMessage['footerMsg'],
                //     ]
                // ]));

            break;
        }

        
    }
}
