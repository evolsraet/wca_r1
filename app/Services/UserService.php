<?php

namespace App\Services;

use App\Models\Role;
// use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UserService
{
    use CrudTrait;

    // TODO: 모든 user_id 해시?

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('user');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->input('user');
            $data = $this->checkJson($data);
            $dealerData = $this->checkJson($request->input('dealer'));

            // print_r($request->all());
            // print_r($data);
            // die();

            // Validator 인스턴스 생성
            // $validator = Validator::make($data, [
            //     'name' => 'required|max:255',
            //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //     'phone' => 'required',
            //     'password' => ['required', 'string', 'min:8', 'confirmed'],
            // ]);

            $data = $this->beforeData($data);

            // 유효성 검사 실패 시
            // if ($validator->fails()) {
            //     return response()->api(null, null, 'fail', 422, ['errors' => $validator->errors()]);
            // }

            $requestData = (array) $data;
            $requestData['model'] = new User;  // $result는 모델 인스턴스를 가리킵니다.

            $validatedData = validator($requestData, [
                'name' => 'required|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => 'required',
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ])->validate();


            // 본인회원가입 - 사용자일경우
            if (!auth()->check() && $data['role'] == 'user') {
                $data['status'] = 'ok';
            }

            // dd([gettype($data), $data, $request->file()]);
            $item = User::create($data);

            // 하위 모델
            if ($data['role'] == 'dealer') {
                $dealerData = $dealerData;
                $dealerData['user_id'] = $item->id;
                $item->dealer()->create($dealerData);
            }

            $model = new User;
            $file_result = [];
            foreach ($model->files as $key => $row) {
                if ($request->hasFile($key)) {
                    $files = $request->file($key);
                    // 파일이 배열이 아닌 경우 배열로 변환
                    if (!is_array($files)) {
                        $files = [$files];
                    }
                    foreach ($files as $file) {
                        // $files_one 배열에 있는 파일들은 자동으로 기존 파일을 삭제하고 새 파일로 대체됨
                        $file_result[] = $item->addMedia($file)->preservingOriginal()->toMediaCollection($key);
                    }
                }
            }

            // 가능한 권한
            $ableRole = $this->ableRoles();

            if ($data['role'] && in_array($data['role'], $ableRole)) {
                $item->assignRole($data['role']);
            } else {
                $item->assignRole('user');
            }

            DB::commit();

            return response()->api(
                (new UserResource($item))
                    ->additional([
                        'file_data' => $file_result
                    ])
            );
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        // // // TODO: put 에서 파일 안넘어옴
        // print_r('user_update_');
        // print_r($request->file());
        // die();

        DB::beginTransaction();

        try {
            $item = User::query();

            $item = $item->findOrFail($id);

            $this->modifyAuth($item);

            $data = $request->input('user');
            $data = $this->checkJson($data);
            $data = $this->beforeData($data);

            $data['dealer'] = $this->checkJson($request->input('dealer'));

            // print_r('업데이트 메소드');
            // print_r($request->all());
            // print_r($data);
            // die();

            // $dealerData = null;
            // $dealerData = $this->checkJson($request->input('dealer'));

            // if (isset($data['dealer'])) {
            //     $dealerData = $this->checkJson($data['dealer']);
            //     unset($data['dealer']);
            // }

            $model = new User;

            if ($item->hasRole('dealer') && $data['dealer']) {
                // $item->dealer()->updateOrCreate 는 제대로 기능안함. create 만 하려고함
                if ($item->dealer()) {
                    // 기존 Dealer 업데이트
                    $item->dealer()->update($data['dealer']);
                    // TODO: 변경불가 사항 업데이트 시 상태변경 (누구에게 알림?)
                } else {
                    $validator = Validator::make($data['dealer'], [
                        'name' => 'required',
                        'phone' => 'required',
                        'birthday' => 'required',
                        'company' => 'required',
                        'company_duty' => 'required',
                        'company_post' => 'required',
                        'company_addr1' => 'required',
                        'company_addr2' => 'required',
                        'receive_post' => 'required',
                        'receive_addr1' => 'required',
                        'receive_addr2' => 'required',
                        'introduce' => 'required',
                    ]);

                    // 유효성 검사 실패 시
                    if ($validator->fails()) {
                        return response()->api(null, null, 'fail', 422, ['errors' => $validator->errors()]);
                    }

                    // 새 Dealer 생성
                    $item->dealer()->create($data['dealer']);
                    // TODO: 알림 : 누구에게?

                }

                $item->load('dealer');
            }
            unset($data['dealer']);


            if ($data) {
                $item->update($data);
            }

            $file_result = [];
            $logs = [];
            foreach ($model->files as $key => $row) {
                if ($request->hasFile($key)) {
                    $files = $request->file($key);
                    // 파일이 배열이 아닌 경우 배열로 변환
                    if (!is_array($files)) {
                        $files = [$files];
                    }
                    foreach ($files as $file) {
                        // $files_one 배열에 있는 파일들은 자동으로 기존 파일을 삭제하고 새 파일로 대체됨
                        $file_result[] = $item->addMedia($file)->preservingOriginal()->toMediaCollection($key);
                    }
                }
            }

            // 역할 지정
            // 기본 패키지는 복수지만, 현 서비스에서는 하나만 지정한다
            if (auth()->user()->hasPermissionTo('act.admin') && $data) {
                if (isset($data['role'])) {
                    // 이름으로 역할 찾기
                    $role = Role::where('name', $data['role'])->first();
                    // error - findByName($name, $guard = 디폴트 auth.defaults.guard) 인데, 어느순간 sanctum 으로 변경되어 사용이 불가하다.
                    // $role = Role::findByName($data['role']);

                    $item->syncRoles($role);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return response()->api(
            (new UserResource($item))
                ->additional([
                    // 'file_result' => $file_result, // media 로 추가된 부분 응답이 온다
                    // '_logs' => $logs,
                    // '_post_files' => $request->files,
                ])
        );

        // return response()->api(new UserResource($item));
    }

    public function checkJson($data)
    {
        if (gettype($data) == 'string') {
            $data = json_decode($data, true);
        }

        return $data;
    }

    public function beforeData($data)
    {
        // 관리자 전용 수정
        if (!auth()->check() or !auth()->user()->hasPermissionTo('act.admin')) {
            if (isset($data['status']))
                unset($data['status']);
        }

        if (isset($data['password_confirmation']))
            unset($data['password_confirmation']);

        // fillable 로 대체
        // unset($data['role']);
        // unset($data['dealer']);

        return $data;
    }

    public function ableRoles()
    {
        $ableRole = ['user', 'dealer'];
        if (auth()->user() && auth()->user()->hasPermissionTo('act.admin'))
            $ableRole += ['admin'];

        return $ableRole;
    }


    protected function modifyAuth($data)
    {
        if (
            auth()->user()->id !== $data->id
            && !auth()->user()->hasPermissionTo('act.admin')
        ) {
            throw new \Exception("권한이 없습니다.");
        }
    }

    protected function beforeProcess($method, $request, $id = null)
    {
    }

    protected function middleProcess($method, $request, $item, $id = null)
    {
        if ($method === 'index') {
            // $item->with('role');
            if (!auth()->user()->hasPermissionTo('act.admin')) {
                $item->where('id', auth()->user()->id);
                $item->role(['user', 'dealer']);
            }
        } elseif ($method === 'show') {
            if (
                !auth()->user()->hasPermissionTo('act.admin')
            ) {
                $item->role(['user', 'dealer']);
            }
        } elseif ($method === 'destroy') {
            $this->modifyAuth($item);
        }
    }
}
