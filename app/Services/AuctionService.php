<?php

namespace App\Services;

use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BidResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\HttpResources\AuctionResource;
use App\Models\User;
use App\Jobs\AuctionCancelJob;
use App\Jobs\AuctionCohosenJob;
use App\Models\Bid;
use App\Jobs\AuctionIngJob;
use App\Jobs\AuctionDoneJob;
use App\Jobs\AuctionDiagJob;
use App\Jobs\AuctionBidStatusJob;
use App\Jobs\TaksongAddJob;
use App\Jobs\AuctionTotalDepositJob;
use App\Jobs\AuctionTotalAfterFeeJob;
use App\Models\Auction;
use App\Jobs\AuctionAfterFeeDonJob;
use App\Jobs\AuctionTotalDepositMissJob;
// use App\Models\TaksongStatusTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Notifications\AuctionsNotification;
use App\Notifications\Templates\NotificationTemplate;
use App\Services\ApiRequestService;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
class AuctionService
{
    use CrudTrait;

    public function __construct()
    {
        $this->defaultCrudTrait('auction');
    }

    // 요청 전 처리: 권한 검증
    protected function beforeProcess($method, $request, $id = null)
    {
        $this->addRequest('with', 'media');

        if ($method == 'store' && (!auth()->check() || !auth()->user()->hasRole('user'))) {
            throw new \Exception('권한이 없습니다.');
        }
    }

    public function middleProcess($method, $request, $auction, $id = null)
    {
        switch ($method) {
            case 'index':
            case 'show':
                $this->filterResultsBasedOnRole($auction);
                break;

            case 'store':
                $this->validateAndSetAuctionData($request, $auction);
                break;

            case 'update':
                Log::info('경매 상태 업데이트 모드??', ['method' => $auction, 'mode' => $request->mode]);

                $this->prepareMode($request, $auction);
                $this->modifyOnlyMe($auction, request()->mode == 'dealerInfo');

                if (request()->has('mode')) {
                    $this->handleMode($request->mode, $auction);
                }

                $this->postUpdateProcess($auction);
                break;

            case 'destroy':
                $this->modifyOnlyMe($auction);
                break;
        }
    }

    private function prepareMode($request, $auction)
    {
        if (!request()->has('mode') && $auction->isDirty('status')) {
            request()->merge(['mode' => $request->status]);
        }
    }

    private function handleMode($mode, $auction)
    {
        switch ($mode) {
            case 'dealerInfo':
                $this->processDealerInfo($auction);
                break;

            case 'reauction':
                $this->processReauction($auction);
                break;

            case 'diag':
                $this->processDiag($auction);
                break;

            case 'dlvr':
                $this->processDlvr($auction);
                break;

            case 'done':
                $this->processDone($auction);
                break;

            case 'cancel':
                $this->processCancel($auction);
                break;

            case 'chosen':
            case 'requested':
            case 'uploaded':
            case 'confirmed':
                Log::info("경매 상태 업데이트 {$mode} 모드", ['method' => $auction]);
                break;
        }
    }

    private function postUpdateProcess($auction)
    {
        $bids = Bid::find($auction->bid_id);

        if ($auction->status == 'cancel') {
            if (empty($auction->diag_check_at)) {
                throw new \Exception('진단이 완료되지 않았습니다.', 500);
            }
            $this->notifyCancel($auction);
        }

        if ($auction->status == 'dlvr') {

            if (empty($auction->is_taksong)) {
                throw new \Exception('탁송 여부가 선택되지 않았습니다.', 500);
            }

            $this->notifyDlvr($auction, $bids);
        }

        if ($auction->status == 'wait') {
            if (empty($auction->diag_check_at)) {
                throw new \Exception('진단이 완료되지 않았습니다.', 500);
            }

            AuctionBidStatusJob::dispatch($auction->user_id, 'wait', $auction->id, '', '');
        }

        if ($auction->status == 'done') {
            if (empty($auction->diag_check_at)) {
                throw new \Exception('진단이 완료되지 않았습니다.', 500);
            }

            if (empty($auction->is_taksong)) {
                throw new \Exception('탁송 여부가 선택되지 않았습니다.', 500);
            }


            if($auction->is_taksong == 'done'){
                $this->notifyDone($auction, $bids);
            }else{
                throw new \Exception('탁송 상태가 완료되지 않았습니다.', 500);
            }

        }

        if ($auction->status == 'diag') {
            AuctionDiagJob::dispatch($auction->user_id, $auction);
        }

        if ($auction->status == 'ing') {
            if (empty($auction->diag_check_at)) {
                throw new \Exception('진단이 완료되지 않았습니다.', 500);
            }
            $this->processIng($auction);
        }

        if ($auction->status == 'chosen') {
            if (empty($auction->diag_check_at)) {
                throw new \Exception('진단이 완료되지 않았습니다.', 500);
            }

            if (empty($auction->bid_id)) {
                throw new \Exception('입찰자가 없습니다.', 500);
            }

            $this->notifyChosen($auction, $bids);
        }
    }

