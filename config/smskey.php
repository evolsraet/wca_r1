<?php
use App\Services\ConfigService;

return [
    'SMS_APIKEY' => ConfigService::safeEnv('SMS_APIKEY', 'SMS_APIKEY'),
    'SMS_USER_ID' => ConfigService::safeEnv('SMS_USER_ID', 'SMS_USER_ID'),
    'SMS_ADMIN_MOBILE' => ConfigService::safeEnv('SMS_ADMIN_MOBILE', 'SMS_ADMIN_MOBILE'),
    'SMS_SENDERKEY' => ConfigService::safeEnv('SMS_SENDERKEY', 'SMS_SENDERKEY'),
    'SMS_SENDER' => ConfigService::safeEnv('SMS_SENDER', 'SMS_SENDER'),
    'SMS_TPL_CODE' => ConfigService::safeEnv('SMS_TPL_CODE', 'SMS_TPL_CODE'),
];
