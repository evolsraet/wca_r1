<div class="auction-price-box border-top pt-4">
    <div class="d-flex justify-content-between align-items-center mb-2 px-2">
        <div class="text-muted small">도매가 <span class="fw-bold text-danger ms-1 fs-3" x-text="Alpine.store('common').formatPriceToMan(auction?.car_price_now)"></span> 만원</div>
        <div class="text-muted small">소매가 <span class="fw-bold text-danger ms-1 fs-3" x-text="Alpine.store('common').formatPriceToMan(auction?.car_price_now_whole)"></span> 만원</div>
    </div>

    <ul class="small text-muted px-2">
        <li>※ 도매가 오토허브/셀카 제공</li>
        <li>※ 소매가 NICE D&R 제공</li>
    </ul>

    <hr class="my-3">

    <div class="text-center text-danger fw-semibold mb-2">
        현재 <span class="text-dark" x-text="auction?.bids.length"></span>명이 입찰했어요.
    </div>

    <div class="text-center px-2 pb-2">
        <button class="btn btn-danger w-100 bid-toggle-btn border-0" type="button"
        @click="Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/auctionDealerIngModal', {
            id: 'auctionDealerIngModal',
            title: '입찰하기',
            size: 'modal-dialog-centered',
            showFooter: false,
            data: {
                auction: auction
            }
        });"
        >
            입찰하기
        </button>
    </div>
</div>
