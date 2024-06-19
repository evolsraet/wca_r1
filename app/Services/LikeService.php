<?php

namespace App\Services;

use App\Models\Like;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\LikeResource;

class LikeService
{
    use CrudTrait;

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('like');
    }

    protected function middleProcess($method, $request, $result, $id = null)
    {
        // 권한체크
        if ($method == 'update' or $method == 'destroy') {
            if (!auth()->user()->hasPermissionTo('act.admin')) {
                if ($result && $result->user_id != auth()->user()->id) {
                    throw new \Exception('권한이 없습니다.');
                }
            }
        }

        // 저장일때 자동 내 아이디 삽입
        if ($method == 'store') {
            $result->user_id = auth()->user()->id;
        }
    }
}
