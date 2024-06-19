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
