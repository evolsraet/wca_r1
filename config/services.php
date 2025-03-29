<?php

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
        'client_id' => env('KAKAO_CLIENT_ID'),
        'client_secret' => env('KAKAO_CLIENT_SECRET'),
        'redirect' => env('KAKAO_REDIRECT_URI'),
    ],

    // 네이버 로그인 
    'naver' => [
        'client_id' => env('NAVER_CLIENT_ID'),  
        'client_secret' => env('NAVER_CLIENT_SECRET'),  
        'redirect' => env('NAVER_REDIRECT_URI') 
    ],

    // 구글 로그인
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    // 나이스 본인인증
    'niceAuth' => [
        'NICE_CLIENT_ID' => env('NICE_CLIENT_ID'),
        'NICE_CLIENT_SECRET' => env('NICE_CLIENT_SECRET'),
        'NICE_PRODUCT_ID' => env('NICE_PRODUCT_ID')
    ],

    // 차량 이력 API
    'carHistory' => [
        'join_code' => env('CARHISTORY_JOIN_CODE'),
        'encrypt_key' => env('CARHISTORY_ENCRYPT_KEY'),
        'member_id' => env('CARHISTORY_MEMBER_ID'),
        'api_url' => env('CARHISTORY_API_URL'),
    ],

    // 코드에프 API
    'codef' => [
        'client_id' => env('CODEF_CLIENT_ID'),
        'client_secret' => env('CODEF_CLIENT_SECRET'),
        'api_url' => env('CODEF_API_URL'),
        'public_key' => env('CODEF_PUBLIC_KEY'),
        'oauth_url' => env('CODEF_OAUTH_URL'),
    ],

    // 나이스 가상계좌

];
