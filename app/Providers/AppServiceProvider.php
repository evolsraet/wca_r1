<?php

namespace App\Providers;

use App\Validators\FieldCommentValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Factory as ValidationFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // // 컨피그 로그
        // $this->app->singleton('config', function ($app) {
        //     $configArray = $app['config']->all();
        //     return new \App\Extends\ConfigSetLog($configArray);
        // });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(ValidationFactory $validationFactory)
    {
        // 발리데이터 - 필드명으로 가져오는 클래스 로드
        $validationFactory->resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
            Log::info("Setting up custom validator.");
            $validator = new FieldCommentValidator($translator, $data, $rules, $messages, $customAttributes);
            if (isset($data['model']) && method_exists($data['model'], 'getTable')) {
                $validator->setTable($data['model']->getTable());
                Log::info("Model table set in validator: " . $data['model']->getTable());
            }
            return $validator;
        });

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
                'code' => $code,
                'status' => $status,
                'message' => $message,
                'data_count' => 0,
                'data' => null,
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

            // 카운트만 조회한 경우
            if (isset($response['data']['count'])) {
                $response['data_count'] = $response['data']['count'];
                $response['data'] = null;
            } elseif (is_array($response['data']) || $response['data'] instanceof \Illuminate\Support\Collection) {
                $response['data_count'] = count($response['data']);
            }

            // print_r([$response, $code]);
            // die();
            return response()->json($response, (int) $code);
            // return response()->json($response, 401);
        });
    }
}
