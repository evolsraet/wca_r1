<?php 

namespace App\Notifications\Templates;
use App\Helpers\FormatHelper;
use Carbon\Carbon;
use App\Http\Controllers\Api\PaymentController;
use App\Models\User;
use App\Models\Auction;
use Vinkla\Hashids\Facades\Hashids;

class NotificationTemplate
{

    protected $type;
    protected $data;
    protected $via;

    public function __construct($type, $data, $via)
    {
        $this->type = $type; // 상황별
        $this->data = $data; // 데이터
        $this->via = $via; // 전송방식 (메일 / 알리고)
    }


    // 회원상태 변경 알림 템플릿
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

            // 딜러 회원가입시 대기 알림 
            case 'dealerRegisterWait':

                $title = config('app.name').' 딜러 회원가입을 환영 합니다!';
                $message = 
                $data['name'].'님 '.config('app.name').' 딜러 회원가입을 환영 합니다! \n'
                .'딜러 회원가입 승인 후 딜러 페이지에 접속 가능합니다. \n'
                .'승인이 될때 까지 조금만 기다려주세요! \n'
                .'';

                break;

            // 회원상태 변경
            case 'userStatus':

                $user = $data['user'];
                $status = $data['status'];

                if($status == 'ok'){ 
                    $isStatus = '승인';
                }else{
                    $isStatus = '반려';
                }

