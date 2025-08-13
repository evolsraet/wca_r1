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
use App\Services\CarInfoService;

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
     * @param  \App\Services\CarInfoService  $carInfoService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request, CarInfoService $carInfoService)
    {
        // DELME: 개발환경에서 세션 기반 로그인 처리
        // if (app()->environment('local', 'testing') && $this->isDevelopmentLogin($request)) {
        //     return $this->handleDevelopmentLogin($request);
        // }

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
        } elseif ($user->status != 'ok' && $user->penalty_until ) {
            if (now()->lt($user->penalty_until)) {
                // 패널티 기간이 아직 지나지 않은 경우
                throw new \Exception('패널티 기간이 종료될 때까지 로그인이 제한됩니다. (' . $user->penalty_until->format('Y-m-d H:i') . '까지)');
            } else {
                // 패널티 기간이 지난 경우 패널티 정보 초기화
                $user->penalty_until = null;
                $user->status = 'ok';
                $user->save();
            }
        /*
           TODO: 경고 기능 제외 - 별도 테이블 생성 후 로그 남길것 + 페널티 서비스로 등록 제거 만들것 
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
        */
        } elseif ($user->status != 'ok') {
            throw new \Exception('정상회원이 아닙니다.');
        }

        $request->authenticate();

        $token = $request->session()->regenerate();
        $token = $request->user()->createToken($request->userAgent())->plainTextToken;

        Log::info('[로그인] 세션 히스토리 마이그레이션 시작', [
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email
        ]);

        // DELME: 로그인 성공 후 세션의 히스토리를 사용자 Cache로 마이그레이션
        // $carInfoService->migrateSessionHistoryToUserCache();
        
        Log::info('[로그인] 세션 히스토리 마이그레이션 완료', [
            'user_id' => $request->user()->id
        ]);

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

    /**
     * 개발환경 로그인 여부 확인
     */
    // DELME: 개발환경에서 세션 기반 로그인 처리
    private function isDevelopmentLogin($request)
    {
        // 개발환경에서 세션 기반 인증을 사용하는지 확인
        // 예: 특정 세션 키가 있거나, 특정 플래그가 있는 경우
        return $request->has('dev_mode') || 
               $request->session()->has('dev_auth_enabled') ||
               $request->header('X-Dev-Mode') === 'true';
    }

    /**
     * 개발환경 로그인 처리
     */
    // DELME: 개발환경에서 세션 기반 로그인 처리
    private function handleDevelopmentLogin($request)
    {
        Log::info('[개발환경 로그인] 세션 기반 인증 시작', [
            'email' => $request->email,
            'session_id' => $request->session()->getId(),
            'ip' => $request->ip()
        ]);

        // 세션에서 개발 인증 정보 확인
        $devAuth = $request->session()->get('dev_login_auth');
        $devAuthTimestamp = $request->session()->get('dev_login_auth_timestamp');
        $currentTime = time();
        
        // 세션 유효 시간: 1시간
        $sessionValidDuration = 3600;

        // 기존 개발 세션이 유효한 경우
        if ($devAuth && $devAuthTimestamp && 
            ($currentTime - $devAuthTimestamp) < $sessionValidDuration) {
            
            Log::info('[개발환경 로그인] 기존 세션 사용');
            
            // 세션에서 사용자 정보 가져오기
            $userData = $request->session()->get('dev_user_data');
            
            if ($userData && isset($userData['id'])) {
                $user = User::find($userData['id']);
                
                if ($user) {
                    Auth::login($user);
                    $request->session()->regenerate();
                    
                    $token = $user->createToken($request->userAgent())->plainTextToken;
                    
                    Log::info('[개발환경 로그인] 세션 기반 로그인 성공', [
                        'user_id' => $user->id,
                        'user_name' => $user->name
                    ]);

                    if ($request->wantsJson()) {
                        return response()->api([
                            'user' => new UserResource($user), 
                            'token' => $token,
                            'dev_mode' => true,
                            'message' => '개발환경 세션 기반 로그인 성공'
                        ]);
                    }

                    return redirect()->intended(RouteServiceProvider::HOME);
                }
            }
        }

        // 새로운 개발 세션 생성이 필요한 경우
        Log::info('[개발환경 로그인] 새로운 세션 인증 필요');
        
        // 실제 사용자 확인 (이메일/전화번호로)
        $user = User::where('email', $request->email)
                   ->orWhere('phone', $request->email)
                   ->first();

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => '개발환경: 등록된 회원이 아닙니다.',
                    'dev_mode' => true
                ], 404);
            }
            
            return back()->withErrors(['email' => '개발환경: 등록된 회원이 아닙니다.']);
        }

        // 개발환경에서는 비밀번호 검증을 스킵하고 세션에 인증 정보 저장
        $request->session()->put('dev_login_auth', true);
        $request->session()->put('dev_login_auth_timestamp', $currentTime);
        $request->session()->put('dev_user_data', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);

        // 사용자 로그인 처리
        Auth::login($user);
        $request->session()->regenerate();
        
        $token = $user->createToken($request->userAgent())->plainTextToken;

        Log::info('[개발환경 로그인] 새로운 세션 생성 및 로그인 성공', [
            'user_id' => $user->id,
            'user_name' => $user->name
        ]);

        if ($request->wantsJson()) {
            return response()->api([
                'user' => new UserResource($user), 
                'token' => $token,
                'dev_mode' => true,
                'message' => '개발환경 세션 기반 로그인 성공 (새 세션 생성)'
            ]);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
