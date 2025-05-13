<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ApiErrorLog;
use Exception;

class ApiRequestService
{
    public function sendPost(string $url, array $data, string $logContext = 'API')
    {
        try {
            Log::info("[$logContext] POST 요청", ['url' => $url, 'data' => $data]);

            $response = Http::timeout(10)->asForm()->post($url, $data);

            if (!$response->successful()) {
                Log::warning("[$logContext] POST 실패", ['status' => $response->status(), 'body' => $response->body()]);
                $this->logErrorToDb('POST', $url, $data, $response->body(), $logContext);
                return null;
            }

            $result = $response->json();
            Log::info("[$logContext] POST 성공", ['result' => $result]);

            return $result;

        } catch (Exception $e) {
            Log::error("[$logContext] 예외 발생", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->logErrorToDb('POST', $url, $data, null, $logContext, $e);
            return null;
        }
    }

    public function sendGet(string $url, array $query = [], string $logContext = 'API')
    {
        try {
            Log::info("[$logContext] GET 요청", ['url' => $url, 'query' => $query]);

            $response = Http::timeout(10)->get($url, $query);

            if (!$response->successful()) {
                Log::warning("[$logContext] GET 실패", ['status' => $response->status(), 'body' => $response->body()]);
                $this->logErrorToDb('GET', $url, $query, $response->body(), $logContext);
                return null;
            }

            $result = $response->json();
            Log::info("[$logContext] GET 성공", ['result' => $result]);

            return $result;

        } catch (Exception $e) {
            Log::error("[$logContext] 예외 발생", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->logErrorToDb('GET', $url, $query, null, $logContext, $e);
            return null;
        }
    }

    public function logErrorToDb(string $method, string $url, array $payload, ?string $responseBody, string $context, Exception $e = null)
    {
        try {
            ApiErrorLog::create([
                'job_name' => $context,
                'method' => $method,
                'url' => $url,
                'payload' => $payload,
                'response_body' => $responseBody,
                'error_message' => $e?->getMessage(),
                'trace' => $e?->getTraceAsString(),
            ]);
        } catch (Exception $ex) {
            Log::critical("[$context] DB 로그 저장 실패", [
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ]);
        }
    }
}