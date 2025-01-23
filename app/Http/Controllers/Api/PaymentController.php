<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Bid;
use App\Models\Auction;
use App\Jobs\PaymentAlertJob;
use App\Models\User;
use Exception;
use App\Jobs\AuctionTotalDepositJob;
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
        $auctionName = $auction->car_maker.' '.$auction->car_model.' ' .$auction->car_model_sub.'('.$auction->car_no.')'; // 상품명
        $auctionPrice = 100; // 테스트 가격
        $vbankExpDate = date('Ymd', strtotime($auction->taksong_wish_at . ' -1 day'));

        $user = User::find($bidFind->user_id);
        $buyerName = $user->name;
        $buyerTel = $user->phone;
        $buyerEmail = $user->email;

        $data = [
            'orderId' => $request->code,
            'bid' => $bid,
            'auctionPrice' => $auctionPrice,
            'auctionName' => $auctionName,
            'buyerName' => $buyerName,
            'buyerTel' => $buyerTel,
            'buyerEmail' => $buyerEmail,
            'vbankExpDate' => $vbankExpDate
        ];

        return view('payment.form', $data);
    }

    public function resultPayment()
    {
        // // 데이터 받기
        // $data = $_REQUEST;

        // $tid = $data['tid'];
        // $amount = $data['amount'];
        // $clientId = $data['clientId'];
        // $clientSecret = env('NICE_PAY_SECRET_KEY');

        // // 인증 토큰 생성
        // $base64 = base64_encode($clientId . ':' . $clientSecret);

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://sandbox-api.nicepay.co.kr/v1/payments/'.$tid,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS =>'{
        //         "amount" : "'.$amount.'"
        //     }',
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json',
        //         'Authorization: Basic '.$base64,
        //     ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);


        // // 응답 데이터 파싱
        // $response = json_decode($response, true);

        // echo '<pre>';
        // print_r($response);
        // echo '</pre>';

        // if($response['resultCode'] == '0000'){
        //     // 데이터 베이스에 저장
        //     $bid = substr($response['orderId'], 8);

        //     $bidFind = Bid::find($bid);
        //     $auction = Auction::find($bidFind->auction_id);

        //     $user = User::find($auction->user_id);
        //     $bidUser = User::find($bidFind->user_id);

        //     // 결제 데이터 저장 
            

        //     // auction 상태 변경 (탁송중)
        //     $auction->status = 'dlvr';
        //     $auction->save();


        //     // 결제 완료 알림
        //     PaymentAlertJob::dispatch($user); // 결제 완료 알림
        //     PaymentAlertJob::dispatch($bidUser); // 낙찰자 알림

        // }

        // die();
    }

    public function resultPayment2()
    {
        // 데이터 받기
        $data = $_REQUEST;

        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public function requestPayment(Request $request)
    {
        // 데이터 받기
        $data = $request->all();

        /*
        ****************************************************************************************
        * <인증 결과 파라미터>
        ****************************************************************************************
        */
        $authResultCode = $data['AuthResultCode'];		// 인증결과 : 0000(성공)
        $authResultMsg = $data['AuthResultMsg'];		// 인증결과 메시지
        $nextAppURL = $data['NextAppURL'];				// 승인 요청 URL
        $txTid = $data['TxTid'];						// 거래 ID
        $authToken = $data['AuthToken'];				// 인증 TOKEN
        $payMethod = $data['PayMethod'];				// 결제수단
        $mid = $data['MID'];							// 상점 아이디
        $moid = $data['Moid'];							// 상점 주문번호
        $amt = $data['Amt'];							// 결제 금액
        $reqReserved = $data['ReqReserved'];			// 상점 예약필드
        $netCancelURL = $data['NetCancelURL'];			// 망취소 요청 URL
            
        /*
        ****************************************************************************************
        * <승인 결과 파라미터 정의>
        * 샘플페이지에서는 승인 결과 파라미터 중 일부만 예시되어 있으며, 
        * 추가적으로 사용하실 파라미터는 연동메뉴얼을 참고하세요.
        ****************************************************************************************
        */

        $response = "";

        if($authResultCode === "0000"){
            /*
            ****************************************************************************************
            * <해쉬암호화> (수정하지 마세요)
            * SHA-256 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
            ****************************************************************************************
            */	
            $ediDate = date("YmdHis");
            $merchantKey = env('NICE_PAY_CLIENT_KEY'); // 상점키
            $signData = bin2hex(hash('sha256', $authToken . $mid . $amt . $ediDate . $merchantKey, true));

            try{
                $data = Array(
                    'TID' => $txTid,
                    'AuthToken' => $authToken,
                    'MID' => $mid,
                    'Amt' => $amt,
                    'EdiDate' => $ediDate,
                    'SignData' => $signData,
                    'CharSet' => 'utf-8'
                );		
                $response = $this->reqPost($data, $nextAppURL); //승인 호출
                $response = json_decode($response);

                $data = [
                    'ResultMsg' => $response->ResultMsg,
                    'MsgSource' => $response->MsgSource,
                    'Amt' => $response->Amt,
                    'MID' => $response->MID,
                    'Moid' => $response->Moid,
                    'BuyerEmail' => $response->BuyerEmail,
                    'BuyerTel' => $response->BuyerTel,
                    'BuyerName' => $response->BuyerName,
                    'GoodsName' => $response->GoodsName,
                    'TID' => $response->TID,
                    'AuthCode' => $response->AuthCode,
                    'AuthDate' => $response->AuthDate,
                    'PayMethod' => $response->PayMethod,
                    'CartData' => $response->CartData,
                    'Signature' => $response->Signature,
                    'MallReserved' => $response->MallReserved,
                    'VbankBankCode' => $response->VbankBankCode,
                    'VbankBankName' => $response->VbankBankName,
                    'VbankNum' => $response->VbankNum,
                    'VbankExpDate' => $response->VbankExpDate,
                    'VbankExpTime' => $response->VbankExpTime,
                ];

                return view('payment.request', $data);
                
            }catch(Exception $e){
                $e->getMessage();
                $data = Array(
                    'TID' => $txTid,
                    'AuthToken' => $authToken,
                    'MID' => $mid,
                    'Amt' => $amt,
                    'EdiDate' => $ediDate,
                    'SignData' => $signData,
                    'NetCancel' => '1',
                    'CharSet' => 'utf-8'
                );
                $response = $this->reqPost($data, $netCancelURL); //예외 발생시 망취소 진행
                $response = json_decode($response);
                //$this->jsonRespDump($response); //response json dump example

                return view('payment.request', data: $response);
            }	
            
        }else{
            //인증 실패 하는 경우 결과코드, 메시지
            $ResultCode = $authResultCode; 	
            $ResultMsg = $authResultMsg;
        }

    }

    // API CALL foreach 예시
    private function jsonRespDump($resp){
        $respArr = json_decode($resp);
        foreach ( $respArr as $key => $value ){
                echo "$key=". $value."<br />";
        }
    }

    private function reqPost(Array $data, $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);					//connection timeout 15 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));	//POST data
        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_exec($ch);
        curl_close($ch);	 
        return $response;
    }


    // PG사 에서 보낸 데이터 수신 
    public function notify(Request $request)
    {
        $data = $request->all();

        // 2. 데이터 유효성 검증 (예: 필수 필드 확인)
        $requiredFields = ['PayMethod', 'Amt', 'VbankNum', 'VbankName', 'ResultCode', 'MOID', 'AuthDate'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return response("FAIL: Missing field $field\n", 400); // 실패 응답
            }
        }

        // 3. DB 저장
        try {
            DB::table('auctions_payments')->insert([
                'pay_method'       => $data['PayMethod'] ?? null,
                'mid'              => $data['MID'] ?? null,
                'mall_user_id'     => $data['MallUserID'] ?? null,
                'amount'           => $data['Amt'] ?? 0,
                'name'             => $data['name'] ?? null,
                'goods_name'       => $data['GoodsName'] ?? null,
                'tid'              => $data['TID'] ?? null,
                'moid'             => $data['MOID'] ?? null,
                'auth_date'        => isset($data['AuthDate']) ? date('Y-m-d H:i:s', strtotime($data['AuthDate'])) : null,
                'result_code'      => $data['ResultCode'] ?? null,
                'result_msg'       => $data['ResultMsg'] ?? null,
                'vbank_num'        => $data['VbankNum'] ?? null,
                'fn_cd'            => $data['FnCd'] ?? null,
                'vbank_name'       => $data['VbankName'] ?? null,
                'vbank_input_name' => $data['VbankInputName'] ?? null,
                'cancel_date'      => isset($data['CancelDate']) ? date('Y-m-d H:i:s', strtotime($data['CancelDate'])) : null,
                'rcpt_tid'         => $data['RcptTID'] ?? null,
                'rcpt_type'        => $data['RcptType'] ?? null,
                'rcpt_auth_code'   => $data['RcptAuthCode'] ?? null,
                'created_at'       => now(),
            ]);

            Log::info('Payment Notification Success: ', ['data' => $data]);

            $bid = substr($data['MOID'], 8);
            $bid = substr($bid, 0, -3);
            $bidFind = Bid::find($bid);
            $auction = Auction::find($bidFind->auction_id);

            Log::info('Payment Notification Success: ', ['bidFind' => $bidFind, 'auction' => $auction]);

            AuctionTotalDepositJob::dispatch($auction->user_id, $auction);
            AuctionTotalDepositJob::dispatch($bidFind->user_id, $auction);

            // 4. 성공 응답 반환 (PG사로 "OK" 반환)
            return response("OK\n", 200);
        } catch (\Exception $e) {
            // DB 저장 실패 시 로그 기록
            Log::error('Payment Notification Error: ' . $e->getMessage());
            return response("FAIL: Server error\n", 500);
        }
    }

}
