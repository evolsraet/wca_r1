<?php

namespace App\Http\Resources;

use App\Models\Bid;
use Illuminate\Support\Str;
use App\Http\Resources\BidResource;
use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class AuctionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    use WithTrait;

    public function toArray($request)
    {
        // 모델 인스턴스를 변수에 저장
        $auction = $this->resource;

        $parentArray = parent::toArray($request);
        $addArray = [];

        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        // 날짜 필드를 Y-m-d 포맷으로 변환
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];
        foreach ($timestampFields as $field) {
            if (isset($auction->$field)) {
                $parentArray[$field] = $auction->$field->toDatetimeString();
            }
        }
        $addArray['bids_count'] = $this->whenLoaded('bids', function () use ($auction) {
            return $auction->bids->count();
        }, function () use ($auction) {
            return $auction->bids()->count();
        });

        // 상위 5개 입찰건
        if ($parentArray['status'] != 'ask') {
            $addArray['top_bids'] = $this->whenLoaded('topBids', function () use ($auction) {
                return BidResource::collection($auction->topBids);
            });

            if (in_array($parentArray['status'], ['done', 'chosen', 'dlvr'])) {
                $addArray['win_bid'] = $this->whenLoaded('winBid', function () use ($auction) {
                    return new BidResource($auction->winBid, $auction->user_id);
                });
            }
        }

        // 파일들
        $this->withFiles($parentArray, $addArray);
        return array_merge($parentArray, $addArray);
    }
}
