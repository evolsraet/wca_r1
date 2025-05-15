<?php

namespace App\Http\Controllers\Api;

use App\Services\OwnershipService;
use App\Models\Auction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class OwnershipController extends Controller
{
    protected $ownershipService;

    public function __construct(OwnershipService $ownershipService)
    {
        $this->ownershipService = $ownershipService;
    }

    // 수동 알림 푸시 (관리자용)
    public function manualNotify($auctionId)
    {
        // auctionid 해시값 일때 복호화
        if (is_string($auctionId)) {
            $decoded = Hashids::decode($auctionId);
            $auctionId = $decoded[0] ?? null;
    
            if (!$auctionId) {
                return response()->json(['error' => '잘못된 auctionId'], 400);
            }
        }
    
        $result = $this->ownershipService->checkAndNotify($auctionId, true);
        return response()->json($result);
    }

    // 외부 API로 명의이전 상태 확인 후 상태 업데이트
    public function checkOwnership($auctionId)
    {
        try {

            $auction = Auction::findOrFail($auctionId);
            $result = $this->ownershipService->checkOwnershipByApi($auction);

            // return response()->json(['success' => $result]);

        } catch (Exception $e) {
            Log::error('[명의이전 알림 오류]', ['auction_id' => $auctionId, 'message' => $e->getMessage()]);
        }

        return response()->json(['success' => $result]);
    }


    public function checkOwnershipByApiTest(Request $request)
    {
        $auctionId = $request->id;
        $test = $request->test;
        $auction = Auction::findOrFail($auctionId);
        $result = $this->ownershipService->checkOwnershipByApi($auction, $test);
        return response()->json(['success' => $result]);
    }

}