<?php

namespace App\Services;

use App\Models\Like;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\LikeResource;
use Illuminate\Support\Facades\Log;
use App\Models\Article;
use App\Jobs\ClaimJob;

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

                Log::info('[댓글] 수정 알림 전송 ' . $result->id, [
                    'name'=> '댓글 수정 알림 전송',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'result' => $result
                ]);
                $article = Article::find($result->commentable_id);
                if($article->board_id === 'claim'){
                    $user = $article->user_id;
                    ClaimJob::dispatch($user, $result, 'comment_update');
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

                // 댓글 알림 전송
                Log::info('[댓글] 알림 전송 ' . $result->id, [
                    'name'=> '댓글 알림 전송',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'result' => $result
                ]);
                $article = Article::find($result->commentable_id);
                if($article->board_id === 'claim'){
                    $user = $article->user_id;
                    ClaimJob::dispatch($user, $result, 'comment');
                }

                break;
        }
    }
}
