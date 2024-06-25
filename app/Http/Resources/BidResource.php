<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class BidResource extends JsonResource
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
            // 'dealer_info' => $this->dealer, // dealer 정보
        ];

        // 날짜 필드를 Y-m-d 포맷으로 변환
        foreach ($parentArray as $key => $value) {
            if (str_ends_with($key, '_at') && $value !== null) {
                $parentArray[$key] = $this->$key->toDatetimeString();
            }
        }

        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        // $addArray['auth()->user()->id'] = auth()->user()->id == $parentArray['user_id'];
        if (
            auth()->user()->hasRole('admin')
            || auth()->user()->id == $parentArray['user_id']
            || auth()->user()->id == $this->auction->user_id
        ) {
            // 관리자나 본인이면 모두
        } else {
            // 아니면 갯수만 공개
            unset($parentArray['price'], $parentArray['user_id']);
        }

        return array_merge($parentArray, $addArray);
    }
}
