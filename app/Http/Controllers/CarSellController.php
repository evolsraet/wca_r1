<?php

namespace App\Http\Controllers;

use App\Services\CarDataMappingService;
use Illuminate\Http\Request;
use App\Services\CarInfoService;
use App\Services\NiceDNRService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CarSellController extends Controller
{
    // 차량 정보 조회 결과 페이지
    public function showResult(Request $request)
    {
        try{
            $request->validate([
                'owner'     => 'required|string',
                'no'        => 'required|string',
                'gradeId'   => 'nullable|numeric',
            ]);
    
            // 파라미터 추출
            $owner      = $request->input('owner');
            $no         = $request->input('no');
            $gradeId    = $request->input('gradeId');

            // 변수 초기화
            $carInfo    = [];
            $extraInfo  = [];
            
            $niceDnrService = new NiceDNRService();
            $niceDnrData    = $niceDnrService->getNiceDnrData([
                'owner_name' => $owner,
                'car_no'     => $no,
            ]);
            if( empty($niceDnrData) ) {
                throw new \Exception('차량 정보를 찾을 수 없습니다.');
            }

            $getCarInfo = $niceDnrData->data;
            if (empty($getCarInfo['carSise']['info']['carinfo']) || (!empty($gradeId) && empty($getCarInfo['carSise']['info']['carinfo']['gradeList']))) {
                throw new \Exception('차량 정보를 찾을 수 없습니다.');
            }
            $gradeList = $getCarInfo['carSise']['info']['carinfo']['gradeList'] ?? [];

            // gradeList에 gradeId가 있는지 확인
            if (!empty($gradeId)) {
                $gradeIdExist = false;
                foreach ($gradeList as $grade) {
                    if (isset($grade['gradeId']) && intval($grade['gradeId']) === intval($gradeId)) {
                        $gradeIdExist = true;
                        break;
                    }
                }
                if (!$gradeIdExist) {
                    throw new \Exception('차량 등급을 찾을 수 없습니다.');
                }
            }
    
            // view용 차량 정보 매핑
            $carDataMappingService = new CarDataMappingService();

            $carInfo = $carDataMappingService->buildAuction($getCarInfo, $gradeId)[0];
            $carInfo['car_first_reg_date'] = Carbon::parse($carInfo['car_first_reg_date'])->format('Y-m-d');

            $extraInfo = [
                'use_history_yn'    => $getCarInfo['carParts']['outB0001']['list'][0]['resUseHistYn'] === 'Y' ? '사용' : '없음', // 용도변경 이력
                'engine_size'       => $getCarInfo['carSise']['info']['carinfo']['engineSize'].' cc',
                'tuning'            => count($getCarInfo['carParts']['outB0001']['list'][0]['resContentsList']),
                'car_model_sub'     => $getCarInfo['carSise']['info']['carinfo']['formNm'],
                'price_now_whole'   => $niceDnrData->carmerce_price ?? 0,
            ];

            return view('v2.pages.sell.carInfo', [
                'carInfo'   => $carInfo ?? [],
                'extraInfo' => $extraInfo ?? [],
            ]);
        } catch(\Exception $e) {
            return redirect()->route('sell')->with('redirectError', $e->getMessage());
        }
    }

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