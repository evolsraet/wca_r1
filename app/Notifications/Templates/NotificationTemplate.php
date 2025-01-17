<?php 

namespace App\Notifications\Templates;

class NotificationTemplate
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    // 회원가입 알림 템플릿
    public static function welcomeTemplate($data)
    {
        $title = '위카옥션 회원가입을 환영합니다!';

        $message1 = $data.'님 위카옥션 회원가입을 환영합니다!';
        $message2 = '위카옥션 url'. url('/');

        $sendMessage = [
            'title' => $title,
            'message1' => $message1,
            'message2' => $message2,
            'link' => url('/'),
        ];

        return $sendMessage;
    }

    // 회원상태 변경 알림 템플릿
    public static function userStatusTemplate($data)
    {

        $user = $data['user'];
        $status = $data['status'];

        if($status == 'ok'){ 
            $isStatus = '정상';
        }else{
            $isStatus = '거절';
        }

        $title = '회원님의 상태가 변경되었습니다.';
        $message1 = $user->name.' 회원님의 상태가 변경되었습니다.';
        $message2 = 'ㅁ 이메일 : '.$user->email;
        $message3 = 'ㅁ 상태 : '.$isStatus;

        $sendMessage = [
            'title' => $title,
            'message1' => $message1,
            'message2' => $message2,
            'message3' => $message3,
            'link' => url('/'),
        ];

        return $sendMessage;
    }


    public static function basicTemplate($data)
    {

        // 푸터 메시지
        $footerMsg = $data['footerMsg'] ?? '';

        // 데이터
        $resultData = $data['data'];
        
        // 전송링크 
        isset($data['link']) ? $link = $data['link'] : $link = '/';

        isset($data['message']) ? $message = $data['message'] : $message = '';

        // 기본 차량 정보 
        $message1 = 'ㅁ 차량 : '.$resultData->car_maker.' '.$resultData->car_model.' '.$resultData->car_model_sub;
        $message2 = 'ㅁ 소유주 : '.$resultData->owner_name;
        $message3 = 'ㅁ 차량번호 : '.$resultData->car_no;
        isset($data['status']) ? $message4 = 'ㅁ 상태 : '.$data['status'] : $message4 = '';

        isset($data['status2']) ? $message5 = 'ㅁ 경매마감일 : '.$data['status2'] : $message5 = ''; 
        
        isset($data['status3']) ? $message6 = 'ㅁ 딜러 : '.$data['status3'] : $message6 = ''; 
        isset($data['status4']) ? $message7 = 'ㅁ 입찰가 : ' . number_format($data['status4']) . '원' : $message7 = ''; 
        
        isset($data['status5']) ? $message8 = 'ㅁ 계좌번호 : '.$data['status5'] : $message8 = ''; 
        isset($data['status6']) ? $message9 = 'ㅁ 입금기일 : '.$data['status6'] : $message9 = ''; 
        
        isset($data['status7']) ? $message10 = 'ㅁ 탁송시작 예정일 : '.$data['status7'] : $message10 = '';
        isset($data['status8']) ? $message11 = 'ㅁ 탁송출발지 : '.$data['status8'] : $message11 = '';

        isset($data['status9']) ? $message12 = 'ㅁ 낙찰가 : ' . number_format($data['status9']) . '만원' : $message12 = '';
        
        // 딜러 : (수수료 입금안내 / 미확인 시 1일 마다 발송)
        isset($data['status10']) ? $message13 = 'ㅁ 입찰가 : ' . number_format($data['status10']) . '원' : $message13 = '';
        isset($data['status11']) ? $message14 = 'ㅁ 총 입금액 : ' . number_format($data['status11']) . '원 (VAT 포함)' : $message14 = '';
        isset($data['status12']) ? $message15 = ' - 수수료 : ' . number_format($data['status12']) . '원' : $message15 = '';
        isset($data['status13']) ? $message16 = ' - 진단비 : ' . number_format($data['status13']) . '원' : $message16 = '';
        isset($data['status14']) ? $message17 = ' ㅁ 계좌번호 : '.$data['status14'] : $message17 = '';
        isset($data['status15']) ? $message18 = ' ㅁ 예금주 : '.$data['status15'] : $message18 = '';
        isset($data['status16']) ? $message19 = ' ㅁ 클레임 마감일 : '.$data['status16'] : $message19 = '';


        $sendMessage = [
            'title' => $data['title'],
            'message' => $message,
            'message1' => $message1,
            'message2' => $message2,
            'message3' => $message3,
            'message4'  => $message4,
            'message5'  => $message5,
            'message6'  => $message6,
            'message7'  => $message7,
            'message8'  => $message8,
            'message9'  => $message9,
            'message10'  => $message10,
            'message11'  => $message11,
            'message12'  => $message12,
            'message13'  => $message13,
            'message14'  => $message14,
            'message15'  => $message15,
            'message16'  => $message16,
            'message17'  => $message17,
            'message18'  => $message18,
            'message19'  => $message19,
            'footerMsg' => $footerMsg,
            'link' => ['url' => url(path: $link), 'text' => '바로가기'],
        ];

        return $sendMessage;

    }
}
?>