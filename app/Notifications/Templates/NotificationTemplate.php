<?php 

namespace App\Notifications\Templates;
use App\Helpers\FormatHelper;
use Carbon\Carbon;
use App\Http\Controllers\Api\PaymentController;
use App\Models\User;
class NotificationTemplate
{

    protected $type;
    protected $data;
    protected $via;

    public function __construct($type, $data, $via)
    {
        $this->type = $type; // 상황별
        $this->data = $data; // 데이터
        $this->via = $via; // 전송방식
    }

    // 회원가입 알림 템플릿
    public static function welcomeTemplate($data)
    {
        $title = config('app.name').' 회원가입을 환영합니다!';

        $message1 = $data.'님 '.config('app.name').' 회원가입을 환영합니다!';
        $message2 = config('app.name').' url'. url('/');

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
        isset($data['linkTitle']) ? $linkTitle = $data['linkTitle'] : $linkTitle = '바로가기';

        isset($data['message']) ? $message = $data['message'] : $message = '';
        isset($data['message_1']) ? $message_1 = $data['message_1'] : $message_1 = '';
        isset($data['message_2']) ? $message_2 = $data['message_2'] : $message_2 = '';
        isset($data['message_3']) ? $message_3 = $data['message_3'] : $message_3 = '';
        isset($data['message_4']) ? $message_4 = $data['message_4'] : $message_4 = '';
        isset($data['message_5']) ? $message_5 = $data['message_5'] : $message_5 = '';
        isset($data['message_6']) ? $message_6 = $data['message_6'] : $message_6 = '';
        isset($data['message_7']) ? $message_7 = $data['message_7'] : $message_7 = '';
        isset($data['message_8']) ? $message_8 = $data['message_8'] : $message_8 = '';
        isset($data['message_9']) ? $message_9 = $data['message_9'] : $message_9 = '';
        isset($data['message_10']) ? $message_10 = $data['message_10'] : $message_10 = '';
        // 기본 차량 정보 
        $message1 = 'ㅁ 차량 : '.$resultData->car_maker.' '.$resultData->car_model.' '.$resultData->car_model_sub;
        $message2 = 'ㅁ 소유주 : '.$resultData->owner_name;
        $message3 = 'ㅁ 차량번호 : '.$resultData->car_no;
        isset($data['status']) ? $message4 = 'ㅁ 상태 : '.$data['status'] : $message4 = '';

        isset($data['status2']) ? $message5 = 'ㅁ 경매마감일 : '.$data['status2'] : $message5 = ''; 
        
        isset($data['status3']) ? $message6 = 'ㅁ 딜러 : '.$data['status3'] : $message6 = ''; 
        isset($data['status4']) ? $message7 = 'ㅁ 입찰가 : ' . FormatHelper::formatPriceToMan(number_format($data['status4'])) : $message7 = ''; 
        
        isset($data['status5']) ? $message8 = 'ㅁ 계좌번호 : '.$data['status5'] : $message8 = ''; 
        isset($data['status6']) ? $message9 = 'ㅁ 입금기일 : '.$data['status6'] : $message9 = ''; 
        
        isset($data['status7']) ? $message10 = 'ㅁ 탁송시작 예정일 : '.$data['status7'] : $message10 = '';
        isset($data['status8']) ? $message11 = 'ㅁ 탁송출발지 : '.$data['status8'] : $message11 = '';

        isset($data['status9']) ? $message12 = 'ㅁ 낙찰가 : ' . FormatHelper::formatPriceToMan(number_format($data['status9'])) : $message12 = '';
        
        // 딜러 : (수수료 입금안내 / 미확인 시 1일 마다 발송)
        isset($data['status10']) ? $message13 = 'ㅁ 입찰가 : ' . FormatHelper::formatPriceToMan(number_format($data['status10'])) : $message13 = '';
        isset($data['status11']) ? $message14 = 'ㅁ 총 입금액 : ' . FormatHelper::formatPriceToMan(number_format($data['status11'])) . ' (VAT 포함)' : $message14 = '';
        isset($data['status12']) ? $message15 = ' - 수수료 : ' . FormatHelper::formatPriceToMan(number_format($data['status12'])) : $message15 = '';
        isset($data['status13']) ? $message16 = ' - 진단비 : ' . FormatHelper::formatPriceToMan(number_format($data['status13'])) : $message16 = '';
        isset($data['status14']) ? $message17 = ' ㅁ 계좌번호 : '.$data['status14'] : $message17 = '';
        isset($data['status15']) ? $message18 = ' ㅁ 예금주 : '.$data['status15'] : $message18 = '';
        isset($data['status16']) ? $message19 = ' ㅁ 클레임 마감일 : '.$data['status16'] : $message19 = '';


        $sendMessage = [
            'title' => $data['title'],
            'message' => $message,
            'message_1' => $message_1,
            'message_2' => $message_2,
            'message_3' => $message_3,
            'message_4' => $message_4,
            'message_5' => $message_5,
            'message_6' => $message_6,
            'message_7' => $message_7,
            'message_8' => $message_8,
            'message_9' => $message_9,
            'message_10' => $message_10,
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
            'link' => ['url' => url(path: $link), 'text' => $linkTitle],
        ];

        return $sendMessage;

    }

