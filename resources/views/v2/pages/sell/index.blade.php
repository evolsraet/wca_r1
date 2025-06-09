@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')
@php
    $container = false;
    $isLogin = Auth::check();
@endphp

<div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="7000" data-bs-pause="false">
  <div class="carousel-inner">
    @foreach (config('auction.mainBanner') as $banner)
      <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
        <div class="carousel-bg" style="background-image: url('../../{{ $banner['image'] }}');">
        </div>
      </div>
    @endforeach
    </div>
  </div>

  <div style="position: absolute; top: 49px; left: 0; width: 100%; z-index: 1;">
    <div class="container">
      <x-layouts.split
          leftClass="col-lg-8"
          rightClass="col-lg-4"
          leftContainerClass=""
          rightContainerClass=""
          :initialRightPanelOpen="true">

          <x-slot:leftContent>
            <div class="flex-grow-1" style="height: 100vh;">
            <div id="mainCarousel" class="">
              <div class="carousel-inner">
                <div class="carousel-item active" style="top:30%;">
                  <x-carousels.carousel />
                </div>
              </div>
            </div>
            </div>
          </x-slot:leftContent>

          <x-slot:rightContent style="box-shadow: none !important;">
            <div class="container bg-white form-custom" style="height: 100vh;">
              
              <div class="check-car-box text-center p-4 rounded bg-white mx-auto" style="max-width: 100%;">

                <video autoplay="" loop="" playsinline="" preload="auto" style="width: 85%;">
                  <source src="../images/video/mainvideo02.mp4" type="video/mp4">
                </video>

                <form method="POST" action="{{ route('sell.result') }}" class="mt-5">
                    @csrf

                    <!-- 소유자 질문 -->
                    <div class="mb-3">
                        <input type="text" name="owner" class="form-control" placeholder="소유자가 누구인가요?" required autofocus>
                    </div>

                    <!-- 차량번호 입력 -->
                    <div class="mb-4">
                        <input type="text" name="no" class="form-control" placeholder="차량 번호를 입력해주세요." required>
                    </div>

                    <!-- 로고 (이미지 직접 넣을 위치) -->
                    <div class="mb-4">
                        <img src="{{ asset('../images/wecar_.png') }}" alt="Car Logo" class="img-fluid" style="max-height: 80px; opacity: 5;">
                    </div>

                    <!-- 버튼들 -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary border-0" style="font-size: 16px;">내 차 조회</button>
                        <a href="{{ route('login') }}" class="btn btn-outline-danger" style="font-size: 16px;">로그인</a>
                    </div>

                    <!-- 약관 -->
                    <div class="mt-3">
                        <small class="text-muted">
                            <a href="#" class="text-decoration-none link-text" @click.prevent="Alpine.store(`modal`).showHtmlFromUrl(`/v2/docs/privacy?raw=1`, {title: `개인정보처리방침`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})">개인정보 처리방침</a> |
                            <a href="#" class="text-decoration-none link-text" @click.prevent="Alpine.store(`modal`).showHtmlFromUrl(`/v2/docs/terms?raw=1`, {title: `이용약관`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})">이용약관</a>
                        </small>
                    </div>
                </form>
              </div>

          </div>
          </x-slot:rightContent>

      </x-layouts.split>
  </div>
</div>

@endsection