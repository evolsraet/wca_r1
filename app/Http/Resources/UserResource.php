<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\DealerResource;
use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $additionalArray = [];
        if (!request()->get('userForComment')) {
            $additionalArray['roles'] = $this->roles->pluck('name');
        }

        // 관리자 또는 본인일 경우 숨겨진 속성을 노출
        if (auth()->check() && (auth()->user()->hasPermissionTo('act.admin') || auth()->id() === $this->resource->id)) {
            // $hiddenAttributes = $this->resource->getHidden();
            // $this->resource->makeVisible($hiddenAttributes);
            $this->resource->makeVisible(['phone']);
        }
        $parentArray = $this->resource->toArray();

        // // if (Str::contains($request->get('with', ''), 'dealer')) {
        // $additionalArray['dealer'] = new DealerResource($this->dealer);
        // // }

        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        // 파일들
        $this->withFiles($parentArray, $additionalArray);

        // 날짜 필드를 Y-m-d 포맷으로 변환
        foreach ($parentArray as $key => $value) {
            if (str_ends_with($key, '_at') && $value !== null) {
                $parentArray[$key] = $this->$key->toDatetimeString();
            }
        }

        return array_merge($parentArray, $additionalArray);

        // $result = [
        //     'id'   => $this->id,
        //     'name' => $this->name,
        //     'email' => $this->email,
        //     'roles' => $this->roles->pluck('name'),
        //     // 'role_id' => $this->roles->pluck('name'),
        //     'dealer_info' => $this->dealer, // dealer 정보
        //     'created_at' => $this->created_at->toDateString()
        // ];

        // $result['role'] = $this->roles->pluck('name');
        // return $result;
    }
}
