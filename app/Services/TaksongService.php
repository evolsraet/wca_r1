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

    public function addTaksong(array $data)
    {
        Log::info('탁송 API 설정 확인', [
            'api_url' => $this->taksongApiUrl,
            'api_key' => $this->taksongApiKey,
            'api_auth' => $this->taksongApiAuth
        ]);

        $data['taksongWishAt'] = $data['taksongWishAt'] ?? '2025-04-22 10:00:00';
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

        // 실제 요청
        $result = $this->api->sendPost($this->taksongApiUrl, $sendData, 'TaksongService');

        if (!$result) {
            throw new Exception('탁송처리 API 요청 실패: 응답 없음');
        }

        return $result;
    }


    public function processStatus(array $data): void
    {
        $status = $data['chk_status'] ?? null;
        $carNo = $data['chk_car_no'] ?? null;

        $auction = Auction::where('car_no', $carNo)->firstOrFail();
        $bid = Bid::find($auction->bid_id);
        $user_id = $auction->user_id;
        $bid_user_id = $bid?->user_id;
        $auction_id = $auction->id;

        Auction::where('id', $auction_id)->update(['status' => 'dlvr']);

        switch ($status) {
            case 'start':
                Auction::where('car_no', $carNo)->update(['is_taksong' => 'start']);

                // 이 부분도 제거 
                // TaksongStatusTemp::where('chk_id', $data['chk_id'])->update(['chk_status' => 'start']);
                break;

            case 'ing':
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

                // 이 부분도 제거 
                // TaksongStatusTemp::where('chk_id', $data['chk_id'])->update(['chk_status' => 'ing']);
                break;

            case 'done':
                // 이 부분도 제거  대채 방법 코드 변경 필요 
                // $taksongStatus = TaksongStatusTemp::where('chk_id', $data['chk_id'])->get();

                // foreach ($taksongStatus as $item) {
                //     if ($item->chk_status === 'ing') {
                //         $updateData = [
                //             'status' => 'dlvr',
                //             'is_taksong' => 'done',
                //             'done_at' => now()
                //         ];

                //         Auction::where('car_no', $carNo)->update($updateData);

                //         // 명의이전 등록 관련 메시지 전달 
                //         TaksongNameChangeJob::dispatch($user_id, $auction_id, 'user');
                //         TaksongNameChangeJob::dispatch($bid_user_id, $auction_id, 'dealer');

                //         // 이 부분도 제거 
                //         // TaksongStatusTemp::where('chk_id', $data['chk_id'])->update(['chk_status' => 'requested']);
                //     }
                // }

                Log::info('[TaksongService] 탁송 상태 처리 완료 확인', ['status' => $status, 'car_no' => $carNo]);

                $isCheck = Auction::where('car_no', $carNo)->where('status', 'dlvr')->where('is_taksong', 'ing')->first();
                if($isCheck){
                    $updateData = [
                        'is_taksong' => 'done',
                        'done_at' => now()
                    ];
                    Auction::where('car_no', $carNo)->update($updateData);

                    // 명의이전 등록 관련 메시지 전달 
                    TaksongNameChangeJob::dispatch($user_id, $auction_id, 'user');
                    TaksongNameChangeJob::dispatch($bid_user_id, $auction_id, 'dealer');

                }

                break;

            case 'cancel':
                Auction::where('car_no', $carNo)->update(['status' => 'cancel']);

                AuctionCancelJob::dispatch($user_id, $auction_id);
                AuctionCancelJob::dispatch($bid_user_id, $auction_id);

                // 이 부분도 제거 
                // TaksongStatusTemp::where('chk_id', $data['chk_id'])->update(['chk_status' => 'cancel']);
                break;
        }

        Log::info('[TaksongService] 탁송 상태 처리 완료', ['status' => $status, 'car_no' => $carNo]);
    }

}