<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Auction;

class ConvertIdToUniqueNumber
{
    public function handle($request, Closure $next)
    {
        if ($request->route('auction')) {
            $auction = Auction::where('unique_number', $request->route('auction'))->first();

            if ($auction) {
                // 경로 파라미터를 unique_number로 변환
                $request->route()->setParameter('auction', $auction->id);
            }
        }

        return $next($request);
    }
} 