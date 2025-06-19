<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Auction;
use App\Models\Article;
use App\Models\Bid;
use Vinkla\Hashids\Facades\Hashids;

class BoardController extends Controller
{
    /**
     * 게시글 목록 페이지
     */
    public function list($boardId)
    {
        $board = Board::findOrFail($boardId);
        return view("v2.boards.{$board->skin}.list", compact('board'));
    }

    /**
     * 게시글 상세 페이지
     */
    public function view($boardId, $articleId)
    {
        $board = Board::findOrFail($boardId);
        return view("v2.boards.{$board->skin}.view", compact('board', 'articleId'));
    }

    /**
     * 게시글 작성/수정 페이지
     */
    public function form($boardId, $articleId = null)
    {

        $result = $this->reviewClaimWriteCheck(auth()->user()->id, $articleId, $boardId);
        
        $board = Board::findOrFail($boardId);
        return view("v2.boards.{$board->skin}.form", [
            'board' => $board,
            'articleId' => $articleId,
            'message' => $result['message'] ?? null,
            'status' => $result['status'] ?? true,
        ]);
    }

    // 클레임, 이용후기 글작성 권한체크 
    public function reviewClaimWriteCheck($userId, $articleId, $boardId)
    {
        if(request()->query('id')) {
            $id = Hashids::decode(request()->query('id'));
            $id = $id[0] ?? 0;
        } else {
            $id = request()->query('id');
        }

        if(!$articleId) $articleId = $id;
        
        if($boardId === 'claim' || $boardId === 'review') {
            try {

                $user = User::find($userId);
                if (!$user) throw new \Exception('유저가 존재하지 않습니다.');
                if ($user->id === 2) return true;

                if ($articleId === null || $articleId === 0) {
                    throw new \Exception('선택된 차량정보가 없습니다.');
                }

                $auction = Auction::findOrFail($articleId);
                if (!$auction) throw new \Exception('경매가 존재하지 않습니다.');

                // 1. 중복 작성 체크
                $exists = Article::where('extra1', $articleId)->where('user_id', $userId)->where('board_id', $boardId)->exists();
                if ($exists) throw new \Exception('이미 작성된 글이 존재합니다.');

                // 2. auction status 확인
                if ($auction->status !== 'done') throw new \Exception('경매가 종료되지 않았습니다.');
            
                // 3. 관리자 bypass
                if ($user->id !== 2) {
                    if ($boardId === 'review') {
                        if ($auction->user_id !== $userId) throw new \Exception('판매유저가 아닙니다.');
                    } elseif ($boardId === 'claim') {
                        // auction->bid 의 값이 bid 테이블의 id 이고 이 테이블 안에 user_id 가 맞는지 확인 
                        if($auction->bid_id) {
                            $bid = Bid::findOrFail($auction->bid_id);
                            if (!$bid || $bid->user_id !== $userId) throw new \Exception('딜러회원이 아닙니다.');
                        } else {
                            throw new \Exception('정상적으로 완료처리된 경매정보가 아닙니다.');
                        }
                    } else {
                        throw new \Exception('처리 가능한 게시판이 아닙니다.'); // 타입 예외처리
                    }
                }
            
                // 5. 작성 가능 기간 제한
                if ($auction->updated_at && now()->diffInDays($auction->updated_at) > 14) {
                    throw new \Exception('작성 가능 기간이 지났습니다.');
                }
            
                return [
                    'status' => true,
                    'message' => '작성 가능합니다.'
                ];
            
            } catch (\Exception $e) {
                return [
                    'status' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
    }

}
