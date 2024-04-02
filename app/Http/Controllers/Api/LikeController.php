<?php

namespace App\Http\Controllers\Api;

use App\Services\LikeService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    use CrudControllerTrait;

    public function __construct(LikeService $service)
    {
        $this->service = $service;
    }
}
