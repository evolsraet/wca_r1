<?php

namespace App\Services;

use App\Models\Like;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\LikeResource;

class LikeService
{
    use CrudTrait;

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('like');
    }

    protected function beforeProcess($method, $request, $data = null, $id = null)
    {
    }


    protected function middleProcess($method, $request, $result, $id = null)
    {

        switch ($method) {
            case 'index':
                // print_r(Like::where('likeable_id', 3)->where('likeable_type', 'auction')->with('likeable')->get()->toArray());
                // die();
            case 'show':
                // echo json_encode($request->all(), JSON_PRETTY_PRINT);  // JSON 형식으로 깔끔하게 출력
                // die();
                break;
            case 'update':
            case 'destroy':
                // 권한체크
                if (!auth()->user()->hasPermissionTo('act.admin')) {
                    if ($result && $result->user_id != auth()->user()->id) {
                        throw new \Exception('권한이 없습니다.');
                    }
                }
                break;
            case 'store':
                // 저장일때 자동 내 아이디 삽입
                $result->likeable_type = $this->typeToModel($result->likeable_type);
                $result->user_id = auth()->user()->id;
                break;
        }
    }
}
