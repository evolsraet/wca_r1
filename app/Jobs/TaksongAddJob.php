<?php
namespace App\Jobs;

use App\Services\ApiRequestService;
use App\Models\ApiErrorLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Jobs\AuctionDlvrJob;
// use App\Models\TaksongStatusTemp;
use App\Notifications\JobSuccessNotification;
use Carbon\Carbon;
use Exception;
use App\Models\Auction;
use App\Helpers\NetworkHelper;

class TaksongAddJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $response;
    protected $endPoint;
    protected $data;

    public function __construct($response, $data)
    {
        $this->response = $response;
        $this->endPoint = config('taksongApi.TAKSONG_API_URL');
        $this->data = $data;
    }

    public function handle(ApiRequestService $api): void
    {
        try {
            $taksong_wish_at = Carbon::parse($this->data['taksong_wish_at'])->format('Y-m-d');
            $taksong_wish_at_time = Carbon::parse($this->data['taksong_wish_at'])->format('H:i');

            $sendData = [
                'auth' => config('taksongApi.TAKSONG_AUTH'),
                'chk_trans_type' => 'RD',
                'chk_accepted_at' => $taksong_wish_at,
                'chk_accepted_time_at' => $taksong_wish_at_time,
                'chk_car_no' => $this->data['car_no'],
                'chk_car_model' => $this->data['car_model'],
                'chk_want_insure' => '0',
                'chk_departure_mobile' => $this->data['mobile'],
                'chk_departure_address' => $this->data['start_addr'],
                'chk_dest_mobile' => $this->data['dest_mobile'],
                'chk_dest_address' => $this->data['dest_addr'],
                'chk_dest_alarm_mobile' => $this->data['dest_mobile'],
                'api_key' => config('taksongApi.TAKSONG_API_KEY')
            ];


            $sendRequest = [
                'method' => 'POST',
                'url' => $this->endPoint,
                'params' => $sendData,
                'logContext' => 'TaksongAddJob',
            ];

            $result = $api->sendRequest($sendRequest);

            if (!$result || !isset($result['data'])) {
                throw new Exception('Connection timed out: Failed to connect to '.$this->endPoint, 500);
            }

            // 이 부분 제거 필요 
            // $taksongStatusTemp = new TaksongStatusTemp();
            // $taksongStatusTemp->auction_id = $this->data['id'];
            // $taksongStatusTemp->chk_id = $result['data']['chk_id'] ?? null;
            // $taksongStatusTemp->chk_status = $result['data']['chk_status'] ?? null;
            // $taksongStatusTemp->save();

            if(isset($result['data'])){
                $auction = Auction::find($this->data['id']);
                //$auction->status = 'dlvr'; // 차량대금 입금완료시 딜리버리 상태로 변경
                $auction->is_taksong = 'ask';
                $auction->taksong_id = $result['data']['chk_id'] ?? null;
                $auction->update();
            }

            Log::info('[탁송 등록] API 호출 성공', [
                'name'=> '탁송 등록 API 호출 성공',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'result' => $result,
                'sendData' => $sendData
            ]);

            try {

                AuctionDlvrJob::dispatch($this->data['user_id'], $this->data, $this->response, 'user');
                AuctionDlvrJob::dispatch($this->data['bid_user_id'], $this->data, $this->response, 'dealer');

                // Notification::route('mail', 'admin@example.com')
                //     ->notify(new JobSuccessNotification($result));

            } catch (Exception $e) {
                Log::error('[탁송 등록] 알림 실패', [
                    'name'=> '탁송 등록 알림 실패',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'message' => $e->getMessage(),
                    'sendData' => $sendData
                ]);
                ApiErrorLog::create([
                    'job_name' => static::class,
                    'method' => 'NOTIFY',
                    'url' => null,
                    'payload' => $this->data,
                    'response_body' => null,
                    'error_message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            

        } catch (Exception $e) {

            NetworkHelper::alertIfNetworkError($e, [
                'source' => [
                    'title' => '탁송API / 탁송신청 요청',
                    'url' => $this->endPoint,
                    'context' => $e->getMessage(),
                    'sendData' => $sendRequest,
                ],
                'time' => now()->toDateTimeString(),
            ]);

            Log::info('[탁송 등록] 처리 실패', [
                'name'=> '탁송 등록 처리 실패',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sendData' => $sendData
            ]);

            ApiErrorLog::create([
                'job_name' => static::class,
                'method' => 'POST',
                'url' => $this->endPoint,
                'payload' => $this->data,
                'response_body' => null,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
