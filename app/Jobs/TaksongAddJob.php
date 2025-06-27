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
use App\Services\TaksongService;
use Carbon\Carbon;
use Exception;
use App\Models\Auction;
use App\Helpers\Wca;

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

        $data = [
            'carNo' => $this->data['car_no'],
            'carModel' => $this->data['car_model'],
            'mobile' => $this->data['mobile'],
            'destMobile' => $this->data['dest_mobile'],
            'taksongWishAt' => $this->data['taksong_wish_at'],
            'startAddr' => $this->data['start_addr'],
            'destAddr' => $this->data['dest_addr'],
        ];

        $taksongService = new TaksongService(app(ApiRequestService::class));
        $result = $taksongService->addTaksong($data);

        if($result['status'] == 'ok'){

            // Auction::where('id', $this->data['auction_id'])->update(['taksong_id' => $result['chk_id'], 'taksong_status' => 'ask', 'status' => 'dlvr']);

            $auction = Auction::find($this->data['id']);
            $auction->taksong_status = '';
            $auction->taksong_id = $result['data']['chk_id'] ?? null;
            $auction->update();

            AuctionDlvrJob::dispatch($this->data['user_id'], $this->data, $this->response, 'user');
            AuctionDlvrJob::dispatch($this->data['bid_user_id'], $this->data, $this->response, 'dealer');

        }

    }
}
