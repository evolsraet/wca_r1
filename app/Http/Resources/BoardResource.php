<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
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

        return array_merge($parentArray, $addArray);
    }
}
