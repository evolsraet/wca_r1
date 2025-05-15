<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\Bid;
use App\Jobs\OwnershipNotifyJob;
use Illuminate\Support\Facades\Log;
use App\Services\ApiRequestService;
use App\Services\NiceDNRService;
use App\Jobs\AuctionDoneJob;
use App\Jobs\TaksongNameChangeJob;
use Exception;

class OwnershipService
{
    protected $apiRequestService;

    public function __construct(ApiRequestService $apiRequestService)
    {
        $this->apiRequestService = $apiRequestService;
    }

    /**
     * 명의이전 상태 확인 및 알림 프로세스 (자동/수동 공통)
     */
    public function checkAndNotify(int $auctionId, bool $isManual = false): array
    {
        try {
            $auction = Auction::findOrFail($auctionId);
            $bid = Bid::find($auction->bid_id);

            // 탁송이 완료되지 않으면 스킵
            if ($auction->is_taksong !== 'done') {
                $message = '탁송 상태가 완료되지 않았습니다.';
                if ($isManual) throw new Exception($message);
                Log::info('[탁송 상태 확인 오류]', ['auction_id' => $auctionId, 'message' => $message]);
                return ['status' => 'skip', 'message' => $message, 'auction_id' => $auctionId];
            }

            // 명의이전 파일 등록 여부 확인
            if ($auction->has_uploaded_name_change_file) {
                Log::info('[명의이전 파일 등록 여부 확인]', ['auction_id' => $auctionId, 'message' => '명의이전 파일이 이미 등록되었습니다.']);
                return ['status' => 'already_registered', 'auction_id' => $auctionId, 'message' => '명의이전 파일이 이미 등록되었습니다.'];
            }

            // 파일이 없으면 알림 전송
            $this->sendOwnershipAlerts($auction, $bid, $isManual);

            return ['status' => 'alert_sent', 'auction_id' => $auctionId, 'message' => '명의이전 알림이 발송되었습니다.'];

        } catch (Exception $e) {
            Log::error('[명의이전 알림 오류]', ['auction_id' => $auctionId, 'message' => $e->getMessage()]);
            return ['status' => 'error', 'auction_id' => $auctionId, 'message' => $e->getMessage()];
        }
    }

    /**
     * 외부 API로 명의이전 여부 확인 및 상태 업데이트
     */
    public function checkOwnershipByApi(Auction $auction, $test = false): bool
    {
        try {

            $niceDnrService = new NiceDNRService();
            $response = $niceDnrService->getNiceDnr($auction->owner_name, $auction->car_no, config('niceDnr.NICE_DNR_API_ENDPOINT_KEY'));

            // dd($response);

            if ($response['resultCode'] !== '0000') {
                Log::warning('[명의이전 API 실패 / ' . $auction->car_no . ']', ['auction_id' => $auction->id, 'response' => $response]);
                return false;
            }

            if ($this->isOwnershipCompleted($response, $auction, $test)) {
                $this->updateAuctionStatus($auction);
                $this->sendOwnershipAlerts($auction, Bid::find($auction->bid_id), false);
                return true;
            }

            return false;

        } catch (Exception $e) {
            Log::error('[명의이전 API 확인 오류 / ' . $auction->car_no . ']', ['auction_id' => $auction->id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * 명의이전 등록 여부 판단
     */
    private function isOwnershipCompleted(array $response, Auction $auction, $test = false): bool
    {
        $latestOwnership = null;

        if($test){
            return $test;
        }

        $resContentsList = $response['carParts']['outB0001']['list'][0]['resContentsList'] ?? [];

        foreach ($resContentsList as $record) {
            if ($record['resContentsCtg'] === '명의이전등록') {
                $registerDate = $record['resRegisterDate'] ?? null;

                if ($registerDate && (!$latestOwnership || $registerDate > $latestOwnership['resRegisterDate'])) {
                    $latestOwnership = $record;
                }
            }
        }

        if ($latestOwnership) {
            $registerDate = \Carbon\Carbon::createFromFormat('Ymd', $latestOwnership['resRegisterDate']);
            $doneAt = \Carbon\Carbon::parse($auction->done_at);

            // 명의이전 등록일이 done_at 이후면 완료
            if ($registerDate->greaterThan($doneAt)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Auction 상태 업데이트
     */
    private function updateAuctionStatus(Auction $auction): void
    {
        $auction->status = 'done';
        $auction->done_at = now();
        $auction->save();


        $dealer = Bid::find($auction->bid_id);

        // 완료된 알림 전달 하기 / 유저
        AuctionDoneJob::dispatch($auction->user_id, $auction->id, 'user');
        AuctionDoneJob::dispatch($dealer->user_id, $auction->id, 'dealer');

        Log::info('[명의이전 완료 처리 / ' . $auction->car_no . ']', ['auction' => $auction]);
    }

    /**
     * 명의이전 알림 전송
     */
    private function sendOwnershipAlerts(Auction $auction, ?Bid $bid, bool $isManual): void
    {
        if($bid && !$auction->done_at){
            TaksongNameChangeJob::dispatch($bid->user_id, $auction->id, 'dealer');
        }

        Log::info('[명의이전 파일첨부 안내 알림 발송 / ' . $auction->car_no . ']', ['auction_id' => $auction->id, 'is_manual' => $isManual]);

        if (!$isManual && $auction->done_at) {
            $daysSinceDone = now()->diffInDays($auction->done_at);
            if ($daysSinceDone >= config('days.auction_last_day')) {
                $adminId = config('services.ownership.admin_id') ?? 1;
                // OwnershipNotifyJob::dispatch($adminId, $auction->id, 'admin');
                TaksongNameChangeJob::dispatch($adminId, $auction->id, 'admin');

                Log::info('[명의이전 파일첨부 안내 운영자 알림 발송 / ' . $auction->car_no . ']', ['auction_id' => $auction->id, 'days_since_done' => $daysSinceDone]);
            }
        }
    }


    public function sendOwnershipAlertsAuto()
    {
        // 명의이전 완료 후 2일 경과 시 운영자에게 알림 발송
        $auctions = Auction::
                        where('status', 'dlvr')
                        ->where('is_taksong', 'done')   
                        ->whereNull('has_uploaded_name_change_file')
                        ->get();

        foreach ($auctions as $auction) {
            $this->checkAndNotify($auction->id, false);
        }
    }

}