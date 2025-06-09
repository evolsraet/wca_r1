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

        Cache::put($key, [
            'owner' => $owner,
            'no' => $no,
        ], now()->addDays(config('days.car_info_cache_ttl')));
    }
}