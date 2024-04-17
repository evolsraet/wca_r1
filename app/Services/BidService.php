<?php

namespace App\Services;

use App\Models\Auction;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AuctionResource;

class BidService
{
    use CrudTrait;

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('bid');
    }

    protected function beforeProcess($method, $request, $id = null)
    {
        if ($method == 'store') {
            // 딜러만 저장
            if (!auth()->check() or !auth()->user()->hasRole('dealer')) {
                throw new \Exception('권한이 없습니다.');
            }
        }
    }

    protected function middleProcess($method, $request, $result, $id = null)
    {
        if ($method == 'index' or $method == 'show') {
            // 관리자 외 딜러는 본인것만
            if (auth()->user()->hasPermissionTo('act.admin')) {
            } elseif (auth()->user()->hasPermissionTo('act.dealer')) {
                // 딜러는 본인이 넣은것만
                $result->where('user_id', auth()->user()->id);
            } else {
                // 유저는 본인의 auction 인것만
                // $userId = auth()->user()->id;
                $result->whereHas('auction', function ($query) {
                    $query->where('user_id', auth()->user()->id);
                });
            }
        } elseif ($method == 'store') {
            if (!auth()->user()->hasPermissionTo('act.user'))
                throw new \Exception('권한이 없습니다.');

            // 기본값 아이디 지정
            $result->user_id = auth()->user()->id;
            $result->status = 'ask';
            // 경매 검증 - 삽입에 auction_id 는 없다
            // $auction = Auction::find($result->auction_id);
            // if ($auction) {
            //     if ($auction->status != 'ing')
            //         throw new \Exception('신청가능한 경매가 아닙니다.');
            // }
        } elseif ($method == 'update') {
            $this->modifyOnlyMe($result);
            unset($result->user_id);
            unset($result->auction_id);
        } elseif ($method == 'destroy') {
            $this->modifyOnlyMe($result);
        }
    }
}
