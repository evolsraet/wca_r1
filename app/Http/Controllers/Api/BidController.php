<?php

namespace App\Http\Controllers\Api;

use App\Services\BidService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;

class BidController extends Controller
{
    use CrudControllerTrait;

    public function __construct(BidService $service)
    {
        $this->service = $service;
    }
}
