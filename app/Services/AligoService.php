<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class AligoService
{
    protected $apiKey;
    protected $userId;

    protected $alimtalk_token = null;
    protected $alimtalk_token_urlencode = null;

    protected $senderkey;
    protected $admin_mobile;
    protected $sms_sender;

    public function __construct()
    {
        $this->apiKey = env('SMS_APIKEY');
        $this->userId = env('SMS_USER_ID');
        $this->admin_mobile = env('SMS_ADMIN_MOBILE');
        $this->senderkey = env('SMS_SENDERKEY');
        $this->sms_sender = env('SMS_SENDER');
    }

    public function send($phone, $message, $tpl_data = null)
    {

        // dd($phone, $message);
        
        // $response = Http::asForm()->post('https://apis.aligo.in/send/', [
        //     'key' => $this->apiKey,
        //     'user_id' => $this->userId,
        //     'receiver' => $phone,
        //     'msg' => $message,
        // ]);

        // return $response->json();

        $required_fields = array(
            'tpl_code',
            'receiver_1',
            'subject_1',
            'message_1',
        );
        foreach( (array) $required_fields as $key => $row ) :
            if( !isset($tpl_data[$row]) ) {
                $msg = "알림톡 발송 에러 : 템플릿 데이터의 필수 요소가 누락되었습니다. {$row}";
                throw new Exception($msg, 1);
                return $msg;
            }
        endforeach;

        if( $this->getToken() !== true )
            return "알림톡 토큰 발행 중 에러가 발생했습니다.";

        $_apiURL    = 'https://kakaoapi.aligo.in/akv10/alimtalk/send/';
        $_hostInfo  = parse_url($_apiURL);
        $_port      = (strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
        $_variables = array(
            'apikey'      => $this->apiKey, // '발급받은 API 키', 
            'userid'      => $this->userId, //'사용중이신 아이디', 
            'token'       => $this->alimtalk_token, 
            'senderkey'   => $this->senderkey, // '발신프로필키', 
            'sender'      => $this->sms_sender, // '발신자번호',
            // 'senddate'    => date("YmdHis", strtotime("+10 minutes")), // 예약
            // 'tpl_code'    => $tpl_data->code, // '전송할 템플릿 코드',
            // 'receiver_1'  => '첫번째 알림톡을 전송받을 휴대폰 번호',
            // 'recvname_1'  => '첫번째 알림톡을 전송받을 사용자 명',
            // 'subject_1'   => '첫번째 알림톡을 제목',
            // 'message_1'   => '첫번째 템플릿내용을 기초로 작성된 전송할 메세지 내용',
            // 'button_1'    => '{"button":[{"name":"테스트 버튼","linkType":"DS"}]}', // 템플릿에 버튼이 없는경우 제거하시기 바랍니다.
        );

        $_variables = $_variables + $tpl_data;
        // kmh_print($_variables);
        // die();


        /*

        -----------------------------------------------------------------
        치환자 변수에 대한 처리
        -----------------------------------------------------------------

        등록된 템플릿이 "#{이름}님 안녕하세요?" 일경우
        실제 전송할 메세지 (message_x) 에 들어갈 메세지는
        "홍길동님 안녕하세요?" 입니다.

        카카오톡에서는 전문과 템플릿을 비교하여 치환자이외의 부분이 일치할 경우
        정상적인 메세지로 판단하여 발송처리 하는 관계로
        반드시 개행문자도 템플릿과 동일하게 작성하셔야 합니다.

        예제 : message_1 = "홍길동님 안녕하세요?"

        -----------------------------------------------------------------
        버튼타입이 WL일 경우 (웹링크)
        -----------------------------------------------------------------
        링크정보는 다음과 같으며 버튼도 치환변수를 사용할 수 있습니다.
        {"button":[{"name":"버튼명","linkType":"WL","linkP":"https://www.링크주소.com/?example=12345", "linkM": "https://www.링크주소.com/?example=12345"}]}

        -----------------------------------------------------------------
        버튼타입이 AL 일 경우 (앱링크)
        -----------------------------------------------------------------
        {"button":[{"name":"버튼명","linkType":"AL","linkI":"https://www.링크주소.com/?example=12345", "linkA": "https://www.링크주소.com/?example=12345"}]}

        -----------------------------------------------------------------
        버튼타입이 DS 일 경우 (배송조회)
        -----------------------------------------------------------------
        {"button":[{"name":"버튼명","linkType":"DS"}]}

        -----------------------------------------------------------------
        버튼타입이 BK 일 경우 (봇키워드)
        -----------------------------------------------------------------
        {"button":[{"name":"버튼명","linkType":"BK"}]}

        -----------------------------------------------------------------
        버튼타입이 MD 일 경우 (메세지 전달)
        -----------------------------------------------------------------
        {"button":[{"name":"버튼명","linkType":"MD"}]}

        -----------------------------------------------------------------
        버튼이 여러개 인경우 (WL + DS)
        -----------------------------------------------------------------
        {"button":[{"name":"버튼명","linkType":"WL","linkP":"https://www.링크주소.com/?example=12345", "linkM": "https://www.링크주소.com/?example=12345"}, {"name":"버튼명","linkType":"DS"}]}

        */

        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_PORT, $_port);
        curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $ret = curl_exec($oCurl);
        $error_msg = curl_error($oCurl);
        curl_close($oCurl);

        // 리턴 JSON 문자열 확인
        // print_r($ret . PHP_EOL);

        // JSON 문자열 배열 변환
        $retArr = json_decode($ret);
        // 결과값 출력
        // print_r($retArr); 

          /*
          code : 0 성공, 나머지 숫자는 에러
          message : 결과 메시지
          */

        if ($retArr->code == '0')
            return true;
        else
            return $retArr->message;

    }


    protected function getToken()
    {

        $_apiURL    = 'https://kakaoapi.aligo.in/akv10/token/create/30/s/';
        $_hostInfo    =   parse_url($_apiURL);
        $_port          = (strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
        $_variables   =   array(
            'apikey' => $this->apiKey,
            'userid' => $this->userId,
        );

        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_PORT, $_port);
        curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

        $ret = curl_exec($oCurl);
        $error_msg = curl_error($oCurl);
        curl_close($oCurl);

        // 리턴 JSON 문자열 확인
        // kmh_print($ret . PHP_EOL);

        // JSON 문자열 배열 변환
        $retArr = json_decode($ret);

        // 결과값 출력
        // kmh_print($retArr);

        if( $retArr->code == 0 ) :
            // 성공
            $this->alimtalk_token = $retArr->token;
            $this->alimtalk_token_urlencode = $retArr->urlencode;
            return true;
        else :
            // throw new Exception($retArr->message, 1);
            return $retArr->message;
        endif;

    }
}
