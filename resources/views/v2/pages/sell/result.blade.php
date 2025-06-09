@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')
@php

echo '<pre>';
print_r($carInfo);
echo '</pre>';

// dd($carInfo);
    // 변환된 구조
    $data = [
        'owner_name' => $carInfo['owner'] ?? null,
        'car_no' => $carInfo['no'] ?? null,
        'car_maker' => $carInfo['maker'] ?? null,
        'car_model' => $carInfo['model'] ?? null,
        'car_model_sub' => $carInfo['modelSub'] ?? null,
        'car_grade' => $carInfo['grade'] ?? null,
        'car_grade_sub' => $carInfo['gradeSub'] ?? null,
        'car_year' => $carInfo['year'] ?? null,
        'car_first_reg_date' => $carInfo['firstRegDate'] ?? null,
        'car_mission' => $carInfo['mission'] ?? null,
        'car_fuel' => $carInfo['fuel'] ?? null,
        'car_price_now' => $carInfo['priceNow'] ?? null,
        'car_price_now_whole' => $carInfo['priceNowWhole'] ?? null,
        'car_thumbnail' => $carInfo['thumbnail'] ?? null,
        'car_km' => $carInfo['km'] ?? null,
        'car_status' => '1', // 고정값
    ];

    $isLogin = auth()->user();

@endphp

<div class="container sell-container form-custom p-6 mt-5 mb-4" style="max-width: 1000px;" x-data=[]>
    <x-layouts.split
        leftClass="col-lg-6"
        rightClass="col-lg-6"
        leftContainerClass=""
        rightContainerClass=""
        :initialRightPanelOpen="true">

        <x-slot:leftContent>
        
        <div class="vehicle-info-card mb-4">
            <div class="card-body">
                <h5 class="vehicle-info-title fw-bold mb-3">차량 정보 조회 되었어요</h5>
                <hr>
        
                <div class="row g-2 vehicle-info-list">
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">차량번호</div>
                        <div class="col-8 info-value">{{ $carInfo['car_no'] ?? '' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">최초등록일</div>
                        <div class="col-8 info-value">{{ $data['car_first_reg_date'] ?? '' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">미션</div>
                        <div class="col-8 info-value">{{ $data['car_mission'] ?? '' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">제조사</div>
                        <div class="col-8 info-value">{{ $data['car_maker'] ?? '' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">년식</div>
                        <div class="col-8 info-value">{{ $data['car_year'] ?? '' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">용도변경이력</div>
                        <div class="col-8 info-value">{{ $carInfo['resUseHistYn'] ?? '' }}</div> {{-- 이건 아직 데이터 변환 안 했으므로 유지 --}}
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">모델</div>
                        <div class="col-8 info-value">{{ $data['car_model'] ?? '' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">배기량</div>
                        <div class="col-8 info-value">{{ $carInfo['engineSize'] ?? '' }}</div> {{-- 이 항목도 변환되지 않았으면 유지 --}}
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">튜닝이력</div>
                        <div class="col-8 info-value">{{ $data['tuning'] ?? '' }} 회</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">등급</div>
                        <div class="col-8 info-value">{{ $data['car_grade'] ?? '' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">연료</div>
                        <div class="col-8 info-value">{{ $data['car_fuel'] ?? '' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">세부모델</div>
                        <div class="col-8 info-value">{{ $data['car_model_sub'] ?? '' }}</div>
                    </div>
                </div>
        
                <div class="mt-4 text-center">
                    <a href="#" 
                            class="btn btn-outline-primary px-4 w-100"
                            @click.prevent="Alpine.store(`modal`).showHtmlFromUrl(`/v2/docs/usageNotice?raw=1`, {title: `차량출품 조건 및 유의사항`, size: `modal-md`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})"
                            >
                        차량출품 조건 및 유의사항
                    </a>
                </div>
            </div>
        </div>


        </x-slot:leftContent>

        <x-slot:rightContent>
            
        <div class="estimate-info-box mb-4">
            <h6 class="fw-bold mb-3">예상 가격</h6>
            <div class="text-end fw-bold text-danger fs-5 mb-3"> 원</div>
        
            <div class="bg-light rounded mb-3">
                <div class="d-flex justify-content-between sell-box p-2">
                    <span class="text-muted fw-bold">현재 시세 <small>(소매가)</small></span>
                    <span class="fw-bold text-danger fs-5"> 만원</span>
                </div>
                <div class="text-end small text-muted">※ NICE D&R 제공</div>
            </div>
        
            <div class="bg-light rounded mb-3">
                <div class="d-flex justify-content-between sell-box p-2">
                    <span class="text-muted fw-bold">현재 시세 <small>(도매가)</small></span>
                    <span class="fw-bold text-danger fs-5"> 만원</span>
                </div>
                <div class="text-end small text-muted">※ 오토허브셀카 제공</div>
            </div>
        
            <p class="small text-muted mt-3 mb-4">
                ※ 도매 및 소매 시세는 무사고 차량 표준주행거리(1년 15000Km)를 기준으로 제시된 금액입니다.
            </p>
        
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold">차량 정보가 다르신가요?</span>
                <a href="#" class="text-danger fw-semibold small">
                    정보갱신하기 <i class="mdi mdi-refresh"></i>
                </a>
            </div>
        
            <!-- 로그인 안내 박스 -->
            <div class="text-center py-5 bg-light rounded text-muted mb-3">
                <div class="fs-6">로그인을 하면<br><strong>경매 신청이 가능해요.</strong></div>
            </div>
        
            <div class="row">
                <div class="{{ $isLogin ? 'col-6' : 'col-12' }}">
                    <a href="#" class="btn btn-outline-primary w-100 py-2 fs-6" id="openCurrentPriceModal">
                        현재 예상 가격
                    </a>
                </div>
                <div class="{{ $isLogin ? 'col-6' : 'col-12' }}" @if(!$isLogin) style="display: none;" @endif>
                    <button 
                    class="btn btn-danger w-100 py-2 rounded-pill fw-semibold border-0" 
                    id="openAuctionModal"
                    >
                        경매 신청하기
                    </button>
                </div>
            </div>
        </div>

        </x-slot:rightContent>

    </x-layouts.split>
</div>

<script>

window.carInfo = @json($data);

document.getElementById('openCurrentPriceModal').addEventListener('click', () => {
    Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/currentPrice', {
        id: 'currentPrice',
        title: '내 차, 예상가격을 확인합니다',
        showFooter: false,
        data: {
            carInfo: window.carInfo
        }
    });
});

// 경매 신청 모달
document.getElementById('openAuctionModal').addEventListener('click', () => {
    Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/auctionProcessSteps', {
        id: 'auctionProcessSteps',
        title: '경매 신청하기',
        showFooter: false
    });
});

</script>

@endsection