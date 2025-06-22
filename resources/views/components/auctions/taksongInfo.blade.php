<div class="delivery-info-box py-4 border-bottom p-2" x-show="auction?.taksong_wish_at && (auction?.bids?.find(bid => bid.id === auction.bid_id))">
    <div class="fw-bold mb-3 fs-5">탁송 신청 정보</div>

    <dl class="row mb-0">
        <dt class="col-3 text-secondary">낙찰 액:</dt>
        <dd class="col-9 text-danger fw-bold"><span x-text="auction?.final_price ?? '0'"></span> 만원</dd>

        <dt class="col-3 text-secondary">입금 은행:</dt>
        <dd class="col-9"><span x-text="auction?.bank ?? '-'"></span> <span x-text="auction?.account ?? '-'"></span></dd>

        <dt class="col-3 text-secondary">탁송 일:</dt>
        <dd class="col-9"><span x-text="auction?.taksong_wish_at ?? '-'"></span></dd>

        <dt class="col-3 text-secondary">장&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소:</dt>
        <dd class="col-9"><span x-text="'(' + auction?.addr_post + ')' + auction?.addr1 + ' ' + auction?.addr2"></span></dd>
    </dl>
</div>