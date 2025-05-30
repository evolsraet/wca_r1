@php
    $user = Auth::user();
@endphp

@if (!$user)
<div class="login-card">
    <div class="login-card-text">
        <p class="login-subtitle">내 차 팔까?</p>
        <a href="{{ route('login') }}" class="login-link">로그인하기</a>
    </div>
    <div class="login-card-image"></div>
</div>
@else
  @if($user->hasRole('user'))
    <a href="{{ route('login') }}" class="sell-car-box d-flex justify-content-between align-items-center">
      <div>
        <div class="sell-car-subtitle">경매를 시작해볼까요?</div>
        <div class="sell-car-title fw-bold">
          내 차팔기 <i class="bi bi-chevron-right ms-1"></i>
        </div>
      </div>
      <div class="sell-car-icon">
        <img src="{{ asset('images/car-key.png') }}" alt="키 아이콘" />
      </div>
    </a>
  @endif
@endif