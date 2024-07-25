<?php

namespace App\Http\Controllers\Api;

use App\Services\CommentService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    use CrudControllerTrait;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }
}
