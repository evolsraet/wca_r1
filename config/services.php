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

    // 나이스 가상계좌

];
