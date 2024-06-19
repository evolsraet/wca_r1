<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $additionalArray = [
            'roles' => $this->roles->pluck('name'),
        ];

        if ((auth()->user()) && auth()->user()->hasPermissionTo('act.admin')) {
            // $parentArray[]
        }

        // // if (Str::contains($request->get('with', ''), 'dealer')) {
        // $additionalArray['dealer'] = new DealerResource($this->dealer);
        // // }

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

        // 파일들
        $additionalArray['files'] = count($this->getMedia('*')) > 0 ? $this->getMedia('*') : null;

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
