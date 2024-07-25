<?php

namespace App\Http\Controllers\Api;

use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;
use App\Services\AddressbookService;

class AddressbookController extends Controller
{
    use CrudControllerTrait;

    protected $model;

    public function __construct(AddressbookService $service)
    {
        $this->service = $service;
    }
}
