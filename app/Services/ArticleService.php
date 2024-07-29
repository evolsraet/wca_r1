<?php

namespace App\Services;

use App\Models\Board;
use App\Models\Article;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ArticleResource;

class ArticleService
{
    use CrudTrait;

    public $board;

    public function __construct()
    {
        $boardId = request()->route('board') ?? 'notice';
        $this->board = Board::findOrFail($boardId);
        // print_r([
        //     $boardId,
        //     $this->board
        // ]);
        // die();

        // CrudTrait
        $this->defaultCrudTrait('article');
        // print_r([
        //     $this->tableName,
        //     $this->modelClass,
        //     $this->resourceClass,
        // ]);
        // die();
    }

    protected function middleProcess($method, $request, $data = null, $id = null)
    {
        $article = null;
        if ($id)
            $article = Article::findOrFail($id);

        if (request()->has('where')) {
            request()->merge(['where' => request()->where . "|articles.board_id:{$this->board->id}"]);
        } else {
            request()->merge(['where' => "articles.board_id:{$this->board->id}"]);
        }

        switch ($method) {
            case 'index':
                $permission = $this->board->index_permission;

                // 클레임 - 본인것만
                if ($this->board->id === 'claim') {
                    // 로그인 확인
                    if (!auth()->check() || !auth()->user()->can('act.dealer')) {
                        throw new \Exception('권한이 없습니다.');
                    }

                    // 관리자 외 본인것만
                    if (!auth()->user()->can('act.admin')) {
                        $where = request()->has('where') ? request()->where . '|' : '';
                        request()->merge(['where' => $where . "articles.user_id:" . auth()->user()->id]);
                    }
                }

                // 게시판 권한
                if (
                    $permission != null
                    || (auth()->check() && auth()->user()->cannot($permission))
                ) {
                    throw new \Exception('권한이 없습니다.');
                }

                break;
            case 'show':
                $permission = $this->board->show_permission;

                // 클레임 - 본인것만
                if ($this->board->id === 'claim') {
                    if (!auth()->user()->can('act.admin')) {
                        request()->merge(['where' => request()->where . "|articles.user_id:" . auth()->user()->id]);
                    }
                }

                // 게시판 권한
                if ($permission && auth()->user()->cannot($permission)) {
                    throw new \Exception('권한이 없습니다.');
                }

                // 비밀글
                if (
                    $article->is_secret
                    && !($article->user_id == auth()->user()->id || !auth()->user()->can('act.admin'))
                ) {
                    throw new \Exception('비밀글입니다.');
                }
                break;
            case 'store':
                $permission = $this->board->write_permission;
                // Log::info(request()->all());

                // store 의 $request 는 배열
                $data->board_id = $this->board->id;
                $data->user_id = auth()->user()->id;
                if ($this->board->id === 'claim' && !auth()->user()->can('act.admin')) {
                    $data->category = '접수';
                }

                // 발리데이션
                $validateCondition = [];
                $validateCondition['title'] = 'required|string|max:255';

                if ($this->board->id === 'claim') {
                    $validateCondition['extra1'] = 'required|numeric';
                } elseif ($this->board->categories) {
                    $validateCondition['category'] = 'required|string';
                }

                $validatedData = validator((array) $request, $validateCondition)
                    ->validate();

                // 로그인
                if (!auth()->check()) {
                    throw new \Exception('로그인이 필요합니다.');
                }
                // 게시판 권한
                if ($permission && auth()->user()->cannot($permission)) {
                    throw new \Exception('권한이 없습니다.');
                }
                break;
            case 'update':
                $permission = $this->board->write_permission;
                $data->where('board_id', $this->board->id);
                // 로그인
                if (!auth()->check()) {
                    throw new \Exception('로그인이 필요합니다.');
                }
                // 게시판 권한
                if ($permission && auth()->user()->cannot($permission)) {
                    throw new \Exception('권한이 없습니다.');
                }
                // 관리자 또는 본인 글만 수정삭제 가능
                if (!auth()->user()->can('act.admin') && $article->user_id != auth()->user()->id) {
                    throw new \Exception('관리자나 본인 글만 수정삭제 가능합니다.');
                }

                break;
            case 'destroy':
                $permission = $this->board->write_permission;
                $data->where('board_id', $this->board->id);
                // 로그인
                if (!auth()->check()) {
                    throw new \Exception('로그인이 필요합니다.');
                }
                // 게시판 권한
                if ($permission && auth()->user()->cannot($permission)) {
                    throw new \Exception('권한이 없습니다.');
                }
                // 관리자 또는 본인 글만 수정삭제 가능
                if (!auth()->user()->can('act.admin') && $article->user_id != auth()->user()->id) {
                    throw new \Exception('관리자나 본인 글만 수정삭제 가능합니다.');
                }

                break;
        }
    }
}
