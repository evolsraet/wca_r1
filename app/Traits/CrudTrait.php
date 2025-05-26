<?php

namespace App\Traits;

use Illuminate\Support\Str;
use App\Exports\ModelExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Api\LibController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Review;

trait CrudTrait
{
    public $modelClass = null;
    public $resourceClass = null;
    public $tableName = null;
    public $files = [];

    // 동적 모델 및 리소스 클래스 초기화
    public function defaultCrudTrait($modelName)
    {
        $modelName = Str::studly($modelName);
        $modelClass = "\\App\\Models\\" . ucfirst($modelName);
        $resourceClass = "\\App\\Http\\Resources\\" . ucfirst($modelName) . "Resource";

        if (!class_exists($modelClass) || !class_exists($resourceClass)) {
            throw new \Exception("모델 또는 리소스 클래스가 없습니다. Crud Trait");
        }

        $this->modelClass = $modelClass;
        $this->resourceClass = $resourceClass;
        $this->tableName = (new $modelClass)->getTable();
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
        // 컨트롤러에서 이 메소드�� 오버라이드하여 사용할 수 있습니다.
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

        $orderColumns = explode(',', request('order_column', 'id'));
        $orderDirections = explode(',', request('order_direction', 'desc'));

        $modelClass = $this->getModelClass();
        $modelInstance = new $modelClass; // 모델 인스턴스 생성
        $result = $modelClass::query();

        foreach ($orderColumns as $index => $orderColumn) {
            $orderDirection = $orderDirections[$index] ?? 'desc'; // 기본값 'desc'

            if (strpos($orderColumn, '.') !== false) {
                $this->applyRelationOrder($modelInstance, $result, $orderColumn, $orderDirection);
            } else {
                $result = $result->orderBy($orderColumn, $orderDirection);
            }
        }

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

        $this->middleProcess(__FUNCTION__, request(), $result);

        // 검색어
        $search_text = request()->input('search_text');
        if ($search_text && $searchable = $modelInstance->searchable) {
            $result = $result->where(function ($query) use ($search_text, $searchable) {
                foreach (explode(',', $search_text) as $search) {
                    // print_r([
                    //     request()->input('search_text'),
                    //     $search_text,
                    //     $searchable,
                    // ]);
                    // die();
                    foreach ($searchable as $column) {
                        $query->orWhere($this->tableName . '.' . $column, 'like', '%' . $search . '%');
                    }
                }
            });
        }
        
        if($search_text && request('with') == 'dealer') {
            
            $query = Review::select('reviews.*')->join('auctions', 'reviews.auction_id', '=', 'auctions.id');
            $query->where(function($q) use ($search_text) {
                $q->where('reviews.content', 'like', '%' . $search_text . '%')
                ->orWhere('auctions.car_model', 'like', '%' . $search_text . '%')
                ->orWhere('auctions.car_no', 'like', '%' . $search_text . '%')
                ->orWhere('auctions.addr1', 'like', '%' . $search_text . '%');
            });

            // 검색 결과 페이지네이션
            $result = $query->with('dealer');

        }

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


        // 연관데이터 없는것들
        // with 와 사용법 동
        // ?doesnthave=reviews
        $result = $result->when(request('doesnthave'), function ($query) {
            $relations = request('doesnthave') ? explode(',', request('doesnthave')) : [];
            foreach ($relations as $relation) {
                if (method_exists($this->modelClass, $relation)) {
                    $query->doesntHave($relation);
                }
            }
        });

        // 삭제데이터도 포함
        $result = $result->when(request('withTrashed'), function ($query) {
            $query->withTrashed();
        });

        // 조건문
        // 키 테이블명(복수).필드
        // 키:값 or 키:비교:값
        // ?where=users.id:<:10|users.id:>:1


        $this->whereRun($result);

        // 후처리
        $this->afterProcess(__FUNCTION__, request(), $result);

        // mode=count 체크
        if (request('mode') === 'count') {
            $count = $result->count();
            return response()->api(['count' => $count]);
        } elseif (request('mode') === 'excelDown') {
            $columns = (new LibController)->fields($this->tableName);

            // columns가 JsonResponse 객체인 경우 배열로 변환
            if ($columns instanceof \Illuminate\Http\JsonResponse) {
                $columns = $columns->getData(true);
                $columns = $columns['data'];
            }

            // print_r($columns['data']);
            // die();

            $data = [];
            $result->chunk(1000, function ($records) use (&$data) {
                foreach ($records as $record) {
                    $resourceData = (new $this->resourceClass($record))->toArray(request());

                    // 각 필드를 문자열로 변환
                    $rowData = [];
                    foreach ($resourceData as $key => $value) {
                        if (is_array($value) || is_object($value)) {
                            $rowData[$key] = json_encode($value);
                        } else {
                            $rowData[$key] = (string) $value;
                        }
                    }

                    $data[] = $rowData;
                }
            });

            // columns가 배열인지 확인
            if (!is_array($columns)) {
                throw new \Exception('Columns must be an array');
            }

            $now = now()->format('YmdHis');
            return Excel::download(new ModelExport($data, $columns), "{$this->tableName}-{$now}.xlsx");
        } else {
            $result = $result->paginate($paginate);
            return response()->api($this->resourceClass::collection($result));
        }
    }

