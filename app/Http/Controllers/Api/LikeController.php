<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\LikeService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    use CrudControllerTrait;

    public function __construct(LikeService $service)
    {
        $this->service = $service;
    }

    public function toggle($likeable_type_model, $likeable_id)
    {
        // $request = request();
        $request = request()->merge([
            'like' => [
                'likeable_type' => $likeable_type_model,
                'likeable_id' => $likeable_id
            ],
        ]);

        // print_r($request->toArray());
        // die();

        $like = Like::where('likeable_id', $likeable_id)
            ->where('likeable_type', $this->service->typeToModel($likeable_type_model))
            ->where('user_id', auth()->user()->id)
            ->first();

        if ($like) {
            // Like 객체가 존재하면 해당 ID로 destroy 메소드 호출
            return $this->service->destroy($like->id);
        } else {
            // Like 객체가 존재하지 않으면 store 메소드 호출
            return $this->service->store($request);
        }
    }
}
