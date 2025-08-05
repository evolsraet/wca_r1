<?php   

namespace App\Services;

use App\Services\ApiRequestService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use App\Models\Auction;
use App\Models\Bid;
use App\Jobs\TaksongNameChangeJob;
use App\Jobs\AuctionCancelJob;
use App\Models\Dealer;
use App\Helpers\Wca;
use App\Jobs\AuctionTotalDepositJob;

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

            Log::debug("[탁송 요청] {$carNo} / 시작", [
                'name'=> '탁송 요청 시작',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'api_url' => $this->taksongApiUrl,
                'data' => $data
            ]);

            // 필수값 검증
            $requiredFields = ['carNo', 'carModel', 'mobile', 'startAddr', 'destMobile', 'destAddr'];

            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $message = "필수값 누락: {$field}";
                    Log::debug("[탁송 요청] {$carNo} / 필수값 누락: {$field}", [
                        'name'=> '탁송 요청 필수값 누락',
                        'path'=> __FILE__,
                        'line'=> __LINE__,
                        'field' => $field,
                        'input_data' => $data
                    ]);
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
                'chk_dest_alarm_mobile' => $data['destMobile'],
                'api_key' => $this->taksongApiKey,
            ];


            $sendRequest = [
                'method' => 'POST',
                'url' => $this->taksongApiUrl,
                'params' => $sendData,
                'logContext' => '[탁송 / '.$carNo.']',
            ];

            $result = $this->api->sendRequest($sendRequest);

            if (!$result) {
                $message = 'Connection timed out: Failed to connect to '.$this->taksongApiUrl;
                Log::error('[탁송 요청] {$carNo} / API 응답 실패', [
                    'name'=> '탁송 요청 API 응답 실패',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'request' => $sendRequest
                ]);
                throw new Exception($message, 500);
            }

            Log::info('[탁송 요청] {$carNo} / 탁송 요청 성공', [
                'name'=> '탁송 요청 성공',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'result' => $result
            ]);
            return $result;

        } catch (Exception $e) {

            // 네트워크 오류 알림 추가
            Wca::alertIfNetworkError($e, [
                'source' => [
                    'title' => '탁송API / 탁송신청 요청',
                    'url' => $this->taksongApiUrl,
                    'context' => $e->getMessage(),
                    'sendData' => $sendRequest,
                ],
                'time' => now()->toDateTimeString(),
            ]);
    
            
            Log::error('[탁송 요청] {$carNo} / 예외 발생', [
                'name'=> '탁송 요청 예외 발생',
                'path'=> __FILE__,
                'line'=> __LINE__,
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
        // TODO: 0625 - carNo 대신 auction_id 로 변경 
        $status = $data['chk_status'] ?? null;
        $carNo = $data['chk_car_no'] ?? null;
        $chk_id = $data['chk_id'] ?? null;

        try {
            $auction = Auction::where('taksong_id', $chk_id)->firstOrFail();
            $bid = Bid::find($auction->bid_id);

            $modifyData = [
                'auction_id' => $auction->id,
                'user_id' => $auction->user_id,
                'bid_user_id' => $bid?->user_id
            ];

            // 아이템 딜리버리 상태로 변경 
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
                'taksong_status' => null
            ];

            // 탁송 상태 처리
            switch($status){



                case 'ask':
                    $updateData['taksong_status'] = 'ask';

                    if($auction->status != 'dlvr'){
                        // TODO: 고객에게 차량대금 송금 (애스크로 / 알림) 추가
                        AuctionTotalDepositJob::dispatch($auction->user_id, $auction, 'user');

                        // auction 에서 dlvr 상태로 변경
                        $auction->status = 'dlvr';
                        $auction->save();
                    }

                    break;

                case 'start':
                    $updateData['taksong_status'] = 'start';
                    break;
                case 'ing':
                    $updateData['taksong_status'] = 'ing';
                    break;
                case 'done': 

                    Log::info("[탁송 상태] {$carNo} / 탁송 상태 처리 완료 확인", [
                        'name'=> '탁송 상태 처리 완료 확인',
                        'path'=> __FILE__,
                        'line'=> __LINE__,
                        'status' => $status,
                        'car_no' => $carNo
                    ]);
            
                    $isCheck = Auction::where('taksong_id', $chk_id)->where('status', 'dlvr')->first();
                    if($isCheck){
                        $updateData = [
                            'taksong_status' => 'done',
                            'status' => 'dlvr_done',
                            'done_at' => now()
                        ];
            
                        // 명의이전 등록 관련 메시지 전달 
                        if($modifyData['user_id']){
                            TaksongNameChangeJob::dispatch($modifyData['user_id'], $modifyData['auction_id'], 'user');
                        }
                        if($modifyData['bid_user_id']){
                            TaksongNameChangeJob::dispatch($modifyData['bid_user_id'], $modifyData['auction_id'], 'dealer');
                        }
            
                    }

                    break;
                case 'cancel':
                    Auction::where('taksong_id', $chk_id)->update(['status' => 'cancel']);

                    // 취소 메시지 전달
                    AuctionCancelJob::dispatch($modifyData['user_id'], $modifyData['auction_id']);
                    AuctionCancelJob::dispatch($modifyData['bid_user_id'], $modifyData['auction_id']);

                    break;
            }


            Auction::where('taksong_id', $chk_id)->update($updateData);            

    
            Log::info("[탁송 상태] {$carNo} / 상태 처리 완료", [
                'name'=> '탁송 상태 처리 완료',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'status' => $status,
                'car_no' => $carNo
            ]);

        } catch (Exception $e) {
            Log::error("[탁송 상태] {$carNo} / 예외 발생", [
                'name'=> '탁송 상태 처리 예외 발생',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'status' => $status,
                'car_no' => $carNo,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

}