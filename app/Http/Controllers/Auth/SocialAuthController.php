<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSns;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\UserResource;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            Log::error('Social Login Redirect Error: ' . $e->getMessage());
            return response()->json([
                'isError' => true,
                'isAlert' => true,
                'msg' => '소셜 로그인 연결 중 오류가 발생했습니다.'
            ]);
        }
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // 1. user_sns 테이블에서 확인
            $userSns = UserSns::where('provider', $provider)
                            ->where('provider_id', $socialUser->getId())
                            ->first();

            $response = null;

            if ($userSns) {
                $user = $userSns->user;
                Auth::login($user, true);
                
                $response = [
                    'isError' => false,
                    'isSuccess' => true,
                    'data' => [
                        'user' => new UserResource($user)
                    ]
                ];
            } else {
                // 2. 이메일로 기존 회원 확인
                $user = User::where('email', $socialUser->getEmail())->first();
                
                if ($user) {
                    UserSns::create([
                        'user_id' => $user->id,
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'provider_email' => $socialUser->getEmail(),
                        'provider_data' => json_encode($socialUser->user)
                    ]);

                    Auth::login($user, true);
                    
                    $response = [
                        'isError' => false,
                        'isSuccess' => true,
                        'data' => [
                            'user' => new UserResource($user)
                        ]
                    ];
                } else {
                    // 3. 회원가입 필요
                    $response = [
                        'isError' => true,
                        'isAlert' => true,
                        'msg' => '회원가입이 필요합니다.',
                        'needRegister' => true,  // 회원가입 필요 플래그 추가
                        'social_data' => [
                            'provider' => $provider,
                            'provider_id' => $socialUser->getId(),
                            'name' => $socialUser->getName(),
                            'email' => $socialUser->getEmail(),
                            'provider_data' => $socialUser->user
                        ]
                    ];
                }
            }

            // 로그인 페이지로 리다이렉트하면서 데이터 전달
            return redirect('/login?social_callback=' . urlencode(json_encode($response)));

        } catch (\Exception $e) {
            Log::error('Social Login Error: ' . $e->getMessage());
            return redirect('/login?social_callback=' . urlencode(json_encode([
                'isError' => true,
                'isAlert' => true,
                'msg' => '소셜 로그인 처리 중 오류가 발생했습니다.'
            ])));
        }
    }
}