                $title = '회원님의 딜러가입이 '.$isStatus.' 되었습니다.';
                $message = 
                $user->name.' 회원님의 딜러가입이 '.$isStatus.' 되었습니다. \n'
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
                    "url" => url('/resetPasswordLogin/'.$data),
                    "text" => '바로가기'
                ];

                break;

            // 딜러가 고객님의 차량에 입찰했습니다.
            case 'AuctionBidStatusJobAsk':

                $dealer = User::find($data->dealer->id);

                $title = $dealer->company.' '.$dealer->name.' 딜러가 고객님의 차량에 입찰했습니다.';

                $message = 
                $dealer->company.' '.$dealer->name.' 딜러가 고객님의 차량에 입찰했습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 딜러 : '.$dealer->company.' '.$dealer->name.' \n'
                .'ㅁ 입찰가 : '.FormatHelper::formatPriceToMan(number_format($data->price)).' \n'
                .'';

                $link = [
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '바로가기'
                ];

                break;


            case 'AuctionBidStatusJobIng':

                $title = '경매 진행 상태';

                $message = 
                '경매 진행 상태 안내. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 상태 : 경매진행';

                $link = [
                    "url" => url('/auction/'.$data->hashid),
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
                    "url" => url('/auction/'.$data->hashid),
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
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '바로가기'
                ];

                break;



            // 경매 입찰이 취소되었습니다.
            case 'AuctionBidStatusJobDelete':

                $dealer = User::find($data->dealer->id);

                $title = $dealer->name.' 딜러가 경매 입찰을 취소했습니다.';

                $message = 
                $dealer->name.' 딜러가 경매 입찰을 취소했습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

                $link = [
                    "url" => url('/auction/'.$data->hashid),
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
                    "url" => url('/auction/'.$data->hashid),
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
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '바로가기'
                ];

                break;

            // 딜러님의 입찰이 선택되었습니다.
            case 'AuctionCohosenJobDealer':
                $title = '판매자가 탁송정보를 입력했습니다.';

                $message = 
                '판매자가 탁송정보를 입력했습니다. \n'
                .'금액을 다시 한번 확인하시고, 탁송정보를 입력해주세요! 미입력시 경매 절차가 진행되지않아요 😅 \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 입찰가 : '.FormatHelper::formatPriceToMan(number_format($data->final_price));

                $link = [
                    "url" => url('/auction/'.$data->hashid),
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
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '바로가기'
                ];

                break;

            // 고객님의 차량이 낙찰되었고, 현재 탁송을 준비 중이에요.
            case 'AuctionCohosenJobUser2':
                $title = '판매가 확정 되었습니다. 탁송정보를 입력해주세요.';

                $message = 
                '탁송정보를 입력해주세요! 미입력시 경매 절차가 진행되지않아요 😅 \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

                $link = [
                    "url" => url('/auction/'.$data->hashid),
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
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '바로가기'
                ];

                break;


            case 'AuctionDiagJobAdmin':

                $user = User::find($data->user_id);

                $title = $data->car_no.' 차량 진단 대기중입니다.';

                $message = 
                '진단 대기중인 차량이 있습니다. \n'
                .'진단팀에서 진단을 진행 해 주세요 \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 소유주 전화번호 : '.$user->phone.' \n'
                .'ㅁ 고객 주소 : '.$data->addr_post.' '.$data->addr1.' '.$data->addr2.' \n'
                .'ㅁ 차량번호 : ['.$data->car_no.'] \n'
                .'ㅁ 고객사코드 : ['.Hashids::encode($data->id).'] \n'
                .'ㅁ 진단일1 : '.$data->diag_first_at.' \n'
                .'ㅁ 진단일2 : '.$data->diag_second_at.' \n'
                .'ㅁ 상태 : 진단대기';  


                $link = [
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '바로가기'
                ];

                break;


            case 'AuctionDlvrJobUser':
                $title = '탁송요청이 정상적으로 처리됐습니다.';

                // $message = 
                // '판매요청이 잘 접수됐어요. \n'
                // .'매니저가 딜러의 견적과 필요서류를 확인하고 있어요. 딜러 사정에 따라 늦어지는 경우가 있습니다. 최대한 빨리 확인 후 안내드릴 예정이니, 조금만 기다려주세요! \n'
                // .'이후 판매 과정 \n'
                // .'1. 판매서류 준비하기 \n'
                // .'2. (탁송일) 기사 도착&입금받기 \n'
                // .'3. (탁송일 + 2일) 명의이전 완료 \n'
                // .'* 더자세한 내용은 바로가기를 클릭하여 확인해 주세요. \n'
                // .'\n'
                // .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                // .'ㅁ 소유주 : '.$data->owner_name.' \n'
                // .'ㅁ 차량번호 : '.$data->car_no.' \n';

                $message = 
                '탁송요청이 정상적으로 처리됐습니다. \n'
                .'탁송전 판매서류를 준비해주세요\n'
                .'❖ 판매대금 입금 전 탁송을 보내지마세요! \n'
                .'탁송전 : 판매서류 준비하기 \n'
                .'탁송일 : 기사 도착, 판매대금 입금받기 \n'
                .'탁송일 + '.config('days.taksong_day').'일) : 명의이전 완료 \n'
                .'❖ 더자세한 내용은 바로가기를 클릭하여 확인해 주세요. \n'
                .'\n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                .'ㅁ 입찰가 : '.FormatHelper::formatPriceToMan(number_format($data->final_price)).' \n';

                $link = [
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '바로가기'
                ];

                break;


            case 'AuctionDlvrJobDealer':
                
                $weekdays = [
                    'Monday'    => '월요일',
                    'Tuesday'   => '화요일',
                    'Wednesday' => '수요일',
                    'Thursday'  => '목요일',
                    'Friday'    => '금요일',
                    'Saturday'  => '토요일',
                    'Sunday'    => '일요일',
                ];
                
                // 현재시간 기준으로 2시간 이내 날짜와 시간 표시 
                $timeNow = Carbon::parse($data->taksong_wish_at);
                $twoHoursLater = $timeNow->copy()->subHours(config('days.taksong_time'));
                // 형식 지정 출력
                //$formattedTime = $twoHoursLater->format('Y-m-d H:i');

                $weekdayEng = $twoHoursLater->format('l'); // Monday, Tuesday 등
                $weekdayKor = $weekdays[$weekdayEng] ?? $weekdayEng;
                $formattedTime = $twoHoursLater->format('Y-m-d') . "({$weekdayKor}) " . $twoHoursLater->format('H시'). "(탁송 ".config('days.taksong_time')."시간 전)";


                $randomPrefix = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
                $randomSuffix = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
                $bidCode = $randomPrefix.$data->bid_id.$randomSuffix;

                $title = '차량대금을 입금해주세요.';

                $message = 
                '차량대금을 입금해주세요. \n'
                ."아래 기일까지 차량대금을 입금해주세요! 탁송은 '".config('services.taksong.name')."' 에서 진행되며 별도의 안내 문자가 발송됩니다 \n"
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 입찰가 : ".FormatHelper::formatPriceToMan(number_format($data->final_price))." \n"
                ."ㅁ 계좌번호 : ".$data->bank." ".$data->account." \n"
                ."ㅁ 입금기일 : ".$formattedTime." \n"
                ."❖ 사이트에서 [경락 확인서]를 확인하실 수 있어요!"
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
                .config('app.name').'을 이용해주셔서 감사합니다. 이전등록증을 확인하실 수 있어요! 후기를 남겨주세요! \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 낙찰가 : ".FormatHelper::formatPriceToMan(number_format($data->final_price))." \n";

                $link = [
                    "url" => url('/view-do/'.$data->hashid),
                    "text" => '후기 남기기'
                ];

                break;  

            case 'AuctionDoneJobDealer':

                $dealer = User::find($data->user_id);
                $auction_id = $data->auction_id;
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

                $title = '차량명의이전서류가 등록됐습니다.';

                $total_fee = FormatHelper::formatPriceToMan(number_format(num: 15)); // 임시금액
                // TODO: 수수료관련 - 실제 필드 금액으로 수정

                $message = 
                '차량명의이전서류가 등록됐습니다. \n'
                ."\n"
                .'수수료 입금 내용을 확인 해 주세요. \n'
                ."❖ 수수료 미입금시 클레임이 불가합니다. \n"
                ."\n"
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 수수료 : ".$total_fee." (테스트용 임시금액) \n"
                ."  - 성공수수료 : 원\n"
                ."  - 진단수수료 : 원\n"
                ."ㅁ 수수료 : ".$total_fee." (테스트용 임시금액) \n"
                ."ㅁ 계좌번호 : 1234124-124124124 (테스트용 계좌번호) \n";

                // .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                // .'ㅁ 소유주 : '.$data->owner_name.' \n'
                // .'ㅁ 차량번호 : '.$data->car_no.' \n'
                // ;

                // $link = [
                //     "url" => url('/api/payment?code='.$bidCode),
                //     "text" => '결재하기'
                // ];

                break;

            case 'AuctionIngJob':

                $weekdays = ['일', '월', '화', '수', '목', '금', '토'];
                $weekday = $weekdays[date('w', strtotime($data->final_at))];
                $finalAtTrans = date('Y-m-d', strtotime($data->final_at)) . "($weekday) " . date('H시', strtotime($data->final_at));

                $title = '경매 상태가 변경되었습니다. ['.$data->car_no.' 경매진행]';

                $message = 
                '경매 상태가 변경되었습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 상태 : 경매진행 \n"
                ."ㅁ 경매마감일 : ".$finalAtTrans." \n"
                ;  

                $link = [
                    "url" => url('/auction/'.$data->hashid),
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
                //     "url" => url('/auction/'.$data->hashid),
                //     "text" => '바로가기'
                // ];

                break;


            case 'AuctionStartJobAdmin':
                
                $title = '경매 등록신청이 완료 되었습니다.';

                $message = 
                '경매 등록신청이 완료 되었습니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

                // $link = [
                //     "url" => url('/auction/'.$data->hashid),
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
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '바로가기'
                ];

                break;

            case 'AuctionTotalDepositJobUser':

                // $formattedDate = Carbon::parse($data->taksong_wish_at)->format('Y-m-d(D) H시');

                $weekdays = ['일', '월', '화', '수', '목', '금', '토'];
                $weekday = $weekdays[date('w', strtotime($data->taksong_wish_at))];
                $formattedDate = date('Y-m-d', strtotime($data->taksong_wish_at)) . "($weekday) " . date('H시', strtotime($data->taksong_wish_at));



                $title = $data->car_no.' 차량대금 송금이 완료되었습니다.';

                $message = 
                $data->car_no.' 차량대금 송금이 완료되었습니다. \n'
                .'영업일 기준 2일 내 명의이전이 완료될 예정입니다. '.config('app.name').'에서 이전등록증을 확인하실 수 있어요! \n'
                .'❖ 보험은 명의이전이 완료된 후 해지해주세요. \n'
                .'❖ 보험 만기 예정이시거나, 신차 구매로 보험 승계가 필요하시다면 단기 책임보험에 가입하여 보험 유지를 부탁드립니다. \n'
                .'추가로 궁금한 점이 있으시다면 아래 [자주묻는 질문] 버튼을 눌러 확인 해주세요. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 탁송예정일 : ".$formattedDate." \n"
                ."ㅁ 탁송출발지 : ".$data->addr_post." ".$data->addr1." ".$data->addr2." \n";

                $link = [
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '자주묻는 질문'
                ];

                break;

            case 'AuctionTotalDepositJobDealer':

                $weekdays = ['일', '월', '화', '수', '목', '금', '토'];
                $weekday = $weekdays[date('w', strtotime($data->taksong_wish_at))];
                $formattedDate = date('Y-m-d', strtotime($data->taksong_wish_at)) . "($weekday) " . date('H시', strtotime($data->taksong_wish_at));

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
                //     "url" => url('/auction/'.$data->hashid),
                //     "text" => '바로가기'
                // ];

                break;

            case 'AuctionTotalDepositJobTaksong':

                $weekdays = ['일', '월', '화', '수', '목', '금', '토'];
                $weekday = $weekdays[date('w', strtotime($data->taksong_wish_at))];
                $formattedDate = date('Y-m-d', strtotime($data->taksong_wish_at)) . "($weekday) " . date('H시', strtotime($data->taksong_wish_at));

                $title = $data->car_no.' 차량대금 입금이 확인 되었습니다.';

                $message = 
                $data->car_no.' 차량대금 입금이 확인 되었습니다. \n'
                .'탁송서비스 예약이 완료되었습니다. \n'
                .'탁송팀 에서는 해당 예약내용을 확인해 주세요. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 탁송예정일 : ".$formattedDate." \n"
                ."ㅁ 탁송출발지 : ".$data->addr_post." ".$data->addr1." ".$data->addr2." \n";

                // $link = [
                //     "url" => url('/auction/'.$data->hashid),
                //     "text" => '탁송서비스 예약확인'
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
                ."ㅁ 입찰가 : ".FormatHelper::formatPriceToMan(number_format($data->final_price))." \n"
                ."ㅁ 계좌번호 : ".$data->bank." ".$data->account." \n"
                ."ㅁ 입금기일 : ".$data->taksong_wish_at." \n";

                // $link = [
                //     "url" => url('/auction/'.$data->hashid),
                //     "text" => '바로가기'
                // ];

                break;


            case 'TaksongNameChangeFileUploadJob':

                $title = '차량명의이전 등록증 업로드가 완료되었습니다.';

                $message = 
                '차량명의이전 등록증 업로드가 완료되었습니다. \n'
                .'모든 차량판매가 완료되었습니다. 감사합니다 \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

                $link = [
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '명의이전 등록증 확인'
                ];

                break;


            case 'TaksongNameChangeJobUser':

                $title = '차량 탁송이 완료되었습니다.';

                $message = 
                '차량 탁송이 완료되었습니다. \n'
                .'영업일 기준 2일 내 명의이전이 완료될 예정입니다.'.config('app.name').'에서 이전등록증을 확인하실 수 있어요! \n'
                .'❖ 보험은 명의이전이 완료된 후 해지 부탁드립니다. \n'
                .'❖ 보험 만기 예정이시거나, 신차 구매로 보험 승계가 필요하시다면 단기 책임보험에 가입하여 보험 유지를 부탁드립니다. \n'
                .'추가로 궁금한 점이 있으시다면 아래 [자주묻는 질문] 버튼을 눌러 확인 부탁드립니다. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';
                
                $link = [
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '자주묻는 질문'
                ];

                break;


            case 'TaksongNameChangeJobDealer':

                $title = $data->car_no.' 차량 탁송이 완료되었습니다.';

                // 수수료 계산방법 내용 필요함
                $total_fee = FormatHelper::formatPriceToMan(number_format(15)); // 임시금액

                $message = 
                $data->car_no." 차량 탁송이 완료되었습니다. \n"
                .'수수료 입금 내용을 확인 해 주세요. \n'
                .'영업일 기준 2일 내 [이전등록증]을 등록해 주세요. \n'
                ."❖ 수수료 미입금시 클레임이 불가합니다. \n"
                ."\n"
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n'
                ."ㅁ 수수료 : ".$total_fee." \n"
                ."ㅁ 계좌번호 : - \n";
                
                $link = [
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '이전등록증 등록'
                ];

                break;



            case 'TaksongNameChangeJobDealerResend':

                $title = $data->car_no.' 차량 명의이전 등록이 안되었습니다.';

                $message = 
                $data->car_no.' 차량 명의이전 등록이 안되었습니다. \n'
                .'영업일 기준 2일 내 명의이전을 등록해 주세요.'.config('app.name').'에서 이전등록증을 등록해 주세요! \n'
                .'❖ 보험은 명의이전이 완료된 후 해지해주세요. \n'
                .'❖ 보험 만기 예정이시거나, 신차 구매로 보험 승계가 필요하시다면 단기 책임보험에 가입하여 보험 유지를 부탁드립니다. \n'
                .'추가로 궁금한 점이 있으시다면 아래 [자주묻는 질문] 버튼을 눌러 확인 해주세요. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';
                
                $link = [
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '자주묻는 질문'
                ];

                break;


            case 'TaksongNameChangeJobAdmin':

                $title = $data->car_no.' 차량 명의이전 등록이 안되었습니다.';

                $message = 
                $data->car_no.' 차량 명의이전 등록이 안되었습니다. \n'
                .'해당 딜러에게 명의이전 등록증을 요청해 주세요. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';
                
                $link = [
                    "url" => url('/auction/'.$data->hashid),
                    "text" => '자주묻는 질문'
                ];

                break;
            case 'DiagnosisErrorJob': // API 오류 

                $title = $data->car_no.' 차량 진단 API 결과 오류';

                $message = 
                $data->car_no.' 차량 진단 API 결과 오류가 발생했습니다. \n'
                .'진단에서 고객사코드가 정확히 입력되었는지 확인 해주세요. \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';
                
                // $link = [
                //     "url" => url('/auction/'.$data->hashid),
                //     "text" => '자주묻는 질문'
                // ];

                break;



        

            // 클래임 알림
            case 'ClaimAdminJob':

                $auction = Auction::find($data->extra1);

                $title = $auction->car_no.' 클레임 알림';

                $message = 
                $auction->car_no.' 클레임 알림 \n'
                .'ㅁ 게시글 제목 : '.$data->title.' \n'
                .'ㅁ 게시글 내용 : '.htmlspecialchars($data->content).' \n';
                
                $link = [
                    "url" => url('/board/claim'),
                    "text" => '바로가기'
                ];

                break;


            case 'ClaimAdminUpdateJob':

                $title = $data->car_no.' 클레임 상태 변경 알림';

                $message = 
                $data->car_no.' 클레임 상태 변경 알림 \n'
                .'ㅁ 게시글 제목 : '.$data->title.' \n'
                .'ㅁ 게시글 내용 : '.htmlspecialchars($data->content).' \n'
                .'ㅁ 상태 : '.$data->category.' \n';

                $link = [
                    "url" => url('/board/claim'),
                    "text" => '바로가기'
                ];

                break;

              # TODO : 클레임이 처리중 변경시 / 담당자가 가급적 빠른 시일내에 연락 드리겠습니다. 

            case 'ClaimCommentJob':
                
                    $title = '댓글 클레임 알림';

                    $message = 
                    '댓글 클레임 알림 \n'
                    .'ㅁ 내용 : '.htmlspecialchars($data->content).' \n';
                    
                    $link = [
                        "url" => url('/board/claim'),
                        "text" => '바로가기'
                    ];

                break;

            case 'ClaimCommentUpdateJob':
                
                    $title = '댓글 수정 클레임 알림';

                    $message = 
                    '댓글 수정 클레임 알림 \n'
                    .'ㅁ 내용 : '.htmlspecialchars($data->content).' \n';
                    
                    $link = [
                        "url" => url('/board/claim'),
                        "text" => '바로가기'
                    ];

                break;


            case 'ReviewSendJob':

                $title = '리뷰 작성 알림';

                $message = 
                '리뷰 작성 알림 \n'
                .'ㅁ 내용 : '.htmlspecialchars($data->content).' \n'
                .'ㅁ 차량 : '.$data->car_maker.' '.$data->car_model.' '.$data->car_model_sub.' \n'
                .'ㅁ 소유주 : '.$data->owner_name.' \n'
                .'ㅁ 차량번호 : '.$data->car_no.' \n';

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