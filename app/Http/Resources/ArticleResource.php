<?php

namespace App\Http\Resources;

use App\Models\Bid;
use App\Models\Comment;
use App\Http\Resources\BidResource;
use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Auction;

class ArticleResource extends JsonResource
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

        $addArray['comments_count'] = Comment::where('commentable_type', 'App\Models\Article')->where('commentable_id', $this->id)->count();

        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        // 날짜 필드를 Y-m-d 포맷으로 변환
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];
        foreach ($timestampFields as $field) {
            if (isset($this->resource->$field)) {
                $parentArray[$field] = $this->$field->toDatetimeString();
            }
        }

        // extra1 에 값이 있을 경우 auction_id 와 매칭 해서 데이터 추가 
        if (isset($this->resource->extra1)) {
            $auction = Auction::find($this->resource->extra1);
            $addArray['auction'] = $auction;
        }

        $this->withFiles($parentArray, $addArray);
        return array_merge($parentArray, $addArray);
    }
}
