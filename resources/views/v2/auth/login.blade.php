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
  
    <div style="position: absolute; top: 62px; left: 0; width: 100%; z-index: 1;">
      <div class="container">
        <x-layouts.split
            leftClass="col-lg-7"
            rightClass="col-lg-5"
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
                <div class="container bg-white form-custom" style="height: 100vh;">
                    <div class="row justify-content-center">

                        <video autoplay="" loop="" playsinline="" preload="auto" style="width: 70%;">
                            <source src="../images/video/mainvideo02.mp4" type="video/mp4">
                        </video>

                        <div class="col-md-12">
                            <div class="mt-5 p-4">
                                {{-- <div class="card-header">
                                    <h4 class="mb-0">로그인</h4>
                                </div> --}}
                                <div class="card-body">
                                    <form x-data="login"
                                        x-init="$store.login.setRedirectUrl('{{ request()->header('referer') }}')"
                                        @submit.prevent="submit">
                                        <!-- 이메일 -->
                                        <div class="mb-3">
                                            {{-- <label for="email" class="form-label">이메일</label> --}}
                                            <input type="email"
                                                class="form-control"
                                                id="email"
                                                x-model="form.email"
                                                :class="{ 'is-invalid': errors.email }"
                                                placeholder="이메일을 입력하세요"
                                                required>
                                            <div class="invalid-feedback" x-text="errors.email"></div>
                                        </div>

                                        <!-- 비밀번호 -->
                                        <div class="mb-3">
                                            {{-- <label for="password" class="form-label">비밀번호</label> --}}
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

                                        <!-- 로그인 버튼 -->
                                        <div class="d-grid">
                                            <button type="submit"
                                                    class="btn btn-primary"
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
