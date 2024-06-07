<?php

namespace App\Http\Resources;

use App\Models\Bid;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parentArray = parent::toArray($request);
        $addArray = [
            // 'roles' => $this->roles->pluck('name'),
            // 'bids' => BidResource::collection(),
        ];

        // 더 업데이트

        // 날짜 필드를 Y-m-d 포맷으로 변환
        foreach ($parentArray as $key => $value) {
            // datetime
            if ($key == 'final_at' && isset($this->final_at)) {
                $parentArray['final_at'] = '2025-01-01 12:30';
            } elseif (str_ends_with($key, '_at') && $value !== null) {
                $parentArray[$key] = $this->$key->toDatetimeString();
            }
        }



        // 상위 5개 입찰건
        if ($parentArray['status'] != 'ask') {
            $addArray['bids_count'] = Bid::where('auction_id', $parentArray['id'])->count();

            $bidsQuery = Bid::where('auction_id', $parentArray['id'])
                ->orderBy('price', 'desc')
                ->limit(5)
                ->get();

            // 완료시 금액 공개
            if ($parentArray['status'] == 'done') {
                if ($parentArray['bid_id']) {
                    $addArray['win_bid'] = new BidResource(Bid::find($parentArray['bid_id']));
                }
                $addArray['top_bids'] = $bidsQuery->makeHidden(['user_id']);
            } elseif ($parentArray['status'] == 'ing') {
                $addArray['top_bids'] = $bidsQuery->makeHidden(['price', 'user_id']);
            } else {
                $addArray['top_bids'] = $bidsQuery;
            }
        }


        return array_merge($parentArray, $addArray);
    }
}
