<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CodefService;

class BusinessController extends Controller
{
    protected $codefService;

    public function __construct(CodefService $codefService)
    {
        $this->codefService = $codefService;
    }

    public function check(Request $request)
    {
        // $request->validate([
        //     'businessNumber' => 'required|string|min:10|max:10',
        // ]);

        // $data = [
        //     'organization' => 'PUBLIC',
        //     'loginType' => '0',
        //     // 'identity' => $request->businessNumber,
        //     'identity' => '2281702211',
        //     'identityEncYn' => 'N',
        //     'startDate' => '20250327',
        //     'usePurposes' => '사업자여부',
        // ];

        $data = $request->all();

        if($data['twoWayAuth'] === true){
            unset($data['twoWayAuth']);
            $result = $this->codefService->twoWayAuth($data);
        }else{
            unset($data['twoWayAuth']);
            $result = $this->codefService->checkBusinessStatus($data);
        }

        return response()->json($result);
    }


    public function getAccessToken()
    {
        $accessToken = $this->codefService->getAccessToken();
        return response()->json(['accessToken' => $accessToken]);
    }

    public function getCertificationData(Request $request)
    {
        $carNumber = $request->carNumber;
        $rawData = $this->codefService->getCertificationData($carNumber);
        
        return response()->json([
            'data' => $rawData
        ]);
    }

    public function clearCertificationData(Request $request)
    {
        $carNumber = $request->carNumber;
        $this->codefService->getCertificationClearData($carNumber);
        return response()->json(['message' => '인증 데이터가 삭제되었습니다.']);
    }
}