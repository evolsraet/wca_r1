<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NiceApiService
{
    private $clientId;
    private $clientSecret;
    private $productId;

    public function __construct()
    {
        $this->clientId = config('services.niceAuth.NICE_CLIENT_ID');
        $this->clientSecret = config('services.niceAuth.NICE_CLIENT_SECRET');
        $this->productId = config('services.niceAuth.NICE_PRODUCT_ID');
    }

    /**
     * NICE API - Access Token 발급
     */
    public function issueAccessToken(): string
    {
        $authorization = base64_encode($this->clientId . ':' . $this->clientSecret);

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Basic ' . $authorization,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->post('https://svc.niceapi.co.kr:22001/digital/niceid/oauth/oauth/token', [
                'grant_type' => 'client_credentials',
                'scope' => 'default',
            ]);

        if ($response->failed()) {
            Log::error('[NICE] 토큰 발급 실패', [
                'name'=> 'NICE 토큰 발급 실패',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'response' => $response->body()
            ]);
            throw new \Exception('토큰 발급 실패: ' . $response->body());
        }


        
        return response()->api($response);

    }

    /**
     * NICE API - Access Token 폐기
     */
    public function revokeAccessToken(string $accessToken): bool
    {
        $timestamp = time();
        $authorization = base64_encode("{$accessToken}:{$timestamp}:{$this->clientId}");

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Basic ' . $authorization,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->post('https://svc.niceapi.co.kr:22001/digital/niceid/oauth/oauth/token/revokeById');

        if ($response->failed() || $response->json()['dataBody']['result'] !== "true") {
            Log::error('[NICE] 토큰 폐기 실패', [
                'name'=> 'NICE 토큰 폐기 실패',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'response' => $response->body()
            ]);
            throw new \Exception('토큰 폐기 실패: ' . $response->body());
        }

        return true;
    }

    /**
     * NICE API 호출
     */
    public function callApi(string $apiPath, string $accessToken, array $requestData = []): array
    {
        $timestamp = time();
        $authorization = base64_encode("{$accessToken}:{$timestamp}:{$this->clientId}");

        $response = Http::withHeaders([
            'Authorization' => 'bearer ' . $authorization,
            'Content-Type' => 'application/json',
            'client_id' => $this->clientId,
            'ProductID' => $this->productId,
        ])->post("https://svc.niceapi.co.kr:22001/{$apiPath}", [
            'dataBody' => $requestData,
        ]);

        if ($response->failed()) {
            #TODO: api 연결안될경우 운영자에게 알림 추가

            Log::error('[NICE] API 호출 실패', [
                'name'=> 'NICE API 호출 실패',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'response' => $response->body()
            ]);
            throw new \Exception('API 호출 실패: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * 토큰 폐기 후 재발급
     */
    public function refreshAccessToken(string $currentAccessToken): string
    {
        $this->revokeAccessToken($currentAccessToken);
        return $this->issueAccessToken();
    }
}