    private function whereRun($result)
    {
        if (!empty(request('where'))) {
            // 그룹을 지정한다.
            // 예시: ?where=users.id:<:10|users.id:>:1_and_users.name:like:%홍길동%_or_users.id:>:20

            // 웨어그룹 아이템은 [웨어문들복합] 또는 그룹을 묶는 [or,and] 로 나위어 있다.
            $whereGroup = preg_split('/(_and_|_or_)/', request('where'), -1, PREG_SPLIT_DELIM_CAPTURE);
            $whereGroupFunction = 'where';

            foreach ($whereGroup as $whereGroupItem) {
                // whereGroupItem 값에 따라 쿼리 그룹화 방식을 결정합니다.
                // '_or_'이면 orWhere 그룹, '_and_'이면 where 그룹을 사용합니다.

                switch ($whereGroupItem) {
                    case '_or_':
                        $whereGroupFunction = 'orWhere';
                        continue 2;
                        break;
                    case '_and_':
                        $whereGroupFunction = 'where';
                        continue 2;
                        break;
                }

                $wheres = explode('|', $whereGroupItem);

                $result->{$whereGroupFunction}(function ($query) use ($wheres) {
                    $this->parseWhereClause($query, $wheres);
                });
            }
        }
    }

    private function parseWhereClause($query, $wheres)
    {
        foreach ((array) $wheres as $onewhere) :
            $row = explode(':', $onewhere);
            if (!isset($row[1])) {
                continue;
            }

            $fieldWithTable = $row[0];
            $operator = isset($row[2]) ? $row[1] : null;
            $value = $row[2] ?? $row[1];
            if ($value == '_null') {
                $value = null;
            }

            // where 와 기타 구분
            $whereFunction = 'where';

            // where 펑션이 바뀌어야할 경우
            if ($operator) {
                switch ($operator) {
                    case 'whereIn':
                    case 'orWhere':
                        $whereFunction = $operator;
                        $operator = null;
                        $value = explode(',', $value);
                        break;
                    case 'like':
                        if (strpos($value, '%') === false) {
                            $value = '%' . $value . '%';
                        }
                        break;
                }
            }

            // 값 변경
            switch ($fieldWithTable) {
                case 'likes.likeable_type':
                    $value = Str::camel($value);
                    break;
                default:
                    break;
            }



            // 조건처리
            switch ($fieldWithTable) {
                case 'users.roles':
                    $result = $query->role($value);
                    break;

                default:
                    if (strpos($fieldWithTable, (new $this->modelClass)->getTable() . ".") !== false) {
                        // 동일테이블
                        $this->addWhere($fieldWithTable, $operator, $value, $whereFunction, $query);
                    } else {
                        // 다른테이블
                        $tableAndField = explode('.', $fieldWithTable); // 0 테이블 1 필드
                        $query->with($tableAndField[0]);

                        $query->whereHas($tableAndField[0], function ($subQuery) use ($tableAndField, $operator, $value, $whereFunction) {
                            // $qry->$whereFunction($tableAndField[1], $operator, $value); // 특정 조건에 맞는 관계를 필터링
                            $this->addWhere($tableAndField[1], $operator, $value, $whereFunction, $subQuery);  // 특정 조건에 맞는 관계를 필터링
                        });
                    }
                    break;
            }
        endforeach;
    }

