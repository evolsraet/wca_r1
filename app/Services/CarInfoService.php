<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CarInfoService
{
    public function getCachedCarInfoForUser(): ?array
    {
        $user = Auth::user();

        if (!$user) return null;

        $userCacheKey = "carInfo.user.{$user->id}";

        if (!Cache::has($userCacheKey)) return null;

        $queryInfo = Cache::get($userCacheKey); // ['owner' => '이름', 'no' => '차량번호']
        $carInfoCacheKey = "carInfo." . $queryInfo['owner'] . $queryInfo['no'];

        if (!Cache::has($carInfoCacheKey)) return null;

        $carInfo = Cache::get($carInfoCacheKey);

        return (!empty($carInfo['owner']) && !empty($carInfo['no'])) ? $carInfo : null;
    }

    public function storeUserQueryHistory(string $owner, string $no): void
    {
        $user = Auth::user();
        if (!$user) return;
    
        $key = "carInfo.user.{$user->id}";
    
        // 기존 데이터 불러오기 (없으면 빈 배열)
        $history = Cache::get($key, []);
    
        // 중복 제거 (같은 owner & no 조합이 있는지 확인)
        $exists = collect($history)->first(fn($item) =>
            isset($item['owner'], $item['no']) &&
            $item['owner'] === $owner &&
            $item['no'] === $no
        );
    
        if (!$exists) {
            $history[] = [
                'owner' => $owner,
                'no' => $no,
            ];
        }
    
        Cache::put($key, $history, now()->addDays(config('days.car_info_cache_ttl')));
    }
}