<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ApiErrorLog;
use Exception;

class ApiRequestService
{
    public function sendRequest(string $method, string $url, array $params = [], string $logContext = 'API', bool $throwOnError = true)
    {
        $errorData = null;

        try {
            // 요청 로그
            Log::debug("[$logContext] {$method} 요청", ['url' => $url, 'params' => $params]);

            // HTTP 요청 초기화
            $httpRequest = Http::timeout(10);

            // POST 방식일 경우 asForm 적용
            if (strtoupper($method) === 'POST') {
                $httpRequest = $httpRequest->asForm();
            }

            // 실제 요청 실행 (POST / GET)
            $response = match (strtoupper($method)) {
                'POST' => $httpRequest->post($url, $params),
                'GET'  => $httpRequest->get($url, $params),
                default => throw new Exception("지원하지 않는 HTTP 메서드입니다. ({$method})"),
            };

            // 실패시 로그 + 예외 처리
            if (!$response->successful()) {
                $errorData = [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ];

                $this->logErrorToDb($method, $url, $params, $response->body(), $logContext);

                Log::warning("[$logContext] {$method} 실패", $errorData);

                if ($throwOnError) {
                    throw new Exception("API 요청 실패 했습니다.");
                }

                return null;
            }

            // 성공 로그
            $result = $response->json();

            Log::info("[$logContext] {$method} 성공", [
                'url'    => $url,
                'params' => $params,
                'result' => $result,
            ]);

            return $result;

        } catch (Exception $e) {
            Log::error("[$logContext] 예외 발생", [
                'message'    => $e->getMessage(),
                'error_data' => $errorData,
                'trace'      => $e->getTraceAsString(),
            ]);

            $this->logErrorToDb($method, $url, $params, $errorData['body'] ?? null, $logContext, $e);

            if ($throwOnError) {
                throw $e;
            }

            return null;
        }
    }

    public function logErrorToDb(string $method, string $url, array $payload, ?string $responseBody, string $context, Exception $e = null)
    {
        try {
            ApiErrorLog::create([
                'job_name'      => $context,
                'method'        => $method,
                'url'           => $url,
                'payload'       => $payload,
                'response_body' => $responseBody,
                'error_message' => $e?->getMessage(),
                'trace'         => $e?->getTraceAsString(),
            ]);
        } catch (Exception $ex) {
            Log::critical("[$context] DB 로그 저장 실패", [
                'message' => $ex->getMessage(),
                'trace'   => $ex->getTraceAsString()
            ]);
        }
    }
}