    private function addWhere($fieldWithTable, $operator, $value, $whereFunction, $qry)
    {
        if ($operator)
            $qry = $qry->$whereFunction($fieldWithTable, $operator, $value);
        else
            $qry = $qry->$whereFunction($fieldWithTable, $value);
    }

    private function applyRelationOrder($modelInstance, $query, $orderColumn, $orderDirection)
    {
        list($tableName, $columnName) = explode('.', $orderColumn);
        $relationMethod = Str::camel($tableName);

        if ($this->tableName == $tableName) {
            $query->orderBy($orderColumn, $orderDirection);
        } elseif (method_exists($modelInstance, $relationMethod)) {
            $relation = $modelInstance->$relationMethod();

            if ($relation && method_exists($relation, 'getRelated')) {
                $relationModel = $relation->getRelated();
                $foreignKey = $relation->getForeignKeyName();
                $relatedTable = $relationModel->getTable();

                $query->join($relatedTable, $relatedTable . '.id', '=', $foreignKey)
                    ->select($modelInstance->getTable() . '.*', $relatedTable . '.' . $columnName . ' AS _related_' . $columnName)
                    ->orderBy('_related_' . $columnName, $orderDirection);
            } else {
                throw new \Exception("Invalid relation method or missing getRelated method.");
            }
        } else {
            throw new \Exception("Relation method {$relationMethod} does not exist on model {$this->modelClass}.");
        }
    }

    public function show($id)
    {
        $this->beforeProcess(__FUNCTION__, request(), $data = null, $id);

        $modelClass = $this->getModelClass();
        $result = $modelClass::query();

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

        $this->middleProcess(__FUNCTION__, request(), $result, $id);
        $this->whereRun($result);
        $result = $result->find($id);

        if (!$result) {
            throw (new ModelNotFoundException())->setModel($modelClass, $id);
        }
        $this->afterProcess(__FUNCTION__, request(), $result);

        return response()->api(new $this->resourceClass($result));
    }

