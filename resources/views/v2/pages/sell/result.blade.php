@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')
@php
    $isLogin = auth()->user();
@endphp

<div class="container sell-container p-6 mt-5 mb-4" style="max-width: 1000px;" x-data="result">
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
                        <div class="col-8 info-value">{{ $carInfo['car_no'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">최초등록일</div>
                        <div class="col-8 info-value">{{ $carInfo['car_first_reg_date'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">미션</div>
                        <div class="col-8 info-value">{{ $carInfo['car_mission'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">제조사</div>
                        <div class="col-8 info-value">{{ $carInfo['car_maker'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">년식</div>
                        <div class="col-8 info-value">{{ $carInfo['car_year'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">용도변경이력</div>
                        <div class="col-8 info-value">{{ $carInfo['resUseHistYn'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">모델</div>
                        <div class="col-8 info-value">{{ $carInfo['car_model'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">배기량</div>
                        <div class="col-8 info-value">{{ $carInfo['engineSize'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">튜닝이력</div>
                        <div class="col-8 info-value">{{ $carInfo['tuning'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">등급</div>
                        <div class="col-8 info-value">{{ $carInfo['car_grade'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">연료</div>
                        <div class="col-8 info-value">{{ $carInfo['car_fuel'] ?? '-' }}</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">세부모델</div>
                        <div class="col-8 info-value">{{ $carInfo['car_model_sub'] ?? '-' }}</div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="#"
                        class="btn btn-outline-primary px-4 w-100"
                        @click.prevent="openUsageNotice"
                        >
                        차량출품 조건 및 유의사항
                    </a>
                </div>
            </div>
        </div>


        </x-slot:leftContent>

        <x-slot:rightContent>

        <div class="estimate-info-box mb-4">

            <div class="bg-light rounded mb-3">
                <div class="d-flex justify-content-between sell-box p-2 estimate-price-box"  @click="openCurrentPriceModal">
                    <span class="text-muted fw-bold">예상 가격</span>
                    <span class="fw-bold text-danger fs-5" id="estimate-price" x-show="estimatedPriceInTenThousandWon">
                        <span x-text="estimatedPriceInTenThousandWon ? estimatedPriceInTenThousandWon + ' 만원' : ''" id="estimatedPriceInTenThousandWon"></span>
                    </span>
                    <span class="text-muted" id="estimate-price-text" x-show="!estimatedPriceInTenThousandWon">
                        예상 가격을 확인해보세요
                    </span>
                </div>
            </div>

            <div class="bg-light rounded mb-3">
                <div class="d-flex justify-content-between sell-box p-2">
                    <span class="text-muted fw-bold">현재 시세 <small>(소매가)</small></span>
                    <span class="fw-bold text-danger fs-5">
                        {{ isset($carInfo['priceNow']) ? number_format($carInfo['priceNow'] / 10000) : '0' }} 만원
                    </span>
                </div>
                <div class="text-end small text-muted">※ NICE D&R 제공</div>
            </div>

            <div class="bg-light rounded mb-3">
                <div class="d-flex justify-content-between sell-box p-2">
                    <span class="text-muted fw-bold">현재 시세 <small>(도매가)</small></span>
                    <span class="fw-bold text-danger fs-5">
                        {{ isset($carInfo['priceNowWhole']) ? number_format($carInfo['priceNowWhole'] / 10000) : '0' }} 만원
                    </span>
                </div>
                <div class="text-end small text-muted">※ 오토허브셀카 제공</div>
            </div>

            <p class="small text-muted mt-3 mb-4">
                ※ 도매 및 소매 시세는 무사고 차량 표준주행거리(1년 15000Km)를 기준으로 제시된 금액입니다.
            </p>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold">차량 정보가 다르신가요?</span>
                <a href="#" class="text-danger fw-semibold small" @click="refreshCarInfo" data-bs-toggle="tooltip" data-bs-placement="top" title="일 1회 갱신 가능합니다, 갱신한 정보는 1주간 보관됩니다"
                >
                    <x-loading font="true" />
                    <span x-show="!loading">
                        정보갱신하기 <i class="mdi mdi-refresh"></i>
                    </span>
                </a>
            </div>

            <!-- 로그인 안내 박스 -->
            <div class="text-center py-5 bg-light rounded text-muted mb-3">
                <div class="fs-6" style="{{ $isLogin ? 'display: none;' : 'padding: 12px;' }}">로그인을 하면<br><strong>경매 신청이 가능해요.</strong></div>
                <div class="fs-6" style="{{ $isLogin ? 'padding: 24px;' : 'display: none;' }}">차량조회에 성공했습니다.</div>
            </div>

            <div class="row">
                <div class="col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-2 fs-6" @click="openCurrentPriceModal">
                        예상 가격 측정
                    </a>
                </div>
                <div class="col-6">
                    <button
                    class="btn btn-danger w-100 py-2 fw-semibold border-0"
                    @click="openAuctionModal"
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
const isLogin = '{{ $isLogin }}';
window.carInfo = @json($carInfo);
</script>

@endsection