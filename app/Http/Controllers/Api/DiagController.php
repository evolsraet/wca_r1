<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DiagService;

class DiagController extends Controller
{
    protected DiagService $diagService;

    public function __construct(DiagService $diagService)
    {
        $this->diagService = $diagService;
    }

    // 진단 결과 조회
    public function diagnostic(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->diagService->getDiagData($request);
        return response()->api($result['data']);
    }

    // 진단 결과 조회
    public function diagnosticCheck(): \Illuminate\Http\JsonResponse
    {
        $result = $this->diagService->diagnosticCheck();
        return response()->api($result);
    }

    // 진단 코드
    public function diagnosticCode(): mixed
    {
        $result = $this->diagService->diagnosticCode();
        return $result;
    }
}