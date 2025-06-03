<?php
use App\Services\ConfigService;

return [
    'company' => [
        'name' => '정태영',
        'business_number' => '755-81-02354',
        'interim_number' => '제2023-용인기흥-6932호',
        'phone' => '1544-2165',
        'email' => 'wecar@wecar-m.co.kr',
        'address' => [
            [
                'name' => '본사',
                'address' => '경기도 용인시 기흥구 중부대로 242 A동 W117호',
            ],
            [
                'name' => '부산지점',
                'address' => '부산 기장군 장안읍 반룡산단3로 95 C동 지하1층 B106호',
            ],
        ],
    ],
    'mainBanner' => [
        ['image' => 'images/main_banner02.png'],
        ['image' => 'images/main_p2.png'],
        ['image' => 'images/main_p3.png'],
    ],
    'menus' => [
        'common' => [
            'introduce' => [
                'label' => '서비스소개',
                'url' => '/introduce',
                'icon' => '/images/Icon-md-bulb.png',
                'desc' => '위카란?',
            ],
            'board/notice' => [
                'label' => '공지사항',
                'url' => '/board/notice',
                'icon' => '/images/Icon-dash.png',
                'desc' => '',
            ],
        ],
        'user' => [
            'mycar' => [
                'label' => '내차조회',
                'url' => '/mycar',
                'icon' => '/images/Icon-awesome-car-side-Black.png',
                'desc' => '',
            ],
            'auction' => [
                'label' => '내 매물관리',
                'url' => '/auction',
                'icon' => '/images/Icon-md-bulb.png',
                'desc' => '',
            ],
            'board/review' => [
                'label' => '이용후기',
                'url' => '/board/review',
                'icon' => '/images/rating.png',
                'desc' => '',
            ],
            'docs/vehicleOwnershipTransfer' => [
                'label' => '명의이전서류',
                'url' => '/docs/vehicleOwnershipTransfer',
                'icon' => '/images/document.png',
                'desc' => '',
            ],
        ],
        'dealer' => [
            'auction' => [
                'label' => '입찰하기',
                'url' => '/auction',
                'icon' => '/images/Icon-tag.png',
                'desc' => '',
            ],
            'board/claim' => [
                'label' => '클레임',
                'url' => '/board/claim',
                'icon' => '/images/document.png',
                'desc' => '',
            ],
        ],
        'guest' => [
            'mycar' => [
                'label' => '내차조회',
                'url' => '/mycar',
                'icon' => '/images/Icon-awesome-car-side-Black.png',
                'desc' => '내 차량 조회',
            ],
            'board/review' => [
                'label' => '이용후기',
                'url' => '/board/review',
                'icon' => '/images/rating.png',
                'desc' => '다양한 판매 후기',
            ],
        ],
    ],
];