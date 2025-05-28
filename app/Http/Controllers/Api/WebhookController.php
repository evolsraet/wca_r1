<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\TaksongService;
use App\Services\ApiRequestService;

class WebhookController extends Controller
{
    // 탁송 상태 변경
    public function statusChange(Request $request)
    {
        $data = $request->all();

        Log::info('[웹훅 수신] 탁송 상태 변경', [
            'name'=> '웹훅 수신 탁송 상태 변경',
            'path'=> __FILE__,
            'line'=> __LINE__,
            'data' => $data
        ]);

        $taksongService = new TaksongService(new ApiRequestService());
        $taksongService->processStatus($data);

        if($taksongService){
            return response()->json([
                'message' => '웹훅 정상 수신',
                'received_status' => $data['chk_status'] ?? null
            ], 200);
        }
        return response()->json([
            'message' => '웹훅 수신 실패',
            'received_status' => null
        ], 400);
    }

}
