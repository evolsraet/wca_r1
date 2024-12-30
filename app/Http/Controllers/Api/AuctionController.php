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
            // 임시 데이터 리턴
            return [
                'owner' => $request->input('owner'),
                'no' => $request->input('no'),
                'model' => "임시데이터",
                'modelSub' => "LX",
                'grade' => "등급",
                'gradeSub' => "세부등급",
                'year' => "년식",
                'mission' => "미션",
                'fuel' => "연료",
                'priceNow' => "시세",
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
            'accidentCount' => 'required', // 사고건수
            'keyCount' => 'required', // 키건수
            'wheelScratch' => 'required', // 바퀴 손상여부
            'tireStatusNormal' => 'required', // 타이어 손상여부
            'tireStatusReplaced' => 'required', // 타이어 교체여부
            'tireStatusScratch' => 'required', // 타이어 손상여부
            'options' => 'required', // 옵션
        ]);

        $mileage = $request->input('mileage');
        $accident = $request->input('accident');
        $accidentCount = $request->input('accidentCount');
        $keyCount = $request->input('keyCount');
        $wheelScratch = $request->input('wheelScratch');
        $tireStatusNormal = $request->input('tireStatusNormal');
        $tireStatusReplaced = $request->input('tireStatusReplaced');
        $tireStatusScratch = $request->input('tireStatusScratch');
        $options = $request->input('options');

        $price = 0;

        Log::info('예상가 확인 호출', ['request' => $request->all()]);

        // 계산식 작성 start


        // 계산식 작성 end

        $result = '';

        return response()->api($result);

    }

}
