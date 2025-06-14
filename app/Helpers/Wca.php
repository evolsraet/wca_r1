<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Notifications\NetworkErrorNotification;
use App\Jobs\NetworkErrorJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Article;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Vinkla\Hashids\Facades\Hashids;

class Wca
{

    protected static array $keywords = [
        'cURL error',
        'Connection refused',
        'Could not resolve host',
        'timed out',
        'Connection timed out',
        'SSL',
        'DNS'
    ];

    // 원 단위로 변환
    public static function formatPriceToWon($price, $showWon = true)
    {
        if (empty($price)) {
            return '0' . ($showWon ? '원' : '');
        }

        // 문자열에서 숫자만 추출
        $numPrice = (float) preg_replace('/[^0-9]/', '', $price);
        $numPrice = number_format($numPrice);

        return $numPrice . '만원';
    }

    // 네트워크 에러 체크
    public static function isNetworkError(Exception $e): bool
    {
        return Str::contains($e->getMessage(), self::$keywords);
    }

    // 네트워크 에러 알림 전송
    public static function alertIfNetworkError(Exception $e, array $context = []): void
    {
        if (self::isNetworkError($e)) {
            $message = $e->getMessage();

            Log::warning('[네트워크 에러]', array_merge([
                'message' => $message,
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], $context));

            // 알림 전송
            NetworkErrorJob::dispatch($message, $context);
        }
    }

    public static function board_menu_label($boardIdOrUrl): ?string
    {
        $targetUrl = str_starts_with($boardIdOrUrl, '/')
            ? $boardIdOrUrl
            : '/board/' . $boardIdOrUrl;

        return collect(config('auction.menus'))
            ->flatMap(fn($group) => $group)
            ->firstWhere('url', $targetUrl)['label'] ?? null;
    }

    // 회원기준 carInfo 캐시 조회
    public static function getCachedCarInfoForUser(): ?array
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $queryInfo = Cache::get("carInfo.user.{$user->id}");

        if (empty($queryInfo) || !is_array($queryInfo)) {
            return null;
        }

        return $queryInfo;
    }

    // 클레임, 리뷰 작성 가능 여부 체크
    public static function isReviewClaimWriteable($userId, $articleId, $type)
    {
        try {

            $adminEmail = 'admin@demo.com';

            $user = User::find($userId);
            if (!$user) throw new \Exception('유저가 존재하지 않습니다.');
            if ($user->email === $adminEmail) return true;

            // $articleId = Hashids::decode($articleId);
            // $articleId = $articleId[0] ?? 0;

            if ($articleId === null || $articleId === 0) {
                throw new \Exception('선택된 차량정보가 없습니다.');
            }

            $auction = Auction::findOrFail($articleId);
            if (!$auction) throw new \Exception('경매가 존재하지 않습니다.');

            // 1. 중복 작성 체크
            $exists = Article::where('extra1', $articleId)->where('user_id', $userId)->where('board_id', $type)->exists();
            if ($exists) throw new \Exception('이미 작성된 글이 존재합니다.');

            // 2. auction status 확인
            if ($auction->status !== 'done') throw new \Exception('경매가 종료되지 않았습니다.');
        
            // 3. 관리자 bypass
            if ($user->email !== $adminEmail) {
                if ($type === 'review') {
                    if ($auction->user_id !== $userId) throw new \Exception('판매유저가 아닙니다.');
                } elseif ($type === 'claim') {
                    // auction->bid 의 값이 bid 테이블의 id 이고 이 테이블 안에 user_id 가 맞는지 확인 
                    $bid = Bid::findOrFail($auction->bid);
                    if (!$bid || $bid->user_id !== $userId) throw new \Exception('덜리회원이 아닙니다.');
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


    public static function isReviewClaimLists($userId, $type)
    {
        try {

            if(!$userId) {
                throw new \Exception('유저 정보가 없습니다.');
            }

            if(!$type) {
                throw new \Exception('게시판 정보가 없습니다.');
            }

            if($type && $userId) {
                if($type === 'review') {
                    $auctions = Auction::
                    where('auctions.status', 'done')
                    ->where('auctions.bid_id', '!=', null)
                    ->where('auctions.user_id', $userId)
                    ->get();
                } elseif($type === 'claim') {
                    // TODO 딜러일때 리스트 수정 필요
                    $auctions = Auction::
                    where('auctions.status', 'done')
                    ->where('auctions.bid_id', '!=', null) // 덜리회원 차량 조회
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

