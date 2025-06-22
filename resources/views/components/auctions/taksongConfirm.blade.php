<div class="p-2" x-show="auction?.bids?.find(bid => bid.id === auction.bid_id)">
    <div class="fw-bold fs-5">경락 확인서</div>
    <p>경락 확인서를 확인 하세요.</p>

    <div>
        <button 
            class="btn w-100 btn-lg btn-outline-primary border-0" 
            style="font-size: 1rem;"
            @click="
                Alpine.store(`modal`).showHtmlFromUrl(`/v2/components/modals/auctionConfirmationDoc`, 
                {title: `경락 확인서`, size: `modal-xl modal-dialog-centered`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]},
                {
                    content: {
                        auction: auction,
                        diag: diag
                    }
                }
            )"
            >경락 확인서</button>
    </div>
</div>