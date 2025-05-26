<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        // 예시: 탁송 상태 처리
        // if (isset($data['chk_api_id'])) {
        //     \App\Models\Auction::where('id', $data['chk_api_id'])->update([
        //         'status' => $data['chk_status'],
        //         'taksong_fee' => $data['chk_courier_fee'] ?? null,
        //         'taksong_updated_at' => now(),
        //     ]);

        //     // 이미지 처리도 나중에 추가 가능
        // }

        // 파일 오는것은 ? 
        // 서버에 파일로 저장 해서 보여 주는 방식으로 ? 

        return response()->json([
            'message' => '웹훅 정상 수신',
            'received_status' => $data['chk_status'] ?? null
        ], 200);
    }

}
