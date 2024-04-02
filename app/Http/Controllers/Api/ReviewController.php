<?php

namespace App\Http\Controllers\Api;

use App\Services\ReviewService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    use CrudControllerTrait;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }
}
