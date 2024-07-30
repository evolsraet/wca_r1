<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class DealerResource extends JsonResource
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

        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        // 날짜 필드를 Y-m-d 포맷으로 변환
        foreach ($parentArray as $key => $value) {
            if (str_ends_with($key, '_at') && $value !== null) {
                $parentArray[$key] = $this->$key->toDatetimeString();
            }
        }

        // 관리자나 본인이 아닐경우, phone, birthday 속성 제거
        if (
            !auth()->check() or
            (!auth()->user()->hasPermissionTo('act.admin') && auth()->user()->id !== $this->id)
        ) {
            unset($parentArray['phone']);
            unset($parentArray['birthday']);
        }

        return array_merge($parentArray, $addArray);
    }
}
