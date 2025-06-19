<?php

namespace App\Helpers;

use Exception;
use App\Models\Article;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Vinkla\Hashids\Facades\Hashids;

class Board
{

    // 작성가능한 차량리스트 출력 (모달창 리스트)
    public static function getWriteableAuctionList($userId, $boardId)
    {
        try {

            if(!$userId) {
                throw new \Exception('유저 정보가 없습니다.');
            }

            if(!$boardId) {
                throw new \Exception('게시판 정보가 없습니다.');
            }

            if($boardId && $userId) {
                if($boardId === 'review') {
                    $auctions = Auction::
                    where('auctions.status', 'done')
                    ->where('auctions.bid_id', '!=', null)
                    ->where('auctions.user_id', $userId)
                    ->get();
                } elseif($boardId === 'claim') {
                    // TODO 딜러일때 리스트 수정 필요
                    $auctions = Auction::
                    where('auctions.status', 'done')
                    ->where('auctions.bid_id', '!=', null) // 딜러회원 차량 조회
                    ->where('auctions.user_id', '!=', $userId) // 판매유저 차량 조회
                    ->get();
                }

                if($auctions->isEmpty()) {
                    throw new \Exception('작성 가능한 차량정보가 없습니다.');
                }

                $lists = [];
                foreach($auctions as $auction) {
                    $auction['hashId'] = Hashids::encode($auction->id);
                    $lists[] = $auction;
                }

                return [
                    'status' => true,
                    'message' => '작성 가능한 차량 리스트 출력',
                    'data' => $lists,
                ];
            }

        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

}
