<?php

namespace App\Http\Controllers\Api;

use App\Services\CacheService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;

class CacheController extends Controller
{
    use CrudControllerTrait;

    public function __construct(CacheService $service)
    {
        $this->service = $service;
    }
}
