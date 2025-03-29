<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\NiceDNRData;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NiceDNRService
{

    private $apiKey;
    private $chkSec;
    private $chkKey;
    private $loginId;
    private $kindOf;
    private $endpointKey;
    public function __construct()
    {
        $this->apiKey = config('niceDnr.NICE_DNR_API_KEY');
        $this->chkSec = date('YmdHis');
        $this->chkKey = $this->calculateCheckKey($this->chkSec, config('niceDnr.NICE_DNR_API_BUSINESS_NUMBER'));
        $this->loginId = config('niceDnr.NICE_DNR_API_LOGIN_ID');
        $this->kindOf = config('niceDnr.NICE_DNR_API_KIND_OF');
        $this->endpointKey = config('niceDnr.NICE_DNR_API_ENDPOINT_KEY');
    }

    // Nice DNR API 호출
    public function getNiceDnr($ownerNm, $vhrNo, $key)
    {
        if($this->endpointKey !== $key) {
            $msg = [
                'error' => 'Invalid endpoint key',
                'count' => 0
            ];
            return $msg;
        }

        $cacheKey = 'niceDnr_' . $ownerNm . '_' . $vhrNo;

        // 1. 캐시에서 먼저 확인
        $cachedData = Cache::get($cacheKey);
        if ($cachedData) {
            return $cachedData;
        }

        // 2. 캐시에 없으면 DB에서 확인
        $dbData = NiceDNRData::where('ownerNm', $ownerNm)
            ->where('vhrNo', $vhrNo)
            ->first();

        if ($dbData) {
            // DB 데이터를 캐시에 저장
            Cache::put($cacheKey, $dbData->data, 60 * 60 * 24 * 30);
            
            return $dbData->data;
        }

        // 3. DB에도 없으면 API 호출
        try {
            $response = Http::get(config('niceDnr.NICE_DNR_API_URL'), [
                'loginId'  => $this->loginId,
                'kindOf'   => $this->kindOf,
                'apiKey'   => $this->apiKey,
                'chkSec'   => $this->chkSec,
                'chkKey'   => $this->chkKey,
                'ownerNm'  => $ownerNm,
                'vhrNo'    => $vhrNo,
            ]);

            Log::info('Nice DNR API 호출 결과', $response->json());

            $responseData = $response->json();

            if($responseData){
                $responseData['carInfo'] = [
                    'resFirstDate' => $responseData['carParts']['outB0001']['list'][0]['resFirstDate'],
                    'classModelNm' => $responseData['carSise']['info']['carinfo']['classModelNm'],
                    'yearType' => $responseData['carSise']['info']['carinfo']['yearType'],
                    'km' => $responseData['carParts']['outB0001']['list'][0]['resValidDistance'],
                ];

                // API 응답 데이터를 DB에 저장
                NiceDNRData::create([
                    'ownerNm'  => $ownerNm,
                    'vhrNo'    => $vhrNo,
                    'isCached' => 'true',
                    'data'     => $responseData
                ]);

                // API 응답 데이터를 캐시에도 저장
                Cache::put($cacheKey, $responseData, 60 * 60 * 24 * 30);

            }

            return $responseData;

        } catch (\Exception $e) {
            Log::error('Nice DNR API 호출 실패: ' . $e->getMessage(), [
                'ownerNm' => $ownerNm,
                'vhrNo' => $vhrNo
            ]);

            return [
                'error' => 'No data',
                'count' => 0
            ];
        }
    }


    // 체크키 계산
    private function calculateCheckKey($chkSec, $businessNumber) 
    {
        if (!is_numeric($businessNumber) || $businessNumber == 0) {
            return 0; // 또는 다른 기본값
        }
        return (($chkSec % $businessNumber) % 997);
    }


    public function getNiceDnrHistory($ownerNm, $vhrNo)
    {

        $dbData = NiceDNRData::where('ownerNm', $ownerNm)
            ->where('vhrNo', $vhrNo)
            ->first();

        if ($dbData) {
            $resContentsList = $dbData->data['carParts']['outB0001']['list'][0]['resContentsList'];
            $resContentsList = array_filter($resContentsList, function($item) {
                return $item['resContentsCtg'] == '명의이전등록';
            });
            $userChangeCount = count($resContentsList);

            $resUseHistYn = $dbData->data['carParts']['outB0001']['list'][0]['resUseHistYn'] ?? 0; // 용도이력
            $resUseHistBiz = $dbData->data['carParts']['outB0001']['list'][0]['resUseHistBiz'] ?? 0; // 사업자
            $resUseHistRent = $dbData->data['carParts']['outB0001']['list'][0]['resUseHistRent'] ?? 0; // 렌트
            $resUseHistGov = $dbData->data['carParts']['outB0001']['list'][0]['resUseHistGov'] ?? 0; // 공공기관

            $mortCt = $dbData->data['carParts']['outB0001']['list'][0]['mortCt'] ?? 0; // 저당
            $seizCt = $dbData->data['carParts']['outB0001']['list'][0]['seizCt'] ?? 0; // 압류

            return [
                'userChangeCount' => $userChangeCount,
                'resUseHistYn' => $resUseHistYn,
                'resUseHistBiz' => $resUseHistBiz,
                'resUseHistRent' => $resUseHistRent,
                'resUseHistGov' => $resUseHistGov,
                'mortCt' => $mortCt,
                'seizCt' => $seizCt
            ];

        }else{
            return [
                'error' => 'No data',
                'count' => 0
            ];
        }

        // return $dbData;
    }

    
}