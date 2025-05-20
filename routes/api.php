<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\LibController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\AddressbookController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Api\DiagController;
use App\Http\Controllers\Api\OwnershipController;
use App\Http\Controllers\Api\WebhookController;

// Route::post('forgetPassword', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
// Route::post('resetPassword', [ResetPasswordController::class, 'reset'])->name('password.reset');



// sanctum 은 컨트롤러에서
Route::get('users/me', [UserController::class, 'me'])->middleware('auth:sanctum');
Route::get('users/abilities', [UserController::class, 'abilities'])->middleware('auth:sanctum');
Route::get('users/test', [UserController::class, 'test']);   // 회원가입은 인증없이
Route::post('users/confirmPassword', [UserController::class, 'confirmPassword'])->middleware('auth:sanctum');
Route::get('users/resetPasswordLink/{phone}', [UserController::class, 'resetPasswordLink']);
Route::get('users/resetPasswordLogin/{encryptCode}', [UserController::class, 'resetPasswordLogin']);
Route::apiResource('users', UserController::class)
    ->middleware('auth:sanctum')
    ->withoutMiddleware('auth:sanctum', ['store', 'test']);


// auction
Route::post('auctions/carInfo', [AuctionController::class, 'carInfo']);
Route::apiResource('auctions', AuctionController::class)
    ->middleware(['auth:sanctum', 'convert.id.to.unique']);
Route::post('auctions/checkExpectedPrice', [AuctionController::class, 'CheckExpectedPrice']);
Route::post('auctions/allIngCount', [AuctionController::class, 'AllIngCount']);
Route::post('/auctions/{auction}/upload', [AuctionController::class, 'uploadFile']);
Route::post('auctions/entryPublic', [AuctionController::class, 'entryPublic']);

// bid
Route::apiResource('bids', BidController::class)
    ->middleware('auth:sanctum');

// review
Route::get('/reviews', [ReviewController::class, 'index']);
Route::apiResource('reviews', ReviewController::class)
    ->middleware('auth:sanctum')
    ->withoutMiddleware('auth:sanctum', ['index', 'show']);

Route::apiResource('board', BoardController::class);
Route::apiResource('board.articles', ArticleController::class);
Route::apiResource('comments', CommentController::class);
// dd(Route::getRoutes());
// like
Route::post('likes/toggle/{likeable_type_model}/{likeable_id}', [LikeController::class, 'toggle'])->middleware('auth:sanctum');
Route::apiResource('likes', LikeController::class)->middleware('auth:sanctum');

Route::apiResource('addressbooks', AddressbookController::class)->middleware('auth:sanctum');

Route::delete('media/{uuid}', [LibController::class, 'deleteMultipleMedia'])->middleware('auth:sanctum');
Route::get('lib/fields/{table}', [LibController::class, 'fields'])->middleware('auth:sanctum');
Route::get('lib/enums/{table}', [LibController::class, 'enums'])->middleware('auth:sanctum');

// 중간에 config('auth.defaults.guard') 가 sanctum 으로 바뀌는 현상 확인용
// Route::put('defaultGuard', function () {
//     return response()->json(['defaultGuard' => config('auth.defaults.guard')]);
// });
// Route::put('users/defaultGuard', [UserController::class, 'defaultGuard']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::put('auctions/statusUpdate/{id}', [AuctionController::class, 'statusUpdate']);


    // Route::apiResource('caches', UserController::class);

    // 2024.6.25 삭제 - 최후에 남았던것. 이상있으면 돌리기 : 실사용 위주로 돌리기
    // Route::apiResource('posts', PostController::class);
    // Route::apiResource('categories', CategoryController::class);
    // Route::apiResource('roles', RoleController::class);
    // Route::apiResource('permissions', PermissionController::class);


    // Route::get('role-list', [RoleController::class, 'getList']);
    // Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    // Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    // Route::get('category-list', [CategoryController::class, 'getList']);

    // Route::put('/user', [ProfileController::class, 'update']); // 본인수정하기

});

// Route::get('category-list', [CategoryController::class, 'getList']);
// Route::get('get-posts', [PostController::class, 'getPosts']);
// Route::get('get-category-posts/{id}', [PostController::class, 'getCategoryByPosts']);
// Route::get('get-post/{id}', [PostController::class, 'getPost']);

Route::get('payment', [PaymentController::class, 'showPaymentForm']);
Route::post('payment/result', [PaymentController::class, 'resultPayment']);
Route::post('payment/result2', [PaymentController::class, 'resultPayment2']);
Route::post('payment/request', [PaymentController::class, 'requestPayment']);
Route::post('payment/notify', [PaymentController::class, 'notify']);

Route::get('diagRequest', [DiagController::class, 'diagnostic']);
Route::get('diag/result', [DiagController::class, 'diagnostic']);
Route::get('diag/code', [DiagController::class, 'diagnosticCode']);
Route::get('diagnostic-check', [DiagController::class, 'diagnosticCheck']);

Route::get('carHistory', [AuctionController::class, 'getCarHistory']);
Route::get('carHistoryMock', [AuctionController::class, 'getCarHistoryMock']);
Route::get('carHistoryCrash', [AuctionController::class, 'getCarHistoryCrash']);

Route::get('getNiceDnr', [AuctionController::class, 'getNiceDnr']);
Route::get('getNiceDnrHistory', [AuctionController::class, 'getNiceDnrHistory']);

Route::post('check-business', [BusinessController::class, 'check']);
// Route::get('get-access-token', [BusinessController::class, 'getAccessToken']);
Route::post('get-certification-data', [BusinessController::class, 'getCertificationData']);
Route::post('clear-certification-data', [BusinessController::class, 'clearCertificationData']);

Route::get('get-car-price', [AuctionController::class, 'getCarPrice']);

Route::get('name-change', [AuctionController::class, 'nameChange']);
Route::get('name-change-status', [AuctionController::class, 'nameChangeStatus']);
Route::post('name-change-file-upload', [AuctionController::class, 'nameChangeFileUpload']);
Route::post('/auctions/{auction}/name-change-file-upload', [AuctionController::class, 'nameChangeFileUpload']);
Route::get('name-change-status-all', [AuctionController::class, 'nameChangeStatusAll']);
Route::get('name-change-status-all-test', [AuctionController::class, 'processCompletedNameChangeAuctions']);
// Route::get('name-change-status-all-test', [AuctionController::class, 'nameChangeStatusAll']);

Route::get('test-auctions-notification', [AuctionController::class, 'testAuctionsNotification']);

Route::get('test-taksong-service', [AuctionController::class, 'testTaksongService']);

// Route::get('diagnostic-check', [AuctionController::class, 'diagnosticCheck']);


// 명의이전 알림 
Route::get('ownership/manual-notify/{auctionId}', [OwnershipController::class, 'manualNotify']);
Route::get('ownership/check/{auctionId}', [OwnershipController::class, 'checkOwnership']);
Route::get('ownership/check-test', [OwnershipController::class, 'checkOwnershipByApiTest']);

// 탁송 웹훅
Route::post('taksong/status_change', [WebhookController::class, 'statusChange']);
