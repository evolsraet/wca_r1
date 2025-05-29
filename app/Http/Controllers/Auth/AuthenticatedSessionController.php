<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        // return response()->api(json_encode($request));

        // return response()->json(['message' => 'fail', 'errors' => [
        //     'laravel' => 'laravel errors',
        //     'debbug' => 'hahahoho',
        // ]], 404);

        $user = User::where('email', $request->email)->orWhere('phone', $request->email)->first();

        Log::info('[로그인] 사용자 정보', [
            'name'=> '로그인 사용자 정보',
            'path'=> __FILE__,
            'line'=> __LINE__,
            'user' => $user
        ]);

        if(!$user){
            throw new \Exception('등록된 회원이 아닙니다.');
        }
        // 사용자 상태에 따른 로그인 제한 처리
        if ($user->status == 'ask') {
            throw new \Exception('심사중인 회원입니다.');
        } elseif ($user->status == 'warning1') {
            // 경고1 상태인 경우 3일 동안 로그인 제한
            $penaltyDays = 3;
            $penaltyUntil = now()->addDays($penaltyDays);

            // penalty_until 필드가 있는 경우에만 업데이트
            if (Schema::hasColumn('users', 'penalty_until')) {
                $user->penalty_until = $penaltyUntil;
                $user->save();
            }

            throw new \Exception("경고1 상태로 {$penaltyDays}일 동안 로그인이 제한됩니다. (" . $penaltyUntil->format('Y-m-d H:i') . "까지)");
        } elseif ($user->status == 'warning2') {
            // 경고2 상태인 경우 1개월 동안 로그인 제한
            $penaltyDays = 30;
            $penaltyUntil = now()->addDays($penaltyDays);

            // penalty_until 필드가 있는 경우에만 업데이트
            if (Schema::hasColumn('users', 'penalty_until')) {
                $user->penalty_until = $penaltyUntil;
                $user->save();
            }

            throw new \Exception("경고2 상태로 {$penaltyDays}일 동안 로그인이 제한됩니다. (" . $penaltyUntil->format('Y-m-d H:i') . "까지)");
        } elseif ($user->status == 'expulsion') {
            // 제명 상태인 경우 영구적으로 로그인 제한
            throw new \Exception('제명 상태로 로그인이 불가능합니다. 관리자에게 문의하세요.');
        } elseif ($user->status != 'ok') {
            throw new \Exception('정상회원이 아닙니다.');
        }

        // penalty_until 필드가 있고, 패널티 기간이 설정되어 있는 경우 확인
        if (Schema::hasColumn('users', 'penalty_until') && $user->penalty_until) {
            if (now()->lt($user->penalty_until)) {
                // 패널티 기간이 아직 지나지 않은 경우
                throw new \Exception('패널티 기간이 종료될 때까지 로그인이 제한됩니다. (' . $user->penalty_until->format('Y-m-d H:i') . '까지)');
            } else {
                // 패널티 기간이 지난 경우 패널티 정보 초기화
                $user->penalty_until = null;
                $user->save();
            }
        }

        $request->authenticate();

        $token = $request->session()->regenerate();
        $token = $request->user()->createToken($request->userAgent())->plainTextToken;

        if ($request->wantsJson()) {
            // return response()->json(['data' => $request->user(), 'token' => $token]);
            return response()->api(['user' => new UserResource($request->user()), 'token' => $token]);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();


        if ($request->wantsJson()) {
            return response()->api();
            // return response()->noContent();
        }

        return redirect('/');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
