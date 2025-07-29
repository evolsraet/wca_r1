@extends('v2.layouts.app')
@section('title', '메인 페이지')
@section('content')
@php
    $container = false;
    $isLogin = Auth::check();

    // 회원기준 차량조회 정보 리스트
    $userCarInfo = Wca::getCachedCarInfoForUser();
@endphp

<script>
  window.userCarInfo = @json($userCarInfo);
</script>

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

  <div style="position: absolute; top: 39px; left: 0; width: 100%; z-index: 1;" x-data="index()">
    <div class="container">
      <x-layouts.split
          leftClass="col-lg-8"
          rightClass="col-lg-4"
          leftContainerClass=""
          rightContainerClass=""
          :initialRightPanelOpen="true">

          <x-slot:leftContent>
            <div class="flex-grow-1 leftContentResponsive">
            <div id="mainCarousel" class="">
              <div class="carousel-inner">
                <div class="carousel-item active" style="top:30%;">
                  <x-carousels.carousel />
                </div>
              </div>
            </div>
            </div>
          </x-slot:leftContent>

          <x-slot:rightContent>
            <div class="container bg-white rightContentResponsive">
              
              <div class="row justify-content-center">

                <video autoplay="" loop="" muted="" playsinline="" preload="auto" class="d-none d-sm-block mb-4 mt-4" style="width: 90%;">
                  <source src="../images/video/mainvideo02.mp4" type="video/mp4">
                </video>

                <div class="col-md-12">
                  <div class="mt-5 p-4 text-center rightContentInner">
                    <form @submit.prevent="submitForm">
                        <!-- 소유자 입력 -->
                        <x-forms.input 
                            name="owner"
                            placeholder="소유자가 누구인가요?"
                            :required="true"
                            autofocus
                            :errors="errors"
                        />

                        <!-- 차량번호 입력 -->
                        <x-forms.input 
                            name="no"
                            placeholder="차량 번호를 입력해주세요."
                            :required="true"
                            :errors="errors"
                        />

                        <!-- 로고 -->
                        <div class="mb-4">
                            <img src="{{ asset('../images/wecar_.png') }}" alt="Car Logo" class="img-fluid" style="max-height: 80px; opacity: 5;">
                        </div>

                        <!-- 버튼들 -->
                        <div class="d-grid gap-2">
                            <button 
                                type="submit" 
                                class="btn btn-primary border-0"
                                :disabled="isLoading"
                                x-text="isLoading ? '조회 중...' : '내 차 조회'"
                            ></button>
                            @if (!Auth::check())
                                <a href="{{ route('login') }}" class="btn btn-outline-danger">로그인</a>
                            @endif
                        </div>
                    </form>
                  </div>
                </div>
              </div>

          </div>
          </x-slot:rightContent>

      </x-layouts.split>
  </div>
</div>

@endsection