    public static function getTemplate($type, $data, $via)
    {

        $title = '';
        $message = '';
        $link = [];
        // $data['count'] = 0;

        switch ($type) {
            // 회원가입시 
            case 'welcome':

                $title = config('app.name').' 회원가입을 환영합니다!';
                $message = 
                $data['name'].'님 '.config('app.name').' 회원가입을 환영합니다! \n'
                .'\n'
                .'';
                
                $link = [
                    "url" => url('/'),
                    "text" => '바로가기'
                ];

                break;

            // 회원상태 변경
            case 'userStatus':

                $user = $data['user'];
                $status = $data['status'];

                if($status == 'ok'){ 
                    $isStatus = '정상';
                }else{
                    $isStatus = '거절';
                }

                $title = '회원님의 상태가 변경되었습니다.';
                $message = 
                $user->name.' 회원님의 상태가 변경되었습니다. \n'
                .'이메일 : '.$user->email.' \n'
                .'상태 : '.$isStatus;
                
                $link = [
                    "url" => url('/'),
                    "text" => '바로가기'
                ];  

                break;


            case 'UserResetPasswordNotification':

                $title = '비밀번호 재설정 링크 발송';
                $message = 
                '귀하의 계정에 대한 비밀번호 재설정 요청이 접수되었기 때문에 이 이메일을 보내드립니다. \n'
                .'만약 비밀번호 재설정을 요청하지 않았다면 이 이메일을 무시하세요. \n'
                .'\n'
                .'비밀번호 재설정 링크 : '.url('/resetPasswordLogin/'.$data);

                $link = [
                    "url" => url('/'),
                    "text" => '바로가기'
                ];

                break;

            // 딜러가 고객님의 차량에 입찰했습니다.
            case 'AuctionBidStatusJobAsk':

                $title = '딜러가 고객님의 차량에 입찰했습니다.';

                $message = 
                '딜러가 고객님의 차량에 입찰했습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 딜러 : '.$data->dealer->company.' '.$data->dealer->name.' \n'
                .'ㅁ 입찰가 : '.FormatHelper::formatPriceToMan(number_format($data->price)).'원 \n'
                .'';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;

            // 경매 상태가 선택대기로 변경되었습니다.
            case 'AuctionBidStatusJobWait':

                $title = '경매 상태가 선택대기로 변경되었습니다.';

                $message = 
                '경매 상태가 선택대기로 변경되었습니다. \n'
                .'경매시간이 종료 되었습니다. 입찰고객을 선택해 주세요. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 상태 : 선택대기';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;

            // 경매 상태가 취소상태로 변경되었습니다.
            case 'AuctionBidStatusJobCancel':

                $title = '경매 상태가 취소상태로 변경되었습니다.';

                $message = 
                '경매 상태가 취소상태로 변경되었습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 상태 : 취소';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;

            // 경매 상태가 재경매로 변경되었습니다.
            case 'AuctionBidStatusJobReauction':

                $title = '경매 상태가 재경매로 변경되었습니다.';

                $message = 
                '경매 상태가 재경매로 변경되었습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 상태 : 재경매';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;

            // 경매가 취소되었습니다.
            case 'AuctionCancelJob':
                $title = '경매가 취소되었습니다.';

                $message = 
                '경매가 취소되었습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 상태 : 취소';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;

            // 딜러님의 입찰이 선택되었습니다.
            case 'AuctionCohosenJobDealer':
                $title = '딜러님의 입찰이 선택되었습니다.';

                $message = 
                '딜러님의 입찰이 선택되었습니다. \n'
                .'금액을 다시 한번 확인하시고, 탁송정보를 입력해주세요! 미입력시 경매 절차가 진행되지않아요 😅 \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 입찰가 : '.FormatHelper::formatPriceToMan(number_format($data->final_price));

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;


            // 딜러님의 입찰이 선택되었습니다.
            case 'AuctionCohosenJobUser1':
                $title = '딜러님의 입찰이 선택되었습니다.';

                $message = 
                '딜러님의 입찰이 선택되었습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;

            // 고객님의 차량이 낙찰되었고, 현재 탁송을 준비 중이에요.
            case 'AuctionCohosenJobUser2':
                $title = '고객님의 차량이 낙찰되었고, 현재 탁송을 준비 중이에요.';

                $message = 
                '고객님의 차량이 낙찰되었고, 현재 탁송을 준비 중이에요. \n'
                .'탁송정보를 입력해주세요! 미입력시 경매 절차가 진행되지않아요 😅 \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;


            case 'AuctionDiagJob':
                $title = '경매 상태가 변경되었습니다.';

                $message = 
                '경매 상태가 변경되었습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 상태 : 진단대기';  


                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;


            case 'AuctionDlvrJobUser':
                $title = '판매요청이 잘 접수됐어요.';

                $message = 
                '판매요청이 잘 접수됐어요. \n'
                .'매니저가 딜러의 견적과 필요서류를 확인하고 있어요. 딜러 사정에 따라 늦어지는 경우가 있습니다. 최대한 빨리 확인 후 안내드릴 예정이니, 조금만 기다려주세요! \n'
                .'이후 판매 과정 \n'
                .'1. 판매서류 준비하기 \n'
                .'2. 탁송일정 입력 \n'
                .'3. (탁송일) 기사 도착&입금받기 \n'
                .'4. (탁송일 + 2일) 명의이전 완료 \n'
                .'* 더자세한 내용은 바로가기를 클릭하여 확인해 주세요. \n'
                .'\n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;


            case 'AuctionDlvrJobDealer':

                $formattedDate = Carbon::parse($data->taksong_wish_at)->format('Y-m-d(D) H시');

                $randomPrefix = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
                $randomSuffix = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
                $bidCode = $randomPrefix.$data->bid_id.$randomSuffix;

                $title = '모든 탁송정보가 입력되었습니다.';

                $message = 
                '모든 탁송정보가 입력되었습니다. \n'
                ."아래 기일까지 차량대금을 입금해주세요! 탁송은 '위카탁송' 에서 진행되며 별도의 안내 문자가 발송됩니다 \n"
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 입찰가 : ".FormatHelper::formatPriceToMan(number_format($data->final_price))." \n"
                ."ㅁ 계좌번호 : ".$data->bank." ".$data->account." \n"
                ."ㅁ 입금기일 : ".$formattedDate." \n"
                ;

                $link = [
                    "url" => url('/api/payment?code='.$bidCode),
                    "text" => '결재하기'
                ];

                break;


            case 'AuctionDoneJobUser':
                $title = '고객님의 차량 경매가 완료되었습니다.';

                $message = 
                '고객님의 차량 경매가 완료되었습니다. \n'
                .config('app.name').'을 이용해주셔서 감사합니다. 후기를 남겨주세요! \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 낙찰가 : ".FormatHelper::formatPriceToMan(number_format($data->final_price))."원 \n";

                $link = [
                    "url" => url('/view-do/'.$data->unique_number),
                    "text" => '후기 남기기'
                ];

                break;  

            case 'AuctionDoneJobDealer':

                $dealer = User::find($data->user_id);
                $auction_id = $data->auction;
                $randomPrefix = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
                $randomSuffix = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
                $moid = $randomPrefix . $auction_id . $randomSuffix; // 앞에8자리 랜덤 + bid_id + 뒤에 랜덤 3자리 조합 
                $VbankExpDate = date('Ymd', strtotime('+1 day'));
                $VbankExpTime = date('His');
                // 나이스페이먼츠 API 가상계좌 번호 발급 
                $paymentData = array(
                    'VbankAccountName'=>$dealer->name, 
                    'VbankExpDate'=>$VbankExpDate, 
                    'VbankExpTime'=>$VbankExpTime, 
                    'Amt'=>$data->final_price, // $auction->final_price
                    'Moid'=>$moid);

                $account = (new PaymentController())->checkOverPayment($paymentData);

                $VbankExpDateTrans = date('Y-m-d', strtotime($account['data']['VbankExpDate']));
                $VbankExpTimeTrans = date('H:i:s', strtotime($account['data']['VbankExpTime']));

                $title = '경매가 완료되었습니다.';

                $message = 
                '경매가 완료되었습니다. \n'
                .'수수료를 입금해 주세요! \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 탁송시작 예정일 : ".$data->taksong_wish_at." \n"
                ."ㅁ 총 입금액 : ".FormatHelper::formatPriceToMan(number_format($data->final_price))." \n"
                ."- 수수료 : ".FormatHelper::formatPriceToMan(number_format($data->total_fee))." \n"
                ."- 계좌번호 : ".$data->bank." ".$data->account." \n"
                ."- 입금기일 : ".$VbankExpDateTrans."(".$VbankExpTimeTrans.") \n"
                ."\n"
                ."* 수수료 미입금시 클레임이 불가합니다."
                ;

                // $link = [
                //     "url" => url('/api/payment?code='.$bidCode),
                //     "text" => '결재하기'
                // ];

                break;

            case 'AuctionIngJob':

                $weekdays = ['일', '월', '화', '수', '목', '금', '토'];
                $weekday = $weekdays[date('w', strtotime($data->final_at))];
                $finalAtTrans = date('Y-m-d', strtotime($data->final_at)) . "($weekday) " . date('H시', strtotime($data->final_at));

                $title = '경매 상태가 변경되었습니다.';

                $message = 
                '경매 상태가 변경되었습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 상태 : 경매진행 \n"
                ."ㅁ 경매마감일 : ".$finalAtTrans." \n"
                ;  

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;

            case 'AuctionStartJob':
                
                $title = '경매 등록신청이 완료 되었습니다.';

                $message = 
                '경매 등록신청이 완료 되었습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .config('app.name')."에 평가사들이 유선전화 드리고 진단요청일에 방문할 예정 입니다. \n";

                // $link = [
                //     "url" => url('/auction/'.$data->unique_number),
                //     "text" => '바로가기'
                // ];

                break;

            case 'AuctionTotalAfterFeeJob':
                $title = '수수료 입금확인 되었습니다.';

                $message = 
                '수수료 입금확인 되었습니다. \n'
                .config('app.name').'을 이용해주셔서 감사합니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '바로가기'
                ];

                break;

            case 'AuctionTotalDepositJobUser':

                $formattedDate = Carbon::parse($data->taksong_wish_at)->format('Y-m-d(D) H시');

                $title = $data->car_no.' 차량대금 송금이 완료되었습니다.';

                $message = 
                $data->car_no.' 차량대금 송금이 완료되었습니다. \n'
                .'영업일 기준 2일 내 명의이전이 완료될 예정입니다. '.config('app.name').'에서 이전등록증을 확인하실 수 있어요! \n'
                .'※ 보험은 명의이전이 완료된 후 해지해주세요. \n'
                .'※ 보험 만기 예정이시거나, 신차 구매로 보험 승계가 필요하시다면 단기 책임보험에 가입하여 보험 유지를 부탁드립니다. \n'
                .'추가로 궁금한 점이 있으시다면 아래 [자주묻는 질문] 버튼을 눌러 확인 해주세요. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 탁송예정일 : ".$formattedDate." \n"
                ."ㅁ 탁송출발지 : ".$data->addr_post." ".$data->addr1." ".$data->addr2." \n";

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '자주묻는 질문'
                ];

