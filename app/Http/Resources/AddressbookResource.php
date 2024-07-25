<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressbookResource extends JsonResource
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
        $addArray = [];

        // 날짜 필드를 Y-m-d 포맷으로 변환
        foreach ($parentArray as $key => $value) {
            if (str_ends_with($key, '_at') && $value !== null) {
                $parentArray[$key] = $this->$key->toDatetimeString();
            }
        }
        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        return array_merge($parentArray, $addArray);
    }
}
