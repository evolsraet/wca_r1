<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    public function showPaymentForm()
    {
        return view('payment.form');
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

            // 결제 완료 알림
            

        }

        die();
    }
}
