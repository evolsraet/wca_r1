@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')
{{-- 유저 메인 배너변수 --}}
@php
  $user = Auth::user();
  $banners = [
    ['image' => asset('images/main_banner02.png')],
    ['image' => asset('images/main_p2.png')],
    ['image' => asset('images/main_p3.png')]
  ];
@endphp

{{-- 상단 메인 배너 --}}
<div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="false">
  <div class="user-main-carousel-inner">
  @foreach ($banners as $banner)
    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
      <div class="carousel-user-bg" style="background-image: url('{{ $banner['image'] }}');">
        {{-- 유저 메인 배너 캡션 --}}
        <div class="carousel-user-caption" style="{{ $user?->hasRole('dealer') ? 'display: none;' : '' }}">
          <h2 class="carousel-title animate__animated animate__bounce">
            쉽고 빠른 <strong>내차팔기,</strong><br>
            {{ config('app.name') }}에서 <strong>높은 가격으로!</strong>
          </h2>
          <p class="carousel-subtitle">자동차 진단 전문가 <strong>{{ config('app.name') }}</strong>가 함께합니다.</p>
          <a href="/v2/sell" class="carousel-btn">
            <i class="bi bi-car-front-fill"></i> 내 차 판매하기
          </a>
        </div>
      </div>
    </div>
  @endforeach
    <div class="carousel-indicators">
      @foreach ($banners as $banner)
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="true" aria-label="Slide {{ $loop->index + 1 }}"></button>
      @endforeach
    </div>
  </div>

  {{-- 딜러 대시보드 --}}
  <div style="{{ $user?->hasRole('dealer') ? '' : 'display: none;' }}">
    <x-latests.dealerDashboard />
  </div>
</div>

{{-- 메인 컨텐츠 --}}
<div class="container mt-5 mb-5">
  <div class="row">
    <div class="col-md-6 order-2 order-md-1">
      <!-- 공지사항 -->
      <x-latests.notice />
    </div>
    <div class="col-md-6 order-1 order-md-2">
      <div style="{{ $user?->hasRole('dealer') ? 'display: none;' : '' }}">
        <x-latests.myListings />
      </div>
      <div style="{{ $user?->hasRole('dealer') ? '' : 'display: none;' }}">
        <x-latests.unassigned />
      </div>
    </div>
  </div>
  <div class="row" style="{{ $user?->hasRole('dealer') ? 'display: none;' : '' }}">
    <div class="col-md-12">
      <x-latests.auctionProcess />
    </div>
    <div class="col-md-12">
      <!-- 나의 이용후기 -->
      <x-latests.myReview />
    </div>
  </div>

</div>

@endsection