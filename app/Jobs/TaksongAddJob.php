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
use App\Models\TaksongStatusTemp;

class TaksongAddJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $response;
    protected $endPoint;
    protected $data;
    public function __construct($response, $data)
    {
        $this->response = $response;
        $this->endPoint = config('taksongApi.TAKSONG_API_URL');
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->taksongAdd($this->response, $this->data);
    }

    protected function taksongAdd($response, $data)
    {

        $curl = curl_init();

        $taksong_wish_at = Carbon::parse($data['taksongWishAt'])->format('Y-m-d');
        $taksong_wish_at_time = Carbon::parse($data['taksongWishAt'])->format('H:i');
        

        $sendData = [
            'auth' => config(key: 'taksongApi.TAKSONG_AUTH'),
            'chk_trans_type' => 'RD', // 탁송 유형
            'chk_accepted_at' => $taksong_wish_at, // 탁송 날짜
            'chk_accepted_time_at' => $taksong_wish_at_time, // 탁송 시간
            'chk_car_no' => $data['carNo'], // 차량번호
            'chk_car_model' => $data['carModel'], // 차량모델
            'chk_want_insure' => '0', // 보험 여부
            'chk_departure_mobile' => $data['mobile'], // 출발지 전화번호
            'chk_departure_address' => $data['startAddr'], // 출발지 주소
            'chk_dest_mobile' => $data['destMobile'], // 도착지 전화번호
            'chk_dest_address' => $data['destAddr'], // 도착지 주소
            'api_key' => config('taksongApi.TAKSONG_API_KEY') // API 키
        ];

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->endPoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $sendData,
        ));
        
        $result = curl_exec($curl);

        Log::info('탁송처리 API 확인!', ['api_result' => $result, 'sendData' => $sendData]);
        
        curl_close($curl);

        Log::info('탁송처리 API 호출..', ['result' => $result, 'data' => $data]);

        // die();
        $resultData = json_decode($result);
        if(is_array($resultData)){

            if($resultData['data'] !== null){
                $taksongStatusTemp = new TaksongStatusTemp();
                $taksongStatusTemp->auction_id = $data['id'];
                $taksongStatusTemp->chk_id = $resultData['data']['chk_id'];
                $taksongStatusTemp->chk_status = $resultData['data']['chk_status'];
                $taksongStatusTemp->save();
            }else{
                return response()->api(['result' => 'false']);
            }

        }else{

            return response()->api(['result' => 'false']);

        }

        Log::info('탁송처리 API 호출?', ['result' => $data]);
        // Log::info('탁송처리 API 호출', ['response' => $response]);
        //AuctionDlvrJob::dispatch($data['userId'], $data, $response, 'user'); // 사용자 알림
        //AuctionDlvrJob::dispatch($data['bidUserId'], $data, $response, 'dealer'); // 입찰자 알림

    }
}
