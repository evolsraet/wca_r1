<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Auction;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User routes
Route::post('/users', [App\Http\Controllers\Api\UserController::class, 'store']);
Route::post('/users/reset-password-link', [App\Http\Controllers\Api\UserController::class, 'resetPasswordLink']);

Route::post('/create-test-user-and-token', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|string|in:user,dealer', // Added role validation
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Assign role
    if ($request->role === 'dealer') {
        $user->assignRole('dealer');
    } else {
        $user->assignRole('user');
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['user' => $user, 'token' => $token]);
});

Route::post('/auctions', function (Request $request) {
    $request->validate([
        'auction.user_id' => 'required|exists:users,id',
        'auction.car_no' => 'required|string|max:255',
        'auction.car_maker' => 'required|string|max:255',
        'auction.car_model' => 'required|string|max:255',
        'auction.car_year' => 'required|string',
        'auction.car_km' => 'required|integer',
        
        'auction.start_price' => 'required|numeric',
        'auction.min_bid_price' => 'required|numeric',
        'auction.auction_type' => 'required|boolean', // Ensure this is boolean
        'auction.status' => 'required|string',
    ]);

    $auction = new Auction();
    $auction->user_id = $request->input('auction.user_id');
    $auction->car_no = $request->input('auction.car_no');
    $auction->car_maker = $request->input('auction.car_maker');
    $auction->car_model = $request->input('auction.car_model');
    $auction->car_year = $request->input('auction.car_year');
    $auction->car_km = $request->input('auction.car_km');
    $auction->auction_start_at = $request->input('auction.auction_start_at');
    $auction->auction_end_at = $request->input('auction.auction_end_at');
    $auction->start_price = $request->input('auction.start_price');
    $auction->min_bid_price = $request->input('auction.min_bid_price');
    $auction->auction_type = $request->input('auction.auction_type');
    $auction->status = $request->input('auction.status');
    $auction->save();

    return response()->json(['auction' => $auction], 201);
})->middleware('auth:sanctum');

Route::post('/bids', function (Request $request) {
    $request->validate([
        'bid.auction_id' => 'required|exists:auctions,id',
        'bid.price' => 'required|numeric',
    ]);

    // Simulate BidService logic for testing
    $auction = Auction::find($request->input('bid.auction_id'));

    if (!$auction || $auction->status !== 'ing') {
        return response()->json(['message' => '신청가능한 경매가 아닙니다.'], 400);
    }

    // In a real scenario, you'd call the BidService here
    // For testing, we'll just return success
    return response()->json(['message' => '입찰 성공'], 201);
})->middleware('auth:sanctum');

// Auction routes
Route::post('/auctions/car-info', [App\Http\Controllers\Api\AuctionController::class, 'carInfo']);
Route::post('/auctions/check-expected-price', [App\Http\Controllers\Api\AuctionController::class, 'checkExpectedPrice']);

// Article routes
Route::post('/articles', [App\Http\Controllers\Api\ArticleController::class, 'store']);

// Comment routes
Route::post('/comments', [App\Http\Controllers\Api\CommentController::class, 'store']);
