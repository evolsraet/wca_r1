<?php   

namespace App\Services;

use App\Services\ApiRequestService;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Jobs\AuctionBidStatusJob;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\DiagInfo;
use App\Helpers\Wca;
use App\Models\AuctionLog;
use App\Models\ApiErrorLog;

// 진단 서비스
class DiagService
{
    protected $diagApiUrl;
    protected $diagApiKey;
    protected $diagApiAuth;

    public function __construct(ApiRequestService $api)
    {
        $this->diagApiUrl = config('services.diagnostic.api_url');
        $this->diagApiKey = config('services.diagnostic.api_key');
        $this->diagApiAuth = config('services.diagnostic.api_id');
    }


    // 진단코드 호출
    public function diagnosticCode()
    {
        // http 사용 해서 위 코드 수정 
        $response = Http::asForm()
            ->post($this->diagApiUrl.'codes', [
                'auth' => $this->diagApiAuth,
                'api_key' => $this->diagApiKey
            ]);

        
        if ($response->failed()) {
            throw new \Exception('[진단 코드 조회 실패] ' . $response->body());
        }
        
        $response = json_decode($response, true);
        return $response;
    }


    // 진단데이터 확인
    public function getDiagData($result): array
    {

        $carNo = $result['diag_car_no'];

        $hashid = Hashids::decode($carNo);
        $auction = Auction::where('id', $hashid)->orWhere('car_no', $carNo)->first();
        
        if (!$carNo) {
            return [
                'status' => 'error',
                'message' => '진단 데이터 조회 실패. 차량번호 또는 고객사코드가 입력되지 않았습니다.',
                'code' => 400,
                'data' => [],
            ];
        }
    
        // 1. DB에 진단 데이터가 이미 있는지 확인 (car_no 기준)
        $existing = DiagInfo::where('code', $carNo)->first();
    
        if ($existing) {
            return [
                'status' => 'ok',
                'message' => 'DB에서 진단 데이터 불러옴',
                'code' => 200,
                'data' => $existing->diag_data,
            ];
        }    

        try {
            $apiRequestService = new ApiRequestService();

            $sendData = [
                'method' => 'POST',
                'url' => $this->diagApiUrl . 'diag_by_car_no',
                'params' => [
                    'auth' => $this->diagApiAuth,
                    'api_key' => $this->diagApiKey,
                    'diag_car_no' => $carNo,
                ],
            ];

            $response = $apiRequestService->sendRequest($sendData);

            if(!$response){
                throw new \Exception('Connection timed out: Failed to connect to '.$this->diagApiUrl, 500);
            }

            if (!isset($response['status']) || $response['status'] !== 'ok') {

                // DB에 저장 
                ApiErrorLog::create([
                    'job_name' => '진단데이터 조회',
                    'method' => 'POST',
                    'url' => $this->diagApiUrl . 'diag_by_car_no',
                    'payload' => json_encode($sendData, JSON_UNESCAPED_UNICODE),
                    'response_body' => json_encode($response, JSON_UNESCAPED_UNICODE),
                    'error_message' => '진단 데이터 조회 실패. 진단에 미등록된 차량입니다.',
                    'trace' => json_encode($response, JSON_UNESCAPED_UNICODE),
                ]);


                Log::debug("[진단 데이터] 진단에 미등록된 차량입니다: {$carNo} / id: {$auction->id}", [
                    'name'=> '진단 데이터 조회 실패. 진단에 미등록된 차량입니다.',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'response' => $response
                ]);
                return [
                    'status' => 'error',
                    'message' => '진단 데이터 조회 실패. 진단에 미등록된 차량입니다.',
                    'code' => 500,
                    'data' => [],
                ];
            }

            // 진단 데이터가 확인되었고 본사검수까지 되었는지 체크
            if (empty($response['data']['diag_is_confirmed'])) {

                // DB에 저장 
                ApiErrorLog::create([
                    'job_name' => '진단데이터 본사검수 체크',
                    'method' => 'POST',
                    'url' => $this->diagApiUrl . 'diag_by_car_no',
                    'payload' => json_encode($sendData, JSON_UNESCAPED_UNICODE),
                    'response_body' => json_encode($response, JSON_UNESCAPED_UNICODE),
                    'error_message' => '본사 검수가 되지 않았습니다.',
                    'trace' => json_encode($response, JSON_UNESCAPED_UNICODE),
                ]);

                Log::debug("[진단 데이터] 본사 검수가 되지 않았습니다: {$carNo}", [
                    'name'=> '진단 데이터 조회 실패. 본사 검수가 되지 않았습니다.',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'response' => $response
                ]);
                return [
                    'status' => 'error',
                    'message' => '본사 검수가 되지 않았습니다.',
                    'code' => 400,
                    'data' => [],
                ];
            }

            // 진단 코드 가져오기
            $diagnosticCode = $this->diagnosticCode();

            if (!isset($diagnosticCode['status']) || $diagnosticCode['status'] !== 'ok') {
                
                // DB에 저장 
                ApiErrorLog::create([
                    'job_name' => '진단데이터 코드 조회',
                    'method' => 'POST',
                    'url' => $this->diagApiUrl . 'diag_by_car_no',
                    'payload' => json_encode($sendData, JSON_UNESCAPED_UNICODE),
                    'response_body' => json_encode($response, JSON_UNESCAPED_UNICODE),
                    'error_message' => '진단 코드 조회 실패.',
                    'trace' => json_encode($response, JSON_UNESCAPED_UNICODE),
                ]);
                
                Log::error("[진단 데이터] 코드오류 : {$carNo}", [
                    'name'=> '진단 데이터 조회 실패. 진단 코드 조회 실패.',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'diagnosticCode' => $diagnosticCode
                ]);
                return [
                    'status' => 'error',
                    'message' => '진단 코드 조회 실패.',
                    'code' => 500,
                    'data' => [],
                ];
            }

            $diagOptions = $diagnosticCode['data']['option'];
            $response['diag_option_view'] = [];

            foreach ($diagOptions as $key => $option) {
                if (isset($response['data']['diag_option'][$key])) {
                    $isOk = !in_array($response['data']['diag_option'][$key], ['없음', 0], true);
                    $response['diag_option_view'][] = [
                        'id' => $key,
                        'name' => $option['name'],
                        'is_ok' => $isOk,
                    ];
                }
            }

            DiagInfo::create([
                'code' => $carNo,
                'diag_data' => $response,
            ]);

            return [
                'status' => 'ok',
                'message' => '진단 데이터 조회 성공',
                'code' => 200,
                'data' => $response,
            ];

        } catch (\Exception $e) {

            // 네트워크 오류 알림 추가
            Wca::alertIfNetworkError($e, [
                'source' => [
                    'title' => '진단API / 진단데이터 확인',
                    'url' => $this->diagApiUrl,
                    'context' => $e->getMessage(),
                    'sendData' => $sendData,
                ],
                'time' => now()->toDateTimeString(),
            ]);

            Log::error("[진단 데이터] 조회 실패 {$carNo}", [
                'name'=> '진단 데이터 조회 실패',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'error' => $e->getMessage()
            ]);
            return [
                'status' => 'error',
                'message' => '진단 데이터 조회 중 오류 발생: ' . $e->getMessage(),
                'code' => 500,
                'data' => [],
            ];
        }
    }


