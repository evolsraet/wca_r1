<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BidResource extends JsonResource
{
    use WithTrait;

    protected static $auctionUserIds = [];

    public static function setAuctionUserId($auctionId, $userId)
    {
        self::$auctionUserIds[$auctionId] = $userId;
    }

    public function toArray($request)
    {
        if (is_null($this->resource)) {
            return [];
        }

        $auctionId = $this->auction_id;
        $auctionUserId = self::$auctionUserIds[$auctionId] ?? null;

        if ($auctionUserId === null) {
            // 요청 객체에서 auction user id를 가져오거나, 없으면 DB에서 조회
            $auctionUserId = $request->attributes->get("auction_user_id_{$auctionId}");
            if ($auctionUserId === null) {
                $auctionUserId = \App\Models\Auction::where('id', $auctionId)->value('user_id');
                $request->attributes->set("auction_user_id_{$auctionId}", $auctionUserId);
            }
            self::setAuctionUserId($auctionId, $auctionUserId);
        }

        $parentArray = $this->formatDates(parent::toArray($request));
        $addArray = [];

        $this->relationResource($request, $parentArray);

        $user = Auth::user();
        $isAuthorized = $user && ($user->hasPermissionTo('act.admin')
            || $user->id === $this->user_id
            || $user->id === $auctionUserId);


        $parentArray['price_value'] = strlen((string)$parentArray['price']) >= 5 ? floor($parentArray['price'] / 10000) : $parentArray['price'];

        if (!$isAuthorized) {
            unset($parentArray['price'], $parentArray['user_id']);
        }

        // 리뷰 갯수 확인하여 사용자 점수 표시 
        // $averageStar = \App\Models\Review::where('dealer_id', $parentArray['user_id'])->avg('star');

        $averageStar = DB::table('articles')
            ->where('board_id', 'review')
            ->whereRaw("JSON_EXTRACT(extra2, '$.dealer_id') = ?", [$parentArray['user_id']])
            ->whereRaw("JSON_EXTRACT(extra2, '$.rating') IS NOT NULL")
            ->selectRaw("AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(extra2, '$.rating')) AS DECIMAL(3,1))) as avg_rating")
            ->value('avg_rating');
            
        if($averageStar){
            $averageStar = min($averageStar, 5);
            $averageStar = round($averageStar, 1);
        }
        $parentArray['points'] = $averageStar ? $averageStar : 0;

        return array_merge($parentArray, $addArray);
    }

    protected function formatDates($array)
    {
        foreach ($array as $key => $value) {
            if (str_ends_with($key, '_at') && $value !== null) {
                $array[$key] = $this->$key->toDatetimeString();
            }
        }
        return $array;
    }
}
