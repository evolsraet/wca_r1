<?php 
use App\Services\ConfigService;

return [
    'NICE_DNR_API_URL' => ConfigService::safeEnv('NICE_API_URL', 'niceDnr config api_url'),
    'NICE_DNR_API_KEY' => ConfigService::safeEnv('NICE_API_APIKEY', 'niceDnr config api_key'),
    'NICE_DNR_API_LOGIN_ID' => ConfigService::safeEnv('NICE_API_LOGIN_ID', 'niceDnr config login_id'),
    'NICE_DNR_API_KIND_OF' => ConfigService::safeEnv('NICE_API_KIND_OF', 'niceDnr config kind_of'),
    'NICE_DNR_API_BUSINESS_NUMBER' => ConfigService::safeEnv('NICE_API_BUSINESS_NUMBER', 'niceDnr config business_number'),
    'NICE_DNR_API_ENDPOINT_KEY' => ConfigService::safeEnv('NICE_API_ENDPOINT_KEY', 'niceDnr config endpoint_key'),
];


