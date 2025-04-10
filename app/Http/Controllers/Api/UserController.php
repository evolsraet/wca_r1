<?php

namespace App\Http\Controllers\Api;

use App\Models\TaksongStatusTemp;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\UploadedFile;
use App\Traits\CrudControllerTrait;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use App\Jobs\UserResetPasswordLinkJob;
use App\Services\AuctionService;

class UserController extends Controller
{
    use CrudControllerTrait;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
        // print_r('userControllerTrait-');
        // print_r(config('auth.defaults.guard'));
        // die();
    }

    /**
     * 비밀번호 재설정
     *
     * @LRD\Request({
     *     @LRD\Parameter("phone", type="string", required=true, description="사용자 전화번호")
     * })
     */

    public function resetPasswordLink(Request $request, $phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // 라라벡 phone 복호화가능한 암호화
        $expire = now()->addHours(3)->format('Y-m-d H:i:s');
        $encryptCode = Crypt::encryptString("{$phone}/{$expire}");


        $user = User::where('phone', $phone)->first();

        if (!$user) {
            throw new \Exception('사용자를 찾을 수 없습니다.');
        }

        Log::info(
            [
                $phone,
                $encryptCode,
                Crypt::decryptString($encryptCode),
                $user->toArray(),

            ]
        );

        // 비밀번호 변경 링크 이벤트 발송
        UserResetPasswordLinkJob::dispatch($user, $encryptCode);

        // TODO: 폰번호로 링크 보내기

        return response()->api(
            [
                'link' => env('APP_URL') . "/resetPasswordLogin/{$encryptCode}",
                'encryptCode' => $encryptCode,
            ],
            "세시간까지 유효한 링크가 생성되었습니다.",
            'ok',
            200
        );
    }

    public function resetPasswordLogin(Request $request, $encryptCode)
    {
        $code = Crypt::decryptString($encryptCode);
        $codeDecode = explode('/', $code);
        $phone = $codeDecode[0];
        $expire = $codeDecode[1];

        Log::info([$phone, $expire, now()->format('Y-m-d H:i:s')]);

        if ($expire < now()->format('Y-m-d H:i:s')) {
            throw new \Exception('링크가 만료되었습니다.');
        }

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            throw new \Exception('사용자를 찾을 수 없습니다.');
        }

        // 로그인
        Auth::login($user);

        return response()->api(null, "로그인 성공했습니다.\n[{$expire}]까지 비밀번호를 변경해주세요.", 'ok', 200);
    }

    public function test(Request $request)
    {

        // $auctionService = new AuctionService();
        // $auctionService->auctionAfterFeeDone();

        // $auctionService->auctionTotalDepositMiss();

        // vXzPR5E0WLeGpbwkrpNo3XsEJ0D0uTPjgVJVQug88E+sNNzAyQzxAZCVxdoJVFnS3KWY9z4FxKMxdcAwCGATww==

        // echo 'asd';
        
        // $data = array(
        //     'VbankAccountName'=>'홍길동', 
        //     'VbankExpDate'=>'20250126', 
        //     'VbankExpTime'=>'100000', 
        //     'Amt'=>'1000', 
        //     'Moid'=>'1234567890');

            // $dealer = User::find($this->user->user_id);
        // $auction_id = '123';
        // $randomPrefix = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
        // $randomSuffix = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        // $moid = $randomPrefix . $auction_id . $randomSuffix; // 앞에8자리 랜덤 + bid_id + 뒤에 랜덤 3자리 조합 
        // $VbankExpDate = date('Ymd', strtotime('+1 day'));
        // $VbankExpTime = date('His');
        // // 나이스페이먼츠 API 가상계좌 번호 발급 
        // $data = array(
        //     'VbankAccountName'=>'홍길동', 
        //     'VbankExpDate'=>$VbankExpDate, 
        //     'VbankExpTime'=>$VbankExpTime, 
        //     'Amt'=>'100', 
        //     'Moid'=>$moid);
                

        // $account1 = (new PaymentController())->checkOverPayment($data);

        // dd($account1);


        // $auctionService = new AuctionService();
        // // $auctionService->auctionFinalAtUpdate();

        // $result = $auctionService->getCarmerceAuth();

        // $currentData = [
        //     'firstRegistrationDate' => '20240101',
        //     'classModelNm' => '현대',
        //     'year' => '2024',
        // ];


        // $result2 = $auctionService->getCarmercePrice($result['accessToken'], $currentData);

        // dd($result2);


        // $ec = (new PaymentController())->getRandomAccount();
        // dd($ec);


    }

    public function defaultGuard()
    {
        return response()->api([
            config('auth.defaults.guard')
        ]);
    }

    public function confirmPassword(Request $request)
    {
        $inputPassword = $request->input('password');
        $user = $request->user();

        if (Hash::check($inputPassword, auth()->user()->password)) {
            return response()->api(null, '비밀번호가 일치합니다.', 'ok', 200);
        } else {
            return response()->api(null, '비밀번호가 일치하지 않습니다.', 'fail', 404);
        }
    }

    /**
     * @lrd:start
     * # 가능 파일
     * #   'file_user_photo' => '사진',
     * #   'file_user_biz'   => '사업자등록증',
     * #   'file_user_sign'  => '매도용인감증명',
     * #   'file_user_cert'  => '매매업체 대표증 / 종사원증',
     * @lrd:end
     */
    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->api(new UserResource($user));
    }

    public function abilities(Request $request)
    {
        return
            response()->api(
                $request->user()->roles()->with('permissions')
                    ->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->pluck('name')
                    ->unique()
                    ->values()
                    ->toArray()
            );
    }
}
