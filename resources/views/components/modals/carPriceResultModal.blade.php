<div class="text-center" x-data="carPriceResultModal">    
    <div class="bg-light rounded py-3 px-4 d-inline-block mb-3">
        <span class="fs-5 fw-bold text-dark me-2" x-text="estimatedPriceInTenThousandWon"></span>
        <span class="fs-5 fw-bold text-danger">만원</span>
    </div>

    <p class="text-muted small mb-4">예상 가격은 현재 차량 정보를 기준으로 산정된 값입니다.</p>

    <div class="d-flex justify-content-center gap-3">
        <button type="button" class="btn btn-outline-danger px-4" @click="reset">재측정하기</button>
        <button type="button" class="btn btn-secondary px-4" @click="submit">확인</button>
    </div>
</div>

