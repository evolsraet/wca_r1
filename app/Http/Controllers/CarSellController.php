<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CarInfoService;
use Illuminate\Support\Facades\Log;

class CarSellController extends Controller
{
    /**
     * 차량 정보 조회 결과 페이지를 표시합니다.
     *
     * @param Request $request
     * @param CarInfoService $carInfoService
     * @return \Illuminate\View\View
     */
    public function result(Request $request, CarInfoService $carInfoService)
    {
        $carInfoService->storeUserQueryHistory(
            $request->input('owner'),
            $request->input('no')
        );

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
    }
} 