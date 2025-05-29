@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')
{{-- 유저 메인 배너변수 --}}
@php
  $banners = [
    ['image' => asset('images/main_banner02.png')],
    ['image' => asset('images/main_p2.png')],
    ['image' => asset('images/main_p3.png')]
  ];
@endphp
{{-- 유저 메인 배너 --}}
<div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="false">
  <div class="user-main-carousel-inner">
  @foreach ($banners as $banner)
    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
      <div class="carousel-user-bg" style="background-image: url('{{ $banner['image'] }}');">
        <div class="carousel-user-caption">
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
</div>


<div class="container mt-5 mb-5">
  <div class="row">
    <div class="col-md-6">
      <!-- <h5>공지사항</h5> -->
    </div>
    <div class="col-md-6">
      <!-- <h5>내 매물관리</h5> -->
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <x-latests.AuctionProcess />
    </div>
    <div class="col-md-12">
      <!-- [나의 이용후기] -->
    </div>
  </div>
</div>

@endsection