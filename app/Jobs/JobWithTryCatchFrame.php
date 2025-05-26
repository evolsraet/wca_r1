<?php

namespace App\Jobs;

use App\Services\ApiRequestService;
use App\Models\ApiErrorLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Exception;

class ExampleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $payload;
    protected string $apiUrl;

    public function __construct(array $payload, string $apiUrl)
    {
        $this->payload = $payload;
        $this->apiUrl = $apiUrl;
    }

    public function handle(ApiRequestService $apiRequestService): void
    {
        try {
            Log::info('[ExampleJob] API 호출 시도', [
                'name'=> 'ExampleJob API 호출 시도',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'url' => $this->apiUrl,
                'payload' => $this->payload
            ]);

            $sendData = [
                'method' => 'POST',
                'url' => $this->apiUrl,
                'params' => $this->payload,
                'logContext' => 'ExampleJob',
            ];

            $response = $apiRequestService->sendRequest($sendData);

            if (!$response) {
                throw new Exception('API 응답이 비어있거나 실패했습니다.');
            }

            Log::info('[ExampleJob] API 호출 성공', [
                'name'=> 'ExampleJob API 호출 성공',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'response' => $response
            ]);

            // 알림 전송 시도
            try {
                Notification::route('mail', 'admin@example.com')
                    ->notify(new \App\Notifications\JobSuccessNotification($response));

                Log::info('[ExampleJob] 알림 전송 성공', [
                    'name'=> 'ExampleJob 알림 전송 성공',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'response' => $response
                ]);
            } catch (Exception $e) {
                Log::error('[ExampleJob] 알림 전송 실패', [
                    'name'=> 'ExampleJob 알림 전송 실패',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                ApiErrorLog::create([
                    'job_name' => static::class,
                    'method' => 'NOTIFY',
                    'url' => null,
                    'payload' => $this->payload,
                    'response_body' => null,
                    'error_message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

        } catch (Exception $e) {
            Log::critical('[ExampleJob] API 호출 실패', [
                'name'=> 'ExampleJob API 호출 실패',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            ApiErrorLog::create([
                'job_name' => static::class,
                'method' => 'POST',
                'url' => $this->apiUrl,
                'payload' => $this->payload,
                'response_body' => null,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
