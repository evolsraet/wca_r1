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
use App\Http\Controllers\BoardController;

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

Route::get('/files/download/{uuid}', function ($uuid) {
    $file = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('uuid', $uuid)->first();
    if (!$file) {
        return abort(404, '파일을 찾을 수 없습니다.');
    }
    // dd($file->file_name);
    return response()->download($file->getPath(), );
})->name('files.download');

// v2 prefix
Route::prefix('v2')->group(base_path('routes/v2.php'));

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


