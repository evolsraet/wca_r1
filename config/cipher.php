<?php

return [
    'seed' => [
        // SEED 암호화 키 (128비트 또는 256비트)
        // .env 파일에서 SEED_KEY 환경 변수를 읽어옴
        // 예: SEED_KEY="abc123abc123abc123aaaaaaaaaaaaaa" (32자리 16진수 문자열)
        'key' => env('SEED_KEY', ''),

        // 초기화 벡터 (IV) (128비트, 16바이트)
        // 매번 다르게 생성하는 것이 보안상 좋지만, Java 코드와 호환을 위해 고정값 사용
        // 예: SEED_IV="02608d0660a70350a801a06f0bad9fa036016025001" (32자리 16진수 문자열)
        'iv' => env('SEED_IV', ''),
    ],
    // 다른 암호화 설정이 있다면 여기에 추가
];