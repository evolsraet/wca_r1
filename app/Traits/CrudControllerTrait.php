<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\LibController;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ModelExport;

trait CrudControllerTrait
{
    public $service = null;

    public function index()
    {
        return $this->service->index();
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function update($id, Request $request)
    {
        // web 에서 sanctum 으로 바뀌는 시점
        // print_r('crudControllerTrait-');
        // print_r(config('auth.defaults.guard'));
        // die();

        return $this->service->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
