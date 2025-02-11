<?php

namespace App\Http\Controllers\Api;

use App\Services\ReviewService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    use CrudControllerTrait;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }

}
