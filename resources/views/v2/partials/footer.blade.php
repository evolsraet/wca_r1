{{-- 푸터 스타일 --}}
<footer class="site-footer py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-6 mb-4">
        <p class="mb-1">온라인 경매 서비스 | {{ config('app.name') }}(주) | 대표이사 정태영</p>
        <p class="mb-1">사업자등록번호 : 755-81-02354 | 통신판매업신고 제2023-용인기흥-6932호</p>
        <p class="mb-1">본사: 경기도 용인시 기흥구 중부대로 242 A동 W117호</p>
        <p class="mb-1">부산지점: 부산 가정로 장안읍 반룡산단로 95 C동 지하1층 B106호</p>
        <p class="mb-0">대표전화번호: 1544-2165 | 이메일 wecar@wecar-m.co.kr</p>
        <img src="{{ asset('images/busan_wecar_logo_footer.png') }}" alt="BUSAN weCar" class="footer-logo mt-3">
      </div>
      <div class="col-md-6 text-md-end">
        <p class="mb-1 fw-bold">{{ config('app.name') }}이 함께하면<br>내차판매 걱정없어요</p>
        <div class="mb-3">
          <a href="{{ route('docs.show', ['type' => 'privacy']) }}" class="footer-link">개인정보 처리방침</a>
          <span class="px-2">|</span>
          <a href="{{ route('docs.show', ['type' => 'terms']) }}" class="footer-link">이용약관</a>
        </div>
        <p class="mb-1">Copyrights {{ config('app.name') }} All Rights Reserved</p>
        <p class="mb-2">Terms of Use / Privacy Policy</p>
        <img src="{{ asset('images/venture.png') }}" alt="KCA 마크" class="footer-cert">
      </div>
    </div>
  </div>
</footer>
