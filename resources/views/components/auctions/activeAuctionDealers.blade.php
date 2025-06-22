<div class="bid-status-box" x-data="activeAuctionDealers();">

    <x-auctions.auctionDocsButton />
    
    {{-- 상단 상태 박스 --}}
    <div class="bg-primary text-white d-flex justify-content-between align-items-center px-3 py-3 rounded-top">
        <div class="fw-semibold">현재 <span x-text="auction?.bids?.length ?? 0"></span>명이 입찰했어요.</div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-light text-white rounded border-0" @click="restartAuction(auction)">경매재시작</button>
            <button class="btn btn-sm btn-outline-light text-white rounded border-0" @click="cancelAuction(auction)">경매취소</button>
        </div>
    </div>

    {{-- 입찰한 딜러 안내 --}}
    <div class="bg-white p-3 border-bottom">
        <div class="fw-bold mb-1">입찰한 딜러</div>
        <div class="text-muted small">※ 입찰 금액이 가장 높은 순으로 5명까지 표시돼요.</div>
    </div>

    {{-- 딜러 리스트 --}}
    <div class="bg-light py-4 px-3">
        {{-- @foreach ($bidders as $bidder) --}}

        <template x-for="bid in auction?.top_bids" :key="bid.id">
            <div 
                class="dealer-card bg-white rounded shadow p-3 mb-3 d-flex justify-content-between align-items-center cursor-pointer over-softgray"
                @click="
                if(auction.status !== 'ing') {
                    Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/dealerBidInfo', {
                        id: 'dealerBidInfo',
                        title: '딜러 상세정보',
                        size: 'modal-dialog-centered',
                        showFooter: false,
                    }, {
                        content: {
                            auction: auction,
                            data: bid
                        }
                    });
                }else{
                    Alpine.store('swal').fire({
                        title: '경매 진행중입니다.',
                        text: '경매시간이 완료후 선택 가능 합니다.',
                        icon: 'warning',
                        confirmButtonText: '확인',
                    });
                }"
            >
                <div class="d-flex align-items-center">
                    {{-- 딜러 프로필 이미지 --}}
                    <div 
                    class="rounded-circle me-3"
                    style="width: 60px; height: 60px;"
                    x-data="(() => {
                        const user = bid.user;
                        return {
                            user: user,
                        };
                    })()"
                    >
                    <x-auctions.userIcon />
                    </div>
                    {{-- 이름 및 금액 --}}
                    <div>
                        <div class="fw-semibold" x-text="bid?.user?.dealer?.name + ' (' + bid?.user?.dealer?.company + ')'"></div>
                        <div class="text-danger fw-bold"><span x-text="bid?.price">124</span> <span class="text-dark">만원</span></div>
                    </div>
                </div>

                {{-- 평점 및 이동 아이콘 --}}
                <div class="text-end">
                    <div class="text-muted small">
                        <i class="bi bi-star-fill me-1 text-warning"></i> <span x-text="bid?.points ?? 0"></span> 점
                    </div>
                    <div><i class="bi bi-chevron-right text-muted"></i></div>
                </div>
            </div>
        </template>
        {{-- @endforeach --}}
    </div>

</div>