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
use App\Models\TaksongStatusTemp;
use App\Notifications\JobSuccessNotification;
use Carbon\Carbon;
use Exception;

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
            $taksong_wish_at = Carbon::parse($this->data['taksongWishAt'])->format('Y-m-d');
            $taksong_wish_at_time = Carbon::parse($this->data['taksongWishAt'])->format('H:i');

            $sendData = [
                'auth' => config('taksongApi.TAKSONG_AUTH'),
                'chk_trans_type' => 'RD',
                'chk_accepted_at' => $taksong_wish_at,
                'chk_accepted_time_at' => $taksong_wish_at_time,
                'chk_car_no' => $this->data['carNo'],
                'chk_car_model' => $this->data['carModel'],
                'chk_want_insure' => '0',
                'chk_departure_mobile' => $this->data['mobile'],
                'chk_departure_address' => $this->data['startAddr'],
                'chk_dest_mobile' => $this->data['destMobile'],
                'chk_dest_address' => $this->data['destAddr'],
                'api_key' => config('taksongApi.TAKSONG_API_KEY')
            ];

            Log::info('[TaksongAddJob] API 호출 시도', ['url' => $this->endPoint, 'payload' => $sendData]);

            $result = $api->sendPost($this->endPoint, $sendData, 'TaksongAddJob');

            if (!$result || !isset($result['data'])) {
                throw new Exception('탁송처리 API 응답 오류');
            }

            $taksongStatusTemp = new TaksongStatusTemp();
            $taksongStatusTemp->auction_id = $this->data['id'];
            $taksongStatusTemp->chk_id = $result['data']['chk_id'] ?? null;
            $taksongStatusTemp->chk_status = $result['data']['chk_status'] ?? null;
            $taksongStatusTemp->save();

            Log::info('[TaksongAddJob] API 호출 성공', ['result' => $result]);

            try {

                AuctionDlvrJob::dispatch($this->data['userId'], $this->data, $this->response, 'user');
                AuctionDlvrJob::dispatch($this->data['bidUserId'], $this->data, $this->response, 'dealer');

                // Notification::route('mail', 'admin@example.com')
                //     ->notify(new JobSuccessNotification($result));
                Log::info('[TaksongAddJob] 알림 전송 완료');
            } catch (Exception $e) {
                Log::error('[TaksongAddJob] 알림 실패', ['message' => $e->getMessage()]);
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
            Log::critical('[TaksongAddJob] 처리 실패', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
