<?php

namespace App\Extends;

use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Log;

class ConfigSetLog extends Repository
{
    public function set($key, $value = null)
    {
        // $originalValue = $this->get($key);
        // parent::offsetSet($key, $value); // parent::set 대신 parent::offsetSet 사용
        // Log::info('Config value changed', [
        //     'key' => $key,
        //     'original' => $originalValue,
        //     'new' => $value,
        //     'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)
        // ]);
    }
}
