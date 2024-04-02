<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DealerResource extends JsonResource
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
            // 'dealer_info' => $this->dealer, // dealer 정보
        ];

        // 날짜 필드를 Y-m-d 포맷으로 변환
        foreach ($parentArray as $key => $value) {
            if (str_ends_with($key, '_at') && $value !== null) {
                $parentArray[$key] = $this->$key->toDatetimeString();
            }
        }

        return array_merge($parentArray, $addArray);
    }
}
