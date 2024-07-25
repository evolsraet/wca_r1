<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait ModelTrait
{
    // protected $dateFormat = 'Y-m-d H:i:s';

    public static function findOrFail($id, $columns = ['*'])
    {
        $model = static::find($id, $columns);

        if (is_null($model)) {
            $addMessage = "";
            if (env('APP_DEBUG'))
                $addMessage = static::class . " {$id}";
            throw new \Exception("존재하지 않는 데이터입니다. {$addMessage}");
        }

        return $model;
    }
}
