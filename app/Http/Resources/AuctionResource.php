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

        // with 부분 리소스로 리턴
        $withRelations = $request->query('with');
        if ($withRelations) {
            $relations = explode(',', $withRelations);
            foreach ($relations as $relation) {
                if ($this->relationLoaded($relation)) {
                    $resourceClass = '\\App\\Http\\Resources\\' . ucfirst($relation) . 'Resource';
                    if (class_exists($resourceClass)) {
                        $parentArray[$relation] = new $resourceClass($this->$relation);
                    }
                }
            }
        }

        // 더 업데이트

        // 날짜 필드를 Y-m-d 포맷으로 변환
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];
        foreach ($timestampFields as $field) {
            if (isset($this->resource->$field)) {
                $parentArray[$field] = $this->$field->toDatetimeString();
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
            if ($parentArray['status'] == 'done' or $parentArray['status'] == 'chosen') {
                if ($parentArray['bid_id']) {
                    $addArray['win_bid'] = new BidResource(Bid::find($parentArray['bid_id']));
                }
            }

            if (auth()->user()->hasRole('admin') or auth()->user()->id == $parentArray['user_id']) {
                // 관리자나 본인이면 모두
                $addArray['top_bids'] = BidResource::collection($bidsQuery);
            } else {
                // 아니면 갯수만 공개
                $addArray['top_bids_text'] = '갯수만 공개';
                $addArray['top_bids'] = BidResource::collection($bidsQuery)->map(function ($bid) {
                    return (new BidResource($bid))->makeHidden(['price', 'user_id']);
                });
            }
        }


        return array_merge($parentArray, $addArray);
    }
}
