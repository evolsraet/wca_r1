<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\LibController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReviewController;

use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;


// Route::post('forgetPassword', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
// Route::post('resetPassword', [ResetPasswordController::class, 'reset'])->name('password.reset');



// sanctum 은 컨트롤러에서
Route::get('users/me', [UserController::class, 'me']);   // 본인가져오기
Route::get('users/abilities', [UserController::class, 'abilities']);
// Route::post('users/test', [UserController::class, 'test']);   // 회원가입은 인증없이
Route::post('users/confirmPassword', [UserController::class, 'confirmPassword'])->middleware('auth:sanctum');
Route::apiResource('users', UserController::class)
    ->middleware('auth:sanctum')
    ->withoutMiddleware('auth:sanctum', ['store', 'test']);

// auction
Route::post('auctions/carInfo', [AuctionController::class, 'carInfo']);
Route::apiResource('auctions', AuctionController::class)
    ->middleware('auth:sanctum');

// bid
Route::apiResource('bids', BidController::class)
    ->middleware('auth:sanctum');

// review
Route::apiResource('reviews', ReviewController::class)
    ->middleware('auth:sanctum')
    ->withoutMiddleware('auth:sanctum', ['index', 'show']);

// like
Route::post('likes/toggle/{likeable_type_model}/{likeable_id}', [LikeController::class, 'toggle'])->middleware('auth:sanctum');
Route::apiResource('likes', LikeController::class)->middleware('auth:sanctum');

Route::delete('media', [LibController::class, 'deleteMultipleMedia'])->middleware('auth:sanctum');
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