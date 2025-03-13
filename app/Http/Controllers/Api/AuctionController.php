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
use App\Services\MediaService;
use Maatwebsite\Excel\Facades\Excel;
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

        AuctionStartJob::dispatch($result, $user, $request->auction, true); // 고객
        AuctionStartJob::dispatch($result, $super, $request->auction); // 운영사
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

        // 경매중인 차량번호 중에 같은 차량번호 있는지 확인
        if($request->input('owner') && $request->input('no') && $request->input('mode') == 'carInfo'){
            $auction = Auction::where('car_no', $request->input('no'))->where('status', 'ing')->first();
            if($auction){
                $data = [
                    'status' => 'is_not',
                    'message' => '경매중인 차량번호입니다.',
                ];
                return response()->api($data, '경매중인 차량번호입니다.', 400);
            }
        }

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
                // 'owner' => $request->input('owner'),
                // 'no' => $request->input('no'),
                // 'model' => $niceDnrResult['carSize']['info']['modelNm'],
                // 'modelSub' => "LX",
                // 'grade' => "등급",
                // 'gradeSub' => "세부등급",
                // 'year' => "2019",
                // 'firstRegDate' => "2018-11-01",
                // 'mission' => "미션",
                // 'fuel' => "연료",
                // 'priceNow' => "시세", // 소매 시세가 (나이스DNR 시세확인 API
                // 'priceNowWhole' => $this->getCarmerceResult(), // 도매 시세가 (카머스 시세확인 API)
                'owner' => $request->input('owner'),
                'no' => $request->input('no'),
                'maker' => $niceDnrResult['carSize']['info']['makerNm'],
                'model' => $niceDnrResult['carSize']['info']['modelNm'],
                'modelSub' => $niceDnrResult['carSize']['info']['subGrade'],
                'grade' => $niceDnrResult['carSize']['info']['grade'],
                'gradeSub' => $niceDnrResult['carSize']['info']['subGrade'],
                'year' => $niceDnrResult['carSize']['info']['year'],
                'firstRegDate' => $niceDnrResult['carSize']['info']['firstRegistrationDate'],
                'mission' => $niceDnrResult['carSize']['info']['transmission'],
                'fuel' => $niceDnrResult['carSize']['info']['fuelType'],
                'priceNow' => $niceDnrResult['carSize']['info']['currentPrice'], // 소매 시세가 (나이스DNR 시세확인 API
                'priceNowWhole' => $this->getCarmerceResult($niceDnrResult['carSize']['info']), // 도매 시세가 (카머스 시세확인 API)
                'thumbnail' => $niceDnrResult['carSize']['info']['thumbnail'],
                'km' => $niceDnrResult['carSize']['info']['km'],
            ];
        });


        return response()->api($resource, $message);
    }

    // 예상가 확인 API
    public function CheckExpectedPrice(Request $request)
    {
        $auctionService = new AuctionService();

        $request->validate([
            'mileage' => 'required', // 주행거리
            'accident' => 'required', // 사고여부
            'keyCount' => 'required', // 키건수
            'wheelScratch' => 'required', // 바퀴 손상여부
            // 'tireStatusNormal' => 'required', // 타이어 손상여부
            'tireStatusReplaced' => 'required', // 타이어 교체여부
            'currentPrice' => 'required' // 소매 시세가
        ]);

        $mileage = $request->input('mileage');
        $accident = $request->input('accident');
        $keyCount = $request->input('keyCount');
        $wheelScratch = $request->input('wheelScratch');
        // $tireStatusNormal = $request->input('tireStatusNormal');
        $tireStatusReplaced = $request->input('tireStatusReplaced');
        $firstRegDate = $request->input('firstRegDate');
        $currentPrice = $request->input('currentPrice');
        // 계산식 작성 start

        // Log::info('예상가 확인 호출', ['request' => $request->all()]);

        $nowYear = Carbon::now()->format('Y'); // 현재년도
        $nowMonth = Carbon::now()->format('m'); // 현재 월
        $firstRegDate = Carbon::parse($firstRegDate);
        $firstRegYear = $firstRegDate->format('Y'); // 최초등록년도
        $firstRegMonth = $firstRegDate->format('m'); // 최초등록월

        // $nowYear = 2025;
        // $nowMonth = 1;
        // $firstRegYear = 2019;
        // $firstRegMonth = 6;
        // $mileage = 80000;

        Log::info('예상가 확인 호출', ['현재년도' => $nowYear, '현재 월' => $nowMonth, '최초등록년도' => $firstRegYear, '최초등록월' => $firstRegMonth, 'mileage' => $mileage]);

        $initialPrice = $currentPrice; // 차량 초기 가격 3천만 원

        $result = $auctionService->calculateCarPrice($nowYear, $nowMonth, $firstRegYear, $firstRegMonth, $mileage, $initialPrice);
        
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
        // if($tireStatusNormal > 0){
        //     $resultPrice -= 150000;
        // }

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
        

        $resultc['사용 월수'] = $result['monthsUsed'] . "개월\n";
        $resultc['표준 주행거리'] = number_format($result['standardMileage']) . "km\n";
        $resultc['주행거리 차이'] = number_format($result['mileageDifference']) . "km\n";
        $resultc['잔가율'] = $result['residualRate'] * 100 . "%\n";
        $resultc['기본 감가 가격 (원 단위)'] = number_format($result['basePrice']) . "원\n";
        $resultc['주행거리 감가 금액 (원 단위)'] = number_format($result['mileageDepreciation']) . "원\n";
        $resultc['최종 예상 가격 (원 단위)'] = number_format($result['estimatedPrice']) . "원\n";
        $resultc['최종 예상 가격 (만원 단위)'] = number_format($result['estimatedPriceInTenThousandWon']) . "만원\n";


        Log::info('예상가 확인 결과', ['result' => $resultc]);

        // 계산식 작성 end
        $result['estimatedPrice'] = round($resultPrice / 10000);

        return response()->api($result);

    }

    // 카머스 시세확인 API
    public function getCarmerceResult( $currentData )
    {

        $auctionService = new AuctionService();
        $auth = $auctionService->getCarmerceAuth(); // 인증 

        $refreshToken = $auth['refreshToken']; // 리프레시 토큰
        $accessToken = $auth['accessToken']; // 액세스 토큰 

        $priceResult = $auctionService->getCarmercePrice($accessToken, $currentData); // 시세확인 

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

    public function AllIngCount(Request $request)
    {

        $result = Auction::where('status', 'ing')->count();
        if(!$result){
            $result = 0;
        }

        return response()->api($result);
    }

    public function uploadFile(Request $request, Auction $auction)
    {
        if ($request->hasFile('file_auction_owner')) {
            $file = $request->file('file_auction_owner');
            $mediaService = new MediaService();
            $media = $mediaService->uploadFile($file, $auction);

            return response()->json(['success' => true, 'media' => $media]);
        } else {
            return response()->json(['success' => false, 'message' => '파일이 전송되지 않았습니다.'], 400);
        }
    }

    public function showByUniqueNumber($unique_number)
    {
        $auction = Auction::where('unique_number', $unique_number)->first();

        if (!$auction) {
            return response()->json(['message' => '경매를 찾을 수 없습니다.'], 404);
        }

        return response()->json($auction);
    }

    public function entryPublic(Request $request)
    {
        $file = $request->file('file');
    
        // 파일이 들어왔는지 확인
        if (!$file) {
            return response()->json(['message' => '파일이 전송되지 않았습니다.'], 400);
        }
    
        // 엑셀 파일에서 데이터 읽기
        try {
            $data = Excel::toArray(new Auction, $file);

            // 첫 번째 시트만 선택
            $firstSheet = $data[0] ?? [];

            // 첫 번째 라인 제거
            if (!empty($firstSheet)) {
                array_shift($firstSheet); // 첫 번째 행 제거
            }

            // JSON 데이터로 변환
            $jsonData = array_map(function($row) {

                $auctionService = new AuctionService();
                $niceDnrResult = $auctionService->getNiceDnr($row[2], $row[1]);
            
                if ($row[0]) { // car_no가 있고, part가 "공매"인 경우에만 변환
                    return [
                        'part' => $row[0] ?? null,
                        'car_no' => $row[1] ?? null,
                        'orner' => $row[2] ?? null,
                        'hope_date' => $row[3] ?? null,
                        'addr_code' => $row[4] ?? null,
                        'addr1' => $row[5] ?? null,
                        'addr2' => $row[6] ?? null,
                        'hope_price' => $row[7] ?? null,
                        'tel' => $row[8] ?? null,
                        'maker' => $niceDnrResult['carSize']['info']['makerNm'],
                        'model' => $niceDnrResult['carSize']['info']['modelNm'],
                        'modelSub' => $niceDnrResult['carSize']['info']['subGrade'],
                        'grade' => $niceDnrResult['carSize']['info']['grade'],
                        'gradeSub' => $niceDnrResult['carSize']['info']['subGrade'],
                        'year' => $niceDnrResult['carSize']['info']['year'],
                        'firstRegDate' => $niceDnrResult['carSize']['info']['firstRegistrationDate'],
                        'mission' => $niceDnrResult['carSize']['info']['transmission'],
                        'fuel' => $niceDnrResult['carSize']['info']['fuelType'],
                        'priceNow' => $niceDnrResult['carSize']['info']['currentPrice'], // 소매 시세가 (나이스DNR 시세확인 API
                        'priceNowWhole' => $this->getCarmerceResult($niceDnrResult['carSize']['info']), // 도매 시세가 (카머스 시세확인 API)
                        'thumbnail' => $niceDnrResult['carSize']['info']['thumbnail'],
                        'km' => $niceDnrResult['carSize']['info']['km'],
                    ];
                }

                return null;

            }, $firstSheet);

            // $jsonData = array_filter($jsonData, function($item) {
            //     // 데이터가 없는 경우 배열 제거
            //     if (empty($item)) {
            //         return false;
            //     }
            
            //     // $item['part']가 "공매"인 경우만 유지
            //     if ($item['part'] !== "공매") {
            //         return false;
            //     }
            
            //     return true;
            // });

            // 데이터 확인
            if (empty($jsonData)) {
                return response()->api([], '엑셀 파일에 데이터가 없습니다.', 400);
            }

            // JSON 데이터 출력 (디버깅용)
            // dd($jsonData);
            
            return response()->api($jsonData);
     
        } catch (\Exception $e) {
            return response()->api([], '엑셀 파일을 처리하는 중 오류가 발생했습니다.', 500);
        }


    }

}
