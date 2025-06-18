{{-- 경매상태 뱃지 / 경매 카운트 다운 포함 --}}
<div class="position-absolute top-0 start-0 m-3 z-2" status-toggle-btn @click="hideStatusStep()">
        <div x-show="auction?.status === 'ing'">
            {{-- 카운트다운 표시 --}}
            <div
                class="bg-danger text-white rounded-3 px-2 py-1 fs-7 d-inline-flex align-items-center gap-1"
                x-init="
                $watch('auction?.final_at', value => {
                    if (value) {
                        window.dispatchEvent(new CustomEvent('start-countdown', { detail: { finalAt: value } }));
                    }
                })
                "
            >
                <i class="mdi mdi-clock-outline"></i>
                <span data-timer>--:--:--</span>
            </div>

        </div>
        <div x-show="auction?.status !== 'ing'">
            {{-- 경매상태 뱃지 --}}
            <span 
                class="auction-item-badge text-white p-2 rounded-3" 
                id="auction-status-badge" 
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="클릭하면 경매상태바 를 숨깁니다." 
                :class="$store.auctionStatus.get(auction?.status).class" 
                x-text="$store.auctionStatus.get(auction?.status).label"
            >
            </span>
        </div>
</div>