    // ===== 각 세부 처리 함수들 =====

    private function processDealerInfo($auction)
    {
        $allowedFields = ['dest_addr_post', 'dest_addr1', 'dest_addr2'];
        $dirtyAttributes = $auction->getDirty();

        foreach ($dirtyAttributes as $key => $value) {
            if (!in_array($key, $allowedFields)) {
                $auction->$key = $auction->getOriginal($key);
            }
        }

        $data = [
            'id' => $auction->id,
            'car_no' => $auction->car_no,
            'car_model' => $auction->car_model,
            'mobile' => User::find($auction->user_id)->phone,
            'dest_mobile' => User::find($auction->bids[0]->user_id)->phone,
            'start_addr' => $auction->addr1 . ' ' . $auction->addr2,
            'dest_addr' => $auction->dest_addr1 . ' ' . $auction->dest_addr2,
            'user_id' => $auction->user_id,
            'bid_user_id' => $auction->bids[0]->user_id,
            'user_email' => User::find($auction->user_id)->email,
            'bid_user_email' => User::find($auction->bids[0]->user_id)->email,
            'taksong_wish_at' => $auction->taksong_wish_at,
        ];

        Log::info('[탁송 처리] 딜러정보 모드', ['method' => $data]);
        TaksongAddJob::dispatch($auction, $data);
    }

    private function processReauction($auction)
    {
        if (!$auction->is_reauction && $auction->status == 'wait') {
            $auction->status = 'ing';
            $auction->is_reauction = true;
            $auction->final_at = now()->addDays(config('days.reauction_day'));

            Log::info('재경매 모드', ['method' => $auction]);

            $bids = Bid::where('auction_id', $auction->id)->get();
            foreach ($bids as $bid) {
                AuctionBidStatusJob::dispatch($bid->user_id, 'reauction', $auction->id, $bid->user_id, '');
            }
            AuctionBidStatusJob::dispatch($auction->user_id, 'reauction', $auction->id, $bids->first()->user_id ?? null, '');
        } else {
            throw new \Exception('재경매변경 가능상태가 아닙니다.');
        }
    }

    private function processDiag($auction)
    {
        if (!$auction->is_reauction && $auction->status == 'ask') {
            $auction->status = 'diag';
            $auction->final_at = now()->addDays(config('days.auction_day'));

            Log::info('경매 상태 업데이트 진단대기중 모드', ['method' => $auction]);
            AuctionDiagJob::dispatch($auction->user_id, $auction);
        } else {
            throw new \Exception('진단변경 가능상태가 아닙니다.');
        }
    }

    private function processDlvr($auction)
    {
        if ($auction->status == 'chosen') {
            $auction->status = 'dlvr';
        } else {
            throw new \Exception('배송변경 가능상태가 아닙니다.');
        }
    }

    private function processDone($auction)
    {
        if (in_array($auction->status, ['dlvr', 'chosen'])) {
            $auction->status = 'done';
            Log::info('경매 상태 업데이트 경매완료 모드', ['method' => $auction]);
        } else {
            throw new \Exception('배송변경 가능상태가 아닙니다.');
        }
    }

    private function processCancel($auction)
    {
        if (!in_array($auction->status, ['done', 'dlvr'])) {
            $auction->status = 'cancel';
            Log::info('경매 상태 업데이트 취소 모드', ['method' => $auction]);
        } else {
            throw new \Exception('취소변경 가능상태가 아닙니다.');
        }
    }

    private function processIng($auction)
    {

        $auction->final_at = now()->addDays(config('days.auction_day'));

        if (!$auction->bid_id) {
            Log::info('경매 상태 업데이트 경매진행중 모드', ['method' => $auction]);
            AuctionIngJob::dispatch($auction->user_id, $auction->id, $auction->final_at);
        }
    }

    private function notifyCancel($auction)
    {
        AuctionCancelJob::dispatch($auction->user_id, $auction->id);

        $bids = Bid::where('auction_id', $auction->id)->get();
        foreach ($bids as $bid) {
            AuctionCancelJob::dispatch($bid->user_id, $auction->id);
        }
    }