    public function store(Request $request)
    {
        $this->beforeProcess(__FUNCTION__, $request);
        $modelClass = $this->getModelClass();

        DB::beginTransaction();
        try {
            $data = $request->get($this->getModelName());
            $data = $this->checkJson($data);
            $data = $this->beforeStoreData($data);
            $item = new $modelClass();

            // 상위 객체 데이터를 먼저 처리합니다.
            foreach ((array)$data as $key => $value) {
                // if (!is_array($value)) { // 배열이 아닌 경우 직접 할당
                $item->$key = $value;
                // }
            }

            $this->middleProcess(__FUNCTION__, $data, $item);
            $item->save(); // 상위 객체 저장

            // print_r($item->toArray());
            // die();

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

            $this->afterProcess(__FUNCTION__, $request, $item);

            // 파일
            $model = new $modelClass();
            $file_result = [];
            foreach ((array) (array) $model->files as $key => $row) {
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
            return response()->api(
                (new $this->resourceClass($item))
                    ->additional([
                        'file_result' => $file_result, // media 로 추가된 부분 응답이 온다
                        // '_logs' => $logs,
                        // '_post_files' => $request->files,
                    ])
            );
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        // print_r('crudTrait');
        // print_r(config('auth.defaults.guard'));
        // die();

        $this->beforeProcess(__FUNCTION__, request());

        $modelClass = $this->getModelClass();

        DB::beginTransaction();

        try {

            $modelClass = $this->getModelClass();
            $item = $modelClass::query();

            $item = $item->findOrFail($id);

            $data = $request->get($this->getModelName());
            $data = $this->checkJson($data);
            $data = $this->beforeUpdateData($data);

            foreach ((array) $data as $key => $row) {
                // 하위 모델 자동 저장
                if (is_array($row)) {
                    $item->{$key}()->upsert($row, ['id'], array_keys($row));
                    unset($data->$key);
                } else {
                    $item->$key = $row;
                }
            }

            $this->middleProcess(__FUNCTION__, $request, $item, $id);
            $item->save();

            $this->afterProcess(__FUNCTION__, request(), $item);

            // 파일
            $file_result = [];
            $model = new $modelClass();
            foreach ((array) $model->files as $key => $row) {
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
        } catch (\Exception $e) {
            DB::rollback();
            // 오류 처리
            // return response()->api(json_encode($e));
            throw $e;
            // throw new \Exception('error', $e->getCode());
        }

        return response()->api(
            (new $this->resourceClass($item))
                ->additional([
                    'file_result' => $file_result, // media 로 추가된 부분 응답이 온다
                    // '_logs' => $logs,
                    // '_post_files' => $request->files,
                ])
        );
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
            return response()->api(null, '삭제되었습니다.', 'ok', 200); // Consider using HTTP 204 for successful deletion with no content
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

        // SQL 문자열과 바인딩에서 where 절 확인
        return strpos($sql, 'where') !== false;
        // if (strpos($sql, 'where') !== false) {
        //     echo "Where 절이 존재합니다.\n";
        // } else {
        //     echo "Where 절이 존재하지 않습니다.\n";
        // }
    }

    protected function modifyOnlyMe($data, $force = false)
    {
        if (
            !$force
            && auth()->user()->id !== $data->user_id
            && !auth()->user()->hasPermissionTo('act.admin')
        ) {
            // dd([
            //     auth()->user()->id,
            //     $data->user_id
            // ]);
            throw new \Exception("권한이 없습니다.");
        }
    }

    public function checkJson($data)
    {
        if (gettype($data) == 'string') {
            $data = json_decode($data, true);
        }

        return $data;
    }

    private function beforeUpdateData($request)
    {
        return $request;
    }

    private function beforeStoreData($request)
    {
        return $request;
    }

    // 리퀘스트에 추가
    private function addRequest($which, $val, $separate = ',')
    {
        $current = request()->get($which, '');
        $changeArray = array_filter(explode($separate, $current));
        if (!in_array($val, $changeArray)) {
            $changeArray[] = $val;
        }
        request()->merge([$which => implode($separate, $changeArray)]);
    }

    private function modifyRequest($which, $key, $newValue, $separate = ',')
    {
        $current = request()->get($which, '');
        $itemArray = array_filter(explode($separate, $current));

        foreach ($itemArray as $index => $item) {
            if (strpos($item, $key) === 0) {
                $parts = explode(':', $item);
                if (count($parts) >= 2) {
                    $parts[1] = $newValue;
                    $itemArray[$index] = implode(':', $parts);
                }
            }
        }

        request()->merge([$which => implode($separate, $itemArray)]);
    }

    private function getRequest($which, $key, $separate = ',')
    {
        $current = request()->get($which);
        $itemArray = explode($separate, $current);

        foreach ($itemArray as $item) {
            $parts = explode(':', $item);
            if ($parts[0] === $key) {
                return $item;
            }
        }

        return null;
    }

    protected function typeToModel($type)
    {
        return 'App\\Models\\' . ucfirst($type);
    }
}
