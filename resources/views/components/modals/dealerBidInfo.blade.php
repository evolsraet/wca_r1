<div class="text-center p-1" 
    x-data="dealerBidInfo">
    <div class="mb-3">
        
        <div 
            class="rounded-circle border mx-auto" style="width: 80px; height: 80px; background-color: #f8f9fa;"
            x-data="(() => {
            const user = data?.user;
            return {
                user: user,
            };
        })()">
            <x-auctions.userIcon />
        </div>

    </div>
    <h5 class="fw-bold mb-1" x-text="data?.user?.dealer?.name ?? '-'"></h5>
    <div class="text-muted small mb-2" x-text="data?.user?.dealer?.company ?? '-'"></div>
    <div class="text-secondary small mb-3">(<span x-text="data?.points ?? 0"></span>점)</div>

    <div class="mb-1 text-secondary">
        <i class="mdi mdi-phone me-2"></i> <a :href="'tel:' + data?.user?.dealer?.phone"><span x-text="data?.user?.dealer?.phone ?? '-'"></span></a>
    </div>
    <div class="mb-3 text-secondary">
        <i class="mdi mdi-map-marker me-2"></i> 
        <span x-text="'('+data?.user?.dealer?.company_post + ') ' + data?.user?.dealer?.company_addr1 + ' ' + data?.user?.dealer?.company_addr2"></span>
    </div>

    <p class="text-muted mb-4 text-start" x-text="data?.user?.dealer?.introduce ?? '-'"></p>

    <div class="form-group mb-2">
        <div class="form-control bg-light text-end fw-bold">
            <span x-text="data?.price ?? 0"></span> <span class="text-muted">만원</span>
        </div>
    </div>

    <div class="text-danger small mb-3">
        선택 완료시, 선택한 딜러에게 문자가 발송됩니다.
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary w-50"
        @click="closeModal"
        >취소</button>
        <button class="btn btn-danger w-50"
        @click="submit"
        >딜러 선택</button>
    </div>
</div>