    public function diagnosticCheck(): array
    {
        $validResults = [];
        $auctions = Auction::where('status', 'diag')
            ->whereNull('diag_id')
            ->whereNull('diag_check_at')
            ->get();

        if ($auctions->isEmpty()) {
            Log::info('[진단 상태 확인] 대기 중인 경매 0건', [
                'name'=> '진단 상태 확인',
                'path'=> __FILE__,
                'line'=> __LINE__,
            ]);
            return $validResults;
        }

        foreach ($auctions as $auction) {
            try {
                $hashid = Hashids::encode($auction->id);
                $result = $this->getDiagData(['diag_car_no' => $hashid]);

                if(!$result){
                    throw new \Exception('Connection timed out: Failed to connect to '.$this->diagApiUrl, 500);
                }

                $resultArray = is_array($result) ? $result : json_decode(json_encode($result), true);

                if (
                    isset($resultArray['status']) &&
                    $resultArray['status'] === 'ok' &&
                    $resultArray['data']['data']['diag_is_confirmed'] == 1
                ) {
                    Log::info('[진단 상태 확인] 완료 : ' . $auction->hashid, [
                        'name'=> '진단 상태 확인',
                        'path'=> __FILE__,
                        'line'=> __LINE__,
                        'result' => $resultArray
                    ]);
                    $validResults[] = $resultArray;

                    $this->processValidDiagResult($auction, $resultArray);
                }

            } catch (\Exception $e) {

                Log::error('[진단 상태 확인] 오류 : Auction hashid: ' . $auction->hashid, [
                    'name'=> '진단 상태 확인',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $validResults;
    }

    private function processValidDiagResult(Auction $auction, array $resultArray): void
    {
        $diagStatus = $resultArray['data']['data']['diag_status'] ?? null;
        $diagDoneAt = $resultArray['data']['data']['diag_done_at'] ?? null;

        if ($diagStatus === 'done' && $diagDoneAt) {
            $hashid = Hashids::decode($resultArray['data']['data']['diag_outer_id']);

            if (empty($hashid[0])) {
                Log::warning('[진단 상태 확인] Hashids decode 실패', [
                    'name'=> '진단 상태 확인',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'diag_outer_id' => $resultArray['data']['data']['diag_outer_id']
                ]);
                return;
            }
            
            $targetAuction = Auction::find($hashid[0] ?? null);

            if (!$targetAuction) {
                Log::warning('[진단 상태 확인] 고객사 코드 오류', [
                    'name'=> '진단 상태 확인',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'diag_outer_id' => $resultArray['data']['data']['diag_outer_id']
                ]);
                return;
            }

            $files = $this->extractDiagFiles($resultArray['data']['data']);
            $targetAuction->status = 'ing';
            $targetAuction->diag_check_at = $diagDoneAt;
            $targetAuction->is_accident = 1;
            $targetAuction->car_thumbnail = json_encode($files, JSON_UNESCAPED_UNICODE);
            $targetAuction->final_at = now()->addDays(config('days.auction_day'));
            $targetAuction->save();

            AuctionBidStatusJob::dispatch($targetAuction->user_id, 'ing', $targetAuction->id, '', '');

            Log::info('[진단 상태 확인 완료] : ' . $targetAuction->id, [
                'name'=> '진단 상태 확인 완료',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'result' => $resultArray
            ]);
        }
    }

    private function extractDiagFiles(array $data): array
    {
        $files = [];
        $baseUrl = str_replace('/api/auth/diag/', '', config('services.diagnostic.api_url'));

        foreach ($data as $key => $value) {
            if (Str::startsWith($key, 'file_') && is_array($value)) {
                foreach ($value as $file) {
                    if (isset($file['web_path'])) {
                        $files[] = $baseUrl . '/' . $file['web_path'];
                    }
                }
            }
        }

        return $files;
    }

    public function diagnosticCheckAdminMessage()
    {
        // 진단일 예정일 하루 지나고 한번 관리자에게 메일 전송 
    }

}