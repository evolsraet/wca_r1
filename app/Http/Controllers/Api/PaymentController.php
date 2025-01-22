<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Bid;
use App\Models\Auction;
use App\Jobs\PaymentAlertJob;
use App\Models\User;

class PaymentController extends Controller
{

    public function showPaymentForm(Request $request)
    {
        // orderId 는 앞에 문자열 8개, 중간에 bid 번호, 뒤에 문자열 3개 를 조함한 코드 (orderId 임시번호 부여)
        $bid = substr($request->code, 8);
        $bid = substr($bid, 0, -3);
        $bidFind = Bid::find($bid);
        $auction = Auction::find($bidFind->auction_id);

        $auctionPrice = $bidFind->price; // 가격
        $auctionName = $auction->owner_name.'님의 '.$auction->car_no.' 차량'; // 상품명
        $auctionPrice = 1300; // 테스트 가격

        $data = [
            'orderId' => $request->code,
            'bid' => $bid,
            'auctionPrice' => $auctionPrice,
            'auctionName' => $auctionName
        ];

        return view('payment.form2', $data);
    }

    public function resultPayment()
    {
        // 데이터 받기
        $data = $_REQUEST;

        $tid = $data['tid'];
        $amount = $data['amount'];
        $clientId = $data['clientId'];
        $clientSecret = env('NICE_PAY_SECRET_KEY');

        // 인증 토큰 생성
        $base64 = base64_encode($clientId . ':' . $clientSecret);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox-api.nicepay.co.kr/v1/payments/'.$tid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "amount" : "'.$amount.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic '.$base64,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        // 응답 데이터 파싱
        $response = json_decode($response, true);

        echo '<pre>';
        print_r($response);
        echo '</pre>';

        if($response['resultCode'] == '0000'){
            // 데이터 베이스에 저장
            $bid = substr($response['orderId'], 8);

            $bidFind = Bid::find($bid);
            $auction = Auction::find($bidFind->auction_id);

            $user = User::find($auction->user_id);
            $bidUser = User::find($bidFind->user_id);

            // 결제 데이터 저장 
            

            // auction 상태 변경 (탁송중)
            $auction->status = 'dlvr';
            $auction->save();


            // 결제 완료 알림
            PaymentAlertJob::dispatch($user); // 결제 완료 알림
            PaymentAlertJob::dispatch($bidUser); // 낙찰자 알림

        }

        die();
    }

    public function resultPayment2()
    {
        // 데이터 받기
        $data = $_REQUEST;

        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public function requestPayment()
    {
        echo 'requestPayment';
        die();

        // 데이터 받기
        $data = $_REQUEST;

        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

}
