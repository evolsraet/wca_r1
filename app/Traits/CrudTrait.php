<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

trait CrudTrait
{
    public $modelClass = null;
    public $resourceClass = null;
    public $files = [];

    // 동적 모델 및 리소스 클래스 초기화
    public function defaultCrudTrait($modelName)
    {
        $modelName = Str::studly($modelName);
        $modelClass = "\\App\\Models\\" . ucfirst($modelName);
        $resourceClass = "\\App\\Http\\Resources\\" . ucfirst($modelName) . "Resource";

        if (!class_exists($modelClass) || !class_exists($resourceClass)) {
            throw new \Exception("Model or Resource class does not exist.");
        }

        $this->modelClass = $modelClass;
        $this->resourceClass = $resourceClass;
    }

    protected function getModelClass(): string
    {
        if (!property_exists($this, 'modelClass')) {
            throw new \Exception('모델이 생성되지 않았습니다.');
        }

        return $this->modelClass;
    }

    public function getModelName()
    {
        return Str::lower(class_basename($this->modelClass));
    }

    // 선처리 훅
    protected function beforeProcess($method, $request, $data = null, $id = null)
    {
        // 컨트롤러에서 이 메소드를 오버라이드하여 사용할 수 있습니다.
    }

    // 중간처리 훅
    protected function middleProcess($method, $request, $result, $id = null)
    {
        // 컨트롤러에서 이 메소드를 오버라이드하여 사용할 수 있습니다.
    }

    // 후처리 훅
    protected function afterProcess($method, $request, $result, $id = null)
    {
        // 컨트롤러에서 이 메소드를 오버라이드하여 사용할 수 있습니다.
    }

    public function index()
    {
        $paginate = request('paginate', 10);

        // 전처리
        $this->beforeProcess(__FUNCTION__, request());

        $orderColumn = request('order_column', 'created_at');
        // if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
        //     $orderColumn = 'created_at';
        // }

        $orderDirection = request('order_direction', 'desc');

        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }

        $modelClass = $this->getModelClass();
        $result = $modelClass::query();
        $this->middleProcess(__FUNCTION__, request(), $result);

