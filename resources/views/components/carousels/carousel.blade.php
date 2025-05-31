{{-- resources/views/components/carousel/dealer.blade.php --}}
<div class="carousel-caption-box">
    <div class="carousel-title animate__animated animate__bounce">
    쉽고 빠른 <strong>내차팔기,</strong>
    </div>
    <h4 class="carousel-title animate__animated animate__bounce">
    {{ config('app.name') }}에서 <strong>높은 가격으로!</strong>
    </h4>
    <p class="carousel-subtitle">자동차 진단 전문가 <strong>{{ config('app.name') }}</strong>가 함께합니다.</p>
    <a href="{{ route('sell') }}" class="btn btn-dark-white rounded-pill" style="width: 200px;">
    내 차 판매하기
    </a>
    <div>
    <img src="{{ asset('images/busan_wecar_logo_white.png') }}" alt="회사로고" class="carousel-logo">
    </div>
    <div class="dealer-join-box mt-4">
    <span class="dealer-label">저는 딜러예요!</span>
    <a href="{{ route('register', ['isDealer' => true]) }}" class="dealer-link">딜러회원가입</a>
    </div>

</div>