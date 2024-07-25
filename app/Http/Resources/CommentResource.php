<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\WithTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommentResource extends JsonResource
{
    use WithTrait;

    protected static $processedUsers = [];

    public function toArray($request)
    {
        $parentArray = parent::toArray($request);
        $addArray = [];

        // 관계 리소스로 리턴
        $this->relationResource($request, $parentArray);

        // 날짜 필드를 Y-m-d 포맷으로 변환
        foreach ($parentArray as $key => $value) {
            if (str_ends_with($key, '_at') && $value !== null) {
                $parentArray[$key] = $this->$key->toDatetimeString();
            }
        }

        // user 정보 처리
        // 리퀘스트에 userForComment 를 집어넣기
        request()->merge(['userForComment' => 1]);
        $parentArray['user'] = $this->processUser($request);

        // comment_type, comment_id 지우고
        // unset($parentArray['commentable_type']);
        // unset($parentArray['commentable_id']);

        return array_merge($parentArray, $addArray);
    }

    protected function processUser($request)
    {
        $userId = $this->user_id;

        // 이미 처리된 사용자인 경우 캐시된 결과 반환
        if (!isset(self::$processedUsers[$userId])) {
            self::$processedUsers[$userId] = new UserResource($this->user);
        }

        return self::$processedUsers[$userId];
    }
}
