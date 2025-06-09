<?php

return [
    'auction_day' => env('AUCTION_DAY', 2), // 일수 시간 환산 48시간
    'choice_day' => env('CHOICE_DAY', 2), // 일수 시간 환산 48시간
    'reauction_day' => env('REAUCTION_DAY', 2), // 일수 시간 환산 48시간
    'taksong_day' => env('TAKSONG_DAY', 3), // 탁송 선택 -휴일포함 3일
    'claim_day' => env('CLAIM_DAY', 2), // 일수 시간 환산 48시간
    'auction_last_day' => env('AUCTION_LAST_DAY', 2), // 명의이전 파일 확인 2일
    'taksong_time' => env('TAKSONG_LAST_TIME', 2), // 탁송 시간 2시간
    'car_info_cache_ttl' => env('CAR_INFO_CACHE_TTL', 30), // 캐시 시간 30분
];


