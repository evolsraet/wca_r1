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
use Illuminate\Http\JsonResponse;
use App\Models\Like;

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

    public static function deleteCarInfoForUser(string $owner, string $no): ?bool
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $key = "carInfo.user.{$user->id}";

        // 기존 데이터 불러오기 (없으면 빈 배열)
        $history = Cache::get($key, []);
        if (empty($history) || !is_array($history)) {
            return null;
        }

        // 삭제 대상 찾기 (owner와 no 둘 다 일치하는 항목)
        $originalCount = count($history);
        $history = array_filter($history, function($car) use ($owner, $no) {
            return !(
                isset($car['owner'], $car['no']) &&
                $car['owner'] === $owner &&
                $car['no'] === $no
            );
        });

        // 삭제된 항목이 있는지 확인
        if (count($history) === $originalCount) {
            return false; // 삭제할 항목이 없었음
        }

        // 배열 인덱스 재정렬
        $history = array_values($history);

        // TTL과 함께 캐시에 저장
        Cache::put($key, $history, now()->addDays(config('days.car_info_cache_ttl')));

        return true;
    }

    public static function auctionStatus()
    {
        $auction = new Auction();

        $status = $auction->enums['status'] ?? [];
        $classMap = $auction->enums['classMap'] ?? [];

        return json_encode([
            'status' => $status,
            'classMap' => $classMap,
        ]);
    }

    public static function bidCount($userId)
    {
        // auction 에서 status 가 ing 인것만 카운트 
        $auction = Auction::where('status', 'ing')->pluck('id')->toArray();

        $auctionList = [];
        foreach ($auction as $item) {
            $auctionList[] = $item;
        }

        // likes 에 user_id 기준으로 등록된 리스트와 갯수 
        $likes = Like::where('user_id', $userId)->get();

        $likeList = [];
        foreach ($likes as $item) {
            $likeList[] = $item;
        }

        // bid 에 user_id 기준으로 리스트 인데, bid에서 auction_id 가 quction 테이블의 id 와 매칭후 status 가 ing 인것만 리스트 및 갯수 
        $bids = Bid::where('user_id', $userId)->get();

        $bidList = [];
        foreach ($bids as $item) {
            $auction_bid = Auction::find($item->auction_id);
            if ($auction_bid->status == 'ing') {
                $bidList[] = $item;
            }
        }

        // bid 에 user_id 기준으로 리스트 인데, bid에서 auction_id 가 quction 테이블의 id 와 매칭후 status 가 done 인것만 리스트 및 갯수 
        $bidDoneList = [];
        foreach ($bids as $item) {
            $auction_bid_done = Auction::find($item->auction_id);
            if ($auction_bid_done->status == 'done') {
                $bidDoneList[] = $item;
            }
        }

        // bid 에 user_id 기준으로 리스트 인데, bid에서 auction_id 가 quction 테이블의 id 와 매칭후 status 가 done 아닌것 리스트 및 갯수 
        $bidNotDoneList = [];
        foreach ($bids as $item) {
            $auction_bid_not_done = Auction::find($item->auction_id);
            if ($auction_bid_not_done->status != 'done') {
                $bidNotDoneList[] = $item;
            }
        }
        
        return [
            'auctionList' => $auctionList,
            'likeList' => $likeList,
            'bidList' => $bidList,
            'bidDoneList' => $bidDoneList,
            'bidNotDoneList' => $bidNotDoneList,
        ];
    }

    // 데이터 구조 정규화 - 1차원/2차원 혼합 배열을 2차원 배열로 통일
    public static function normalizeDataStructure(array $data): array 
    {
        // 빈 배열 체크
        if (empty($data)) {
            return [];
        }

        // 이미 2차원 배열인지 확인 (첫 번째 요소가 배열인지 체크)
        $firstElement = reset($data);

        if (is_array($firstElement)) {
            // 이미 2차원 배열 - 그대로 반환
            return $data;
        } else {
            // 1차원 연관배열 - 2차원 배열로 래핑
            return [$data];
        }
    }
}