    private function notifyDlvr($auction, $bids)
    {
        if ($auction->is_deposit == 'totalDeposit') {
            AuctionTotalDepositJob::dispatch($auction->user_id, $auction, 'user');
            AuctionTotalDepositJob::dispatch($bids->user_id, $auction, 'dealer');
        } else {
            if ($auction->bids) {
                AuctionCohosenJob::dispatch($auction->bids->first()->user_id, $auction->id, 'dealer');
            }
            AuctionCohosenJob::dispatch($auction->user_id, $auction->id, 'user');
        }
    }

    private function notifyDone($auction, $bids)
    {
        if ($auction->is_deposit == 'totalAfterFee') {
            AuctionTotalAfterFeeJob::dispatch($bids->user_id, $auction);
        } else {
            AuctionDoneJob::dispatch($auction->user_id, $auction->id, 'user');
            AuctionDoneJob::dispatch($bids->user_id, $auction->id, 'dealer');
        }
    }

    private function notifyChosen($auction, $bids)
    {
        if (!request()->mode) {
            AuctionCohosenJob::dispatch($bids->user_id, $auction->id, 'dealer');
        }
    }



    // 요청 후 처리: 조회수 증가
    protected function afterProcess($method, $request, $auction, $id = null)
    {
        if ($method == 'show') {
            $userId = auth()->id();
            $auctionId = $auction->id;
            $key = "auction_view_{$userId}_{$auctionId}";

            if (!Redis::get($key)) {
                Log::info("조회수 증가 user : {$userId} , auction : {$auctionId}");
                $auction->increment('hit');  // 조회수 증가
                Redis::setex($key, 86400, true);  // 24시간 동안 유효한 키 설정
            }
        }
    }

    // 역할에 따른 결과 필터링
    private function filterResultsBasedOnRole($auction)
    {
        if (auth()->user()->hasPermissionTo('act.admin')) {
            // 관리자는 모든 결과를 볼 수 있음
        } elseif (auth()->user()->hasRole('dealer')) {
            // 딜러는 진행 중인 경매와 자신의 입찰만 볼 수 있음
            $auction->where(function ($query) {
                $query->where('status', 'ing')
                    ->orWhereHas('bids', function ($subQuery) {
                        $subQuery->where('user_id', auth()->user()->id);
                    });
            });
        } else {
            // 일반 사용자는 자신의 경매만 볼 수 있음
            $auction->where('user_id', auth()->user()->id);
        }
    }

    // 경매 데이터 검증 및 설정
    private function validateAndSetAuctionData($request, $auction)
    {
        $requestData = (array) $request;
        $requestData['model'] = $auction;

        validator($requestData, [
            'owner_name' => 'required',
            'car_no' => 'required',
            'region' => 'required',
            'addr_post' => 'required',
            'addr1' => 'required',
            'addr2' => 'required',
        ])->validate();

        $auction->user_id = auth()->user()->id;
        $auction->status = 'ask';
        $auction->final_at = null;
    }

    // 카머스 시세확인 인증 API
    public function getCarmerceAuth()
    {
        $auth = config('carmerceApi.CARMERCE_API_AUTH');
        $password = config('carmerceApi.CARMERCE_API_PASSWORD');

        // 시세확인 인증 
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('carmerceApi.CARMERCE_AUTH_API_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "userId": "'.$auth.'",
                "password": "'.$password.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $result = curl_exec($curl);

        $result = json_decode($result, true);

        curl_close($curl);

        return $result;
    }   

    // 카머스 시세확인 API
    public function getCarmercePrice($accessToken, $currentData)
    {

        $today = date('Y-m-d');
        $nowYear = date('Y');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('carmerceApi.CARMERCE_PRICE_API_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "startDt" : "'.$currentData['firstRegistrationDate'].'",
                "endDt" : "'.$today.'",
                "carName" : "'.$currentData['classModelNm'].'",
                "startMakeYear" : "'.$currentData['year'].'",
                "endMakeYear" : "'.$nowYear.'",
                "startDriveKm" : "",
                "endDriveKm" : ""
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$accessToken
            ),
        ));

        $result = curl_exec($curl);

        $result = json_decode($result, true);

