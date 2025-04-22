<?php
use App\Services\ConfigService;
return [
    'TAKSONG_API_URL' => ConfigService::safeEnv('TAKSONG_API_URL', 'TAKSONG_API_URL'),
    'TAKSONG_API_STATUS_URL' => ConfigService::safeEnv('TAKSONG_API_STATUS_URL', 'TAKSONG_API_STATUS_URL'),
    'TAKSONG_API_KEY' => ConfigService::safeEnv('TAKSONG_API_KEY', 'TAKSONG_API_KEY'),
    'TAKSONG_AUTH' => ConfigService::safeEnv('TAKSONG_AUTH', 'TAKSONG_AUTH'),
];
