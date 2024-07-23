<?php

namespace App\Services;

use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BidResource;
use App\HttpResources\AuctionResource;

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
        if ($method == 'store' && (!auth()->check() || !auth()->user()->hasRole('user'))) {
            throw new \Exception('권한이 없습니다.');
        }
    }

    // 요청 중간 처리: 결과 필터링 및 데이터 검증
    protected function middleProcess($method, $request, $auction, $id = null)
    {
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
                // 상태변경
                // request()->mode 가 있을 경우 그대로 두고, 없으면서 $acution->status 가 변경됬을 경우 그 request()->mode 에 $acution->status 대입
                if (!request()->has('mode') && $auction->isDirty('status')) {
                    request()->merge(['mode' => $request->status]);
                }

                if (request()->has('mode')) {
                    switch (request()->mode) {
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
                if (
                    request()->has('mode') && request()->reauction

                    // 본인일 경우
                ) {
                    $auction->status = 'ing';
                    $auction->is_reauction = true;
                    $auction->final_at = now()->addDays(env('REAUCTION_DAY'));
                } elseif (request()->has('mode') && request()->reauction) {
                }

                $this->modifyOnlyMe($auction);
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
            $auction->increment('hit');  // 조회수 증가
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
    }
}
