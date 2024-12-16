<?php

namespace App\Http\Resources\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * API 리소스 변환을 위한 트레이트
 *
 * 이 트레이트는 Laravel API 리소스에서 관계(relations)와 미디어 파일을 처리하는 기능을 제공합니다.
 *
 * 주요 기능:
 * - 리소스 캐싱 및 로드된 관계 추적
 * - URL 파라미터 'with'를 통한 동적 관계 로딩
 * - 관계된 모델의 리소스 변환
 * - 미디어 파일 처리 및 그룹화
 *
 * 사용 예시:
 * - ?with=comments,user,media 와 같은 URL 파라미터를 통해 필요한 관계를 동적으로 로드
 * - 미디어 파일은 collection_name으로 그룹화되어 반환
 */
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