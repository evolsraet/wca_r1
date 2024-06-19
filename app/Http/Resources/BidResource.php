<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BidResource extends JsonResource
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

        // 날짜 필드를 Y-m-d 포맷으로 변환
        foreach ($parentArray as $key => $value) {
            if (str_ends_with($key, '_at') && $value !== null) {
                $parentArray[$key] = $this->$key->toDatetimeString();
            }
        }

        return array_merge($parentArray, $addArray);
    }
}
