<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BoardController;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Api\AuctionController;
use App\Services\CarInfoService;
use Illuminate\Support\Facades\Cache;

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
        return response($html)->header('Content-Type', 'text/html');
    }

    return view('v2.pages.docs', [
        'html' => $html,
    ]);
})->where('doc', '[A-Za-z0-9\-_]+')->name('docs.show');

Route::get('/components/modals/{modal}', function ($modal) {
    return view("components.modals.{$modal}");
});

Route::prefix('auction')->group(function () {
    Route::get('/', function () {
        return view('v2.pages.auction.auctionList');
    })->name('auction.list');

    Route::get('/{id}', function ($id) {
        return view('v2.pages.auction.auctionDetail', ['id' => $id]);
    })->name('auction.detail');
});

Route::get('auctionform', function () {
    return view('v2.pages.auction.auctionRegisterForm');
})->name('auction.registerform');


Route::post('login', [AuthenticatedSessionController::class, 'login']);
Route::post('logout', [AuthenticatedSessionController::class, 'logout']);


Route::prefix('sell')->group(function () {

    Route::get('/', function (CarInfoService $carInfoService) {
        return view('v2.pages.sell.index');
    })->name('sell');

    Route::post('/result', function (Request $request, CarInfoService $carInfoService) {
        if (Auth::check()) {
            $carInfoService->storeUserQueryHistory(
                $request->input('owner'),
                $request->input('no')
            );
        }

        // Request 유효성 검사
        $request->validate([
            'owner' => 'required',
            'no' => 'required',
        ]);

        // 내부 API 호출
        $apiRequest = Request::create('/api/auctions/carInfo', 'POST', [
            'owner' => $request->input('owner'),
            'no' => $request->input('no'),
        ]);
        $response = app()->handle($apiRequest);
        $result = json_decode($response->getContent(), true);

        if ($response->getStatusCode() !== 200) {
            return redirect()->back()->with('error', $result['message'] ?? '조회 실패');
        }

        $carInfo = $result['data'] ?? null;

        if (!$carInfo || !is_array($carInfo)) {
            return redirect()->back()->with('error', $result['message'] ?? '차량 정보를 가져올 수 없습니다.');
        }

        return view('v2.pages.sell.result', compact('carInfo'));
    })->name('sell.result');

    Route::post('/apply', fn () => view('v2.pages.sell.apply'))->name('sell.apply');
});

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
        return view('v2.auth.passwords.email');
    })->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('/reset-password/{token}', function ($token) {
        return view('v2.auth.passwords.reset', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});
Route::post('logout', [AuthenticatedSessionController::class, 'logout']);
Route::get('modify', function () {
    return view('v2.auth.register');
})->name('modify');

Route::get('dealer-my', function () {
    return view('v2.auth.dealerMy');
})->name('dealer-my');

// 공유 모달 컴포넌트 라우트
Route::get('components/share-modal', function () {
    return view('components.modals.shareModal');
});

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

Route::get('/carhistory', function () {
    return view('v2.pages.carhistory');
})->name('carhistory');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('v2.admin.index');
    })->name('index');

    Route::get('/auction', function () {
        return view('v2.admin.auction.list');
    })->name('auction.list');

    Route::get('/auction/view/{id}', function ($id) {
        return view('v2.admin.auction.view');
    })->name('auction.view');

    Route::get('/auction/form/{id?}', function ($id = null) {
        return view('v2.admin.auction.form');
    })->name('auction.form');

});