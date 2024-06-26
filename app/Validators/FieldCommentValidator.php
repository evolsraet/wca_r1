<?php

namespace App\Validators;

use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FieldCommentValidator extends Validator
{
    protected $table;

    public function setTable($table)
    {
        $this->table = $table;
        Log::info("검증기 테이블 설정: {$table}");
    }

    protected function replaceAttributePlaceholder($message, $value)
    {
        // 공백을 언더스코어로 변환
        $formattedColumn = str_replace(' ', '_', $value);
        $comment = $this->getColumnComment($this->table, $formattedColumn);
        $attributeName = $comment ?: $value;
        Log::info("Replacing attribute '{$value}' with comment '{$attributeName}' in message.");
        return str_replace([':attribute', ':ATTRIBUTE', ':Attribute'], $attributeName, $message);
    }

    protected function getColumnComment($table, $column)
    {
        Log::info("테이블 '{$table}', 컬럼 '{$column}'의 코멘트를 가져옵니다.");
        $comment = DB::table('INFORMATION_SCHEMA.COLUMNS')
            ->select('COLUMN_COMMENT')
            ->where('TABLE_SCHEMA', DB::connection()->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->value('COLUMN_COMMENT');

        if (!$comment) {
            Log::warning("테이블 '{$table}', 컬럼 '{$column}'에 코멘트가 없습니다.");
        }

        return $comment ?: null;
    }
}
