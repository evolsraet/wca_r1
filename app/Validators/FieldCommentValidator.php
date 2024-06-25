<?php

namespace App\Validators;

use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;

class FieldCommentValidator extends Validator
{
    protected $table;

    public function setTable($table)
    {
        $this->table = $table;
    }

    protected function getAttribute($attribute)
    {
        $comment = $this->getColumnComment($this->table, $attribute);
        return $comment ?: parent::getAttribute($attribute);
    }

    protected function getColumnComment($table, $column)
    {
        if ($table) {
            $comment = DB::selectOne("SELECT COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND TABLE_SCHEMA = DATABASE()", [$table, $column]);
            return $comment ? $comment->COLUMN_COMMENT : null;
        }
        return null;
    }
}
