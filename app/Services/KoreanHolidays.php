<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class KoreanHolidays
{
    private $apiUrl = 'http://apis.data.go.kr/B090041/openapi/service/SpcdeInfoService';
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('KOR_HOLIDAY_API_KEY', null);
        if (!$this->apiKey) {
            throw new \Exception('KOR_HOLIDAY_API_KEY is not set');
        }
    }

    public function getHolidays($year, $month)
    {
        // year와 month를 정수로 변환
        $year = intval($year);
        $month = intval($month);

        // 유효한 년도와 월인지 확인
        if ($year < 1900 || $year > 2100 || $month < 1 || $month > 12) {
            throw new \InvalidArgumentException('유효하지 않은 년도 또는 월입니다.');
        }
        // 월을 2자리 문자열로 포맷팅
        $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);

        // 데이터베이스에서 해당 년월의 공휴일 확인
        $existingHolidays = Holiday::where('year', $year)
            ->where('month', $month)
            ->get();

        // 데이터베이스에 해당 년월의 공휴일이 있으면 반환
        if ($existingHolidays->isNotEmpty()) {
            return $existingHolidays;
        }

        // API에서 공휴일 정보 가져오기
        $response = Http::get("{$this->apiUrl}/getHoliDeInfo", [
            'ServiceKey' => $this->apiKey,
            'solYear' => $year,
            'solMonth' => $formattedMonth,
            'numOfRows' => 100,
            '_type' => 'json',
        ]);

        if ($response->successful()) {
            $data = $response->json();

            Log::info('KoreanHolidays API 연결 : API Response:', $data);  // 전체 응답을 dump로 출력

            // 응답 구조 확인 및 안전한 접근
            $totalCount = $data['response']['body']['totalCount'];
            if ($totalCount == 0) {
                $items = null;
            } else {
                $items = $data['response']['body']['items']['item'];
            }

            // dd('Items:', $items);  // items 내용을 dump로 출력

            $holidays = [];
            if ($items) {

                // 디비 테스트용으로 트랜젝션 후 롤백
                DB::beginTransaction();
                try {
                    foreach ($items as $item) {
                        if (isset($item['isHoliday']) && $item['isHoliday'] === 'Y') {
                            $holiday = Holiday::create([
                                'year' => substr($item['locdate'], 0, 4),
                                'month' => substr($item['locdate'], 4, 2),
                                'day' => substr($item['locdate'], 6, 2),
                                'name' => $item['dateName'],
                            ]);
                            $holidays[] = $holiday;
                        }
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
                DB::commit();
            }

            return $holidays;
        }

        // API 호출 실패 시 빈 배열 반환
        return [];
    }
}

// (new \App\Services\KoreanHolidays())->getHolidays(2024, 1);