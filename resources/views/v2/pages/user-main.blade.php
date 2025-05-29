@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')

<div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
  <div class="carousel-inner">

    {{-- 슬라이드 1 --}}
    <div class="carousel-item active">
      <div class="carousel-bg" style="background-image: url('{{ asset('images/main_p2.png') }}');">
        <div class="carousel-caption-box">
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

    {{-- 슬라이드 2 --}}
    <div class="carousel-item">
      <div class="carousel-bg" style="background-image: url('{{ asset('images/main_p3.png') }}');">
        <div class="carousel-caption-box">
          <h2 class="carousel-title animate__animated animate__bounce">
            간편한 절차,<br><strong>신속한 판매 경험</strong>
          </h2>
          <p class="carousel-subtitle">딜러 가입도 쉽게, 판매도 빠르게!</p>
          <a href="/v2/dealer/join" class="carousel-btn">
            <i class="bi bi-person-plus-fill"></i> 딜러회원가입
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection