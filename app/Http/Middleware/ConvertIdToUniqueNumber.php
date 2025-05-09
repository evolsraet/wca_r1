<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Auction;
use Vinkla\Hashids\Facades\Hashids;

class ConvertIdToUniqueNumber
{
    public function handle($request, Closure $next): mixed
    {
        if ($request->route('auction')) {
            $hash = $request->route('auction');

            // 해시디코딩
            $decoded = Hashids::decode($hash);
            $id = $decoded[0] ?? null;

            if ($id) {
                // 실제 ID 기준으로 모델 조회
                $auction = Auction::find($id);
                if ($auction) {
                    $request->route()->setParameter('auction', $auction->id);
                }
            }
        }

        return $next($request);
    }
}