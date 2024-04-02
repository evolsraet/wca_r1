<?php

namespace App\Http\Controllers\Api;

use App\Services\AuctionService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;

class AuctionController extends Controller
{
    use CrudControllerTrait;

    public function __construct(AuctionService $service)
    {
        $this->service = $service;
    }
}
