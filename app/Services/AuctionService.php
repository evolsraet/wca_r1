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
use App\Jobs\AuctionDlvrJob;
use App\Jobs\AuctionDiagJob;
use App\Jobs\AuctionBidStatusJob;
use App\Jobs\TaksongAddJob;

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
                                    AuctionBidStatusJob::dispatch($bid->user_id, 'reauction', $auction->id, $bid->user_id);
                                }


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
                    }
                }


                // 취소시 알림
                if($auction->status == 'cancel'){

                    Log::info('경매 상태 업데이트 취소 모드', ['method' => $auction]);
                    AuctionCancelJob::dispatch($auction->user_id, $auction->id);

                    $bids = Bid::where('id', $auction->id)->get();
                    foreach($bids as $bid){
                        // Log::info('경매 취소 모드 입찰자 알림', ['method' => $bid->user_id]);
                        AuctionCancelJob::dispatch($bid->user_id, $auction->id);
                    }
                }   

                // 입찰자에게 알림
                if($auction->status == 'chosen'){
                    Log::info('경매 상태 업데이트 입찰선택 모드', ['method' => $auction]);

                    if($auction->bids){
                        Log::info('경매 상태 업데이트 입찰선택 모드 입찰자 알림' . $auction->bids->first()->user_id, ['method' => '']);
                        AuctionCohosenJob::dispatch($auction->bids->first()->user_id, $auction->id, 'dealer');
                    }

                    // AuctionCohosenJob::dispatch($auction->user_id, $auction->id, 'user'); 
                }   

                // 입찰자에게 알림
                if($auction->status == 'wait'){
                    Log::info('경매 상태 업데이트 선택대기 모드1', ['method' => $auction]);

                    // AuctionBidStatusJob::dispatch($auction->user_id, 'wait', $auction->id);
                }
                
                // 경매완료시 전체 입찰자에게 알림
                if($auction->status == 'done'){
                    
                    Log::info('경매 상태 업데이트 경매완료 모드', ['method' => $auction]);

                    $bids = Bid::find($auction->bid_id);

                    AuctionDoneJob::dispatch($auction->user_id, $auction->id, 'user');
                    AuctionDoneJob::dispatch($bids->user_id, $auction->id, 'dealer');
                }

                // 진단대기중 알림 
                if($auction->status == 'diag'){
                    Log::info('경매 상태 업데이트 진단대기중 모드', ['method' => $auction]);

                    AuctionDiagJob::dispatch($auction->user_id, $auction);
                }

                // 경매진행중 알림
                if($auction->status == 'ing'){
                    if(!$auction->bid_id){
                        Log::info('경매 상태 업데이트 경매진행중 모드', ['method' => $auction]);

                        AuctionIngJob::dispatch($auction->user_id, $auction->id);

                    }
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

        // 샘플 데이터
        $result = array(
            "refreshToken" => "9b549f45-a75f-4fa4-b86b-08aea40316c8",
            "user" => array(
                "userId" => "012601",
                "companyName" => "(주)오토허브셀카",
                "userName" => "김희용"
            ),
            "accessToken" => "eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJhY2Nlc3MiLCJpYXQiOjE3MjQ3MjgzNjMsImV4cCI6MTcyNDgxNDc2MywicGFyYW1zIjp7ImxvZ2luSWQiOiIwMTI2MDIiLCJpZCI6IjEwMCJ9fQ.d3KcSi38cVXFitIPamo4OanszIYybOQry-1mZZ2-llw",
            "tokenType" => "Bearer"
        );

        // print_r($result);

        curl_close($curl);

        return $result;
    }   

    // 카머스 시세확인 API
    public function getCarmercePrice($accessToken)
    {

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
                "startDt" : "2024-08-21",
                "endDt" : "2024-08-21",
                "carName" : "그랜져",
                "startMakeYear" : " 2021",
                "endMakeYear" : "2021",
                "startDriveKm" : "20000",
                "endDriveKm" : "100000"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: '.$accessToken
            ),
        ));

        $result = curl_exec($curl);

        $result = json_decode($result, true);

        // Log::info('카머스 시세확인 API 결과', ['result' => $result]);
        
        // 샘플 데이터
        $result = array(
            "code" => "0",
            "message" => "성공",
            "timestamp" => "2024-08-27 13:33:30.131",
            "data" => array(
                array(
                    "receiveCd" => "RC202408140461",
                    "startDt" => "2024-08-21 00:00:00",
                    "carName" => "현대 더 뉴 그랜져 IG 가솔린2500cc 익스클루시브",
                    "makeYear" => "2021",
                    "gearTypeCode" => "001",
                    "fuelTypeCode" => "01",
                    "driveKm" => 44944,
                    "bidAmt" => 2270,
                    "regDate" => "2021-01-15",
                    "inspGrade" => "DB",
                    "domexType" => "내수"
                ),
                array(
                    "receiveCd" => "RC202408140485",
                    "startDt" => "2024-08-21 00:00:00",
                    "carName" => "현대 더 뉴 그랜져 IG 가솔린3300cc 프리미엄 초이스",
                    "makeYear" => "2021",
                    "gearTypeCode" => "001",
                    "fuelTypeCode" => "01",
                    "driveKm" => 84745,
                    "bidAmt" => 2130,
                    "regDate" => "2021-04-21",
                    "inspGrade" => "BB",
                    "domexType" => "내수"
                )
            )
        );


        curl_close($curl);
        return $result;
    }

    // 나이스DNR 차량정보/시세확인 API
    public function getNiceDnr($ownerNm, $vhrNo)
    {
        $curl = curl_init();
        
        $chkSec = date('YmdHis'); // 예: chkSec 값
        $businessNumber = env('NIDE_API_BUSINESS_NUMBER'); // 예: 사업자번호
        $chkKey = (($chkSec % $businessNumber) % 997);

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('NICE_API_URL').'?apiKey='.env('NICE_API_APIKEY').'&chkSec='.$chkSec.'&chkKey='.$chkKey.'&loginId='.env('NICE_API_LOGIN_ID').'&kindOf='.env('NICE_API_KIND_OF').'&ownerNm='.$ownerNm.'&vhrNo='.$vhrNo,
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
        
        // 샘플 데이터
        $response = array(
            "carSize" => array(
                "info" => array(
                    "makerId" => "012601",
                    "makerNm" => "현대",
                    "classModelld" => "96",
                    "classModelNm" => "그랜져",
                    "modelld" => "222",
                    "modelNm" => "더 뉴 그랜져IG",
                    "carName" => "현대 더 뉴 그랜져 IG 가솔린2500cc 익스클루시브"
                )
            ),
            "carPats" => array(
                "info" => array(
                    "carName" => "현대 더 뉴 그랜져 IG 가솔린2500cc 익스클루시브"
                )
            ),
            "resultCode" => "0000",
            "resultMsg" => "성공"
        ); 

        curl_close($curl);

        return $response;
    }

}
