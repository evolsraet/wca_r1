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
    protected $businessCheckApi;
    const REPEAT_COUNT = 3;
    public function __construct()
    {
        $this->clientId = config('services.codef.client_id');
        // $this->clientId = "ef27cfaa-10c1-4470-adac-60ba476273f9";
        $this->clientSecret = config('services.codef.client_secret');
        // $this->clientSecret = "83160c33-9045-4915-86d8-809473cdf5c3";
        $this->apiUrl = config('services.codef.api_url');
        $this->authUrl = config('services.codef.oauth_url');
        $this->publicKey = config('services.codef.public_key');
        $this->businessCheckApi = 'v1/kr/public/nt/etc-yearend-tax/individual-business';
    }

    public function getAccessToken()
    {
        $accessToken = $this->getToken($this->clientId, $this->clientSecret);
        return $accessToken;
    }

    public function checkBusinessStatus($data)
    {

        $payload = [
            "organization" => "0004", // 고정값
            "id" => "",
            "startDate" => date('Ymd'),// 인증일자
            "usePurposes" => "본인인증 및 개인사업자 여부 확인", // 사용용도
            "identity" => $data['identity'], // 주민번호
            "birthDate" => "", // 생년월일
            "loginType" => "5", // 로그인 타입 5. 간편인증
            "loginTypeLevel" => $data['loginTypeLevel'], // 로그인 타입 레벨 1:카카오톡, 2:페이코, 3:삼성패스, 4:KB모바일, 5:통신사(PASS), 6:네이버, 7:신한인증서, 8:토스, 9:뱅크샐러드 
            "telecom" => $data['telecom'], // 통신사 0:SKT(SKT알뜰폰), 1:KT(KT알뜰폰), 2:LGU+(LGU+알뜰폰)
            "phoneNo" => $data['phoneNo'], // 휴대폰번호
            "loginIdentity" => $data['loginIdentity'], // 생년월일
            "userName" => $data['userName'], // 이름
            // "identityEncYn" => "Y",
            // "birthDateEncYn" => $data['loginIdentity']
        ];

        $result = $this->execute($this->businessCheckApi, 1, $payload);

        Log::info('checkBusinessStatus', $result);

        return $result;
    }

    public function twoWayAuth($data){
        $payload = [
            "organization" => "0004", // 고정값
            "id" => "",
            "startDate" => date('Ymd'),// 인증일자
            "usePurposes" => "본인인증 및 개인사업자 여부 확인", // 사용용도
            "identity" => $data['identity'], // 주민번호
            "birthDate" => "", // 생년월일
            "loginType" => "5", // 로그인 타입 5. 간편인증
            "loginTypeLevel" => $data['loginTypeLevel'], // 로그인 타입 레벨 1:카카오톡, 2:페이코, 3:삼성패스, 4:KB모바일, 5:통신사(PASS), 6:네이버, 7:신한인증서, 8:토스, 9:뱅크샐러드 
            "telecom" => $data['telecom'], // 통신사 0:SKT(SKT알뜰폰), 1:KT(KT알뜰폰), 2:LGU+(LGU+알뜰폰)
            "phoneNo" => $data['phoneNo'], // 휴대폰번호
            "loginIdentity" => $data['loginIdentity'], // 생년월일
            "userName" => $data['userName'], // 이름
            "simpleAuth" => "1", // 고정값
            "is2Way" => true,
            "twoWayInfo" => [
                "jti" => $data['jti'],
                "twoWayTimestamp" => $data['twoWayTimestamp'],
                "jobIndex" => $data['jobIndex'],
                "threadIndex" => $data['threadIndex']
            ]
        ];  

        $result = $this->requestCertification($this->businessCheckApi, $payload, $data['carNumber']);

        Log::info('twoWayAuth', [$result]);

        return $result;
    }


    public static function execute(string $urlPath, int $serviceType, array $bodyMap): array
    {
        // 1. Domain, clientId, clientSecret 설정
        switch ($serviceType) {
            case 2:
                $domain = config('codef.api_domain');
                $clientId = config('codef.client_id');
                $clientSecret = config('codef.client_secret');
                break;
            case 1:
                $domain = config('services.codef.api_url');
                $clientId = config('services.codef.client_id');
                $clientSecret = config('services.codef.client_secret');
                break;
            default:
                $domain = config('services.codef.api_url');
                $clientId = 'ef27cfaa-10c1-4470-adac-60ba476273f9';
                $clientSecret = '83160c33-9045-4915-86d8-809473cdf5c3';
        }

        // 2. 토큰 확인 및 발급
        $accessToken = self::getToken($clientId, $clientSecret);

        // 3. Body 문자열 인코딩
        $bodyString = urlencode(json_encode($bodyMap, JSON_UNESCAPED_UNICODE));

        // 4. API 호출
        $responseMap = self::requestProduct($domain . $urlPath, $accessToken, $bodyString);

        if (isset($responseMap['result']['code']) && $responseMap['result']['code'] === 'CF-00401') {
            // 액세스 토큰 만료 시 재발급
            Cache::forget('codef_token_' . $clientId);
            $accessToken = self::getToken($clientId, $clientSecret);
            $responseMap = self::requestProduct($domain . $urlPath, $accessToken, $bodyString);
        } elseif (isset($responseMap['result']['code']) && $responseMap['result']['code'] === 'CF-00403') {
            return [
                'code' => 'UNAUTHORIZED',
                'message' => 'Access denied'
            ];
        }

        return $responseMap;
    }

    private static function requestProduct(string $url, string $accessToken, string $encodedBody): array
    {
        try {
            $decodedBody = json_decode(urldecode($encodedBody), true);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($url, $decodedBody);

            $raw = $response->body();

            $decoded = urldecode($raw);
            $json = json_decode($decoded, true);

            return $json;


        } catch (\Exception $e) {
            return [
                'error' => 'LIBRARY_SENDER_ERROR',
                'message' => $e->getMessage()
            ];
        }
    }

    private static function getToken(string $clientId, string $clientSecret): ?string
    {
        $cacheKey = 'codef_token_' . $clientId;
        $accessToken = Cache::get($cacheKey);

        if (!$accessToken || !self::checkToken($accessToken)) {
            $i = 0;
            while ($i < self::REPEAT_COUNT) {
                $tokenMap = self::publishToken($clientId, $clientSecret);

                if ($tokenMap && isset($tokenMap['access_token'])) {
                    $accessToken = $tokenMap['access_token'];
                    Cache::put($cacheKey, $accessToken, now()->addMinutes(55));
                    break;
                }

                usleep(20000); // 20ms 대기
                $i++;
            }
        }

        return $accessToken;
    }

    private static function publishToken(string $clientId, string $clientSecret): ?array
    {
        try {
            $auth = base64_encode($clientId . ':' . $clientSecret);

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $auth,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post(config('services.codef.oauth_url'), [
                'grant_type' => 'client_credentials',
                'scope' => 'read'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function checkToken(string $accessToken): bool
    {
        // 유효성 검사 로직 단순화. 실제로는 JWT 디코딩해서 exp 확인 가능
        return true; // 혹은 토큰 만료시간 확인 로직 추가
    }


    public function requestCertification(string $productUrl, array $parameterMap, string $carNumber)
    {
        // 1. 필수 항목 체크
        if (!$this->clientId || !$this->clientSecret) {
            return response()->json(['code' => 'CF-00001', 'message' => '클라이언트 정보가 누락되었습니다.']);
        }

        // 2. 퍼블릭 키 체크
        if (!$this->publicKey) {
            return response()->json(['code' => 'CF-00002', 'message' => '퍼블릭 키가 누락되었습니다.']);
        }

        // 3. 추가인증 파라미터 확인 (예: twoWayInfo)
        if (empty($parameterMap['twoWayInfo'])) {
            return response()->json(['code' => 'CF-00003', 'message' => '추가인증 파라미터(twoWayInfo)가 필요합니다.']);
        }

        Log::info('requestCertification_parameterMap?:', [$parameterMap, $carNumber]);

        $result = $this->execute($this->businessCheckApi, 1, $parameterMap);
        Log::info('requestCertification?:', [$result]);

        if ($result) {
            
            

            if($result['result']['code'] === 'CF-00000'){
                
                $resData = [
                    'userName' => $parameterMap['userName'],
                    'carNumber' => $carNumber,
                    'isBusinessOwner' => $result['data']['resIndividualBusinessYN'] === 'Y' ? 1 : 0,
                    'isAuth' => true
                ];

                $cacheKey = 'codef_car_number_' . $carNumber;
                Cache::put($cacheKey, $resData, now()->addDay());
            }

            return $result;
        }

        return response()->json([
            'code'    => 'CF-99999',
            'message' => '요청 실패',
            'error'   => $result['error'],
        ], $result['status']);
    }


    public function getCertificationData(string $carNumber){
        $cacheKey = 'codef_car_number_' . $carNumber;
        $resData = Cache::get($cacheKey);
        return $resData;
    }

    public function getCertificationClearData(string $carNumber){
        $cacheKey = 'codef_car_number_' . $carNumber;
        Cache::forget($cacheKey);
        return true;
    }

    // 이름과 차량정보 기준으로 세션에 저장 여부 확인해서 데이터 가져오기 


}