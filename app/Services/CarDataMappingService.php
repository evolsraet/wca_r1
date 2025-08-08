<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Helpers\Wca;

class CarDataMappingService
{
    public function buildCarMaker(array $apiData): array
    {
        $carInfo = $apiData['carSise']['info']['carinfo'];
        return [
            // API 필드 → DB 컬럼
            'id'        => $carInfo['makerId'],
            'name'      => $carInfo['makerNm'],
            'name_en'   => $carInfo['makerNmEng'] ?? null,
            'country'   => $carInfo['country'] ?? null,
            'import_yn' => $this->formatImportYn($carInfo['importYn']) ?? null,
        ];
    }

    public function buildCarModel(array $apiData): array
    {
        $carInfo = $apiData['carSise']['info']['carinfo'];
        return [
            'id'        => $carInfo['classModelId'],
            'name'      => $carInfo['classModelNm'],
            'maker_id'  => $carInfo['makerId'],
        ];
    }

    public function buildCarDetail(array $apiData): array
    {
        $carInfo = $apiData['carSise']['info']['carinfo'];
        return [
            'id'                => $carInfo['modelId'],
            'name'              => $carInfo['modelNm'],
            'model_id'          => $carInfo['classModelId'],
            'short_name'        => $carInfo['nameShort'] ?? null,
            'generation_name'   => $carInfo['nameGen'] ?? null,
            'start_date'        => $carInfo['modelStartDate'] ?? null,
            'end_date'          => $carInfo['modelEndDate'] ?? null,
            'image_url'         => $carInfo['imageUrl'] ?? null,
        ];
    }

    public function buildCarBp(array $apiData): array
    {
        $carInfo = $apiData['carSise']['info']['carinfo'];
        $gradeList      = $apiData['carSise']['info']['carinfo']['gradeList'] ?? [];
        $bps            = [];
        $uniqueBpIds    = []; // 중복 bpNo 추적

        foreach ($gradeList as $gradeInfo) {
            $bpId = $gradeInfo['bpNo'] ?? null;
            
            // 중복 bpNo 건너뛰기
            if ($bpId && !in_array($bpId, $uniqueBpIds)) {
                $uniqueBpIds[] = $bpId;
                $bps[] = [
                    'id'        => $bpId,
                    'name'      => $gradeInfo['bpNm'] ?? null,
                    'detail_id' => $carInfo['modelId'] ?? null,
                ];
            }
        }

        return $bps;
    }

    public function buildCarGrade(array $apiData): array
    {
        $gradeList  = $apiData['carSise']['info']['carinfo']['gradeList'] ?? [];
        $grades     = [];

        foreach ($gradeList as $gradeInfo) {
            $grades[] = [
                'id'                    => $gradeInfo['gradeId'],
                'name'                  => $gradeInfo['gradeNm'],
                'bp_id'                 => $gradeInfo['bpNo'],
                'type_name'             => $gradeInfo['nmTitle'] ?? null,
                'car_type_id'           => $gradeInfo['categoryId'] ?? null,
                'car_type_name'         => $gradeInfo['category'] ?? null,
                'shape_category_id'     => $gradeInfo['shapeCategoryId'] ?? null,
                'shape_category_name'   => $gradeInfo['shapeCategory'] ?? null,
                'purpose_id'            => $gradeInfo['plugId'] ?? null,
                'purpose_name'          => $gradeInfo['plug'] ?? null,
                'displacement'          => $gradeInfo['displace'] ?? null,
                'gearbox'               => $gradeInfo['gearbox'] ?? null,
                'gearbox_auto'          => $gradeInfo['gearboxAt'] ?? null,
                'gearbox_manual'        => $gradeInfo['gearboxMt'] ?? null,
                'fuel'                  => $gradeInfo['fuel'] ?? null,
                'seating_capacity'      => $gradeInfo['seatingCapacity'] ?? null,
                'grade_newcar_price'    => intval($gradeInfo['newcarPrice'] ?? 0),
                'start_date'            => $gradeInfo['gradeStartDate'] ?? null,
                'end_date'              => $gradeInfo['gradeEndDate'] ?? null,
                'created_at'            => now()->format('Y-m-d H:i:s'),
            ];
        }
  
        return $grades;
    }

