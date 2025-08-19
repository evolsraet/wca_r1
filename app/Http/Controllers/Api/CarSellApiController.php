<?php

namespace App\Http\Controllers\Api;

use App\Services\CarDataMappingService;
use Illuminate\Http\Request;
use App\Services\CarInfoService;
use App\Services\NiceDNRService;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class CarSellApiController extends Controller
{
    // 차량 정보 캐시 및 api 호출
    function lookupCarInfo(Request $request)
    {
        // referrer에 APP_URL 포함 여부 체크
        $referrer = $request->header('referer');
        if( strpos($referrer, config('app.url')) === false ) {
            return response()->api([] , '잘못된 경로로 접근하였습니다.', 400);
        }

        // 유효성 검사
        $request->validate([
            'owner'     => 'required|string',
            'no'        => 'required|string',
            'gradeId'   => 'nullable|numeric',
        ]);

        // carInfo 조회
        $apiRequest = Request::create('/api/auctions/carInfo', 'POST', [
            'owner' => $request->input('owner'),
            'no'    => $request->input('no'),
        ]);

        $response   = app()->handle($apiRequest);
        $result     = json_decode($response->getContent(), true);
        $resultData = $result['data'] ?? [];

        if ($response->getStatusCode() !== 200) {
            return response()->api([], $result['message'], 400);
        }

        if( empty($resultData['carInfo']) ) {
            return response()->api([], '차량 조회에 실패했습니다.', 400);
        }

        // 등급을 선택할 필요 없는 경우 조회 내역 캐시 저장
        if( empty($resultData['gradeList']) ) {
            $carInfoService = new CarInfoService();
            $carInfoService->storeUserQueryHistory(
                $request->input('owner'),
                $request->input('no'),
                $request->input('gradeId')
            );
        }

        return response()->api($resultData);
    }

    // 등급 선택 후 조회 내역 캐시 저장
    function storeGradeSelection( Request $request ) {
        try{
            $request->validate([
                'owner'     => 'required|string',
                'no'        => 'required|string',
                'gradeId'   => 'required|string',
            ]);
    
            $carInfoService = new CarInfoService();
            $carInfoService->storeUserQueryHistory(
                $request->input('owner'),
                $request->input('no'),
                $request->input('gradeId')
            );
    
            return response()->api([], '조회 내역 업데이트에 성공했습니다.');
        }catch(\Exception $e){
            return response()->api([], $e->getMessage(), 400);
        }
    }
} 