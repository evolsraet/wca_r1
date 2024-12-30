<?php

namespace App\Http\Controllers\Api;

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
use App\Helpers\SmsAligoHelper;
use App\Notifications\AligoNotification;
use App\Jobs\UserRegisteredJob;
use App\Jobs\AuctionDlvrJob;
use App\Jobs\TaksongStatusJob;
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
        // echo sayHello('worlds');

        // $smsAligoHelper = new SmsAligoHelper();
        // echo $smsAligoHelper->get_token();

        // echo $smsAligoHelper->alimtalk_send([
        //     'tpl_code' => 'TN_1093',
        //     'receiver_1' => '010-2802-0327',
        //     'subject_1' => '세상의 모든 타이어. 올타이어',
        //     'message_1' => "안녕하세요. 올타이어입니다!\n관리자가 20102010 주문의 배송상태를 [배송완료] 으로 변경했습니다.",
        // ]);

        // echo $smsAligoHelper->alimtalk_list();

        // $user = User::find(36); // 예: User 모델
        // $user->notify(new AligoNotification([
        //     'message' => '안녕하세요. 올타이어입니다!\n관리자가 20102010 주문의 배송상태를 [배송완료] 으로 변경했습니다.',
        //     'tpl_data' => [
        //         'tpl_code' => 'TN_1093',
        //         'receiver_1' => '010-2802-0327',
        //         'subject_1' => '세상의 모든 타이어. 올타이어',
        //         'message_1' => "안녕하세요. 올타이어입니다!\n관리자가 20102010 주문의 배송상태를 [배송완료] 으로 변경했습니다.",
        //     ],
        // ]));

        // UserRegisteredJob::dispatch($user);


        // AuctionDlvrJob::dispatch(36);

        TaksongStatusJob::dispatch(35);


        // $user = User::factory()->make();
        // $data['user'] = $user->makeVisible($user->getHidden())->toArray();
        // $data['user']['role'] = 'user';

        // $file = UploadedFile::fake()->create('testfile.pdf', 100); // 100KB 크기의 PDF 파일
        // $data['file_sign'] = $file; // 여기서는 $data 배열에 직접 추가하는 대신, 파일을 요청에 별도로 추가합니다.

        // // 리퀘스트에 직접 값을 할당한다
        // $request->merge($data);
        // return response()->api([
        //     $request->all(),
        //     $request->file(),
        // ]);
        // return $this->service->store($request);
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
