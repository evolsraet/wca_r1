<div class="text-center p-4" 
    x-data="{
        data:{},
        auction:{},
        init() {
            this.data = window.modalData.content.data;
            this.auction = window.modalData.content.auction;
            console.log('data', this.data);
        }
    }">
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
    <div class="text-secondary small mb-3">★ (<span x-text="data?.user?.points ?? 0"></span>점)</div>

    <div class="mb-1 text-secondary">
        <i class="bi bi-telephone me-2"></i> <span x-text="data?.user?.dealer?.phone ?? '-'"></span>
    </div>
    <div class="mb-3 text-secondary">
        <i class="bi bi-geo-alt me-2"></i> 
        <span x-text="'('+data?.user?.dealer?.company_post + ') ' + data?.user?.dealer?.company_addr1 + ' ' + data?.user?.dealer?.company_addr2"></span>
    </div>

    <p class="text-muted small mb-4" x-text="data?.user?.dealer?.introduce ?? '-'"></p>

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
        @click="Alpine.store('modal').close('dealerBidInfo')"
        >취소</button>
        <button class="btn btn-danger w-50"
        @click="
        Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/dealerDeliveryChoice', {
            id: 'dealerDeliveryChoice',
            title: '탁송 예정일 선택',
            size: 'modal-dialog-centered',
            showFooter: false,
        }, {
            content: {
                auction: auction,
                data: data
            }
        });
        
        Alpine.store('modal').close('dealerBidInfo')
        "
        >딜러 선택</button>
    </div>
</div>