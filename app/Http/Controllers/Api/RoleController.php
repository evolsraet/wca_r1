<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\Auth\Access\AuthorizationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $paginate = 50;
        $model = new Role;;

        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $list = $model::when(request('search_id'), function ($query) {
            $query->where('id', request('search_id'));
        })
            ->when(request('search_title'), function ($query) {
                $query->where('name', 'like', '%' . request('search_title') . '%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('name', 'like', '%' . request('search_global') . '%');
                });
            })
            ->orderBy($orderColumn, $orderDirection);

        // throw  new \Exception('error test');

        if (!request('all'))
            $list = $list->paginate($paginate);
        else
            $list = $list->get();

        return response()->api(RoleResource::collection($list));
        return RoleResource::collection($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RoleResource
     */
    public function store(StoreRoleRequest $request)
    {
        $this->authorize('role.super');

        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = 'web';

        if ($role->save()) {
            return response()->api(new RoleResource($role));
        }

        return response()->api($data = null, $message = null, $status = 'fail', $code = 405);
        // return response()->json(['status' => 405, 'success' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return RoleResource
     */
    public function show(Role $role)
    {
        $this->authorize('role.super');

        return response()->api(new RoleResource($role));
        // return new RoleResource($role);
        // return ResponseHelper::apiResponse(true, new RoleResource($role));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Role $role
     * @param StoreRoleRequest $request
     * @return RoleResource
     * @throws AuthorizationException
     */
    public function update(Role $role, StoreRoleRequest $request)
    {
        $this->authorize('role.super');

        $role->name = $request->name;

        if ($role->save()) {
            return response()->api(new RoleResource($role));
            // return new RoleResource($role);
        }
        return response()->api($data = null, $message = null, $status = false, $code = 405);
        // return response()->json(['status' => 405, 'success' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $this->authorize('role.super');
        $role->delete();

        return response()->api();
        // return response()->noContent();
    }

    // public function getList()
    // {
    //     return RoleResource::collection(Role::all());
    // }
}
