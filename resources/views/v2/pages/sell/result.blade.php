@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')
@php

@endphp

<div class="container form-custom p-6 mt-5 mb-4" style="max-width: 1100px;">
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
                        <div class="col-8 info-value">24너8437</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">최초등록일</div>
                        <div class="col-8 info-value">2011-02-18</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">미션</div>
                        <div class="col-8 info-value">오토</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">제조사</div>
                        <div class="col-8 info-value">기아</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">년식</div>
                        <div class="col-8 info-value">2011</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">용도변경이력</div>
                        <div class="col-8 info-value">없음</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">모델</div>
                        <div class="col-8 info-value">스포티지 R</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">배기량</div>
                        <div class="col-8 info-value">1995 cc</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">튜닝이력</div>
                        <div class="col-8 info-value">21 회</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">등급</div>
                        <div class="col-8 info-value">TLX 최고급형</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">연료</div>
                        <div class="col-8 info-value">디젤</div>
                    </div>
                    <div class="list-item col-12 row mb-3">
                        <div class="col-4 info-label">세부모델</div>
                        <div class="col-8 info-value">SL53BA-S</div>
                    </div>
                </div>
        
                <div class="mt-4 text-center">
                    <button type="button" class="btn btn-outline-primary px-4 w-100">
                        차량출품 조건 및 유의사항
                    </button>
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
                    <span class="fw-bold text-danger fs-5">747 만원</span>
                </div>
                <div class="text-end small text-muted">※ NICE D&R 제공</div>
            </div>
        
            <div class="bg-light rounded mb-3">
                <div class="d-flex justify-content-between sell-box p-2">
                    <span class="text-muted fw-bold">현재 시세 <small>(도매가)</small></span>
                    <span class="fw-bold text-danger fs-5">741 만원</span>
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
                <div class="col-6">
                    <button class="btn btn-outline-primary w-100 py-2 rounded-pill fw-semibold">
                        현재 예상 가격
                    </button>
                </div>
                <div class="col-6">
                    {{-- <button 
                    class="btn btn-danger w-100 py-2 rounded-pill fw-semibold border-0" 
                    @click.prevent="Alpine.store(`modal`).showHtmlFromUrl(`/v2/pages/sell/auction-apply`, {title: `경매 신청하기`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})"
                    >
                        경매 신청하기
                    </button> --}}

                    <button 
                    class="btn btn-danger w-100 py-2 rounded-pill fw-semibold border-0" 
                    data-bs-toggle="modal"
                    data-bs-target="#auctionApplyModal"
                    >
                        경매 신청하기
                    </button>
                </div>
            </div>
        </div>

        </x-slot:rightContent>

    </x-layouts.split>
</div>



<!-- Scrollable modal -->
<div class="modal fade" id="auctionApplyModal">
    <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">경매 신청하기</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>경매 신청하기</p>
        </div>
        <div class="modal-footer">
            <form action="{{ route('sell.apply') }}" method="post">
                @csrf
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="submit" class="btn btn-primary">경매 신청하기</button>
            </form>
        </div>
    </div>
    </div>
</div>

@endsection