<?php

namespace App\Services;

use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BidResource;
use App\Http\Resources\AuctionResource;

class AuctionService
{
    use CrudTrait;

    // TODO: 옥션에 재경매 횟수 기록

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('auction');

        // IP제한
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

    /**
     * 중간 처리를 수행하는 함수입니다.
     *
     * @param string $method 메소드 이름
     * @param mixed $request 요청 객체
     * @param mixed $result 결과 객체
     * @param mixed $id ID 값 (기본값: null)
     * @return void
     */



    protected function middleProcess($method, $request, $result, $id = null)
    {
        if ($method == 'index' || $method == 'show') {
            if (auth()->user()->hasPermissionTo('act.admin')) {
                // 관리자 권한을 가진 사용자인 경우 아무 작업도 수행하지 않습니다.
            } elseif (auth()->user()->hasRole('dealer')) {
                // 딜러 권한을 가진 사용자인 경우
                // 1. status가 ing인 경매 항목.
                // 2. bids 관계에서 user_id가 현재 인증된 사용자의 ID인 경매 항목.

                $result->where(function ($query) {
                    $query->where('status', 'ing')
                        ->orWhereHas('bids', function ($subQuery) {
                            $subQuery->where('user_id', auth()->user()->id);
                        });
                });

                // $result->with('bids');

                // $result->with(['bids' => function ($query) {
                //     $query->where('user_id', auth()->user()->id)->get()->map(function ($bid) {
                //         return new BidResource($bid);
                //     });
                // }]);
            } else {
                // 일반 사용자인 경우 자신이 등록한 경매만 조회합니다.
                $result->where('user_id', auth()->user()->id);
            }
        } elseif ($method == 'store') {
            // TODO: 본인인증 검증 추가
            $requestData = (array) $request;
            $requestData['model'] = $result;  // $result는 모델 인스턴스를 가리킵니다.

            $validatedData = validator($requestData, [
                'owner_name' => 'required',
                'car_no' => 'required',
                'region' => 'required',
                'addr_post' => 'required',
                'addr1' => 'required',
                'addr2' => 'required',
            ])->validate();

            // 경매 등록 메소드인 경우 사용자 ID와 상태를 설정합니다.
            $result->user_id = auth()->user()->id;
            $result->status = 'ask';
        } elseif ($method == 'update') {
            // 경매 업데이트 메소드인 경우 자신의 정보만 수정할 수 있도록 제한합니다.
            $this->modifyOnlyMe($result);
            unset($result->user_id);

            // TODO: 상황별 업데이트 처리

            // print_r('middle');
            // print_r($result->toArray());
            // print_r($result->getDirty());
            // die();
        } elseif ($method == 'destroy') {
            // 경매 삭제 메소드인 경우 자신의 정보만 삭제할 수 있도록 제한합니다.
            $this->modifyOnlyMe($result);
        }
    }


    protected function afterProcess($method, $request, $result, $id = null)
    {
        if ($method == 'show') {
            // Auction 의 hit 를 +1 하기
            $result->increment('hit');
        } elseif ($method == 'update') {

            // print_r('after');
            // print_r($result->toArray());
            // print_r($result->getChanges());
            // die();
        }
    }
}
