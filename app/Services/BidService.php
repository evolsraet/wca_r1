<?php

namespace App\Services;

use App\Models\Auction;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AuctionResource;
use App\Jobs\AuctionBidStatusJob;
use Illuminate\Support\Facades\Log;

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
        }
    }

    protected function middleProcess($method, $request, $result, $id = null)
    {
        if ($method == 'index' or $method == 'show') {
            // 관리자 외 딜러는 본인것만
            if (auth()->user()->hasPermissionTo('act.admin')) {
            } elseif (auth()->user()->hasPermissionTo('act.dealer')) {
                // $result->where('user_id', auth()->user()->id)->where('status','!=', 'done');
                $result
                ->where('user_id', auth()->user()->id)
                ->where(function ($query) {
                    $query->where('status', '!=', 'done')
                        ->orWhereNull('status');
                });
            } else {
                // 유저는 본인의 auction 인것만
                // $userId = auth()->user()->id;
                $result->whereHas('auction', function ($query) {
                    $query->where('user_id', auth()->user()->id);
                });
            }
        } elseif ($method == 'store') {
            // 딜러만 저장
            if (!auth()->check() or !auth()->user()->hasRole('dealer')) {
                throw new \Exception('권한이 없습니다.');
            }

            // 프라이스 필수
            if (!request()->input('bid.auction_id'))
                throw new \Exception('경매 아이디가 누락되었습니다.');

            if (!request()->input('bid.price'))
                throw new \Exception('입찰가격이 누락되었습니다.');

            // 기본값 아이디 지정
            $result->user_id = auth()->user()->id;
            $result->status = 'ask';
            $result->price = request()->input('bid.price');
            $result->auction_id = request()->input('bid.auction_id');

            // 경매 검증 - 삽입에 auction_id 는 없다
            $auction = Auction::find($result->auction_id);
            if ($auction) {
                if ($auction->status != 'ing')
                    throw new \Exception('신청가능한 경매가 아닙니다.');
            } else {
                throw new \Exception('신청가능한 아이디가 아닙니다.');
            }

            // 경매 입찰시 상태 업데이트
            if($auction->status == 'ing'){
                Log::debug("[경매] 입찰 상태 업데이트 - bidService {$auction->hashid}", [
                    'name'=> '경매 입찰 상태 업데이트',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'user_id' => $auction->user_id,
                    'status' => 'ask',
                    'result' => $result->user_id]);
                AuctionBidStatusJob::dispatch($auction->user_id, 'ask', $auction->id, $result->user_id, $result->price);
            }
            
        } elseif ($method == 'update') {
            $this->modifyOnlyMe($result);
            // 수정 시 사용자 아이디와 경매 아이디는 수정 불가

            Log::debug('[경매] 수정 시', [
                'name'=> '경매 수정 시',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'method' => $method,
                'result' => $result
            ]);

            unset($request->user_id);
            unset($request->auction_id);
        } elseif ($method == 'destroy') {

            // 경매 입찰취소시 상태 업데이트
            $auction = Auction::find($result->auction_id);
            AuctionBidStatusJob::dispatch($auction->user_id, 'delete', $auction->id, $result->user_id, $result->price);

            $this->modifyOnlyMe($result);
        }
    }
}
