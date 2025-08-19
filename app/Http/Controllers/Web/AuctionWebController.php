<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Services\AuctionService;
use App\Traits\CrudControllerTrait;
use App\Http\Controllers\Controller;

class AuctionWebController extends Controller
{
    use CrudControllerTrait;

    public function __construct(AuctionService $service)
    {
        $this->service = $service;
    }

    public function list(Request $request)
    {
        // 사이드바 제조사 필터 노출을 위한 데이터 조회
        $carMakers = $this->service->getCarMakersForFilter();

        return view('v2.pages.auction.auctionList', [
            'carMakers' => $carMakers
        ]);
    }

}
