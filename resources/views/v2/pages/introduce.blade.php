@extends('v2.layouts.app')
@section('content')

<div class="pb-5 intro">

    <div class="section section01" data-animate="animate__fadeIn">
        <p class="small-text">내차팔때 고민이시죠?</p>
        <p class="title mb-5">{{ config('app.name') }}의 내차팔기 서비스</p>
        <div class="chat-box">
            <img src="{{ asset('images/intro-01.png') }}" alt="카카오톡 채팅" class="chat-img">
        </div>
    </div>

    <div class="section section02" data-animate="animate__fadeIn">
        <p class="title mb-5">이제 이런 걱정은 하지 마세요</p>
        <div class="features">
            <div class="feature-item">
                <img src="{{ asset('images/intro-02.png') }}" alt="손가락 1" class="hand-img mb-22 mt-2">
                <p>월 1만 대 이상 진단하는<br>진단 전문가의 차량 진단</p>
            </div>
            <div class="feature-item">
                <img src="{{ asset('images/intro-03.png') }}" alt="손가락 2" class="hand-img mb-22 mt-2">
                <p>입찰 후 가격 네고없는<br>정직한 시스템</p>
            </div>
            <div class="feature-item">
                <img src="{{ asset('images/intro-04.png') }}" alt="손가락 3" class="hand-img mb-4 mt-2">
                <p>믿을 수 있는 nice d&R에서<br>차량 정보 및 시세 제공, 차량<br>대금 관리까지 안전 거래 담보</p>
            </div>
        </div>
    </div>

    <div class="section section03" data-animate="animate__fadeIn">
        <div class="content">
            <p class="title">내차팔기 이젠 {{ config('app.name') }}에 의뢰하세요</p>
            <p>이제 안심하고 내차를 높은 가격으로 판매할 수 있습니다</p>
        </div>
    </div>

    <div class="section section04" data-animate="animate__fadeIn">
        <img src="{{ asset('images/intro-05.png') }}" width="600px">
        <p class="text-muted02 d-flex mt-4 align-center">
            {{ config('app.name') }}은
            <img src="{{ asset('images/intro-logo-01.png') }}" width="180px" class="ms-2">
            <span class="mx-2">와</span>
            <img src="{{ asset('images/intro-logo-02.png') }}" width="130px" class="me-2">
            이 함께합니다.
        </p>
    </div>

</div>

{{-- 애니메이션 스크립트 --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const elements = document.querySelectorAll('[data-animate]');

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const animation = el.dataset.animate;

                    el.classList.add('animate__animated', animation);
                    observer.unobserve(el);
                }
            });
        }, {
            threshold: 0.2 // 화면에 20% 보이면 실행
        });

        elements.forEach(el => observer.observe(el));
    });
</script>

@endsection