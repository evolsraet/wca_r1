<?php
use App\Services\ConfigService;

return [
    'NICE_PAY_CLIENT_KEY' => ConfigService::safeEnv('NICE_PAY_CLIENT_KEY', 'nicePay config client_key'),
    'NICE_PAY_CLIENT_ID' => ConfigService::safeEnv('NICE_PAY_CLIENT_ID', 'nicePay config client_id'),
];

