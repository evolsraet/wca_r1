<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Request 매크로 등록
        Request::macro('dbFilter', function ($modelPrefix, $allData) {
            // 초기화된 결과 배열
            $filteredData = [];

            foreach ($allData as $key => $value) {
                // 모델명으로 시작하는 키를 찾습니다.
                if (strpos($key, $modelPrefix . '.') === 0) {
                    // prefix 제거 (예: 'user.id'에서 'user.'를 제거하고 'id'만 남깁니다)
                    $filteredKey = substr($key, strlen($modelPrefix) + 1);
                    $filteredData[$filteredKey] = $value;
                }
            }

            // prefix가 제거된 필터링된 데이터 반환
            return $filteredData;
        });

        Response::macro('api', function ($data = null, $message = null, $status = 'ok', $code = 200, $additional = []) {
            // return $data;
            $response = [
                'status' => $status,
                'message' => $message,
            ];

            // 데이터 대입
            if ($data instanceof \Illuminate\Http\Resources\Json\JsonResource) {
                // JsonResource의 경우
                $resourceResponse = $data->response()->getData(true);
                foreach ($resourceResponse as $key => $value) {
                    $response[$key] = $value;
                }
            } else {
                // 기타 일반 데이터 처리
                $response['data'] = $data;
            }

            // 기본메세지
            if ($status == 'ok' && $message == null) {
                $response['message'] = '정상처리 되었습니다.';
            } elseif ($status != 'ok' && $message == null) {
                $response['message'] = '에러가 발생했습니다.';
            }

            // 추가 첨부
            if (!empty($additional)) {
                $response = array_merge($response, $additional);
            }

            return response()->json($response, $code);
        });
    }
}