<?php

namespace App\Http\Resources\Traits;

use Illuminate\Support\Str;

trait WithTrait
{
    protected function relationResource($request, &$parentArray)
    {
        $withRelations = $request->query('with');
        if ($withRelations) {
            $relations = explode(',', $withRelations);

            foreach ($relations as $relation) {

                // print_r($relation);
                // print_r($parentArray);
                // die();

                if ($relation == 'likeable') {
                    $relation = Str::studly(Str::singular(class_basename($parentArray['likeable_type'])));
                }

                // print_r($relation);
                // print_r($parentArray);
                // die();

                $this->handleRelation($relation, $parentArray);
            }
        }
    }

    protected function handleRelation($relation, &$parentArray)
    {
        if ($this->relationLoaded($relation)) {
            $singularRelation = Str::singular($relation);  // 'bids' -> 'bid'
            $resourceClass = '\\App\\Http\\Resources\\' . ucfirst($singularRelation) . 'Resource';
            if (class_exists($resourceClass)) {
                $relatedResource = $this->$relation;
                $parentArray[$relation] = $relatedResource instanceof \Illuminate\Database\Eloquent\Collection
                    ? $resourceClass::collection($relatedResource)
                    : new $resourceClass($relatedResource);
            } else {
                $parentArray[$relation] = '리소스 클래스 에러: ' . $resourceClass;
            }
        }
    }
}
