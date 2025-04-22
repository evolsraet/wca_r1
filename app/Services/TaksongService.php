<?php   

namespace App\Services;

use App\Services\ApiRequestService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class TaksongService
{
    protected $api;
    protected $taksongApiUrl;
    protected $taksongApiKey;
    protected $taksongApiAuth;

    public function __construct(ApiRequestService $api)
    {
        $this->api = $api;

        Log::info('TaksongService 생성자 호출됨', [
            'api_is_instance' => $api instanceof \App\Services\ApiRequestService
        ]);
        
        $this->taksongApiUrl = config('taksongApi.TAKSONG_API_URL');
        $this->taksongApiKey = config('taksongApi.TAKSONG_API_KEY');
        $this->taksongApiAuth = config('taksongApi.TAKSONG_AUTH');
    }

    public function addTaksong(array $data)
    {
        Log::info('탁송 API 설정 확인', [
            'api_url' => $this->taksongApiUrl,
            'api_key' => $this->taksongApiKey,
            'api_auth' => $this->taksongApiAuth
        ]);

        $data['taksongWishAt'] = $data['taksongWishAt'] ?? '2025-04-22 10:00:00';
        $taksongDate = Carbon::parse($data['taksongWishAt'])->format('Y-m-d');
        $taksongTime = Carbon::parse($data['taksongWishAt'])->format('H:i');

        // $sendData = [
        //     'auth' => $this->taksongApiAuth,
        //     'chk_trans_type' => 'RD',
        //     'chk_accepted_at' => $taksongDate,
        //     'chk_accepted_time_at' => $taksongTime,
        //     'chk_car_no' => $data['carNo'],
        //     'chk_car_model' => $data['carModel'],
        //     'chk_want_insure' => '0',
        //     'chk_departure_mobile' => $data['mobile'],
        //     'chk_departure_address' => $data['startAddr'],
        //     'chk_dest_mobile' => $data['destMobile'],
        //     'chk_dest_address' => $data['destAddr'],
        //     'api_key' => $this->taksongApiKey,
        // ];

        $testSendData = [
            'auth' => 'wcadev',
            'chk_trans_type' => 'RD',
            'chk_accepted_at' => '2024-01-01a',
            'chk_accepted_time_at' => '11:30',
            'chk_car_no' => '24API12343',
            'chk_car_model' => '개발모델',
            'chk_want_insure' => '0',
            'chk_departure_mobile' => '010-3425-8175',
            'chk_departure_address' => '대전 유성',
            'chk_dest_mobile' => '01034258175',
            'chk_dest_address' => '충남 계룡',
            'api_key' => '123123123'
        ];

        // 실제 요청
        $result = $this->api->sendPost($this->taksongApiUrl, $testSendData, 'TaksongService');

        if (!$result) {
            throw new Exception('탁송처리 API 요청 실패: 응답 없음');
        }

        return $result;
    }
}