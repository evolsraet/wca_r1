<?php   

namespace App\Services;

use App\Services\ApiRequestService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\TaksongStatusTemp;
use App\Jobs\TaksongNameChangeJob;
use App\Jobs\AuctionCancelJob;

class TaksongService
{
    protected $api;
    protected $taksongApiUrl;
    protected $taksongApiKey;
    protected $taksongApiAuth;

    public function __construct(ApiRequestService $api)
    {
        $this->api = $api;
        $this->taksongApiUrl = config('taksongApi.TAKSONG_API_URL');
        $this->taksongApiKey = config('taksongApi.TAKSONG_API_KEY');
        $this->taksongApiAuth = config('taksongApi.TAKSONG_AUTH');
    }

    // 탁송 요청 처리
    public function addTaksong(array $data)
    {
        $carNo = $data['carNo'] ?? 'Unknown';

        try {

            if($carNo == 'Unknown'){
                throw new Exception('차량번호가 없습니다.', 422);
            }

            Log::debug("[탁송요청 {$carNo}] 시작", [
                'api_url' => $this->taksongApiUrl,
                'data' => $data
            ]);

            // 필수값 검증
            $requiredFields = ['carNo', 'carModel', 'mobile', 'startAddr', 'destMobile', 'destAddr'];

            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $message = "필수값 누락: {$field}";
                    Log::debug("[탁송요청 {$carNo}] 필수값 누락: {$field}", ['field' => $field, 'input_data' => $data]);
                    throw new Exception($message, 422);
                }
            }

            // 기본값 처리
            $data['taksongWishAt'] = $data['taksongWishAt'] ?? now()->format('Y-m-d H:i:s');
            $taksongDate = Carbon::parse($data['taksongWishAt'])->format('Y-m-d');
            $taksongTime = Carbon::parse($data['taksongWishAt'])->format('H:i');

            $sendData = [
                'auth' => $this->taksongApiAuth,
                'chk_trans_type' => 'RD',
                'chk_accepted_at' => $taksongDate,
                'chk_accepted_time_at' => $taksongTime,
                'chk_car_no' => $data['carNo'],
                'chk_car_model' => $data['carModel'],
                'chk_want_insure' => '0',
                'chk_departure_mobile' => $data['mobile'],
                'chk_departure_address' => $data['startAddr'],
                'chk_dest_mobile' => $data['destMobile'],
                'chk_dest_address' => $data['destAddr'],
                'api_key' => $this->taksongApiKey,
            ];

            $result = $this->api->sendRequest('POST', $this->taksongApiUrl, $sendData, '[탁송 / '.$carNo.']');

            if (!$result) {
                $message = '탁송처리 API 요청 실패: 응답 없음';
                Log::error('[탁송 / '.$carNo.'] API 응답 실패', ['request' => $sendData]);
                throw new Exception($message, 500);
            }

            Log::info('[탁송 / '.$carNo.'] 탁송 요청 성공', ['result' => $result]);
            return $result;

        } catch (Exception $e) {
            Log::error('[탁송 / '.$carNo.'] 예외 발생', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ]);

            // 컨트롤러에서 바로 json 반환할 경우를 가정한 구조
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }


    public function processStatus(array $data): void
    {
        $status = $data['chk_status'] ?? null;
        $carNo = $data['chk_car_no'] ?? null;


        try {
            $auction = Auction::where('car_no', $carNo)->firstOrFail();
            $bid = Bid::find($auction->bid_id);
            $user_id = $auction->user_id;
            $bid_user_id = $bid?->user_id;
            $auction_id = $auction->id;

            $modifyData = [
                'auction_id' => $auction_id,
                'user_id' => $user_id,
                'bid_user_id' => $bid_user_id
            ];

            // 아이템 딜리버리 상태로 변경 
            $this->updateAuctionStatus($auction_id);

            // 탁송 상태 처리
            match($status) {
                'start'  => $this->handleStart($carNo),
                'ing'    => $this->handleInProgress($carNo, $data),
                'done'   => $this->handleDone($carNo, $auction, $bid, $status, $modifyData),
                'cancel' => $this->handleCancel($carNo, $modifyData),
                default  => Log::warning("[탁송 / {$carNo}] 알 수 없는 상태", ['status' => $status, 'car_no' => $carNo]),
            };
    
            Log::info("[탁송 / {$carNo}] 상태 처리 완료", ['status' => $status, 'car_no' => $carNo]);

        } catch (Exception $e) {
            Log::error("[탁송 / {$carNo}] 예외 발생", [
                'status' => $status,
                'car_no' => $carNo,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }


    // 아이템 딜리버리 상태로 변경 
    private function updateAuctionStatus($auction_id){
        Auction::where('id', $auction_id)->update(['status' => 'dlvr']);
    }

    // 탁송 시작 상태 처리
    private function handleStart(string $carNo)
    {
        Auction::where('car_no', $carNo)->update(['is_taksong' => 'start']);
    }

    // 탁송 진행중 상태 처리
    private function handleInProgress(string $carNo, $data){
        $updateData = [
            'taksong_courier_fee' => $data['chk_courier_fee'] ?? null,
            'taksong_courier_name' => $data['chk_courier_name'] ?? null,
            'taksong_courier_mobile' => $data['chk_courier_mobile'] ?? null,
            'taksong_departure_address' => $data['chk_departure_address'] ?? null,
            'taksong_departure_mobile' => $data['chk_departure_mobile'] ?? null,
            'taksong_dest_address' => $data['chk_dest_address'] ?? null,
            'taksong_dest_mobile' => $data['chk_dest_mobile'] ?? null,
            'taksong_departure_at' => $data['chk_departure_at'] ?? null,
            'taksong_dest_at' => $data['chk_dest_at'] ?? null,
            'is_taksong' => 'ing'
        ];

        Auction::where('car_no', $carNo)->update($updateData);
    }

    // 탁송 완료 상태 처리
    private function handleDone(string $carNo, $auction, $bid, $status, $modifyData){
        Log::info("[탁송 / {$carNo}] 탁송 상태 처리 완료 확인", ['status' => $status, 'car_no' => $carNo]);

        $isCheck = Auction::where('car_no', $carNo)->where('status', 'dlvr')->first();
        if($isCheck){
            $updateData = [
                'is_taksong' => 'done',
                'done_at' => now()
            ];
            Auction::where('car_no', $carNo)->update($updateData);

            // 명의이전 등록 관련 메시지 전달 
            TaksongNameChangeJob::dispatch($modifyData['user_id'], $modifyData['auction_id'], 'user');
            TaksongNameChangeJob::dispatch($modifyData['bid_user_id'], $modifyData['auction_id'], 'dealer');

        }
    }

    // 탁송 취소 상태 처리
    private function handleCancel(string $carNo, $modifyData){


        Auction::where('car_no', $carNo)->update(['status' => 'cancel']);

        // 취소 메시지 전달
        AuctionCancelJob::dispatch($modifyData['user_id'], $modifyData['auction_id']);
        AuctionCancelJob::dispatch($modifyData['bid_user_id'], $modifyData['auction_id']);
    }

}