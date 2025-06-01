@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')

<div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="7000" data-bs-pause="false">
  <div class="carousel-inner">
  @foreach (config('auction.mainBanner') as $banner)
    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
      <div class="carousel-bg" style="background-image: url('{{ $banner['image'] }}');">
        <x-carousels.carousel />
      </div>
    </div>
  @endforeach
  </div>
</div>

@endsection