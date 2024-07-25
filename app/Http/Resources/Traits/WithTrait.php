<?php

namespace App\Http\Resources\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait WithTrait
{
    protected $cachedResource;
    protected $loadedRelations = [];

    protected function getCachedResource()
    {
        if (!$this->cachedResource) {
            $this->cachedResource = $this->resource;
            $this->loadedRelations = array_keys($this->cachedResource->getRelations());
            Log::debug('로드된 관계: ' . implode(', ', $this->loadedRelations));
        }
        return $this->cachedResource;
    }

    protected function relationResource($request, &$parentArray)
    {
        $withRelations = $request->query('with');
        if ($withRelations) {
            $relations = explode(',', $withRelations);

            foreach ($relations as $relation) {
                Log::debug("관계 처리 중: {$relation}");

                if ($relation == 'likeable') {
                    $relation = Str::studly(Str::singular(class_basename($parentArray['likeable_type'])));
                }

                $this->handleRelation($relation, $parentArray);
            }
        }
    }

    protected function handleRelation($relation, &$parentArray)
    {
        $resource = $this->getCachedResource();
        if (in_array($relation, $this->loadedRelations)) {
            Log::debug("로드된 관계 처리 중: {$relation}");
            $singularRelation = Str::singular($relation);
            $resourceClass = '\\App\\Http\\Resources\\' . ucfirst($singularRelation) . 'Resource';
            if (class_exists($resourceClass)) {
                $relatedResource = $resource->$relation;
                $parentArray[$relation] = $relatedResource instanceof \Illuminate\Database\Eloquent\Collection
                    ? $resourceClass::collection($relatedResource)
                    : new $resourceClass($relatedResource);
            } else {
                $parentArray[$relation] = '리소스 클래스 에러: ' . $resourceClass;
            }
        } else {
            Log::debug("관계가 로드되지 않음: {$relation}");
        }
    }

    protected function withFiles(&$parentArray, &$addArray)
    {
        $resource = $this->getCachedResource();
        if (in_array('media', $this->loadedRelations)) {
            Log::debug("미디어 파일 처리 중");
            $addArray['files'] = $resource->media->groupBy('collection_name')
                ->map(function ($group) {
                    return $group->map(function ($media) {
                        return $media->toArray();
                    });
                });
            unset($parentArray['media']);
        } else {
            Log::debug("미디어가 로드되지 않음");
        }
    }
}
