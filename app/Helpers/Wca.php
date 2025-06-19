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
}