        $result = $result->when(request('search_id'), function ($query) {
            $query->where('id', request('search_id'));
        })
            ->when(request('search_title'), function ($query) {
                $query->where('name', 'like', '%' . request('search_title') . '%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('name', 'like', '%' . request('search_global') . '%');
                });
            });


        // 연관 데이터 로딩
        // 예: 1:1 단수 (dealer), 1:N 복수 (roles)
        // ?with=dealer,roles
        $result = $result->when(request('with'), function ($query) {
            $relations = request('with') ? explode(',', request('with')) : [];
            $validRelations = array_filter($relations, function ($relation) {
                return method_exists($this->modelClass, $relation);
            });
            $query->with($validRelations);
        });

        // 조건문
        // 키 테이블명(복수).필드
        // 키:값 or 키:비교:값
        // ?where=users.id:<:10|users.id:>:1
        if (!empty(request('where'))) {
            $wheres = [];
            $exploded = explode('|', request('where'));

            foreach ((array) $exploded as $row) :
                $row = explode(':', $row);

                switch ($row[0]) {
                    case 'users.roles':
                        $result = $result->role($row[1]);
                        break;

                    default:
                        if (strpos($row[0], (new $this->modelClass)->getTable() . ".") !== false) {
                            // 동일테이블
                            if (isset($row[2])) {
                                $result = $result->where($row[0], $row[1], $row[2]);
                            } else {
                                $result = $result->where($row[0], $row[1]);
                            }
                        } else {
                            // 다른테이블
                            $findKey = explode('.', $row[0]); // 0 테이블 1 필드
                            $result->with($findKey[0]);

                            if (isset($row[2])) {
                                $result->whereHas($findKey[0], function ($qry) use ($findKey, $row) {
                                    // findKey[1]는 필드명, row[1]은 연산자, row[2]는 값
                                    $qry->where($findKey[1], $row[1], $row[2]); // 특정 조건에 맞는 관계를 필터링
                                });
                            } else {
                                $result->whereHas($findKey[0], function ($qry) use ($findKey, $row) {
                                    // findKey[1]는 필드명, row[1]은 값 (기본 연산자는 '=')
                                    $qry->where($findKey[1], '=', $row[1]); // 기본 연산자 '='를 명시적으로 사용
                                });
                            }
                        }
                        break;
                }
            endforeach;
        }
        // 후처리

        $this->afterProcess(__FUNCTION__, request(), $result);

        $result = $result->orderBy($orderColumn, $orderDirection)
            ->paginate($paginate);

        return response()->api($this->resourceClass::collection($result));
    }

    public function show($id)
    {
        $this->beforeProcess(__FUNCTION__, request());

        $modelClass = $this->getModelClass();
        $result = $modelClass::query();

        $this->middleProcess(__FUNCTION__, request(), $result);

        // 연관 데이터 로딩
        // 예: 1:1 단수 (dealer), 1:N 복수 (roles)
        // ?with=dealer,roles
        $result = $result->when(request('with'), function ($query) {
            $relations = request('with') ? explode(',', request('with')) : [];
            $validRelations = array_filter($relations, function ($relation) {
                return method_exists($this->modelClass, $relation);
            });
            $query->with($validRelations);
        });

        $result = $result->find($id);
        if (!$result) {
            throw (new ModelNotFoundException())->setModel($modelClass, $id);
        }
        $this->afterProcess(__FUNCTION__, request(), $result);

        return response()->api(new $this->resourceClass($result));
    }

    public function store(Request $request)
    {
        $this->beforeProcess(__FUNCTION__, request());
        $modelClass = $this->getModelClass();

        DB::beginTransaction();
        try {
            $data = $request->get($this->getModelName());
            $item = new $modelClass();

            // 상위 객체 데이터를 먼저 처리합니다.
            foreach ((array)$data as $key => $value) {
                // if (!is_array($value)) { // 배열이 아닌 경우 직접 할당
                $item->$key = $value;
                // }
            }

            $this->middleProcess(__FUNCTION__, request(), $item);
            $item->save(); // 상위 객체 저장

            // 하위 객체를 동적으로 처리합니다.
            foreach ((array) $data as $relationName => $relationData) {
                if (is_array($relationData) && method_exists($item, $relationName)) {
                    // relationName이 실제 모델의 관계 메서드와 일치하는 경우
                    $relation = $item->$relationName();

                    if ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne) {
                        // HasOne 관계인 경우, 하위 객체를 생성합니다.
                        $relation->create($relationData);
                    } elseif ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                        // HasMany 관계인 경우, 여러 하위 객체를 생성할 수 있습니다.
                        foreach ($relationData as $singleRelationData) {
                            $relation->create($singleRelationData);
                        }
                    }
                    // 여기에 더 많은 관계 타입에 대한 처리를 추가할 수 있습니다.
                }
            }

            $this->afterProcess(__FUNCTION__, request(), $item);

            DB::commit();
            return response()->api(new $this->resourceClass($item));
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        $this->beforeProcess(__FUNCTION__, request());

        $modelClass = $this->getModelClass();

        DB::beginTransaction();

        try {

            $modelClass = $this->getModelClass();
            $item = $modelClass::query();

            $item = $item->findOrFail($id);
            $data = request()->get($this->getModelName());

            foreach ($data as $key => $row) {
                // 하위 모델 자동 저장
                if (is_array($row)) {
                    $item->{$key}->update($row);
                } else {
                    $item->$key = $row;
                }
            }

            $this->middleProcess(__FUNCTION__, request(), $item, $id);
            $item->save();

            $this->afterProcess(__FUNCTION__, request(), $item);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // 오류 처리
            // return response()->api(json_encode($e));
            throw $e;
            // throw new \Exception('error', $e->getCode());
        }

        return response()->api(new $this->resourceClass($item));
    }

    public function destroy($id)
    {
        $this->beforeProcess(__FUNCTION__, request());

        $modelClass = $this->getModelClass();

        DB::beginTransaction();
        try {
            $item = $modelClass::findOrFail($id);
            $this->middleProcess(__FUNCTION__, request(), $item, $id);
            $item->delete();
            $this->afterProcess(__FUNCTION__, request(), null); // Item is deleted, so passing null

            DB::commit();
            return response()->api(null, null, 'ok', 204); // Consider using HTTP 204 for successful deletion with no content
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // 조건문이 있는지 검사 (쿼리)
    public function hasWhere($query)
    {
        $sql = $query->toSql();

        // 바인딩된 값 가져오기
        // $bindings = $query->getBindings();

        // Log::error($sql);
        // Log::error($bindings);

        // SQL 문자열과 바인딩에서 where 절 확인
        return strpos($sql, 'where') !== false;
        // if (strpos($sql, 'where') !== false) {
        //     echo "Where 절이 존재합니다.\n";
        // } else {
        //     echo "Where 절이 존재하지 않습니다.\n";
        // }
    }

    protected function modifyOnlyMe($data)
    {
        if (
            auth()->user()->id !== $data->user_id
            && !auth()->user()->hasPermissionTo('act.admin')
        ) {
            // dd([
            //     auth()->user()->id,
            //     $data->user_id
            // ]);
            throw new \Exception("권한이 없습니다.");
        }
    }
}
