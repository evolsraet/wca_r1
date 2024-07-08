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
                // TODO: 본인인증 검증 추가
                $this->validateAndSetAuctionData($request, $auction);
                break;
            case 'update':
                // TODO: 상황별 업데이트 처리
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