                break;

            case 'AuctionTotalDepositJobDealer':

                $formattedDate = Carbon::parse($data->taksong_wish_at)->format('Y-m-d(D) H시');

                $title = '차량대금 입금확인 되어 탁송이 진행됩니다.';

                $message = 
                '차량대금 입금확인 되어 탁송이 진행됩니다. \n'
                ."탁송은 '위카탁송' 에서 진행되며 별도의 안내 문자가 발송됩니다. \n"
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 탁송예정일 : ".$formattedDate." \n"
                ."ㅁ 탁송출발지 : ".$data->addr_post." ".$data->addr1." ".$data->addr2." \n";

                // $link = [
                //     "url" => url('/auction/'.$data->unique_number),
                //     "text" => '바로가기'
                // ];

                break;


            case 'AuctionTotalDepositMissJob':

                $title = '차량대금 입금이 확인 되지 않았습니다.';

                $message = 
                '차량대금 입금이 확인 되지 않았습니다. \n'
                .'미입금시 경매가 취소됩니다. 😅\n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 입찰가 : ".FormatHelper::formatPriceToMan(number_format($data->final_price))."원 \n"
                ."ㅁ 계좌번호 : ".$data->bank." ".$data->account." \n"
                ."ㅁ 입금기일 : ".$data->taksong_wish_at." \n";

