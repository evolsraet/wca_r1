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

        $user = User::where('email', $request->email)->first();

        if ($user->status == 'ask') {
            throw new \Exception('심사중인 회원입니다.');
        } elseif ($user->status != 'ok') {
            throw new \Exception('정상회원이 아닙니다.');
        }

        $request->authenticate();

        $token = $request->session()->regenerate();
        $token = $request->user()->createToken($request->userAgent())->plainTextToken;

        if ($request->wantsJson()) {
            // return response()->json(['data' => $request->user(), 'token' => $token]);
            return response()->api([$request->user(), 'token' => $token]);
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

    /**
     * Create User
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        // dd('register');
        $user = User::where('email', $request['email'])->first();

        if ($user) {
            return response(['error' => 1, 'message' => 'user already exists'], 409);
        }

        $user = User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'name' => $request['name'],
        ]);

        $role = $request->role_id
            ? Role::find($request->role_id)
            : Role::where('name', 'user')->first();

        $user->assignRole($role);

        return new UserResource($user);

        return response()->api($user, '회원등록 되었습니다.');
        return $this->successResponse($user, 'Registration Successfully');
    }
}
