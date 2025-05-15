<?php
use App\Services\ConfigService;

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // 카카오 로그인
    'kakao' => [
        'client_id' => ConfigService::safeEnv('KAKAO_CLIENT_ID', 'kakao config client_id'),
        'client_secret' => ConfigService::safeEnv('KAKAO_CLIENT_SECRET', 'kakao config client_secret'),
        'redirect' => ConfigService::safeEnv('KAKAO_REDIRECT_URI', 'kakao config redirect'),
    ],

    // 네이버 로그인 
    'naver' => [
        'client_id' => ConfigService::safeEnv('NAVER_CLIENT_ID', 'naver config client_id'),  
        'client_secret' => ConfigService::safeEnv('NAVER_CLIENT_SECRET', 'naver config client_secret'),  
        'redirect' => ConfigService::safeEnv('NAVER_REDIRECT_URI', 'naver config redirect') 
    ],

    // 구글 로그인
    'google' => [
        'client_id' => ConfigService::safeEnv('GOOGLE_CLIENT_ID', 'google config client_id'),
        'client_secret' => ConfigService::safeEnv('GOOGLE_CLIENT_SECRET', 'google config client_secret'),
        'redirect' => ConfigService::safeEnv('GOOGLE_REDIRECT_URI', 'google config redirect'),
    ],

    // 나이스 본인인증
    'niceAuth' => [
        'NICE_CLIENT_ID' => ConfigService::safeEnv('NICE_CLIENT_ID', 'niceAuth config client_id'),
        'NICE_CLIENT_SECRET' => ConfigService::safeEnv('NICE_CLIENT_SECRET', 'niceAuth config client_secret'),
        // 'NICE_PRODUCT_ID' => ConfigService::safeEnv('NICE_PRODUCT_ID', 'niceAuth config product_id')
    ],

    // 차량 이력 API
    'carHistory' => [
        'join_code' => ConfigService::safeEnv('CARHISTORY_JOIN_CODE', 'carHistory config join_code'),
        'encrypt_key' => ConfigService::safeEnv('CARHISTORY_ENCRYPT_KEY', 'carHistory config encrypt_key'),
        'member_id' => ConfigService::safeEnv('CARHISTORY_MEMBER_ID', 'carHistory config member_id'),
        'api_url' => ConfigService::safeEnv('CARHISTORY_API_URL', 'carHistory config api_url'),
    ],

    // 코드에프 API
    'codef' => [
        'client_id' => ConfigService::safeEnv('CODEF_CLIENT_ID', 'codef config client_id'),
        'client_secret' => ConfigService::safeEnv('CODEF_CLIENT_SECRET', 'codef config client_secret'),
        'api_url' => ConfigService::safeEnv('CODEF_API_URL', 'codef config api_url'),
        'public_key' => ConfigService::safeEnv('CODEF_PUBLIC_KEY', 'codef config public_key'),
        'oauth_url' => ConfigService::safeEnv('CODEF_OAUTH_URL', 'codef config oauth_url'),
    ],


    // 진단 API
    'diagnostic' => [
        'api_url' => ConfigService::safeEnv('WCA_DIAGNOSTIC_API_URL', 'diagnostic config api_url'),
        'api_id' => ConfigService::safeEnv('WCA_DIAGNOSTIC_API_ID', 'diagnostic config api_id'),
        'api_key' => ConfigService::safeEnv('WCA_DIAGNOSTIC_API_KEY', 'diagnostic config api_key'),
    ],

    // 명의이전 알림 관리자 아이디
    'ownership' => [
        'admin_id' => ConfigService::safeEnv('OWNERSHIP_ADMIN_ID', 'ownership config admin_id'),
    ],

];
