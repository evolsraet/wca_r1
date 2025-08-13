<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CarmerceService
{

    private $apiUrl;
    private $apiAuth;
    private $apiPassword;
    private $priceApiUrl;

    public function __construct()
    {
        $this->apiUrl = config('carmerceApi.CARMERCE_AUTH_API_URL');
        $this->apiAuth = config('carmerceApi.CARMERCE_API_AUTH');
        $this->apiPassword = config('carmerceApi.CARMERCE_API_PASSWORD');
        $this->priceApiUrl = config('carmerceApi.CARMERCE_PRICE_API_URL');
    }

    // 인증 토큰 발급
    public function getCarmerceAuth()
    {
        $response = Http::post($this->apiUrl, [
            'userId' => $this->apiAuth,
            'password' => $this->apiPassword
        ]);

        return $response->json();
    }

    // 카머스 시세 조회
    public function getCarmercePrice($accessToken, $carInfo)
    {
        $today = date('Y-m-d');
        $nowYear = date('Y');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post(config('carmerceApi.CARMERCE_PRICE_API_URL'), [
            'startDt' => $carInfo['resFirstDate'],
            'endDt' => $today,
            'carName' => $carInfo['classModelNm'],
            'startMakeYear' => $carInfo['yearType'],
            'endMakeYear' => $nowYear,
            'startDriveKm' => $carInfo['km'],
            'endDriveKm' => '',
        ]);

        Log::info('[카머스] 시세 조회 결과', [
            'name'=> '카머스 시세 조회 결과',
            'path'=> __FILE__,
            'line'=> __LINE__,
            'result' => $response->json()
        ]);
        
        return $response->json();
    }   

    // 카머스 시세 결과
    public function getCarmerceResult( $carInfo )
    {
        $auth = $this->getCarmerceAuth(); // 인증 

        // $refreshToken = $auth['refreshToken']; // 리프레시 토큰
        $accessToken = $auth['accessToken']; // 액세스 토큰 

        $priceResult = $this->getCarmercePrice($accessToken, $carInfo); // 시세확인 
        $result = $priceResult;

        // bidAmt 값의 평균 계산
        $bidAmts = array_column($result['data'], 'bidAmt');
        $averageBidAmt = count($bidAmts) > 0 ? array_sum($bidAmts) / count($bidAmts) : 0;
        $roundedPrice = round($averageBidAmt);

        // 차량 시세 계산 레인지 두기 (수정필요)
        // $minPrice = $roundedPrice - 1000000;
        // $maxPrice = $roundedPrice + 1000000;

        return $roundedPrice * 10000;
    }

}