<?php   

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class TaksongService
{
    protected $taksongApiUrl;
    protected $taksongApiKey;
    protected $taksongApiAuth;

    public function __construct()
    {
        $this->taksongApiUrl = config('taksongApi.TAKSONG_API_URL');
        $this->taksongApiKey = config('taksongApi.TAKSONG_API_KEY');
        $this->taksongApiAuth = config('taksongApi.TAKSONG_AUTH');
    }

    public function addTaksong(array $data)
    {

        Log::info('탁송 api', [
            'api_url' => $this->taksongApiUrl,
            'api_key' => $this->taksongApiKey,
            'api_auth' => $this->taksongApiAuth
        ]);

        // 탁송 희망 시간 지정 (임시)
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

        Log::info('탁송처리 API 요청', [
            'url' => $this->taksongApiUrl,
            'payload' => $testSendData
        ]);

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->withHeaders([
                    // 필요한 경우 쿠키나 헤더 추가 가능
                ])
                ->post($this->taksongApiUrl, $testSendData);

            if (!$response->successful()) {
                Log::error('탁송처리 API 실패 - HTTP 오류', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception("API 요청 실패: HTTP " . $response->status());
            }

            $result = $response->json();

            if (!$result || !is_array($result)) {
                Log::error('탁송처리 API 실패 - JSON 파싱 오류', ['body' => $response->body()]);
                throw new Exception('API 응답 파싱 실패');
            }

            Log::info('탁송처리 API 성공 응답', ['result' => $result]);
            return $result;

        } catch (Exception $e) {
            Log::critical('탁송처리 API 예외 발생', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new Exception('탁송처리 중 오류가 발생했습니다. 다시 시도해주세요.');
        }
    }
}