{{-- 푸터 스타일 --}}
<footer class="site-footer py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-6 mb-4">
        <p class="mb-1">온라인 경매 서비스 | {{ config('app.name') }}(주) | 대표이사 {{ config('auction.company.name') }}</p>
        <p class="mb-1">사업자등록번호 : {{ config('auction.company.business_number') }} | 통신판매업신고 제{{ config('auction.company.interim_number') }}</p>
        @foreach(config('auction.company.address') as $address)
        <p class="mb-1">{{ $address['name'] }}: {{ $address['address'] }}</p>
        @endforeach
        <p class="mb-0">대표전화번호: <a href="tel:{{ config('auction.company.phone') }}">{{ config('auction.company.phone') }}</a> | 이메일 <a href="mailto:{{ config('auction.company.email') }}">{{ config('auction.company.email') }}</a></p>
        <img src="{{ asset('images/busan_wecar_logo_footer.png') }}" alt="BUSAN weCar" class="footer-logo mt-3">
      </div>
      <div class="col-md-6 text-md-end">
        <p class="mb-1 fw-bold">{{ config('app.name') }}이 함께하면<br>내차판매 걱정없어요</p>
        <div class="mb-3">
          <a href="{{ route('docs.show', 'privacy') }}" class="footer-link">개인정보 처리방침</a>
          <span class="px-2">|</span>
          <a href="{{ route('docs.show', 'terms') }}" class="footer-link">이용약관</a>
        </div>
        <p class="mb-1">Copyrights {{ config('app.name') }} All Rights Reserved</p>
        <p class="mb-2">Terms of Use / Privacy Policy</p>
        <img src="{{ asset('images/venture.png') }}" alt="KCA 마크" class="footer-cert">
      </div>
    </div>
  </div>
</footer>
