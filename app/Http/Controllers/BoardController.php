<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * 게시글 목록 페이지
     */
    public function list($boardId)
    {
        $board = Board::findOrFail($boardId);
        return view("v2.boards.{$board->skin}.list", compact('board'));
    }

    /**
     * 게시글 상세 페이지
     */
    public function view($boardId, $articleId)
    {
        $board = Board::findOrFail($boardId);
        return view("v2.boards.{$board->skin}.view", compact('board', 'articleId'));
    }

    /**
     * 게시글 작성/수정 페이지
     */
    public function form($boardId, $articleId = null)
    {
        $board = Board::findOrFail($boardId);
        return view("v2.boards.{$board->skin}.form", compact('board', 'articleId'));
    }
}
