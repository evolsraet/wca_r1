<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Notifications\NetworkErrorNotification;
use App\Jobs\NetworkErrorJob;

class NetworkHelper
{
    protected static array $keywords = [
        'cURL error',
        'Connection refused',
        'Could not resolve host',
        'timed out',
        'Connection timed out',
        'SSL',
        'DNS'
    ];

    public static function isNetworkError(Exception $e): bool
    {
        return Str::contains($e->getMessage(), self::$keywords);
    }

    public static function alertIfNetworkError(Exception $e, array $context = []): void
    {
        if (self::isNetworkError($e)) {
            $message = $e->getMessage();

            Log::warning('[네트워크 에러]', array_merge([
                'message' => $message,
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], $context));

            // 알림 전송
            NetworkErrorJob::dispatch($message, $context);
        }
    }
}