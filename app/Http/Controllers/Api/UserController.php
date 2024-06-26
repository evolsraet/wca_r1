<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\UploadedFile;
use App\Traits\CrudControllerTrait;
use Database\Factories\UserFactory;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use CrudControllerTrait;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
        // print_r('userControllerTrait-');
        // print_r(config('auth.defaults.guard'));
        // die();
    }

    public function test(Request $request)
    {

        $user = User::factory()->make();
        $data['user'] = $user->makeVisible($user->getHidden())->toArray();
        $data['user']['role'] = 'user';

        $file = UploadedFile::fake()->create('testfile.pdf', 100); // 100KB 크기의 PDF 파일
        $data['file_sign'] = $file; // 여기서는 $data 배열에 직접 추가하는 대신, 파일을 요청에 별도로 추가합니다.

        // 리퀘스트에 직접 값을 할당한다
        $request->merge($data);
        return response()->api([
            $request->all(),
            $request->file(),
        ]);
        return $this->service->store($request);
    }

    public function defaultGuard()
    {
        return response()->api([
            config('auth.defaults.guard')
        ]);
    }

    public function confirmPassword(Request $request)
    {
        $inputPassword = $request->input('password');
        $user = $request->user();

        if (Hash::check($inputPassword, auth()->user()->password)) {
            return response()->api(null, '비밀번호가 일치합니다.', 'ok', 200);
        } else {
            return response()->api(null, '비밀번호가 일치하지 않습니다.', 'fail', 401);
        }
    }

    /**
     * @lrd:start
     * # 가능 파일
     * #   'file_user_photo' => '사진',
     * #   'file_user_biz'   => '사업자등록증',
     * #   'file_user_sign'  => '매도용인감증명',
     * #   'file_user_cert'  => '매매업체 대표증 / 종사원증',
     * #   'file_user_owner'  => '위임장/소유자 인감증명서',
     * @lrd:end
     */
    public function store(Request $request)
    {
        return $this->service->store($request);
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
