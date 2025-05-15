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
use Exception;

class TaksongStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $response;
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

            Log::info("[탁송 job / chk_id : {$this->response}] API 호출", ['url' => $this->endPoint, 'payload' => $payload]);

            $result = $api->sendRequest('POST', $this->endPoint, $payload, "탁송 / chk_id : {$this->response}");

            if (!$result || !isset($result['data'][0])) {
                Log::error("[탁송 job / chk_id : {$this->response}] API 응답 오류 또는 데이터 없음", ['response' => $result]);
                throw new Exception('API 응답 오류 또는 데이터 없음');
            }

            Log::info("[탁송 job / chk_id : {$this->response}] API 응답", ['response' => $result]);

            $taksongService->processStatus($result['data'][0]);

        } catch (Exception $e) {
            Log::critical("[탁송 job / chk_id : {$this->response}] 예외 발생", [
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
