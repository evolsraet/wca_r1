<?php
use App\Services\ConfigService;

return [
    'NICE_PAY_VIRTUAL_ACCOUNT_URL' => ConfigService::safeEnv('NICE_PAY_VIRTUAL_ACCOUNT_URL', 'nicePayV config virtual_account_url'),
    'NICE_PAY_VIRTUAL_ACCOUNT_CLIENT_KEY' => ConfigService::safeEnv('NICE_PAY_VIRTUAL_ACCOUNT_CLIENT_KEY', 'nicePayV config client_key'),
    'NICE_PAY_VIRTUAL_ACCOUNT_CLIENT_ID' => ConfigService::safeEnv('NICE_PAY_VIRTUAL_ACCOUNT_CLIENT_ID', 'nicePayV config client_id'),
];

