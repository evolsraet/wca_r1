<?php

namespace App\Services;

use App\Models\Role;
use App\Traits\CrudTrait;
use Illuminate\Support\Facades\Log;

class UserService
{
    use CrudTrait;

    public function __construct()
    {
        // CrudTrait
        $this->defaultCrudTrait('user');
    }

    protected function beforeProcess($method, $request, $id = null)
    {
    }

    protected function middleProcess($method, $request, $result, $id = null)
    {
        // 삽입, 업댓 시 패스워드가 있으면 암호화
        if ($method === 'store' or $method === 'update') {
            // unset($result->role); // 롤 네임은 뒤에서 처리
        }

        if ($method === 'index') {
            if (!auth()->user()->hasPermissionTo('act.admin')) {
                $result->where('id', auth()->user()->id);
                $result->role(['user', 'dealer']);
            }
        }

        if ($method === 'show') {
            if (
                !auth()->user()->hasPermissionTo('act.admin')
            ) {
                $result->role(['user', 'dealer']);
            }
        }

        if ($method == 'update') {
            $result->where('id', 10);
        }

        // 업데이트, 삭제는 본인 or 관리자만
        if ($method == 'update' or $method == 'destroy') {
            if (
                auth()->user()->id !== $result->id
                && !auth()->user()->hasPermissionTo('act.admin')
            ) {
                throw new \Exception("권한이 없습니다.");
            }
        }
    }

    protected function afterProcess($method, $request, $result)
    {
        // 삽입 : 롤 아이디가 있으면 적용, 없다면 user
        if ($method === 'store') {
            if ($request->input('user.role')) {
                $result->assignRole($request->input('user.role'));
            } else {
                $result->assignRole('user');
            }
        } elseif ($method === 'update') {
            if (auth()->user()->hasPermissionTo('act.admin') && $request->input('user.role')) {

                if ($request->input('user.role')) {
                    $role = Role::findByName($request->input('user.role'));
                    $result->syncRoles($role);
                }
            }
        }
    }
}
