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
        
        if (!$carNo) {
            return [
                'status' => 'error',
                'message' => '유효하지 않은 차량번호',
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

            $response = $apiRequestService->sendRequest(
                'POST',
                $this->diagApiUrl . 'diag_by_car_no',
                [
                    'auth' => $this->diagApiAuth,
                    'api_key' => $this->diagApiKey,
                    'diag_car_no' => $carNo,
                ],
                '진단 결과 / ' . $carNo
            );

            if (!isset($response['status']) || $response['status'] !== 'ok') {
                Log::debug("[진단 데이터] 진단에 미등록된 차량입니다: {$carNo}", ['response' => $response]);
                return [
                    'status' => 'error',
                    'message' => '진단 데이터 조회 실패. 진단에 미등록된 차량입니다.',
                    'code' => 500,
                    'data' => [],
                ];
            }

            // 진단 데이터가 확인되었고 본사검수까지 되었는지 체크
            if (empty($response['data']['diag_is_confirmed'])) {
                Log::debug("[진단 데이터] 본사 검수가 되지 않았습니다: {$carNo}", ['response' => $response]);
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
                Log::error("[진단 데이터] 코드오류 : {$carNo}", ['diagnosticCode' => $diagnosticCode]);
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
            Log::error("[진단 데이터] 조회 실패 {$carNo}", ['error' => $e->getMessage()]);
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
            Log::info('[진단 상태 확인] 대기 중인 경매 0건');
            return $validResults;
        }

        foreach ($auctions as $auction) {
            try {
                $hashid = Hashids::encode($auction->id);
                $result = $this->getDiagData(['diag_car_no' => $hashid]);
                $resultArray = is_array($result) ? $result : json_decode(json_encode($result), true);

                if (
                    isset($resultArray['status']) &&
                    $resultArray['status'] === 'ok' &&
                    $resultArray['data']['data']['diag_is_confirmed'] == 1
                ) {
                    Log::info('[진단 상태 확인] 완료 : ' . $auction->hashid, ['result' => $resultArray]);
                    $validResults[] = $resultArray;

                    $this->processValidDiagResult($auction, $resultArray);
                }

            } catch (\Exception $e) {
                Log::error('[진단 상태 확인] 오류 : Auction hashid: ' . $auction->hashid, ['error' => $e->getMessage()]);
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
                Log::warning('[진단 상태 확인] Hashids decode 실패', ['diag_outer_id' => $resultArray['data']['data']['diag_outer_id']]);
                return;
            }
            
            $targetAuction = Auction::find($hashid[0] ?? null);

            if (!$targetAuction) {
                Log::warning('[진단 상태 확인] 고객사 코드 오류', ['diag_outer_id' => $resultArray['data']['data']['diag_outer_id']]);
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

            Log::info('[진단 상태 확인 완료] : ' . $targetAuction->id, ['result' => $resultArray]);
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

}