        Log::info('카머스 시세확인 API 호출 결과', ['result' => $result, 'currentData' => $currentData]);

        
        curl_close($curl);
        return $result;
    }


    private function calculateCheckKey($chkSec, $businessNumber) 
    {
        if (!is_numeric($businessNumber) || $businessNumber == 0) {
            return 0; // 또는 다른 기본값
        }
        return (($chkSec % $businessNumber) % 997);
    }


    public function calculateCarPrice(
        $currentYear,
        $currentMonth,
        $regYear,
        $regMonth,
        $currentMileage,
        $initialPrice,
        $isImported = false, // 수입차 여부
        $isVan = false        // 승합차 여부
    ) {
        // 1. 사용 개월 수 계산
        $monthsUsed = max(0, ($currentYear - $regYear) * 12 + ($currentMonth - $regMonth));
    
        // 2. 표준 주행거리 계산 (승합차: 2500, 승용차: 1250)
        $mileageStandardUnit = $isVan ? 2500 : 1250;
        $standardMileage = $monthsUsed * $mileageStandardUnit;
    
        // 3. 주행거리 차이 계산 (절댓값 처리)
        $mileageDifference = abs($standardMileage - $currentMileage);
    
        // 4. 연식 계산
        $yearsUsed = $currentYear - $regYear;
    
        // 5. 잔가율 테이블 설정
        $residualRateTable = $isImported
            ? [0=>0.85,1=>0.76,2=>0.67,3=>0.58,4=>0.49,5=>0.40,6=>0.31,7=>0.26,8=>0.21,9=>0.16]
            : [0=>0.95,1=>0.85,2=>0.77,3=>0.69,4=>0.61,5=>0.53,6=>0.45,7=>0.37,8=>0.29,9=>0.21];
    
        $defaultRate = 0.16;
        $residualRate = $residualRateTable[$yearsUsed] ?? $defaultRate;
        $prevResidualRate = $residualRateTable[$yearsUsed - 1] ?? $defaultRate;
    
        // 6. 주행거리 감가 계산 (잔가율 변화가 없으면 단순 방식, 있으면 복합 수식)
        // if (($residualRate === $prevResidualRate)) {
        //     $mileageAdjustment = ($prevResidualRate - $residualRate) * $initialPrice / 20 * ($mileageDifference / 1000) * $residualRate;            
        // } else {
        //     $mileageAdjustment = ($residualRate / 20) * ($mileageDifference / 1000) * $initialPrice;
        // }

        if($yearsUsed >= 11){
            $mileageAdjustment = ($residualRate / 20) * ($mileageDifference / 1000) * $initialPrice;
        } else {
            $mmdo = abs(number_format($residualRate - $prevResidualRate, 3, '.', ''));
            $mileageAdjustment = ($mmdo) * ($residualRate / 20) * ($mileageDifference / 1000) * $initialPrice;
        }


        $mileageAdjustment = abs($residualRate * $initialPrice + $mileageAdjustment);
    
        // 7. 기준 시세 계산
        $basePrice = $initialPrice * $residualRate;
    
        // 8. 최종 예상 가격 계산 (음수 방지)
        $estimatedPrice = max(0, $mileageAdjustment);
        $estimatedPriceInTenThousandWon = round($estimatedPrice / 10000, 1);
    

        if($yearsUsed >= 11){
            $mileageAdjustmentView = "(".$residualRate." / 20) * (".$mileageDifference." / 1000) * ".$initialPrice."=".$mileageAdjustment;
        } else {
            $mmdo = abs(number_format($residualRate - $prevResidualRate, 3, '.', ''));
            $mileageAdjustmentView = "(".$mmdo.") * (".$residualRate." / 20) * (".$mileageDifference." / 1000) * ".$initialPrice."=".$mileageAdjustment;
        }

        // 9. 계산 설명 정리
        $calculationSteps = [
            '사용개월 계산식' => "({$currentYear} - {$regYear}) * 12 + ({$currentMonth} - {$regMonth}) = {$monthsUsed}",
            '표준주행거리 계산식' => "{$monthsUsed} * {$mileageStandardUnit} = {$standardMileage}",
            '주행거리 차이 계산식' => "|{$standardMileage} - {$currentMileage}| = {$mileageDifference}",
            '연식 계산식' => "floor({$monthsUsed} / 12) = {$yearsUsed}",
            '잔가율' => $residualRate,
            '이전년도 잔가율' => $prevResidualRate,
            '잔가율 차이' => abs(number_format($residualRate - $prevResidualRate, 3, '.', '')),
            '예상가 계산식' => "{$mileageAdjustmentView}",
        ];
    
        // 10. 수식 설명 문자열
        $susic = ""
        . "[1] 사용개월 = (현재년도 - 등록년도) × 12 + (현재월 - 등록월)\n"
        . "[2] 표준주행거리 = 사용개월 × {$mileageStandardUnit}km (" . ($isVan ? '승합차' : '승용차') . ")\n"
        . "[3] 주행거리차이 = |표준주행거리 - 실제주행거리|\n"
        . "[4] 연식 = 사용개월 ÷ 12 (소수점 버림)\n"
        . "[5] 잔가율 = 연식 기준 테이블 참조 (" . ($isImported ? '수입차' : '국산차') . ")\n"
        . "[6] 예상가 = 기준시세 + 감가/가산 (최소 0 이상)\n"
        . "[7] 만원 단위 = round(예상가 ÷ 10,000, 1)";
    
        return [
            'susic' => $susic,
            'monthsUsed' => $monthsUsed,
            'standardMileage' => $standardMileage,
            'mileageDifference' => $mileageDifference,
            'residualRate' => $residualRate,
            'prevResidualRate' => $prevResidualRate,
            'basePrice' => round($basePrice),
            'mileageDepreciation' => round($mileageAdjustment),
            'estimatedPrice' => round($mileageAdjustment),
            'estimatedPriceInTenThousandWon' => $estimatedPriceInTenThousandWon,
            'calculationSteps' => $calculationSteps
        ];
    }

    // 경매 완료 수수료 처리 확인 
    public function auctionAfterFeeDone()
    {
        $auction = Auction::where('status', 'done')->where('is_deposit', 'totalDeposit')->get(); 
        foreach($auction as $bid){
            if(isset($bid->bid_id)){
                $bids = Bid::find($bid->bid_id);
                AuctionAfterFeeDonJob::dispatch($bids->user_id, $bid);
            }
        }
    }

    public function auctionTotalDepositMiss()
    {
        // 탁송 상태 확인 이 부분도 제거 필요 
        // $taksongStatusTemp = TaksongStatusTemp::where('chk_status', 'ask')->get();

        $auctions = Auction::where('status', 'dlvr')->where('is_taksong', 'ask')->get();

        if($auctions){

            foreach($auctions as $bid){
                if(isset($bid->auction_id)){

                    $auction = Auction::find($bid->auction_id);
                    $bids = Bid::find($auction->bid_id);
                    $user = User::find($bids->user_id);
                    
                    // 고정된 이벤트 시간
                    $eventTime = Carbon::parse($auction->taksong_wish_at);

                    // 이벤트 시간 2시간 전 계산
                    $twoHoursBefore = $eventTime->copy()->subHours(2);

                    // 현재 시간
                    $now = Carbon::now();

                    // 조건: 현재 시간이 2시간 전과 정확히 일치할 경우 알림 실행
                    if ($now->format('Y-m-d H:i') === $twoHoursBefore->format('Y-m-d H:i')) {
                        AuctionTotalDepositMissJob::dispatch($user, $auction);
                    }

                }
            }

        }

    }

    // 경매시간 만료시 선택대기로 변경
    public function auctionFinalAtUpdate()
    {
        $auctions = Auction::where('status', 'ing')
                        ->whereNotNull('final_at')
                        ->where('final_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                        ->get();

        Log::info('경매시간만료 확인', [$auctions]);

        foreach($auctions as $auction){
            // 현재시간 final_at 시간 비교해서 현재 시간이 더 클 경우 상태 변경 
            $auction->status = 'wait';
            $auction->choice_at = Carbon::now()->addDays(config('days.choice_day'));
            $auction->save();

            Log::info("경매시간 만료시 선택대기로 변경 {$auction->id}", [$auction]);

            // 알림 보내기
            AuctionBidStatusJob::dispatch($auction->user_id, 'wait', $auction->id, '','');

        }
    }


    // 경매만료시 선택대기 2일동안 아무 내용 없으면 자동으로 취소 처리 
    public function auctionCancel()
    {
        $auction = Auction::where('status', 'wait')->whereNull('bid_id')->get();
        foreach($auction as $auction){
            if($auction->choice_at){
                if(Carbon::now() > $auction->choice_at){
                    $auction->status = 'cancel';
                    $auction->save();   

                    // 알림 보내기
                    AuctionBidStatusJob::dispatch($auction->user_id, 'cancel', $auction->id, '','');
                }
            }
        }
    }


    // 나이스 API (본인인증 토큰생성)
    public function niceApiToken()
    {
        $clientId = config('services.niceAuth.NICE_CLIENT_ID');
        $clientSecret = config('services.niceAuth.NICE_CLIENT_SECRET');
        
        // Base64 인코딩
        $authorization = base64_encode($clientId . ':' . $clientSecret);

        // API 요청
        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Basic ' . $authorization,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->post('https://svc.niceapi.co.kr:22001/digital/niceid/oauth/oauth/token', [
                'grant_type' => 'client_credentials',
                'scope' => 'default',
            ]);

        // 오류 처리
        if ($response->failed()) {
            throw new \Exception('Failed to fetch access token: ' . $response->body());
        }

        // 토큰 반환
        return $response->json()['dataBody']['access_token'];
    }

}
