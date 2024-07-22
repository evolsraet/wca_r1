<?php

namespace App\Http\Controllers\Api;

use App\Traits\CrudControllerTrait;
use App\Services\BoardService;
use App\Http\Controllers\Controller;

class BoardController extends Controller
{
    use CrudControllerTrait;

    protected $model;

    public function __construct(BoardService $service)
    {
        $this->service = $service;
    }
}
