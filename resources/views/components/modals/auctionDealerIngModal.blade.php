<div x-data="auctionDealerIngModal">
    <div class="d-flex justify-content-end text-muted mb-2" style="font-size: 0.9rem;">
        <div><i class="bi bi-hand-index-thumb me-1"></i> 입찰 <strong x-text="bidsCount"></strong></div>
        <div class="mx-2">|</div>
        <div><i class="bi bi-heart me-1"></i> 관심 <strong x-text="likesCount"></strong></div>
    </div>

    <div class="mb-3">
        <div class="input-group">
        <span class="input-group-text text-muted">₩</span>
        <input type="number" x-model="bidAmount" class="form-control fs-6 text-end" placeholder="입찰 금액 입력" />
        <span class="input-group-text text-danger">만원</span>
        </div>
        <div class="form-text text-end text-muted mt-1">평균 <strong>0</strong>원</div>
    </div>

    <button type="button" class="btn btn-danger w-100 py-2 fs-6 rounded mt-4"
    @click="openSuccessModal"
    >입찰 완료</button>
</div>