<div x-data="taksongAddress()" x-init="initWithWatch()">
    <div class="delivery-address-box py-4 p-2" id="taksongAddress" x-show="auction?.bids?.find(bid => bid.id === auction.bid_id) && !auction?.is_taksong">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <div class="fw-bold fs-5">탁송 주소지</div>
                <div class="text-muted small">※ 현 주소지로 탁송이 진행 됩니다.</div>
            </div>
            <button class="btn btn-outline-danger btn-sm address-edit-btn" @click="openAddressBookModal">
                주소지 변경
            </button>
        </div>

        <div class="mb-3 ps-1">
            <div class="mb-1">
                <span class="fw-semibold text-secondary">우편번호 :</span> 
                <span x-text="addr_post ?? '미정'"></span>
            </div>
            <div>
                <span class="fw-semibold text-secondary">주소 :</span> 
                <span x-text="(addr1 && addr2) ? (addr1 + ' ' + addr2) : '미정'"></span>
            </div>
        </div>

        <div class="pt-2">
            <button class="btn btn-danger w-100 py-2 fw-semibold delivery-confirm-btn border-0" :disabled="!auction?.taksong_wish_at" type="button" @click="submit">
                탁송하기
            </button>
        </div>        
        
    </div>
</div>
