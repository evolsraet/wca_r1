<?php

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
// use App\Http\Controllers\PaymentController;

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

Route::post('login', [AuthenticatedSessionController::class, 'login']);
// Route::post('register', [AuthenticatedSessionController::class, 'register']);
Route::post('logout', [AuthenticatedSessionController::class, 'logout']);

Route::get('excelDown/{resource}', function ($resource) {

    // Log::info('엑셀 다운로드 라우트 실행: ' . $resource);
    // Log::info(auth()->check());
    // Log::info('EOF');

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

Route::view('/{any?}', 'main-view')
    ->name('dashboard')
    ->where('any', '.*');


// Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
// Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');