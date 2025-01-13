<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuctionService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Auction;
use App\Jobs\AuctionStartJob;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class AuctionController extends Controller
{
    use CrudControllerTrait;

    public function __construct(AuctionService $service)
    {
        $this->service = $service;
    }
    /**
     * @lrd:start
     * # 가능 파일
     * #    'file_auction_proxy'  => '위임장/소유자 인감증명서',
     * #    'file_auction_owner'  => '매도자관련서류', (관리자가 저장 후 낙찰 딜러에게 표시된다)
     * @lrd:end
     */
    public function store(Request $request)
    {
        $super = User::find(2); // 운영사
        $user = $request->user(); // 고객
        $result = $this->service->store($request);

        Log::info('경매 등록 결과', ['result' => $result]);

        AuctionStartJob::dispatch($result, $user, $request->auction);
        AuctionStartJob::dispatch($result, $super, $request->auction);
        return $result;
    }


    public function swapNumber($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b)) {
            return response()->api([], '입력값은 숫자여야 합니다.', 400);
        }

        $a = $a + $b;
        $b = $a - $b;
        $a = $a - $b;

        return response()->api([$a, $b]);
    }

    /**
     * @lrd:start
     * # 최초 차량정보가져오기
     * 비회원 가능
     * @lrd:end
     * @LRDparam owner required
     * @LRDparam no required
     * @LRDparam forceRefresh optional
     */
    public function carInfo(Request $request)
    {
        $request->validate([
            'owner' => 'required',
            'no' => 'required',
        ]);

        $cacheKey = "carInfo." . $request->input('owner') . $request->input('no');
        $message = null;

        // 캐시 삭제 조건
        // 강제동기 && 캐시있을때 && 같은 차번 하루1회만
        if ($request->input('forceRefresh') && Cache::has($cacheKey) && !Cache::has($cacheKey . "forceRefresh")) {
            Cache::forget($cacheKey);
            Cache::put($cacheKey . "forceRefresh", 'true', now()->endOfDay());
        } elseif ($request->input('forceRefresh') && Cache::has($cacheKey . "forceRefresh")) {
            $message = '갱신은 하루 1회만 가능합니다.';
        }

        // 캐시 없을 경우, 한달동안 저장
        $resource = Cache::remember($cacheKey, now()->addDays(30), function () use ($request) {

            $auctionService = new AuctionService();
            $niceDnrResult = $auctionService->getNiceDnr($request->input('owner'), $request->input('no'));

            // 임시 데이터 리턴
            return [
                'owner' => $request->input('owner'),
                'no' => $request->input('no'),
                'model' => $niceDnrResult['carSize']['info']['modelNm'],
                'modelSub' => "LX",
                'grade' => "등급",
                'gradeSub' => "세부등급",
                'year' => "2023",
                'firstRegDate' => "2022-11-01",
                'mission' => "미션",
                'fuel' => "연료",
                'priceNow' => "시세", // 소매 시세가 (나이스DNR 시세확인 API
                'priceNowWhole' => $this->getCarmerceResult(), // 도매 시세가 (카머스 시세확인 API)
            ];
        });


        return response()->api($resource, $message);
    }

    // 예상가 확인 API
    public function CheckExpectedPrice(Request $request)
    {
        $request->validate([
            'mileage' => 'required', // 주행거리
            'accident' => 'required', // 사고여부
            'keyCount' => 'required', // 키건수
            'wheelScratch' => 'required', // 바퀴 손상여부
            'tireStatusNormal' => 'required', // 타이어 손상여부
            'tireStatusReplaced' => 'required' // 타이어 교체여부
        ]);

        $mileage = $request->input('mileage');
        $accident = $request->input('accident');
        $keyCount = $request->input('keyCount');
        $wheelScratch = $request->input('wheelScratch');
        $tireStatusNormal = $request->input('tireStatusNormal');
        $tireStatusReplaced = $request->input('tireStatusReplaced');
        $firstRegDate = $request->input('firstRegDate');
        
        // 계산식 작성 start

        // Log::info('예상가 확인 호출', ['request' => $request->all()]);

        $nowYear = Carbon::now()->format('Y'); // 현재년도
        $nowMonth = Carbon::now()->format('m'); // 현재 월
        $firstRegDate = Carbon::parse($firstRegDate);
        $firstRegYear = $firstRegDate->format('Y'); // 최초등록년도
        $firstRegMonth = $firstRegDate->format('m'); // 최초등록월

        $initialPrice = 30000000; // 차량 초기 가격 3천만 원

        $result = $this->calculateCarPrice($nowYear, $nowMonth, $firstRegYear, $firstRegMonth, $mileage, $initialPrice);

        $resultPrice = $result['estimatedPrice'];

        // 사고이력 
        switch($accident){
            case '교환':
                $resultPrice -= 500000;
                break;
            case '판금 사고':
                $resultPrice -= 150000;
                break;
            case '전손이력':
                $resultPrice *= 0.9;
                break;
        }

        // 키갯수
        if($keyCount > 2){
            $resultPrice -= 300000;
        }

        // 휠스크래치   
        if($wheelScratch > 0){
            $resultPrice -= 100000;
        }

        // 타이어 
        if($tireStatusNormal > 0){
            $resultPrice -= 150000;
        }

        // 타이어 교체 
        if($tireStatusReplaced > 0){
            $resultPrice -= 150000;
        }
        
        /***
        # 표준주행거리 계산 ( 25(현재년도) - 이차량의 최초등록일 연도  ) 
        - 차량 등록일 연도 - 현재월 

        # 년도((현재년도) - 이차량의 최초등록일 연도) * 12 + (차량 등록일 연도 - 현재월 ) * 1.25 * 1000 // 표준주행거리 
        # 표준주행거리 - 현재주행거리 

        # 감가 계산 

        # 잔가율 
        - 1~년 0.518 
        - 4~년 0.417 
        - 5~년 0.368 
        - 6~년 0.311
        - 7~년 0.262 

        # 주행거리 계산식
        (표준주행거리 - 현재주행거리) * 잔가율 

        # 판단가 20만원 
        
        # 감가로직 * 

        # 판단감가 1판당 20만원 

        // 전손이력 (10% 감가 )
        // 교환(20),판금(), 사고 ()

        수리이력 / 교환 (50만원)
        수리이력 / 판금 (15만원)

        // 선루프, 어라운드 뷰, 크루즈 컨트롤 (30만원)
        // 사고발생 건수 제외 

        #  이 감가 기준은 일반적인 감가 기준이며, 실제 평가 금액과는 차이가 있을 수 있습니다. 

        사고 - > 과거 사고이력 
        교환 -> 외판손상 (15)

        옵션부분 제거 

        // 주행거리 계산 
        // 사고 -> 과거 사고이력
        수리이력 / 교환 (50만원)
        수리이력 / 판금 (15만원)

        전손이력 -> 감가 20% 
        - 침수 제거 

        사고 발생 건수 부분 지우고, "수리 필요" 추가 

        키갯수 30만원 (두개 기준)
        휠스크래치 10만원
        
        타이어
        정상 > 15만원 (상태불량일 경우)
        교환 > 15만원

        외판 스크레치 제거 
        
        **/
        

        // $resultc['사용 월수'] = $result['monthsUsed'] . "개월\n";
        // $resultc['표준 주행거리'] = number_format($result['standardMileage']) . "km\n";
        // $resultc['주행거리 차이'] = number_format($result['mileageDifference']) . "km\n";
        // $resultc['잔가율'] = $result['residualRate'] * 100 . "%\n";
        // $resultc['기본 감가 가격 (원 단위)'] = number_format($result['basePrice']) . "원\n";
        // $resultc['주행거리 감가 금액 (원 단위)'] = number_format($result['mileageDepreciation']) . "원\n";
        // $resultc['최종 예상 가격 (원 단위)'] = number_format($result['estimatedPrice']) . "원\n";
        // $resultc['최종 예상 가격 (만원 단위)'] = number_format($result['estimatedPriceInTenThousandWon']) . "만원\n";


        // Log::info('예상가 확인 결과', ['result' => $resultc]);

        // 계산식 작성 end

        $result['estimatedPrice'] = $resultPrice / 10000;

        return response()->api($result);

    }

    private function calculateCarPrice($currentYear, $currentMonth, $regYear, $regMonth, $currentMileage, $initialPrice, $mileageStandard = 1250) {
        // 1. 사용 월수 계산
        $monthsUsed = ($currentYear - $regYear) * 12 + ($currentMonth - $regMonth);
    
        // 2. 표준 주행거리 계산 (월별 주행거리 기준)
        $standardMileage = $monthsUsed * $mileageStandard;
    
        // 3. 주행거리 차이 계산
        $mileageDifference = $standardMileage - $currentMileage;
    
        // 주행거리 차이가 음수일 경우 감가를 초과로 처리
        $mileageDifferenceEffect = $mileageDifference > 0 ? 1 : -1;
    
        // 4. 잔가율 결정
        $yearsUsed = floor($monthsUsed / 12);
        $residualRate = 0;
        if ($yearsUsed <= 1) {
            $residualRate = 0.518;
        } elseif ($yearsUsed <= 4) {
            $residualRate = 0.417;
        } elseif ($yearsUsed == 5) {
            $residualRate = 0.368;
        } elseif ($yearsUsed == 6) {
            $residualRate = 0.311;
        } elseif ($yearsUsed >= 7) {
            $residualRate = 0.262;
        }
    
        // 5. 기본 감가 가격 계산
        $basePrice = $initialPrice * $residualRate;
    
        // 6. 주행 거리 감가 계산 (1km당 200원 가정)
        $depreciationPerKm = 200;
        $mileageDepreciation = abs($mileageDifference) * $depreciationPerKm * $mileageDifferenceEffect;
    
        // 7. 최종 예상 가격 계산
        $estimatedPrice = $basePrice + $mileageDepreciation;

        $estimatedPriceInTenThousandWon = $estimatedPrice / 10000;
    
        // 결과 반환
        return [
            'monthsUsed' => $monthsUsed,
            'standardMileage' => $standardMileage,
            'mileageDifference' => $mileageDifference,
            'residualRate' => $residualRate,
            'basePrice' => $basePrice,
            'mileageDepreciation' => $mileageDepreciation,
            'estimatedPrice' => $estimatedPrice,
            'estimatedPriceInTenThousandWon' => $estimatedPriceInTenThousandWon
        ];
    }

    // 카머스 시세확인 API
    public function getCarmerceResult()
    {

        $auctionService = new AuctionService();
        $auth = $auctionService->getCarmerceAuth(); // 인증 

        $refreshToken = $auth['refreshToken']; // 리프레시 토큰
        $accessToken = $auth['accessToken']; // 액세스 토큰 

        $priceResult = $auctionService->getCarmercePrice($accessToken); // 시세확인 

        $result = $priceResult['data'];

        // bidAmt 값의 평균 계산
        $bidAmts = array_column($result, 'bidAmt');
        $averageBidAmt = count($bidAmts) > 0 ? array_sum($bidAmts) / count($bidAmts) : 0;
        $roundedPrice = round($averageBidAmt);

        // 차량 시세 계산 레인지 두기 (수정필요)
        $minPrice = $roundedPrice - 1000000;
        $maxPrice = $roundedPrice + 1000000;

        return $roundedPrice;
    }

}
