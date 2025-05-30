<?php

namespace App\Services;

use App\Models\Bid;
// use Spatie\Permission\Models\Role;
use App\Models\Role;
use App\Models\User;
use App\Models\Auction;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Jobs\UserRegisteredJob;
use App\Jobs\UaerDealerStatusJob;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    use CrudTrait;

    // TODO: 모든 user_id 해시?

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('user');
    }

    protected function beforeProcess($method, $request, $id = null)
    {
        $this->addRequest('with', 'media');

        if (request()->input('mode') === 'excelDown' && !auth()->check()) {
            throw new \Exception('로그인이 필요합니다.');
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->input('user');
            $data = $this->checkJson($data);
            $data['socialLogin'] = isset($data['socialLogin']) ? $data['socialLogin'] : false;
            $data['role'] = isset($data['role']) ? $data['role'] : 'user';

            $dealerData = $this->checkJson($request->input('dealer'));

            // return response()->api(null, ''.count( $request->file() ), 'fail', 400, [
            //         '_FILES' => $_FILES,
            //         'request->file()' => $request->file(),
            //         'request->all()' => $request->all(),
            //     ]
            // );

            // print_r([
            //     '_FILES' => $_FILES,
            //     'request->file()' => $request->file(),
            //     'request->all()' => $request->all(),
            // ]);

            // print_r($data);
            // die();

            // Validator 인스턴스 생성
            // $validator = Validator::make($data, [
            //     'name' => 'required|max:255',
            //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //     'phone' => 'required',
            //     'password' => ['required', 'string', 'min:8', 'confirmed'],
            // ]);

            $requestData = (array) $data;
            $requestData['model'] = new User;  // $result는 모델 인스턴스를 가리킵니다.

            // beforeData() 이전에 발리데이트 해야함

            if ($data['socialLogin'] !== 'true') {
                $validator = Validator::make($requestData, [
                    'name' => 'required|max:255',
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'phone' => ['required', 'string', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    'isCheckPrivacy' => 'required',
                ]);
            }else{
                $validator = Validator::make($requestData, [
                    'name' => 'required|max:255',
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'phone' => ['required', 'string', 'unique:users'],
                    'isCheckPrivacy' => 'required',
                ]);
            }

            // 유효성 검사 실패 시
            if ($validator->fails()) {
                return response()->api(null, '입력값을 확인하세요.', 'fail', 422, ['errors' => ['user' => $validator->errors()]]);
            }

            $data = $this->beforeData($data);

            if($data['socialLogin'] === 'true'){
                $data['password'] = Hash::make(Str::random(32));
            }

            // dd($data);

            unset($data['socialLogin']);

            // 유효성 검사 실패 시
            // if ($validator->fails()) {
            //     return response()->api(null, null, 'fail', 422, ['errors' => $validator->errors()]);
            // }




            // 본인회원가입 - 사용자일경우
            // if (!auth()->check() && $data['role'] == 'user') {
            if ($data['role'] == 'user') {
                $data['status'] = 'ok';
            }

            // dd($data);

            // dd([gettype($data), $data, $request->file()]);
            unset($data['isCheckPrivacy']);
            $item = User::create($data);

            // 회원가입 알림 발송
            if($data['role'] == 'user' && $item){
                UserRegisteredJob::dispatch($item, 'user');
            }

            // 하위 모델
            if ($data['role'] == 'dealer') {
                $dealerData = $dealerData;

                // print_r($dealerData);
                // die();

                $dealerData['user_id'] = $item->id;


                $requiredFiles = [
                    'file_user_photo',
                    'file_user_biz',
                    'file_user_cert',
                    'file_user_sign',
                ];

                $requiredFileNames = (
                        request()->headers->get('referer')
                        && str_contains(request()->headers->get('referer'), 'v2')
                        ? [
                            'isCheckDealer1' => 'required',
                            'isCheckDealer2' => 'required',
                            'isCheckDealer3' => 'required',
                            'isCheckDealer4' => 'required',
                        ]
                        : [
                            'file_user_photo_name' => 'required',
                            'file_user_biz_name' => 'required',
                            'file_user_cert_name' => 'required',
                            'file_user_sign_name' => 'required',
                        ]
                    );

                $validator = Validator::make($dealerData, [
                    'name' => 'required',
                    'phone' => 'required',
                    'birthday' => 'required|digits:6',
                    'company' => 'required',
                    'company_duty' => 'required',
                    'company_post' => 'required',
                    'company_addr1' => 'required',
                    'company_addr2' => 'required',
                    'introduce' => 'required',
                    'car_management_business_registration_number' => 'required',
                    'business_registration_number' => 'required',
                    'corporation_registration_number' => 'required',
                    ...$requiredFileNames,
                ]);

                // 유효성 검사 실패 시
                if ($validator->fails()) {
                    return response()->api(null, '입력값을 확인하세요.', 'fail', 422, ['errors' => ['dealer' => $validator->errors()]]);
                }

                $remindFiles = [];
                foreach($requiredFiles as $key){
                    if( !$request->hasFile($key) ){
                        $remindFiles[$key][] = '파일을 첨부하세요';
                    }
                }
                if( count($remindFiles) > 0 ){
                    return response()->api(null, '파일을 첨부하세요.', 'fail', 422, ['errors' => $remindFiles]);
                }

                unset($dealerData['file_user_photo_name']);
                unset($dealerData['file_user_biz_name']);
                unset($dealerData['file_user_cert_name']);
                unset($dealerData['file_user_sign_name']);
                unset($dealerData['isCheckDealer1']);
                unset($dealerData['isCheckDealer2']);
                unset($dealerData['isCheckDealer3']);
                unset($dealerData['isCheckDealer4']);

                $dealerData['biz_check'] = 0;
                $dealer = $item->dealer()->create($dealerData);

                // 회원가입 알림 발송
                if($dealer){
                    UserRegisteredJob::dispatch($item, 'dealer');
                }
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
                        'file_result' => $file_result
                    ])
            );
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        // print_r('user_update_');
        // print_r($request->file());
        // die();

        DB::beginTransaction();

        try {
            $item = User::query();

            $item = $item->findOrFail($id);

            // print_r($item->dealer()->exists() ? 'true' : 'false');
            // die();

            // return response()->api(null, ''.count( $request->file() ), 'fail', 400, [
            //         '_FILES' => $_FILES,
            //         'request->file()' => $request->file(),
            //         'request->all()' => $request->all(),
            //     ]
            // );

            $this->modifyAuth($item);

            $data = $request->input('user');
            $data = $this->checkJson($data);


            if ($data) {

                Log::info('[유저] 상태 업데이트', [
                    'name'=> '유저 상태 업데이트',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'data' => $data
                ]);

                // 승인여부 업데이트
                if(auth()->user()->hasPermissionTo('act.admin') && $item->hasRole('dealer')){
                    $user = User::find($item->id);
                    if($data['status'] == 'ok'){
                        Log::info('[유저] 딜러 상태 업데이트', [
                            'name'=> '유저 딜러 상태 업데이트',
                            'path'=> __FILE__,
                            'line'=> __LINE__,
                            'user_id' => $item->id,
                            'status' => 'ok'
                        ]);
                        UaerDealerStatusJob::dispatch($user, 'ok');
                    }else if($data['status'] == 'reject'){
                        Log::info('[유저] 딜러 상태 업데이트', [
                            'name'=> '유저 딜러 상태 업데이트',
                            'path'=> __FILE__,
                            'line'=> __LINE__,
                            'user_id' => $item->id,
                            'status' => 'reject'
                        ]);
                        UaerDealerStatusJob::dispatch($user, 'reject');
                    }
                }

                if( $data['password'] == null ) {
                    unset($data['password']);
                }

                $validator = Validator::make($data, [
                    'name' => 'sometimes|required|max:255',
                    'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users'],
                    'phone' => 'sometimes|required',
                    'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
                ]);
                // 유효성 검사 실패 시
                if ($validator->fails()) {
                    return response()->api(null, '입력값을 확인하세요.', 'fail', 422, ['errors' => ['user' => $validator->errors()]]);
                }


                unset($data['password_confirmation']);

                $item->update($data);
            }

            // $validatedData = validator($data, [
            //     'name' => 'sometimes|required|max:255',
            //     'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users'],
            //     'phone' => 'sometimes|required',
            //     'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
            // ])->validate();

            $data = $this->beforeData($data);
            $data['dealer'] = $this->checkJson($request->input('dealer'));

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


            // print_r('업데이트 메소드');
            // print_r($request->all());

            // $dealerData = null;
            // $dealerData = $this->checkJson($request->input('dealer'));

            // if (isset($data['dealer'])) {
            //     $dealerData = $this->checkJson($data['dealer']);
            //     unset($data['dealer']);
            // }

            $model = new User;

            if ($item->hasRole('dealer') && $data['dealer']) {
                // $item->dealer()->updateOrCreate 는 제대로 기능안함. create 만 하려고함

                if ($item->dealer()->exists()) {
                    // 딜러 모델 로드
                    $item->load('dealer');
                    $dealer = $item->dealer;

                    // 업데이트 전 상태 저장
                    $dealer->fill($data['dealer']);

                    // 변경사항이 있는지 확인
                    if ($dealer->isDirty()) {
                        // 기존 Dealer 업데이트
                        $item->dealer()->update(values: $data['dealer']);
                    }
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
                        'introduce' => 'required',
                    ]);
                    // 유효성 검사 실패 시
                    if ($validator->fails()) {
                        return response()->api(null, '입력값을 확인하세요.', 'fail', 422, ['errors' => ['dealer' => $validator->errors()]]);
                    }

                    $item->dealer()->create($data['dealer']);
                }
            }
            unset($data['dealer']);

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

            DB::commit();

            if( $item->dealer && $item->dealer->isDirty() ) {
                $item->status = 'ask';
                $item->save();
                auth()->user()->tokens()->delete(); // API 토큰 삭제
                auth()->logout(); // 세션 로그아웃

                UaerDealerStatusJob::dispatch($item, 'ask');
                return response()->api(['redirect' => '/', 'dirty' => $item->dealer->getDirty()], '딜러 정보가 변경되었습니다. 재심사 후 로그인 가능합니다.', 'ok', 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return response()->api(
            (new UserResource($item))
                ->additional([
                    'file_result' => $file_result, // media 로 추가된 부분 응답이 온다
                    // '_logs' => $logs,
                    // '_post_files' => $request->files,
                ])
        );

        // return response()->api(new UserResource($item));
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
        // throw new \Exception(auth()->user()->id . ' / ' . $data->id);
        if (
            auth()->user()->id !== $data->id
            && !auth()->user()->hasPermissionTo('act.admin')
        ) {
            throw new \Exception("권한이 없습니다.");
        }
    }

    protected function middleProcess($method, $request, $item, $id = null)
    {
        if ($method === 'index') {
            $this->readAuth($item);
        } elseif ($method === 'show') {
            $this->readAuth($item);
        } elseif ($method === 'destroy') {
            $this->modifyAuth($item);
        }
    }

    protected function readAuth($item)
    {
        $item->with('roles');

        if (auth()->check() && !auth()->user()->hasPermissionTo('act.admin')) {
            $item->role(['user', 'dealer']);

            // 딜러는 본인만, 유저는 본인과 모든 딜러
            if (auth()->user()->can('act.dealer')) {
                $item->where('id', auth()->user()->id);
            } elseif (auth()->user()->can('act.user')) {

                $item->where(function ($query) {
                    $query
                        ->role('dealer')
                        ->orWhere('id', auth()->user()->id);
                });
            }
        }
    }
}
