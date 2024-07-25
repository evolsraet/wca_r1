<?php

namespace App\Services;

use App\Models\Addressbook;
use App\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Collection;

class AddressbookService
{
    use CrudTrait;

    public function __construct()
    {
        $this->defaultCrudTrait('addressbook');
    }

    protected function middleProcess($method, $request, $item, $id = null)
    {
        // 최초 권한검사
        if (!auth()->check() || !auth()->user()->can('act.dealer')) {
            throw new \Exception('권한이 없습니다.');
        }

        // 메소드별 내용
        switch ($method) {
            case 'index':
                $this->whereDealerUser($item);
                break;
            case 'show':
                $this->whereDealerUser($item);
                break;
            case 'destroy':
                $this->whereDealerUserAfter($item);
                break;
            case 'update':
                $request = $this->beforeUpdateData($request);
                $this->whereDealerUserAfter($item);

                $validatedData = validator((array) $request, [
                    'name' => 'sometimes|string|max:255',
                    'addr_post' => 'sometimes|string|max:20',
                    'addr1' => 'sometimes|string|max:255',
                    'addr2' => 'sometimes|string',
                ])->validate();

                break;
            case 'store':
                $validatedData = validator((array) $request, [
                    'name' => 'required|string|max:255',
                    'addr_post' => 'required|string|max:20',
                    'addr1' => 'required|string|max:255',
                    'addr2' => 'nullable|string',
                ])->validate();

                $this->dataDealerUser($item);
                break;
        }
    }

    private function beforeUpdateData($request)
    {
        unset($request['user_id']);
        return $request;
    }

    private function whereDealerUser($item)
    {
        // 딜러면 where 본인아이디
        if (auth()->user()->hasRole('dealer')) {
            $item->where('user_id', auth()->user()->id);
        }
    }

    private function whereDealerUserAfter($item)
    {
        // print_r([
        //     '딜러아이디' => auth()->user()->id,
        //     '아이템아이디' => $item->user_id,
        // ]);
        // die();
        // 딜러면 where 본인아이디
        if (auth()->user()->hasRole('dealer') && $item->user_id != auth()->user()->id) {
            throw new \Exception('본인글이 아닙니다.');
        }
    }

    private function dataDealerUser($item)
    {
        // 딜러면 ->user_id = 본인아이디
        if (auth()->user()->hasRole('dealer')) {
            $item->user_id = auth()->user()->id;
        }
    }
}
