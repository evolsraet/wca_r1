<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CarHistoryService
{
    public function getCarHistory(string $carNumber)
    {
        $sessionKey = 'car_history_' . $carNumber;

        // 세션에서 먼저 확인
        if (Session::has($sessionKey)) {
            Log::info("[CarHistory] 캐시된 데이터 사용 - 차량번호: {$carNumber}");
            return Session::get($sessionKey);
        }

        // 파라미터 준비
        $payload = [
            'joinCode'    => config('services.carHistory.join_code'),
            'sType'       => '1', // PC 요청
            'memberID'    => config('services.carHistory.member_id'),
            'carnum'      => $carNumber,
            'carNumType'  => '0',
            'stdDate'     => Carbon::now()->format('Ymd'),
            'rType'       => 'J',
        ];

        Log::info("[CarHistory] API 요청 준비 완료", ['payload' => $payload]);

        try {
            $response = Http::asForm()->timeout(15)->post(config('services.carHistory.api_url'), $payload);

            if ($response->successful()) {
                $data = $response->json();

                Log::info("[CarHistory] 응답 수신 성공", ['response' => $data]);

                // 세션에 저장
                Session::put($sessionKey, $data);
                Session::put($sessionKey . '_fetched_at', now());

                return $data;
            }

            Log::error("[CarHistory] 응답 실패", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error("[CarHistory] 요청 중 예외 발생", [
                'message' => $e->getMessage()
            ]);
        }

        return null;
    }

    private function seedEncrypt(string $plainText): string
    {
        $key = config('services.carHistory.encrypt_key');

        // 16바이트 단위 패딩 (null padding)
        $padded = str_pad($plainText, 16, "\0");

        // SEED 알고리즘이 openssl에 등록된 경우 사용 가능
        $cipherText = openssl_encrypt(
            $padded,
            'seed-ecb',
            $key,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
        );

        return base64_encode($cipherText);
    }
}