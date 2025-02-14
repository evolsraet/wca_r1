<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\TaksongStatusTemp;
use App\Models\Auction;
use App\Jobs\AuctionDoneJob;
use App\Jobs\AuctionCancelJob;
use App\Models\Bid;
use App\Models\User;
class TaksongStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $response;
    protected $endPoint;
    public function __construct($response)
    {
        $this->response = $response;
        $this->endPoint = 'https://check-dev.wecarmobility.co.kr/api/outside/check/taksong_status';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->taksongStatus($this->response);
    }

    protected function taksongStatus($response)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->endPoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('auth' => env('TAKSONG_AUTH'),'chk_id' => $response,'api_key' => env('TAKSONG_API_KEY')),
        ));

        $result = curl_exec($curl);

        if($result){
            $result = json_decode($result);
        
            // 탁송 상태 업데이트
            $status = $result->data[0]->chk_status;
            TaksongStatusTemp::where('chk_id', $response)->update(['chk_status' => $status]);
    
            $auction = Auction::where('car_no', $result->data[0]->chk_car_no)->get();
            $bid = Bid::where('id', $auction->implode('bid_id'))->get();
            $auction_id = $auction->implode('id');
            $user_id = $auction->implode('user_id'); // 차량등록자
            $bid_user_id = $bid->implode('user_id'); // 입찰자

            // 탁송진행시 매물 상태 탁송중으로 변경
            Auction::where('id', $auction_id)->update(['status' => 'dlvr']);

            // 이 시점에 입금링크 정보 전달 
            

            // 상태에 따른 알림
            // status = start, ing, done, cancel
            switch($status){

                case 'start':
                    // 배차

                    // 배차일때, 탁송정보 받아서 저장 그리고 데이터 저장 매물정보에 표시 
                    Auction::where('car_no', $result->data[0]->chk_car_no)
                        ->update(['is_taksong' => 'start']);

                    break;

                case 'ing':
                    // 배송중

                    $taksong_date = $result->data[0]->chk_accepted_at; // 탁송일
                    $taksong_time = $result->data[0]->chk_accepted_time_at; // 탁송시간
                    $taksong_courier_fee = $result->data[0]->chk_courier_fee; // 탁송요금
                    $taksong_courier_name = $result->data[0]->chk_courier_name; // 기사명
                    $taksong_courier_mobile = $result->data[0]->chk_courier_mobile; // 기사전화번호
                    $taksong_departure_address = $result->data[0]->chk_departure_address; // 출발지
                    $taksong_departure_mobile = $result->data[0]->chk_departure_mobile; // 출발지 전화번호
                    $taksong_dest_address = $result->data[0]->chk_dest_address; // 도착지
                    $taksong_dest_mobile = $result->data[0]->chk_dest_mobile; // 도착지 전화번호
                    $taksong_departure_at = $result->data[0]->chk_departure_at; // 출발시간
                    $taksong_dest_at = $result->data[0]->chk_dest_at; // 도착시간 
                    
                    $updateData = [
                        'taksong_courier_fee' => $taksong_courier_fee,
                        'taksong_courier_name' => $taksong_courier_name,
                        'taksong_courier_mobile' => $taksong_courier_mobile,
                        'taksong_departure_address' => $taksong_departure_address,
                        'taksong_departure_mobile' => $taksong_departure_mobile,
                        'taksong_dest_address' => $taksong_dest_address,
                        'taksong_dest_mobile' => $taksong_dest_mobile,
                        'taksong_departure_at' => $taksong_departure_at,
                        'taksong_dest_at' => $taksong_dest_at,
                        'is_taksong' => 'ing'
                    ];
                    
                    Auction::where('car_no', $result->data[0]->chk_car_no)
                        ->update($updateData);

                    break;  

                case 'done':
                    // 배송완료

                    // 딜러가 입금하고 확인하는 과정 필요 일단 임시로 자동으로 경매완료 처리                     
                    Auction::where('car_no', $result->data[0]->chk_car_no)->update(['status' => 'done', 'is_taksong' => 'done']);

                    // 완료 알림 발송
                    AuctionDoneJob::dispatch($user_id, $auction_id, 'user');
                    AuctionDoneJob::dispatch($bid_user_id, $auction_id, 'dealer');

                    break;

                case 'cancel':
                    // 취소

                    // 주문 상태 업데이트
                    $auction = Auction::where('car_no', $result->data[0]->chk_car_no)->update(['status' => 'cancel']);

                    // 취소 알림 발송
                    AuctionCancelJob::dispatch($user_id, $auction_id);
                    AuctionCancelJob::dispatch($bid_user_id, $auction_id);

                    break;
            }

            
            Log::info('탁송처리 API 호출', ['request' => $result]);
        }
        

        curl_close($curl);
    }
}
