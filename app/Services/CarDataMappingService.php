<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

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
            $normalizedData = $this->normalizeDataStructure($data);

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

    // 데이터 구조 정규화 - 1차원/2차원 혼합 배열을 2차원 배열로 통일 (차량 재원 등록 시 사용)
    private function normalizeDataStructure(array $data): array 
    {
        // 빈 배열 체크
        if (empty($data)) {
            return [];
        }

        // 이미 2차원 배열인지 확인 (첫 번째 요소가 배열인지 체크)
        $firstElement = reset($data);

        if (is_array($firstElement)) {
            // 이미 2차원 배열 - 그대로 반환
            return $data;
        } else {
            // 1차원 연관배열 - 2차원 배열로 래핑
            return [$data];
        }
    }

    # TODO: Nice API <-> auction 테이블 매핑 함수 추가 (jdh)
}