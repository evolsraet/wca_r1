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
use App\Models\TaksongStatusTemp;
use Carbon\Carbon;
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

    // 요청 중간 처리: 결과 필터링 및 데이터 검증
    protected function middleProcess($method, $request, $auction, $id = null)
    {
        // Log::info('경매 상태 업데이트 모드?', ['method' => $method]);

        // $user = User::find($auction->user_id);

        switch ($method) {
            case 'index':
            case 'show':
                $this->filterResultsBasedOnRole($auction);
                break;
            case 'store':
                // 본인인증 검증 추가
                $this->validateAndSetAuctionData($request, $auction);
                break;
            case 'update':

                Log::info('경매 상태 업데이트 모드??', ['method' => $auction, 'mode' => $request->mode]);

                // 상태변경
                // request()->mode 가 있을 경우 그대로 두고, 없으면서 $acution->status 가 변경됬을 경우 그 request()->mode 에 $acution->status 대입
                if (!request()->has('mode') && $auction->isDirty('status')) {
                    request()->merge(['mode' => $request->status]);
                }

                $this->modifyOnlyMe($auction, request()->mode == 'dealerInfo');

                // TODO: 딜러정보가 탁송 입력되고, 고객 탁송필요 정보가 모두 입력되면 dlvr 로 변경해야한다

                // 모드별 분기
                if (request()->has('mode')) {
                    switch (request()->mode) {
                        case 'dealerInfo':
                            // 허용된 필드 목록
                            $allowedFields = ['dest_addr_post', 'dest_addr1', 'dest_addr2'];

                            // 변경된 속성만 가져오기
                            $dirtyAttributes = $auction->getDirty();

                            // 허용되지 않은 필드만 원래 값으로 되돌림
                            foreach ($dirtyAttributes as $key => $value) {
                                if (!in_array($key, $allowedFields)) {
                                    $auction->$key = $auction->getOriginal($key);
                                }
                            }

                            // Log::info('딜러정보 모드', ['method' => $auction]);
                            $data = [];
                            $data['id'] = $auction->id;
                            $data['carNo'] = $auction->car_no; // 차량번호  
                            $data['carModel'] = '개발모델'; // 차량모델
                            $data['mobile'] = User::find($auction->user_id)->phone; // 출발지 전화번호
                            $data['destMobile'] = User::find($auction->bids[0]->user_id)->phone; // 출발지 전화번호
                            $data['startAddr'] = $auction->addr1 . ' ' . $auction->addr2; // 주소
                            $data['destAddr'] = $auction->dest_addr1 . ' ' . $auction->dest_addr2; // 주소
                            $data['userId'] = $auction->user_id; // 사용자 아이디
                            $data['bidUserId'] = $auction->bids[0]->user_id; // 입찰자 아이디
                            $data['userEmail'] = User::find($auction->user_id)->email; // 사용자 이메일
                            $data['bidUserEmail'] = User::find($auction->bids[0]->user_id)->email; // 입찰자 이메일
                            $data['taksongWishAt'] = $auction->taksong_wish_at; // 탁송 날짜

                            // 탁송 처리
                            TaksongAddJob::dispatch($auction, $data);

                            break;
                        case 'reauction':
                            // 재경매 : 옥션변수가 오고, 재옥션 상태가 아니고, auction->status 가 wait 일 경우,  상태변경
                            if (!$auction->is_reauction && $auction->status == 'wait') {
                                $auction->status = 'ing';
                                $auction->is_reauction = true;
                                $auction->final_at = now()->addDays(env('REAUCTION_DAY'));

                                Log::info('재경매 모드', ['method' => $auction]);

                                $bids = Bid::where('auction_id', $auction->id)->get();
                                foreach($bids as $bid){
                                    Log::info('재경매 모드 입찰자 알림', ['mode' => 'reauction','method' => $bid->user_id]);
                                    AuctionBidStatusJob::dispatch($bid->user_id, 'reauction', $auction->id, $bid->user_id,'');
                                }
                                AuctionBidStatusJob::dispatch($auction->user_id, 'reauction', $auction->id, $bid->user_id,'');



                            } else {
                                throw new \Exception('재경매변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'diag':
                            // 진단으로 변경
                            if (!$auction->is_reauction && $auction->status == 'ask') {
                                $auction->status = 'diag';
                                $auction->final_at = now()->addDays(env('AUCTION_DAY'));


                                Log::info('경매 상태 업데이트 진단대기중 모드', ['method' => $auction]);

                                AuctionDiagJob::dispatch($auction->user_id, $auction);

                            } else {
                                throw new \Exception('진단변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'dlvr':
                            // 배송으로 변경
                            if ($auction->status == 'chosen') {
                                $auction->status = 'dlvr';
                            } else {
                                throw new \Exception('배송변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'done':
                            // 완료으로 변경
                            if (in_array($auction->status, ['dlvr', 'chosen'])) {
                                $auction->status = 'done';


                                Log::info('경매 상태 업데이트 경매완료 모드', ['method' => $auction]);

                                $bids = Bid::find($auction->bid_id);

                                AuctionDoneJob::dispatch($auction->user_id, $auction->id, 'user');
                                AuctionDoneJob::dispatch($bids->user_id, $auction->id, 'dealer');


                            } else {
                                throw new \Exception('배송변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'cancel':
                            // 취소으로 변경
                            if (!in_array($auction->status, ['done', 'dlvr'])) {
                                $auction->status = 'cancel';


                                Log::info('경매 상태 업데이트 취소 모드', ['method' => $auction]);
                                AuctionCancelJob::dispatch($auction->user_id, $auction->id);

                                $bids = Bid::where('id', $auction->id)->get();
                                foreach($bids as $bid){
                                    // Log::info('경매 취소 모드 입찰자 알림', ['method' => $bid->user_id]);
                                    AuctionCancelJob::dispatch($bid->user_id, $auction->id);
                                }

                            } else {
                                throw new \Exception('취소변경 가능상태가 아닙니다.');
                            }
                            break; 

                        case 'chosen':
                            // $auction->status = 'chosen';

                            Log::info('유저가 경매를 선택 했을때1 ', ['method' => $auction]);

                            break;

                        // case 'ing':
                        //     $auction->final_at = now()->addDays(env('AUCTION_DAY'));
                            
                        //     break;
                    }
                }

                $bids = Bid::find($auction->bid_id);

                // 취소시 알림
                if($auction->status == 'cancel'){

                    Log::info('경매 상태 업데이트 취소 모드', ['method' => $auction]);
                    AuctionCancelJob::dispatch($auction->user_id, $auction->id);

                    $bids = Bid::where('auction_id', $auction->id)->get();
                    foreach($bids as $bid){
                        // Log::info('경매 취소 모드 입찰자 알림', ['method' => $bid->user_id]);
                        AuctionCancelJob::dispatch($bid->user_id, $auction->id);
                    }
                }   

                // 입찰자에게 알림
                if($auction->status == 'dlvr'){


                    if($auction->is_deposit == 'totalDeposit'){
                        Log::info('경매 상태 업데이트 입금완료 모드', ['method' => $auction]);
                        // 고객 / 딜러 에게 알림 
                        AuctionTotalDepositJob::dispatch($auction->user_id, $auction);
                        AuctionTotalDepositJob::dispatch($bids->user_id, $auction);

                    }else {
                        Log::info('경매 상태 업데이트 입찰선택 모드', ['method' => $auction]);

                        if($auction->bids){
                            Log::info('경매 상태 업데이트 입찰선택 모드 입찰자 알림' . $auction->bids->first()->user_id, ['method' => '']);
                            AuctionCohosenJob::dispatch($auction->bids->first()->user_id, $auction->id, 'dealer');
                        }
    
                        // AuctionCohosenJob::dispatch($auction->user_id, $auction->id, 'user'); s
                    }

                    
                }   

                // 입찰자에게 알림
                if($auction->status == 'wait'){
                    Log::info('경매 상태 업데이트 선택대기 모드1', ['method' => $auction]);

                    AuctionBidStatusJob::dispatch($auction->user_id, 'wait', $auction->id, '', '');
                }
                
                // 경매완료시 전체 입찰자에게 알림
                if($auction->status == 'done'){
                    
                    if($auction->is_deposit == 'totalAfterFee'){

                        Log::info('경매 상태 업데이트 수수료 입금완료 모드', ['method' => $auction]);
                        // 딜러에게 알림 
                        AuctionTotalAfterFeeJob::dispatch($bids->user_id, $auction);

                    }else{
                        Log::info('경매 상태 업데이트 경매완료 모드', ['method' => $auction]);

                        $bids = Bid::find($auction->bid_id);
    
                        AuctionDoneJob::dispatch($auction->user_id, $auction->id, 'user');
                        AuctionDoneJob::dispatch($bids->user_id, $auction->id, 'dealer');
                    }

                }

                // 진단대기중 알림 
                if($auction->status == 'diag'){
                    Log::info('경매 상태 업데이트 진단대기중 모드', ['method' => $auction]);

                    AuctionDiagJob::dispatch($auction->user_id, $auction);
                }

                // 경매진행중 알림
                if($auction->status == 'ing'){
                    $auction->final_at = now()->addDays(env('AUCTION_DAY'));
                    if(!$auction->bid_id){
                        Log::info('경매 상태 업데이트 경매진행중 모드', ['method' => $auction]);

                        AuctionIngJob::dispatch($auction->user_id, $auction->id, $auction->final_at);

                    }
                }


                if($auction->status == 'chosen'){
                    Log::info('유저가 경매를 선택 했을때2', ['method' => $auction]);

                    // AuctionCohosenJob::dispatch($auction->user_id, $auction->id, 'user');    
                    AuctionCohosenJob::dispatch($auction->bids->first()->user_id, $auction->id, 'dealer');
                }


                break;
            case 'destroy':
                $this->modifyOnlyMe($auction);
                break;
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
        $auth = env('CARMERCE_API_AUTH');
        $password = env('CARMERCE_API_PASSWORD');

        // 시세확인 인증 
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('CARMERCE_AUTH_API_URL'),
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
            CURLOPT_URL => env('CARMERCE_PRICE_API_URL'),
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

    // 나이스DNR 차량정보/시세확인 API
    public function getNiceDnr($ownerNm, $vhrNo)
    {
        $curl = curl_init();

        Log::info('나이스DNR 차량정보/시세확인 API 호출 시작', ['ownerNm' => $ownerNm, 'vhrNo' => $vhrNo]);
        
        $chkSec = date('YmdHis'); // 예: chkSec 값
        $businessNumber = env('NICE_API_BUSINESS_NUMBER'); // 예: 사업자번호
        $chkKey = (($chkSec % $businessNumber) % 997);

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('NICE_API_URL').'?
            apiKey='.env('NICE_API_APIKEY').'&
            chkSec='.$chkSec.'&
            chkKey='.$chkKey.'&
            loginId='.env('NICE_API_LOGIN_ID').'&
            kindOf='.env('NICE_API_KIND_OF').'&
            ownerNm='.$ownerNm.'&
            vhrNo='.$vhrNo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);

        Log::info('나이스DNR 차량정보/시세확인 API 호출 결과', ['result' => $response]);
        
        // 샘플 데이터
        $tmpCars = [
            [
                "makerId" => "001",
                "makerNm" => "현대",
                "classModelld" => "101",
                "classModelNm" => "쏘나타",
                "modelld" => "301",
                "modelNm" => "더 뉴 쏘나타",
                "carName" => "현대 더 뉴 쏘나타 가솔린 2000cc 프리미엄",
                "year" => "2022",
                "detailedModel" => "더 뉴 쏘나타 프리미엄",
                "grade" => "프리미엄",
                "subGrade" => "프리미엄 플러스",
                "firstRegistrationDate" => "2022-03-15",
                "engineCapacity" => "2000cc",
                "fuelType" => "가솔린",
                "transmission" => "자동",
                "useChangeHistory" => "없음",
                "tuningHistory" => "0회",
                "recallHistory" => "없음",
                "currentPrice" => 1800, // 시세 숫자만 입력
                "km" => "2.4",
                "thumbnail" => "https://image-cdn.hypb.st/https%3A%2F%2Fkr.hypebeast.com%2Ffiles%2F2022%2F05%2Fhyundai-motor-company-sonata-discontinued-01.jpg?q=75&w=800&cbr=1&fit=max"
            ],
            [
                "makerId" => "002",
                "makerNm" => "기아",
                "classModelld" => "102",
                "classModelNm" => "스포티지",
                "modelld" => "302",
                "modelNm" => "더 뉴 스포티지",
                "carName" => "기아 더 뉴 스포티지 디젤 2000cc 노블레스",
                "year" => "2021",
                "detailedModel" => "더 뉴 스포티지 노블레스",
                "grade" => "노블레스",
                "subGrade" => "노블레스 스페셜",
                "firstRegistrationDate" => "2021-08-10",
                "engineCapacity" => "2000cc",
                "fuelType" => "디젤",
                "transmission" => "자동",
                "useChangeHistory" => "없음",
                "tuningHistory" => "1회",
                "recallHistory" => "없음",
                "currentPrice" => 2000, // 시세 숫자만 입력,
                "km" => "3.6",
                "thumbnail" => "https://image-cdn.hypb.st/https%3A%2F%2Fkr.hypebeast.com%2Ffiles%2F2021%2F07%2FKia-release-new-sportage-suv-model-design-price-spec-info-twtw.jpg?w=960&cbr=1&q=90&fit=max"
            ],
            [
                "makerId" => "003",
                "makerNm" => "르노코리아",
                "classModelld" => "103",
                "classModelNm" => "SM6",
                "modelld" => "303",
                "modelNm" => "SM6",
                "carName" => "르노코리아 SM6 가솔린 1600cc LE",
                "year" => "2020",
                "detailedModel" => "SM6 LE",
                "grade" => "LE",
                "subGrade" => "LE 프리미엄",
                "firstRegistrationDate" => "2020-11-25",
                "engineCapacity" => "1600cc",
                "fuelType" => "가솔린",
                "transmission" => "자동",
                "useChangeHistory" => "1회",
                "tuningHistory" => "0회",
                "recallHistory" => "1회",
                "currentPrice" => 1200, // 시세 숫자만 입력,
                "km" => "1.5",
                "thumbnail" => "https://file.carisyou.com/upload/2020/07/15/EDITOR_202007150443005100.jpg"
            ],
            [
                "makerId" => "004",
                "makerNm" => "쌍용",
                "classModelld" => "104",
                "classModelNm" => "코란도",
                "modelld" => "304",
                "modelNm" => "코란도",
                "carName" => "쌍용 코란도 디젤 2200cc 어드벤처",
                "year" => "2019",
                "detailedModel" => "코란도 어드벤처",
                "grade" => "어드벤처",
                "subGrade" => "어드벤처 플러스",
                "firstRegistrationDate" => "2019-06-30",
                "engineCapacity" => "2200cc",
                "fuelType" => "디젤",
                "transmission" => "자동",
                "useChangeHistory" => "2회",
                "tuningHistory" => "1회",
                "recallHistory" => "없음",
                "currentPrice" => 1000, // 시세 숫자만 입력,
                "km" => "3.2",
                "thumbnail" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqgDnOXZPCRL7PDb1Kln4-MPxmAfWa8zzSZA&s"
            ],
            [
                "makerId" => "005",
                "makerNm" => "제네시스",
                "classModelld" => "105",
                "classModelNm" => "G80",
                "modelld" => "305",
                "modelNm" => "G80",
                "carName" => "제네시스 G80 가솔린 3300cc AWD",
                "year" => "2023",
                "detailedModel" => "G80 AWD 프리미엄",
                "grade" => "프리미엄",
                "subGrade" => "프리미엄 럭셔리",
                "firstRegistrationDate" => "2023-02-15",
                "engineCapacity" => "3300cc",
                "fuelType" => "가솔린",
                "transmission" => "자동",
                "useChangeHistory" => "없음",
                "tuningHistory" => "0회",
                "recallHistory" => "없음",
                "currentPrice" => 4500, // 시세 숫자만 입력,
                "km" => "2.6",
                "thumbnail" => "https://cdn.motorgraph.com/news/photo/202211/30950_97376_454.jpg"
            ]
        ];


        $response = array(
            "carSize" => array(
                "info" => $tmpCars[array_rand($tmpCars)]
            ),
            "resultCode" => "0000",
            "resultMsg" => "성공"
        ); 

        curl_close($curl);

        return $response;
    }

    // 예상가 계산
    public function calculateCarPrice($currentYear, $currentMonth, $regYear, $regMonth, $currentMileage, $initialPrice, $mileageStandard = 1250) {
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
        // 탁송 상태 확인
        $taksongStatusTemp = TaksongStatusTemp::where('chk_status', 'ask')->get();
        if($taksongStatusTemp){

            foreach($taksongStatusTemp as $bid){
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
        $auction = Auction::where('status', 'ing')->get();
        foreach($auction as $auction){
            // 현재시간 final_at 시간 비교해서 현재 시간이 더 클 경우 상태 변경 
            if($auction->final_at){
                if(Carbon::now() > $auction->final_at){
                    $auction->status = 'wait';
                    $auction->choice_at = Carbon::now()->addDays(env('CHOICE_DAY'));
                    $auction->save();
                    // 알림 보내기
                    AuctionBidStatusJob::dispatch($auction->user_id, 'wait', $auction->id, '','');
                }
            }

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

}