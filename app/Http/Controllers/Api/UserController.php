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

class UserController extends Controller
{
    use CrudControllerTrait;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
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