    // 국산차 수입차 구분
    private function formatImportYn(string $yn) {
        // 숫자인 경우
        // 숫자가 1이면 n, 그 외 y
        // 문자열 영어인 경우 소문자로 치환
        if (is_numeric($yn)) {
            return intval($yn) === 1 ? 'n' : 'y';
        }
        return strtolower($yn);
    }
    
    // 차량 재원 데이터 존재 여부 확인 및 저장
    public function checkAndSave($modelClass, $data) {
        if (empty($data)) {
            return 0;
        }

        try {
            DB::beginTransaction();

            // 데이터 구조 정규화 - 항상 2차원 배열로 변환
            $normalizedData = Wca::normalizeDataStructure($data);

            // 배열의 모든 id를 1차원 배열로 만들어 일괄 조회
            $inputIds = array_column($normalizedData, 'id');
            $existingRecords = $modelClass::whereIn('id', $inputIds)->get();
            $existingIds = $existingRecords->pluck('id')->toArray();

            // 새 데이터만 필터링
            $newData = array_filter($normalizedData, function($item) use ($existingIds) {
                return !in_array($item['id'], $existingIds);
            });
            
            // 1000개 단위로 저장
            if (!empty($newData)) {
                $chunks = array_chunk(array_values($newData), 1000);
                foreach ($chunks as $chunk) {
                    $modelClass::insert($chunk);
                }
            }

            DB::commit();
            
            return count($newData);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    // Nice DNR API 응답 데이터를 auctions 테이블 형식으로 매핑
    public function buildAuction(array $niceData, ?int $gradeId = null): array
    {
        $carInfo        = $niceData['carSise']['info']['carinfo'];
        $carPartsList   = $niceData['carParts']['outB0001']['list'] ?? [];
        $gradeList      = $carInfo['gradeList'] ?? [];
        $auctionMappings = [];
        
        // gradeId 파라미터가 제공되면 해당 gradeId의 gradeList만 사용
        if ($gradeId !== null) {
            $filteredGradeList = [];
            foreach ($gradeList as $grade) {
                if (isset($grade['gradeId']) && intval($grade['gradeId']) === $gradeId) {
                    $filteredGradeList[] = $grade;
                }
            }
            $gradeList = $filteredGradeList;
        }

        // 기본 차량 정보 매핑 구조 (상단에 한 번만 정의)
        $baseMapping = [
            'car_maker_id'          => $carInfo['makerId'] ?? null,
            'car_maker'             => $carInfo['makerNm'] ?? null,
            'car_model_id'          => $carInfo['classModelId'] ?? null,
            'car_model'             => $carInfo['classModelNm'] ?? null,
            'car_detail_id'         => $carInfo['modelId'] ?? null,
            'car_model_sub'         => $carInfo['modelNm'] ?? null,
            'car_year'              => $carInfo['yearType'] ?? null,
            'car_mission'           => $carInfo['gearBox'] ?? null,
            'car_fuel'              => $carInfo['fuel'] ?? null,
            'car_thumbnail'         => $carInfo['classModelImg'] ?? null,
            'car_engine_type'       => $carInfo['engineType'] ?? null,
            // carPart 관련 필드들 기본값
            'car_first_reg_date'    => null,
            'car_km'                => null,
            'owner_name'            => null,
            'car_no'                => null,
        ];
        
        // carParts 데이터가 있는 경우 각 차량별로 매핑 처리
        foreach ($carPartsList as $carPart) {
            // 필요한 필드만 업데이트
            $currentMapping = $baseMapping;
            $currentMapping['car_first_reg_date']   = $carPart['resFirstDate'] ?? null;
            $currentMapping['car_km']               = $carPart['resValidDistance'] ?? null;
            $currentMapping['owner_name']           = $carPart['ownerNm'] ?? null;
            $currentMapping['car_no']               = $carPart['vhrNo'] ?? null;

            // gradeList가 있는 경우 각 등급별 데이터 추가
            if (!empty($gradeList)) {
                $processedGradeIds = []; // 중복 gradeId 방지

                foreach ($gradeList as $grade) {
                    $gradeId = $grade['gradeId'] ?? null;
                    
                    // 중복 gradeId 건너뛰기
                    if ($gradeId && !in_array($gradeId, $processedGradeIds)) {
                        $processedGradeIds[] = $gradeId;
                        
                        // 등급별 정보 추가
                        $gradeMapping = $currentMapping;
                        $gradeMapping['car_bp_id']      = $grade['bpNo'] ?? null;
                        $gradeMapping['car_grade']      = $grade['bpNm'] ?? null;
                        $gradeMapping['car_grade_id']   = $gradeId;
                        $gradeMapping['car_grade_sub']  = $grade['gradeNm'] ?? null;
                        
                        // 주행거리 기반 시세 적용
                        $currentKm = $currentMapping['car_km']; // 현재 주행거리
                        $priceList = $grade['trvlDstncPriceList'] ?? [];
                        
                        // findPriceByKmRange 실제 주행거리에 맞는 시세 산출
                        $gradeMapping['car_price_now'] = $this->findPriceByKmRange($currentKm, $priceList);
                        $auctionMappings[] = $gradeMapping;
                    }
                }
            } else {
                // gradeList가 없는 경우 기본 정보만 매핑
                $currentMapping['car_bp_id']       = null;
                $currentMapping['car_grade']       = null;
                $currentMapping['car_grade_id']    = null;
                $currentMapping['car_grade_sub']   = null;
                $currentMapping['car_price_now']   = null;
                $auctionMappings[] = $currentMapping;
            }
        }

        // carParts 데이터가 없는 경우 기본 차량 정보만 반환
        if (empty($carPartsList)) {
            $finalMapping = $baseMapping + [
                'car_bp_id'     => null,
                'car_grade'     => null,
                'car_grade_id'  => null,
                'car_grade_sub' => null,
                'car_price_now' => null,
            ];
            
            $auctionMappings[] = $finalMapping;
        }

        return $auctionMappings;
    }

    /* 주행거리 범위 산출 함수 (최적화됨)
        - trvlDstnc는 주행거리 범위를 의미한다.
        - trvlDstnc는 resValidDistance 값을 기준으로 산출할 수 있다.
        - trvlDstncPriceList는 trvlDstnc 범위에 따라 시세가 달라진다.
        - 정렬 없이 O(n) 성능으로 최적값을 직접 찾음
    */
    private function findPriceByKmRange($currentKm, array $trvlDstncPriceList): ?string
    {
        if (empty($trvlDstncPriceList)) {
            return null;
        }
        
        $currentKm = intval($currentKm);
        $bestPrice = null;
        $bestDistance = PHP_INT_MAX;
        
        // 현재 주행거리 이상인 구간 중 가장 작은 값 찾기
        foreach ($trvlDstncPriceList as $priceInfo) {
            $rangeKm = intval($priceInfo['trvlDstnc'] ?? 0);
            if ($rangeKm >= $currentKm && $rangeKm < $bestDistance) {
                $bestDistance = $rangeKm;
                $bestPrice = $priceInfo['trvlDstncPrice'] ?? null;
            }
        }
        
        // 매치되는 구간이 없으면 최대 범위 값 사용
        if ($bestPrice === null) {
            $maxDistance = 0;
            foreach ($trvlDstncPriceList as $priceInfo) {
                $rangeKm = intval($priceInfo['trvlDstnc'] ?? 0);
                if ($rangeKm > $maxDistance) {
                    $maxDistance = $rangeKm;
                    $bestPrice = $priceInfo['trvlDstncPrice'] ?? null;
                }
            }
        }
        
        return $bestPrice;
    }
}