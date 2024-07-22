<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Services\ArticleService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class ArticleController extends Controller
{
    use CrudControllerTrait;

    protected $model;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, $boardId)
    {
        return $this->service->index();
    }

    public function show($boardId, $id)
    {
        return $this->service->show($id);
    }

    public function store(Request $request, $boardId)
    {
        // print_r($request->all());
        // die();
        return $this->service->store($request);
    }

    public function update(Request $request, $boardId, $id)
    {
        // print_r([
        //     'boardId' => $boardId,
        //     'id' => $id,
        // ]);
        // die();
        return $this->service->update($id, $request);
    }

    public function destroy($boardId, $id)
    {
        return $this->service->destroy($id);
    }
}