                // $link = [
                //     "url" => url('/auction/'.$data->unique_number),
                //     "text" => '바로가기'
                // ];

                break;


            case 'TaksongNameChangeFileUploadJob':

                $title = '차량명의이전 등록증 업로드가 완료되었습니다.';

                $message = 
                '차량명의이전 등록증 업로드가 완료되었습니다. \n'
                .config('app.name').'에서 이전등록증을 확인하실 수 있어요! \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '명의이전 등록증 확인'
                ];

                break;


            case 'TaksongNameChangeJobUser':

                $title = '차량대금 송금이 완료되었습니다.';

                $message = 
                '차량대금 송금이 완료되었습니다. \n'
                .'영업일 기준 2일 내 명의이전이 완료될 예정입니다.'.config('app.name').'에서 이전등록증을 확인하실 수 있어요! \n'
                .'※ 보험은 명의이전이 완료된 후 해지해주세요. \n'
                .'※ 보험 만기 예정이시거나, 신차 구매로 보험 승계가 필요하시다면 단기 책임보험에 가입하여 보험 유지를 부탁드립니다. \n'
                .'추가로 궁금한 점이 있으시다면 아래 [자주묻는 질문] 버튼을 눌러 확인 해주세요. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';
                
                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '자주묻는 질문'
                ];

                break;


            case 'TaksongNameChangeJobDealer':

                $title = $data->car_no.' 차량대금 송금이 완료됐습니다.';

                $message = 
                $data->car_no.' 차량명의이전 완료되었습니다. \n'
                .'영업일 기준 2일 내 명의이전을 등록해 주세요.'.config('app.name').'에서 이전등록증을 등록해 주세요! \n'
                .'※ 보험은 명의이전이 완료된 후 해지해주세요. \n'
                .'※ 보험 만기 예정이시거나, 신차 구매로 보험 승계가 필요하시다면 단기 책임보험에 가입하여 보험 유지를 부탁드립니다. \n'
                .'추가로 궁금한 점이 있으시다면 아래 [자주묻는 질문] 버튼을 눌러 확인 해주세요. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';
                
                $link = [
                    "url" => url('/auction/'.$data->unique_number),
                    "text" => '자주묻는 질문'
                ];

                break;



            case 'basic':

                break;
                
        }

        $sendMessage = [   
            'title' => $title,
            'message' => $message,
            'link' => $link ? $link : null
        ];

        return $sendMessage;


    }
}
?>