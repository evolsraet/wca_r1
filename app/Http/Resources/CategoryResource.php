<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        return [
            'id'   => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->toDateString()
        ];
    }
}
