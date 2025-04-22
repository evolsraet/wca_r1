<?php 

namespace App\Services;

// use App\Models\ApiErrorLog;
use Illuminate\Support\Facades\Log;
use Exception;

class ConfigService
{
    public static function safeEnv(string $key, string $context = 'Config', $default = null)
    {
        try {
            $value = env($key, $default);

            if ($value === null) {
                throw new \RuntimeException("환경변수 {$key} 가 설정되지 않았습니다.");
            }

            return $value;

        } catch (Exception $e) {
            Log::error("[$context] config/env 로딩 실패", [
                'key' => $key,
                'message' => $e->getMessage(),
            ]);

            // ApiErrorLog::create([
            //     'job_name' => $context,
            //     'method' => 'ENV',
            //     'url' => null,
            //     'payload' => ['env_key' => $key],
            //     'response_body' => null,
            //     'error_message' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            // ]);

            // throw 해서 앱 중단되도록 할 수도 있음, 아니면 fallback return
            throw $e;
        }
    }
}