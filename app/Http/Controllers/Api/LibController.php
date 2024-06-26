<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\MediaService;

class LibController extends Controller
{
    public function fields($table)
    {
        $columns = DB::select(
            "SELECT COLUMN_NAME,
                COALESCE(NULLIF(COLUMN_COMMENT, ''), COLUMN_NAME) AS COLUMN_COMMENT
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = ?
                AND TABLE_NAME = ?",
            [env('DB_DATABASE'), $table]
        );

        $modelName = ucfirst(Str::singular($table)); // 예: 'users' -> 'User'
        $modelClass = "App\\Models\\" . $modelName; // 네임스페이스 포함 전체 클래스 이름

        if (class_exists($modelClass)) {
            $model = new $modelClass;

            // 모델 인스턴스에서 $enums 변수가 존재하는지 확인
            if (!property_exists($model, 'files')) {
                $model->files = [];
            }
        } else {
            throw new \Exception("{$modelName} 모델 클래스를 찾을 수 없습니다.");
        }

        // 결과를 배열로 변환
        $columnsArray = collect($columns)->mapWithKeys(function ($item) {
            return [$item->COLUMN_NAME => $item->COLUMN_COMMENT];
        })->toArray();

        return response()->api(
            array_merge((array) $columnsArray, (array) $model->files)
        );
    }

    public function enums($table)
    {
        // 테이블 이름을 기반으로 모델 클래스 이름 추정
        // 이 부분은 프로젝트의 네이밍 컨벤션에 맞게 조정할 필요가 있을 수 있습니다.
        $modelName = ucfirst(Str::singular($table)); // 예: 'users' -> 'User'
        $modelClass = "App\\Models\\" . $modelName; // 네임스페이스 포함 전체 클래스 이름

        if (class_exists($modelClass)) {
            $model = new $modelClass;

            // 모델 인스턴스에서 $enums 변수가 존재하는지 확인
            if (property_exists($model, 'enums')) {
                return response()->api($model->enums);
            } else {
                return response()->api(null);
            }
        } else {
            throw new \Exception("{$modelName} 모델 클래스를 찾을 수 없습니다.");
        }
    }

    public function deleteMultipleMedia(Request $request)
    {
        DB::beginTransaction();
        try {
            $mediaService = new MediaService;
            $uuids = $request->input('uuids', []);
            $count = $mediaService->deleteMediaByUuids($uuids);

            DB::commit();
            return response()->api("{$count}개의 파일이 삭제되었습니다.");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
