<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TaksongStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $response;
    protected $endPoint;
    public function __construct($response)
    {
        $this->response = $response;
        $this->endPoint = 'https://check-dev.wecarmobility.co.kr/api/outside/check/taksong_status';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->taksongStatus($this->response);
    }

    protected function taksongStatus($response)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->endPoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('auth' => env('TAKSONG_AUTH'),'chk_id' => '10010962','api_key' => env('TAKSONG_API_KEY')),
        // CURLOPT_HTTPHEADER => array(
        //     'Cookie: ci_session=cn8uk20toe5p7tcrc42cej9ee4572oht'
        // ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        
        Log::info('탁송처리 API 호출', ['request' => $response]);
    }
}
