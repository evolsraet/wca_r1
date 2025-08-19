<?php

namespace App\Http\Controllers\Web;

use App\Services\CarDataMappingService;
use Illuminate\Http\Request;
use App\Services\NiceDNRService;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class CarSellWebController extends Controller
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
} 