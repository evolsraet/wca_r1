<?php

namespace App\Http\Controllers\Api;

use App\Services\CarDataMappingService;
use Illuminate\Http\Request;
use App\Services\CarInfoService;
use App\Services\NiceDNRService;
use App\Services\AuctionService;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CarSellApiController extends Controller
{
    private $auctionService;

    public function __construct(AuctionService $auctionService)
    {
        $this->auctionService = $auctionService;
    }
    // 차량 정보 캐시 및 api 호출
    function lookupCarInfo(Request $request)
    {
        try {
            // Referrer 검증
            if (!$this->validateReferrer($request)) {
                return response()->api([], '잘못된 경로로 접근하였습니다.', 'fail', 400);
            }

            // 유효성 검사
            $validated = $request->validate([
                'owner' => 'required|string|max:50',
                'no' => 'required|string|max:20',
                'gradeId' => 'nullable|numeric',
            ]);

            // AuctionService를 통한 차량 정보 조회
            $resultData = $this->auctionService->getCarInfo(
                $validated['owner'], 
                $validated['no'],
                ['forceRefresh' => $request->input('forceRefresh')]
            );

            // 등급 선택이 필요하지 않은 경우 조회 내역 저장
            if (empty($resultData['gradeList'])) {
                $carInfoService = new CarInfoService();
                $carInfoService->storeUserQueryHistory(
                    $validated['owner'],
                    $validated['no'],
                    $validated['gradeId']
                );
            }

            return response()->api($resultData, '차량 정보를 성공적으로 조회했습니다.');

        } catch (\Exception $e) {
            Log::error('[차량정보 조회] API 오류', [
                'owner' => $request->input('owner'),
                'no' => $request->input('no'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->api([], $e->getMessage(), 'fail', 400);
        }
    }

    /**
     * Referrer 검증
     */
    private function validateReferrer(Request $request): bool
    {
        $referrer = $request->header('referer');
        return $referrer && strpos($referrer, config('app.url')) !== false;
    }

    // 등급 선택 후 조회 내역 캐시 저장
    public function storeGradeSelection(Request $request)
    {
        try {
            $validated = $request->validate([
                'owner' => 'required|string|max:50',
                'no' => 'required|string|max:20',
                'gradeId' => 'required|string',
            ]);
    
            $carInfoService = new CarInfoService();
            $carInfoService->storeUserQueryHistory(
                $validated['owner'],
                $validated['no'],
                $validated['gradeId']
            );
    
            return response()->api([], '조회 내역을 성공적으로 저장했습니다.');
            
        } catch (\Exception $e) {
            Log::error('[등급 선택] 저장 오류', [
                'owner' => $request->input('owner'),
                'no' => $request->input('no'),
                'gradeId' => $request->input('gradeId'),
                'error' => $e->getMessage()
            ]);

            return response()->api([], $e->getMessage(), 'fail', 400);
        }
    }
} 