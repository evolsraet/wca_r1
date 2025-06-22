<div class="my-bid-box border-top pt-4" x-data="auctionDealerWait">

    <div class="d-flex justify-content-between align-items-center px-2 mb-2">
        <span class="fw-semibold text-dark">나의 입찰 금액</span>
        <div class="d-flex gap-3 align-items-center text-muted small">
            <div><i class="bi bi-hand-index-thumb"></i> 입찰 <span x-text="auction?.bids_count ?? 0"></span></div>
            <div><i class="bi bi-heart"></i> 관심 <span x-text="auction?.like_count ?? 0"></span></div>
        </div>
    </div>

    <div class="bg-light rounded py-3 px-4 text-end fw-bold fs-5 text-dark mb-3">
        <span x-text="auction?.bids[0]?.price ?? 0"></span> <span class="text-secondary fs-6">만원</span>
    </div>

    <div class="px-2 pb-2">
        <button class="btn btn-outline-secondary w-100 cancel-bid-btn " type="button" @click="cancelBid(auction?.bids[0]?.id)">
            입찰 취소하기
        </button>

        <x-auctions.auctionDocsButton />

    </div>

</div>