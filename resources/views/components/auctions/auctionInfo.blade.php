@php
    $title = $title ?? '매물 신청 완료';
    $message = $message ?? '해당 매물 신청이 완료 되었습니다.';
    $subMessage = $subMessage ?? '※ 경매진행까지 약간의 검토 시간이 소요됩니다.';
@endphp
<div>
    <div x-show="(auction?.status == 'ask' || auction?.status == 'diag' || auction?.status == 'cancel' || auction?.status == 'ing')">
        <div class="bg-white rounded p-4 text-center" x-show="auction?.status == 'cancel' || auction?.bids?.length == 0">
            {{-- 상단 타이틀 영역 --}}
            <div class="bg-light py-3 mb-4" style="background-color: #f8f9fa;">
                <span class="fs-5">{{ $title }}</span>
            </div>

            {{-- 본문 안내 메시지 --}}
            <p class="mb-2 text-dark fw-semibold">{{ $message }}</p>
            <p class="mb-0 text-muted small">{{ $subMessage }}</p>

            <div x-show="!(auction?.status == 'ask' || auction?.status == 'diag')">
                <x-auctions.auctionDocsButton />
            </div>
        </div>
    </div>

    <div x-show="(auction?.status == 'ing' || auction?.status == 'wait')">
        <div x-show="auction?.bids?.length > 0">
            <x-auctions.activeAuctionDealers />
        </div>
    </div>

</div>