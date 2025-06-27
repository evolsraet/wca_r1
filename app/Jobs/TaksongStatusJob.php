<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Services\ApiRequestService;
use App\Services\TaksongService;
use App\Models\ApiErrorLog;
use App\Helpers\Wca;
use Exception;

class TaksongStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $response; // taksong id
    protected $endPoint;

    public function __construct($response)
    {
        $this->response = $response;
        $this->endPoint = config('taksongApi.TAKSONG_API_STATUS_URL');
    }

    public function handle(ApiRequestService $api, TaksongService $taksongService): void
    {
        try {
            $payload = [
                'auth' => config('taksongApi.TAKSONG_AUTH'),
                'chk_id' => $this->response,
                'api_key' => config('taksongApi.TAKSONG_API_KEY')
            ];

            $sendRequest = [
                'method' => 'POST',
                'url' => $this->endPoint,
                'params' => $payload,
                'logContext' => "탁송 / chk_id : {$this->response}",
            ];

            $result = $api->sendRequest($sendRequest);

            if (!$result || !isset($result['data'][0])) {
                Log::error("[탁송 job / chk_id : {$this->response}] API 응답 오류 또는 데이터 없음", [
                    'name'=> '탁송 job / chk_id : {$this->response}] API 응답 오류 또는 데이터 없음',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'response' => $result
                ]);
                throw new Exception('Connection timed out: Failed to connect to '.$this->endPoint, 500);
            }

            Log::debug("[탁송 job / chk_id : {$this->response}] API 응답", [
                'name'=> "탁송 job / chk_id : {$this->response}] API 응답",
                'path'=> __FILE__,
                'line'=> __LINE__,
                'response' => $result
            ]);

            $taksongService->processStatus($result['data'][0]);

        } catch (Exception $e) {

            // 네트워크 오류 알림 추가
            Wca::alertIfNetworkError($e, [
                'source' => [
                    'title' => '탁송API / 탁송상태 확인',
                    'url' => $this->endPoint,
                    'context' => $e->getMessage(),
                    'sendData' => $sendRequest,
                ],
                'time' => now()->toDateTimeString(),
            ]);

            Log::critical("[탁송 job / chk_id : {$this->response}] 예외 발생", [
                'name'=> "탁송 job / chk_id : {$this->response}] 예외 발생",
                'path'=> __FILE__,
                'line'=> __LINE__,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            ApiErrorLog::create([
                'job_name' => static::class,
                'method' => 'POST',
                'url' => $this->endPoint,
                'payload' => ['chk_id' => $this->response],
                'response_body' => null,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
