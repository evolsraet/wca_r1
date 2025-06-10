@extends('v2.layouts.app')
@section('content')
@php
    $container = false;
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


            <x-slot:rightContent>
                <div class="container bg-white form-custom rightContentResponsive">
                    <div class="row justify-content-center">

                        <video autoplay="" loop="" playsinline="" preload="auto" class="d-none d-sm-block mb-4 mt-4" style="width: 90%;">
                            <source src="../images/video/mainvideo02.mp4" type="video/mp4">
                        </video>

                        <div class="col-md-12">
                            <div class="mt-5 p-4 rightContentInner">
                                <div class="card-header">
                                    <div class="mb-3">이미 {{ config('app.name') }} 회원 이신가요?</div>
                                </div>
                                <div class="card-body">
                                    <form x-data="login"
                                        x-init="$store.login.setRedirectUrl('{{ request()->header('referer') }}')"
                                        @submit.prevent="submit">
                                        <!-- 이메일 -->
                                        <div class="mb-3">
                                            <input type="email"
                                                class="form-control"
                                                id="email"
                                                x-model="form.email"
                                                :class="{ 'is-invalid': errors.email }"
                                                placeholder="이메일을 입력하세요"
                                                required autofocus>
                                            <div class="invalid-feedback" x-text="errors.email"></div>
                                        </div>

                                        <!-- 비밀번호 -->
                                        <div class="mb-3">
                                            <input type="password"
                                                class="form-control"
                                                id="password"
                                                x-model="form.password"
                                                :class="{ 'is-invalid': errors.password }"
                                                placeholder="비밀번호를 입력하세요"
                                                required>
                                            <div class="invalid-feedback" x-text="errors.password"></div>
                                        </div>

                                        <!-- 에러 메시지 -->
                                        <div class="alert alert-danger" x-show="error" x-text="error"></div>


                                        <div class="text-center my-4">
                                            <div class="d-flex align-items-center justify-content-center mb-3">
                                                <hr class="flex-grow-1 mx-3" />
                                                <span class="text-muted">또는 소셜 로그인</span>
                                                <hr class="flex-grow-1 mx-3" />
                                            </div>
                                        
                                            <div class="d-flex justify-content-center gap-3">
                                                <!-- Google 로그인 -->
                                                <a href="#" class="btn btn-outline-secondary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                                                    <img src="{{ asset('images/google_ico.png') }}" alt="Google" style="width:45px; height:45px;">
                                                </a>
                                        
                                                <!-- Naver 로그인 -->
                                                <a href="#" class="btn btn-outline-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                                                    <img src="{{ asset('images/naver_ico.png') }}" alt="Naver" style="width:45px; height:45px;">
                                                </a>
                                        
                                                <!-- Kakao 로그인 -->
                                                <a href="#" class="btn btn-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                                                    <img src="{{ asset('images/kakao_ico.png') }}" alt="Kakao" style="width:45px; height:45px;">
                                                </a>
                                            </div>
                                        </div>

                                        <!-- 로그인 버튼 -->
                                        <div class="d-grid">
                                            <button type="submit"
                                                    class="btn btn-primary border-0 fs-6"
                                                    :disabled="loading">
                                                <span x-show="loading" class="spinner-border spinner-border-sm me-2"></span>
                                                로그인
                                            </button>
                                        </div>

                                        <!-- 추가 링크 -->
                                        <div class="mt-3 text-center">
                                            <a href="{{ route('password.request') }}" class="text-decoration-none link-text">비밀번호 찾기</a>
                                            <span class="mx-2">|</span>
                                            <a href="{{ route('register') }}" class="text-decoration-none link-text">회원가입</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </x-slot:leftContent>

        </x-layouts.split>

    </div>
</div>




@endsection
