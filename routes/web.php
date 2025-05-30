<?php

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Services\AuctionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
// use App\Http\Controllers\PaymentController;

use App\Http\Controllers\NiceApiController;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Board\BoardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (app()->environment('production')) {
        return redirect('/v1');
    }
    return redirect('/v2');
});

Route::get('/v1', function () {
    return view('v1.main-view');
});


// V1 용 auth
Route::post('login', [AuthenticatedSessionController::class, 'login']);
// Route::post('register', [AuthenticatedSessionController::class, 'register']);
Route::post('logout', [AuthenticatedSessionController::class, 'logout']);


// v2 prefix
Route::prefix('v2')->group(function () {
    // v2 Routes
    Route::get('/', function () {
        // return view('v2.pages.home');
        $user = Auth::user();
        if ($user) {
            return view('v2.pages.userMain');
        } else {
            return view('v2.pages.home');
        }
    })->name('home');

    Route::get('/test', function () {
        return view('v2.pages.test');
    });

    Route::get('/introduce', function () {
        return view('v2.pages.introduce');
    });

    Route::get('/test/upload', function () {
        return view('v2.test.upload');
    });

    Route::get('/user-main', function () {
        return view('v2.pages.userMain');
    });

    // docs 라우트 / docs 폴더에 html 파일을 찾아서 보여줌
    Route::get('/docs/{doc}', function ($doc) {
        $filePath = resource_path("v2/docs/{$doc}.html");

        if (!file_exists($filePath)) {
            abort(404, '문서를 찾을 수 없습니다.');
        }

        $html = file_get_contents($filePath);

        // 쿼리 파라미터가 ?raw=1 인 경우 원본 HTML 출력
        if (request()->query('raw') === '1') {
            $html = preg_replace('/<h1[^>]*>.*?<\/h1>/s', '', $html);
            return response($html)->header('Content-Type', 'text/html');
        }

        return view('v2.pages.docs', [
            'html' => $html,
        ]);
    })->where('doc', '[A-Za-z0-9\-_]+')->name('docs.show');

    Route::get('/auction', function () {
        return view('v2.pages.auction.auctionList');
    })->name('auction.list');

    Route::post('login', [AuthenticatedSessionController::class, 'login']);
    Route::post('logout', [AuthenticatedSessionController::class, 'logout']);

    Route::get('/sell', function () {
        return view('v2.pages.sell');
    })->name('sell');

    Route::get('/style-guide', function () {
        return view('v2.pages.styleGuide');
    })->name('style-guide');

    // 인증 관련 라우트
    Route::middleware('guest')->group(function () {
        Route::get('/login', function () {
            return view('v2.auth.login');
        })->name('login');

        Route::get('/register', function () {
            return view('v2.auth.register');
        })->name('register');

        // 비밀번호 재설정 라우트
        Route::get('/forgot-password', function () {
            return view('v2.auth.forgot-password');
        })->name('password.request');

        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('password.email');

        Route::get('/reset-password/{token}', function ($token) {
            return view('v2.auth.reset-password', ['token' => $token]);
        })->name('password.reset');

        Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
            ->name('password.update');
    });
    Route::post('logout', [AuthenticatedSessionController::class, 'logout']);
    Route::get('modify', function () {
        return view('v2.auth.register');
    })->name('modify');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });

    // 게시판 라우트 그룹
    Route::prefix('board/{boardId}')->name('board.')->group(function () {
        // 목록 페이지
        Route::get('/', [BoardController::class, 'list'])->name('list');

        // 상세 페이지
        Route::get('/view/{articleId}', [BoardController::class, 'view'])->name('view');

        // 글쓰기/수정 페이지
        Route::get('/form/{articleId?}', [BoardController::class, 'form'])->name('form');
    });
});


// 엑셀파일 다운로드
Route::get('excelDown/{resource}', function ($resource) {

    if (!auth()->check() || !auth()->user()->hasPermissionTo('act.admin')) {
        return abort(401, '로그인이 필요합니다.');
    }


    $controller = '\App\Http\Controllers\Api\\' . Str::studly(Str::singular($resource)) . 'Controller';
    if (empty($resource)) {
        return abort(400, '리소스 이름이 필요합니다.');
    }
    if (class_exists($controller)) {
        request()->merge(['mode' => 'excelDown']);
        return app()->make($controller)->index();
    }

    return abort(404, '컨트롤러가 존재하지 않습니다.');
})->name('excelDown/');


// SNS 간편로그인 리다이렉트
Route::middleware(['web'])->group(function () {
    Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
});

// v1 fallback 라우트
Route::view('/v1/{any?}', 'v1.main-view')
    ->name('dashboard')
    ->where('any', '.*');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});


// Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
// Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');


