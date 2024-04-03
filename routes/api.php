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


// Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
// Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::post('users', [UserController::class, 'store']);   // 회원가입은 인증없이
Route::post('users/test', [UserController::class, 'test']);   // 회원가입은 인증없이
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::put('auctions/statusUpdate/{id}', [AuctionController::class, 'statusUpdate']);

    Route::apiResource('auctions', AuctionController::class);
    Route::apiResource('bids', BidController::class);
    // Route::apiResource('reviews', ReviewController::class);
    // Route::apiResource('likes', LikeController::class);

    Route::get('users/me', [UserController::class, 'me']);   // 본인가져오기
    Route::get('users/abilities', [UserController::class, 'abilities']);
    Route::apiResource('users', UserController::class)->except(['store']);

    // Route::apiResource('caches', UserController::class);

    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);

    Route::get('lib/fields/{table}', [LibController::class, 'fields']);
    Route::get('lib/enums/{table}', [LibController::class, 'enums']);

    // Route::get('role-list', [RoleController::class, 'getList']);
    // Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    // Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    // Route::get('category-list', [CategoryController::class, 'getList']);

    // Route::put('/user', [ProfileController::class, 'update']); // 본인수정하기

});

Route::get('category-list', [CategoryController::class, 'getList']);
Route::get('get-posts', [PostController::class, 'getPosts']);
Route::get('get-category-posts/{id}', [PostController::class, 'getCategoryByPosts']);
Route::get('get-post/{id}', [PostController::class, 'getPost']);
