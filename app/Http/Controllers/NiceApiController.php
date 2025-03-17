<?php

namespace App\Http\Controllers;

use App\Services\NiceApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NiceApiController extends Controller
{
    protected $niceApiService;

    public function __construct(NiceApiService $niceApiService)
    {
        $this->niceApiService = $niceApiService;
    }

    /**
     * NICE API 호출 테스트
     */
    public function testApi(Request $request)
    {
        try {
            // 1. 기존 토큰을 캐시에서 가져오기
            $currentToken = Cache::get('nice_access_token');

            // 2. 토큰이 없거나, 만료된 경우 재발급
            if (!$currentToken) {
                $currentToken = $this->niceApiService->issueAccessToken();
                Cache::put('nice_access_token', $currentToken, now()->addMinutes(30));
            }

            // 3. API 호출
            $apiPath = 'digital/niceid/example-api'; // 실제 API 경로로 변경
            $requestData = [
                'param1' => 'value1',
                'param2' => 'value2',
            ];

            $response = $this->niceApiService->callApi($apiPath, $currentToken, $requestData);

            return response()->json([
                'message' => 'API 호출 성공',
                'data' => $response,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'API 호출 실패',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}