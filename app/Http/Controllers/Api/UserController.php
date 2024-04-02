<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;


class UserController extends Controller
{
    use CrudControllerTrait;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->api(new UserResource($user));
    }

    public function abilities(Request $request)
    {
        return
            response()->api(
                $request->user()->roles()->with('permissions')
                    ->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->pluck('name')
                    ->unique()
                    ->values()
                    ->toArray()
            );
    }
}
