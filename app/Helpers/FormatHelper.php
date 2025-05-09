<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * 숫자를 '만원' 단위로 변환
     *
     * @param int|string $price 변환할 가격
     * @param bool $showWon '원' 표시 여부
     * @return string
     * 
     * 사용 예:
     * formatPriceToMan(32000000) => "3,200만원"
     * formatPriceToMan(32000000, false) => "3,200만"
     * formatPriceToMan(320000) => "32만원"
     */
    public static function formatPriceToMan($price, $showWon = true)
    {
        if (empty($price)) {
            return '0' . ($showWon ? '원' : '');
        }

        // 문자열에서 숫자만 추출
        $numPrice = (float) preg_replace('/[^0-9]/', '', $price);
        
        // 만 단위로 변환
        // $man = $numPrice / 10000;
        
        // // 1만원 미만일 경우
        // if ($man < 1) {
        //     return number_format($numPrice) . ($showWon ? '원' : '');
        // }
        
        // // 만 단위로 포맷팅
        // $formatted = number_format($man, $man < 10 ? 2 : 0);
        
        // return $formatted . '만' . ($showWon ? '원' : '');

        return $numPrice . '만원';
    }
}
