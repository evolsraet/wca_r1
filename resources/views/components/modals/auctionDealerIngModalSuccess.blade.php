<div class="text-center" x-data="auctionDealerIngModalSuccess">
  <!-- 입찰 금액 표시 -->
  <div class="bg-light py-3 mb-3 rounded">
    <div class="text-muted small mb-1">나의 입찰 금액</div>
    <div class="fs-3 fw-bold text-danger">
      <span x-text="bidAmount"></span> <span class="fs-5">만원</span>
    </div>
  </div>

  <!-- 안내 문구 -->
  <div class="fw-semibold mb-2">해당 금액으로 입찰하시겠습니까?</div>
  <div class="text-muted mb-4">
    걱정마세요! 입찰 한 뒤에도<br/>
    취소 후 재입찰이 가능합니다.
  </div>

  <!-- 버튼 영역 -->
  <div class="justify-content-center border-0 pt-0 pb-4 gap-2">
    <button type="button" class="btn btn-danger px-4 rounded border-0" @click="submit">입찰하기</button>
    <button type="button" class="btn btn-secondary px-4 rounded border-0" data-bs-dismiss="modal">취소</button>
  </div>

</div>