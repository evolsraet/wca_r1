<?php

namespace App\Http\Resources;

use App\Models\Bid;
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
        $parentArray = parent::toArray($request);
        $addArray = [
            // 'roles' => $this->roles->pluck('name'),
            // 'bids' => BidResource::collection(),
        ];

        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        // 날짜 필드를 Y-m-d 포맷으로 변환
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];
        foreach ($timestampFields as $field) {
            if (isset($this->resource->$field)) {
                $parentArray[$field] = $this->$field->toDatetimeString();
            }
        }

        $addArray['bids_count'] = Bid::where('auction_id', $parentArray['id'])->count();

        // 상위 5개 입찰건
        if ($parentArray['status'] != 'ask') {

            $bidsQuery = Bid::where('auction_id', $parentArray['id'])
                ->orderBy('price', 'desc')
                ->limit(5)
                ->get();

            // 완료시 금액 공개
            if ($parentArray['status'] == 'done' or $parentArray['status'] == 'chosen') {
                if ($parentArray['bid_id']) {
                    $addArray['win_bid'] = new BidResource(Bid::find($parentArray['bid_id']));
                }
            }

            $addArray['top_bids'] = BidResource::collection($bidsQuery);
        }


        return array_merge($parentArray, $addArray);
    }
}
