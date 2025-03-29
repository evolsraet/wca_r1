<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
class CodefService
{
    protected $clientId;
    protected $clientSecret;
    protected $apiUrl;
    protected $authUrl;
    protected $publicKey;
    public function __construct()
    {
        $this->clientId = config('services.codef.client_id');
        $this->clientSecret = config('services.codef.client_secret');
        $this->apiUrl = config('services.codef.api_url');
        $this->authUrl = config('services.codef.oauth_url');
        $this->publicKey = config('services.codef.public_key');
    }

    public function getAccessToken()
    {

        if (Cache::has('codef_access_token')) {
            return Cache::get('codef_access_token');
        }

        try {
            // Base64 인코딩된 인증 정보 생성
            $authString = base64_encode($this->clientId . ':' . $this->clientSecret);
            $authHeader = "Basic " . $authString;

            // 요청 바디 설정
            $params = 'grant_type=client_credentials&scope=read';

            // HTTP 요청 (cURL 대신 Laravel HTTP 클라이언트 사용)
            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->withBody($params, 'application/x-www-form-urlencoded')
              ->post($this->authUrl);

            // 응답 확인
            if ($response->successful()) {
                $tokenData = $response->json();

                // 토큰 캐싱 (만료 60초 전 제거)
                Cache::put('codef_access_token', $tokenData['access_token'], now()->addSeconds($tokenData['expires_in'] - 60));

                return $tokenData['access_token'];
            }

            \Log::error('Codef Token Request Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function checkBusinessStatus($businessNumbers)
    {
        $accessToken = $this->getAccessToken();

        $startDate = date('Ymd');
        $businessNumbers = "7558102354";

        $payload = [
            "organization" => "0004",
            "loginType" => "5",
            "loginTypeLevel" => "1",
            "userName" => "김성완",
            "loginIdentity" => "8704261686018",
            "telecom" => "2",
            "phoneNo" => "01028020327",
            "id" => "",
            "startDate" => $startDate,
            "usePurposes" => "사업자여부",
            "identity" => $businessNumbers,
            "birthDate" => "",
            "identityEncYn" => "Y"
        ];

        // rsa 암호화 
        $password = $this->rsaEncrypt("Dhksl1524!!");

        $payload = [
            "organization" => "0001",
            "userName" => "김성완",
            "identity" => "8704261686018",
            "loginTypeLevel" => "1",
            "phoneNo" => "01028020327",
            "loginType" => "5",
            "telecom" => "2"
        ];

        // dd($payload);
        // dd($this->apiUrl);

        $response = Http::asForm()
        ->withHeaders([
            'Authorization' => 'Bearer '.$accessToken,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ])
        ->post('https://development.codef.io/v1/kr/public/ef/driver-license/detail', $payload);

        return response()->json([
            'status' => $response->status(),
            'body' => $response->json()
        ]);
    }


    public function rsaEncrypt($data)
    {
        $publicKey = openssl_pkey_get_public(file_get_contents(storage_path('app/codef_public_key.pem')));
        openssl_public_encrypt($data, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);
        return base64_encode($encrypted);
    }

}