<?php

namespace App\Services;

use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AuctionResource;

class AuctionService
{
    use CrudTrait;

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('auction');
    }

    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $data = $request->input('auction');
    //         $data = $this->beforeData($data);

    //         // 유저만 등록
    //         if (!auth()->check() OR !auth()->user()->hasRole('user')) {
    //             throw new \Exception('권한이 없습니다.');
    //         }

    //         $item = AuctionResource::create($data);

    //         DB::commit();
    //         return response()->api(new AuctionResource($item));
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         throw $e;
    //     }
    // }

    // public function beforeData($data) {
    //     return $data;
    // }

    protected function beforeProcess($method, $request, $id = null)
    {
        if ($method == 'store') {
            if (!auth()->check() or !auth()->user()->hasRole('user')) {
                throw new \Exception('권한이 없습니다.');
            }
        }
    }

    protected function middleProcess($method, $request, $result, $id = null)
    {
        if ($method == 'index' or $method == 'show') {
            // 관리자 딜러 아니면 본인것만
            if (auth()->user()->hasPermissionTo('act.admin')) {
            } elseif (auth()->user()->hasPermissionTo('act.dealer')) {
                $result->where('status', 'ing');
            } else {
                $result->where('user_id', auth()->user()->id);
            }
        } elseif ($method == 'store') {
            // 사용자 아이디 지정
            $result->user_id = auth()->user()->id;
            $result->status = 'ask';
        } elseif ($method == 'update') {
            $this->modifyOnlyMe($result);
            unset($result->user_id);
        } elseif ($method == 'destroy') {
            $this->modifyOnlyMe($result);
        }
    }
}
