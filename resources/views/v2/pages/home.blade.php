@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')

<div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="7000" data-bs-pause="false">
  <div class="carousel-inner">

    {{-- 슬라이드 1 --}}
    <div class="carousel-item active">
      <div class="carousel-bg" style="background-image: url('{{ asset('images/main_banner02.png') }}');">
      <x-carousel.carousel />
      </div>
    </div>

    {{-- 슬라이드 2 --}}
    <div class="carousel-item active">
      <div class="carousel-bg" style="background-image: url('{{ asset('images/main_p2.png') }}');">
      <x-carousel.carousel />
      </div>
    </div>

    {{-- 슬라이드 3 --}}
    <div class="carousel-item">
      <div class="carousel-bg" style="background-image: url('{{ asset('images/main_p3.png') }}');">
      <x-carousel.carousel />
      </div>
    </div>

  </div>
</div>

@endsection