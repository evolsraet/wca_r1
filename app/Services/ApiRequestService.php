<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ApiErrorLog;
use Exception;

class ApiRequestService
{
    public function sendRequest(array $data)
    {
        $method = $data['method'];
        $url = $data['url'];
        $params = $data['params'];
        $logContext = $data['logContext'] ?? 'API';
        $throwOnError = $data['throwOnError'] ?? true;

        $errorData = null;

        try {

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

                if ($throwOnError) {
                    throw new Exception("API 요청 실패 했습니다.", 500);
                }

                return null;
            }

            // 성공 로그
            $result = $response->json();

            Log::info("[리퀘스트 {$method} 성공] $logContext ", [
                'name'=> '리퀘스트 성공',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'url'    => $url,
                'params' => $params,
                'result' => $result,
            ]);

            return $result;

        } catch (Exception $e) {
            $msg = "[리퀘스트 {$method} 실패] $logContext";
            $context = [
                'message'    => $e->getMessage(),
                'error_data' => $errorData,
                'trace'      => $e->getTraceAsString(),
            ];

            $errorLevel = 'warning';
            if( $e->getCode() == 500 ) {
                Log::error($msg, [
                    'name'=> '리퀘스트 실패',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'context' => $context
                ]);
            } else {
                Log::warning($msg, [
                    'name'=> '리퀘스트 실패',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'context' => $context
                ]);
            }
            
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
                'name'=> 'DB 로그 저장 실패',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'message' => $ex->getMessage(),
                'trace'   => $ex->getTraceAsString()
            ]);
        }
    }
}