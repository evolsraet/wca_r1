<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\NiceCarHistory;
use App\Libraries\SeedEncryptor;

class CarHistoryService
{
    public function getCarHistory(string $carNumber)
    {
        $sessionKey = 'car_history_' . $carNumber;

        // 세션에서 먼저 확인
        if (Session::has($sessionKey)) {
            Log::info("[카히스토리] 캐시된 데이터 사용 - 차량번호: {$carNumber}", [
                'name'=> '카히스토리 캐시된 데이터 사용',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'carNumber' => $carNumber
            ]);
            
            $data = Session::get($sessionKey);
            $data['is_session'] = true;

            return $data;
        }

        // 데이터베이스에서 확인
        $carHistory = NiceCarHistory::where('car_no', $carNumber)->first();
        if ($carHistory) {
            Log::info("[카히스토리] 데이터베이스에서 데이터 사용 - 차량번호: {$carNumber}", [
                'name'=> '카히스토리 데이터베이스에서 데이터 사용',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'carNumber' => $carNumber
            ]);

            $data['is_database'] = true;

            return $carHistory->data;
        }

        // 파라미터 준비
        $payload = [
            'joinCode'    => config('services.carHistory.join_code'),
            'sType'       => '1', // PC 요청
            'memberID'    => config('services.carHistory.member_id'),
            'carnum'      => $carNumber,
            'carNumType'  => '0',
            'stdDate'     => Carbon::now()->format('Ymd'),
            'rType'       => 'J',
        ];

        // dd($payload);

        Log::info("[카히스토리] API 요청 준비 완료", [
            'name'=> '카히스토리 API 요청 준비 완료',
            'path'=> __FILE__,
            'line'=> __LINE__,
            'payload' => $payload
        ]);

        try {
            $response = Http::asForm()->timeout(15)->post(config('services.carHistory.api_url'), $payload);

            if ($response->successful()) {
                $data = $response->json();

                Log::info("[카히스토리] 응답 수신 성공", [
                    'name'=> '카히스토리 응답 수신 성공',
                    'path'=> __FILE__,
                    'line'=> __LINE__,
                    'response' => $data
                ]);

                // 세션에 저장 
                // TODO: 캐시 저장기간 7일 
                Session::put($sessionKey, $data);
                Session::put($sessionKey . '_fetched_at', now());

                // 데이터베이스에 저장 
                // TODO: 접속기기, IP정보 추가
                NiceCarHistory::create([
                    'car_no' => $carNumber,
                    'first_regdate' => $data['r105'],
                    'data' => $data
                ]);

                return $data;
            }

            Log::error("[카히스토리] 응답 실패", [
                'name'=> '카히스토리 응답 실패',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error("[카히스토리] 요청 중 예외 발생", [
                'name'=> '카히스토리 요청 중 예외 발생',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'message' => $e->getMessage()
            ]);
        }

        return null;
    }


    private function seedEncrypt(string $plainText): string
    {
        $key = hex2bin(config('services.carHistory.encrypt_key'));
        $iv = config('services.carHistory.encrypt_iv');
    
        // 길이 확인 로그
        if (strlen($key) !== 16) {
            throw new \Exception('암호화 키는 16바이트여야 합니다.');
        }
    
        if (strlen($iv) !== 16) {
            throw new \Exception('IV는 16바이트여야 합니다.');
        }
    
        $padded = str_pad($plainText, 16 * ceil(strlen($plainText) / 16), "\0");
    
        $cipherText = openssl_encrypt(
            $padded,
            'seed-cbc',
            $key,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $iv
        );
    
        if ($cipherText === false) {
            throw new \Exception('SEED 암호화 실패');
        }
    
        return base64_encode($cipherText);
    }


    public function getCarHistoryCrash($carNumber)
    {

        // $carHistory = NiceCarHistory::where('car_no', $carNumber)->first();

        // $carHistory['data'] = json_decode(file_get_contents(storage_path('mock/car_history_sample.json')), true);

        $carHistory = $this->getCarHistory($carNumber);

        // dd($carHistory);

        // dd($carHistory);

        if ($carHistory) {
            Log::info("[카히스토리] 데이터베이스에서 데이터 사용 - 차량번호: {$carNumber}", [
                'name'=> '카히스토리 데이터베이스에서 데이터 사용',
                'path'=> __FILE__,
                'line'=> __LINE__,
                'carNumber' => $carNumber
            ]);


            $data = $carHistory;

            // dd($data);

            // data > r502 for / r502-01 가 1,2 면 내차피해 리스트, 3 이면 타차피해 리스트 
            $r502 = $data['r502'];
            $crashList = [];
            foreach ($r502 as $key => $value) {
                // 내차피해
                if ($value['r502-01'] == 1 || $value['r502-01'] == 2) {

                    $value_array = [
                        'group' => $value['r502-01'],
                        'crashDate' => date('Y-m-d', strtotime($value['r502-02'])),
                        'part' => number_format($value['r502-06']),
                        'labor' => number_format($value['r502-07']),
                        'paint' => number_format($value['r502-08']),
                        'cost' => number_format($value['r502-15']),
                    ];

                    $crashList['self'][] = $value_array;
                    $crashList['self_length'] = count($crashList['self']);
                }

                // 타차피해
                if ($value['r502-01'] == 3) {

                    $value_array = [
                        'group' => $value['r502-01'],
                        'crashDate' => date('Y-m-d', strtotime($value['r502-02'])),
                        'part' => number_format($value['r502-06']),
                        'labor' => number_format($value['r502-07']),
                        'paint' => number_format($value['r502-08']),
                        'cost' => number_format($value['r502-15']),
                    ];

                    $crashList['other'][] = $value_array; // 타차피해 리스트
                    $crashList['other_length'] = count($crashList['other']);
                } 

            }


            $r406_01 = $data['r406-01'];
            if ($r406_01 !== "") {
                foreach ($r406_01 as $key => $value) {
                    $crashList['special_crash']['basic'][] = $value;
                }
            }
            $crashList['special_crash']['basic_length'] = $data['r405'];

            $r408 = $data['r408-01'];
            if ($r408 !== "") {
                foreach ($r408 as $key => $value) {
                    $crashList['special_crash']['partial'][] = $value;
                }
            }
            $crashList['special_crash']['partial_length'] = $data['r407'];

            $r410 = $data['r410-01'];
            if ($r410 !== "") {
                foreach ($r410 as $key => $value) {
                    $crashList['special_crash']['theft'][] = $value;
                }
            }
            $crashList['special_crash']['theft_length'] = $data['r409'];


            // 차량용도
            $r103 = $data['r103'];
            switch ($r103) {
                case 1:
                    $crashList['car_use'] = '관용';
                    break;
                case 2:
                    $crashList['car_use'] = '자가용';
                    break;
                case 3:
                    $crashList['car_use'] = '영업용';
                    break;
                case 4:
                    $crashList['car_use'] = '개인택시';
                    break;
                default:
                    $crashList['car_use'] = '미분류';
                    break;
            }

            return $crashList;
        }


        // return [
        //     'carNumber' => '53라9319',
        //     'carHistory' => '53라9319',
        // ];
    }
}