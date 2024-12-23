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

                Log::info('경매 상태 업데이트 모드??', ['method' => $auction]);

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

                            break;
                        case 'reauction':
                            // 재경매 : 옥션변수가 오고, 재옥션 상태가 아니고, auction->status 가 wait 일 경우,  상태변경
                            if (!$auction->is_reauction && $auction->status == 'wait') {
                                $auction->status = 'ing';
                                $auction->is_reauction = true;
                                $auction->final_at = now()->addDays(env('REAUCTION_DAY'));
                            } else {
                                throw new \Exception('재경매변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'diag':
                            // 진단으로 변경
                            if (!$auction->is_reauction && $auction->status == 'ask') {
                                $auction->status = 'diag';
                                $auction->final_at = now()->addDays(env('AUCTION_DAY'));
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
                            } else {
                                throw new \Exception('배송변경 가능상태가 아닙니다.');
                            }
                            break;

                        case 'cancel':
                            // 취소으로 변경
                            if (!in_array($auction->status, ['done', 'dlvr'])) {
                                $auction->status = 'cancel';
                            } else {
                                throw new \Exception('취소변경 가능상태가 아닙니다.');
                            }
                            break;
                    }
                }


                // 취소시 알림
                if($auction->status == 'cancel'){

                    Log::info('경매 상태 업데이트 취소 모드', ['method' => $auction]);
                    AuctionCancelJob::dispatch($auction->user_id);

                    $bids = Bid::where('id', $auction->bid_id)->get();
                    foreach($bids as $bid){
                        // Log::info('경매 취소 모드 입찰자 알림', ['method' => $bid->user_id]);
                        AuctionCancelJob::dispatch($bid->user_id);
                    }
                }

                // if($auction->status == 'ing'){
                //     Log::info('경매 상태 업데이트 ing 모드', ['method' => $auction]);
                    
                //     AuctionIngJob::dispatch($auction->user_id);
                    
                //     // $bids = Bid::where('id', $auction->bid_id)->get();
                //     // foreach($bids as $bid){
                //     //     AuctionIngJob::dispatch($bid->user_id);
                //     // }
                // }   

                // 입찰자에게 알림
                if($auction->status == 'chosen'){
                    Log::info('경매 상태 업데이트 입찰선택 모드', ['method' => $auction]);

                    AuctionCohosenJob::dispatch($auction->user_id);
                    AuctionCohosenJob::dispatch($auction->bids->first()->user_id);
                }   

                // 탁송중 알림
                if($auction->status == 'dlvr'){
                    Log::info('경매 상태 업데이트 탁송중 모드', ['method' => $auction]);

                    AuctionDlvrJob::dispatch($auction->bids->first()->user_id);
                }
                
                // 경매완료시 전체 입찰자에게 알림
                if($auction->status == 'done'){
                    Log::info('경매 상태 업데이트 경매완료 모드', ['method' => $auction]);

                    AuctionDoneJob::dispatch($auction->user_id);
                    AuctionDoneJob::dispatch($auction->bids->first()->user_id);

                }

                // 진단대기중 알림 
                if($auction->status == 'diag'){
                    Log::info('경매 상태 업데이트 진단대기중 모드', ['method' => $auction]);

                    AuctionDiagJob::dispatch($auction->user_id);
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
}
