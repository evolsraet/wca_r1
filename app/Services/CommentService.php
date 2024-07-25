<?php

namespace App\Services;

use App\Models\Like;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\LikeResource;

class CommentService
{
    use CrudTrait;

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('comment');
    }

    protected function beforeProcess($method, $request, $data = null, $id = null)
    {
        $order_column = request()->order_column ?? 'created_at';
        $order_direction = request()->order_direction ?? 'desc';

        if (!request()->has('order_column')) {
            request()->merge(['order_column' => $order_column]);
        }
        if (!request()->has('order_direction')) {
            request()->merge(['order_direction' => $order_direction]);
        }
    }

    protected function middleProcess($method, $request, $result, $id = null)
    {
        switch ($method) {
            case 'index':
                if (!$this->getRequest('where', 'comments.commentable_type', '|')) {
                    throw new \Exception('commentable_type 필수');
                }
                break;
            case 'show':
                if (!$this->getRequest('where', 'comments.commentable_type', '|')) {
                    throw new \Exception('commentable_type 필수');
                }
                break;
            case 'update':
                // 본인 글일때만 수정가능
                if ($result->getOriginal('user_id') != auth()->user()->id) {
                    throw new \Exception('권한이 없습니다. [서비스]');
                }
                break;
            case 'destroy':
                if (!$this->getRequest('where', 'comments.commentable_type', '|')) {
                    throw new \Exception('commentable_type 필수');
                }
                // 권한체크
                $this->modifyOnlyMe($result);
                break;
            case 'store':
                // commentable_type 필수
                if (!$request['commentable_type'] or !$request['commentable_id']) {
                    throw new \Exception('commentable_type 필수');
                }
                $result->commentable_type = $this->typeToModel($result->commentable_type);
                $result->user_id = auth()->user()->id;
                $result->ip = request()->header('X-Real-IP') ?? request()->ip();
                // $result->ip = file_get_contents('https://api.ipify.org');
                break;
        }
    }
}
