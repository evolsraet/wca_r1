<div class="py-4 border-bottom p-2">
    <div class="fw-bold fs-5">매수자 정보</div>
    <div class="text-muted mb-3">본인서명 사실 확인서(자동차매도용) 발급정보</div>

    <div class="row row-cols-2 gy-3 text-muted bg-gray-50 rounded p-2">
        <div class="col-3">성명</div>
        <div class="col-9 text-dark fw-semibold" x-text="auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.name ?? '미정'"></div>
        <div class="col-3">주민(법인)번호</div>
        <div class="col-9 text-dark fw-semibold" x-text="auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.corporation_registration_number ?? '미정'"></div>
        <div class="col-3">주소</div>
        <div class="col-9 text-dark fw-semibold" x-text="(auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.company_addr1 && auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.company_addr2) ? (auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.company_addr1 + ' ' + auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.company_addr2) : '미정'"></div>
    </div>
</div>