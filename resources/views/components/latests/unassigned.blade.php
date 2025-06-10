@php
    // 향후 차량 데이터가 들어오면 배열로 구성
    $unassignedCars = []; // 데이터가 없을 경우

    $auctionCount = 0;
    $interestCount = 0;

    // 예: 있으면 카운팅만 업데이트
    if (!empty($unassignedCars)) {
        $auctionCount = 2;
        $interestCount = 1;
    }
@endphp

<div class="unassigned-wrapper">
    <div class="unassigned-header d-flex justify-content-between align-items-center">
        <div>
            <strong>탁송지 미등록 매물</strong>
            <div class="text-muted small mt-1">낙찰된 매물중 탁송지 미등록 매물입니다</div>
        </div>
        <a href="#" class="notice-link text-danger fw-bold" @click.prevent="Alpine.store(`modal`).showHtmlFromUrl(`/v2/docs/deliveryGuide?raw=1`, {title: `탁송운영 안내`, size: `modal-lg`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]})">탁송운영 안내</a>
    </div>

    @if (empty($unassignedCars))
        <div class="unassigned-empty-box">
            <div class="unassigned-section">
                <div class="unassigned-icon">
                    <img src="{{ asset('images/favorite-car-icon02.png') }}" alt="경매장 아이콘" />
                </div>
                <div class="unassigned-count">{{ $auctionCount }}대</div>
                <div class="unassigned-label">경매장</div>
            </div>

            <div class="divider"></div>

            <div class="unassigned-section">
                <div class="unassigned-icon">
                    <img src="{{ asset('images/favorite-car-icon.png') }}" alt="관심차 아이콘" />
                </div>
                <div class="unassigned-count">{{ $interestCount }}대</div>
                <div class="unassigned-label">관심차</div>
            </div>
        </div>

        <div class="unassigned-action">
            <a href="{{ route('auction.list') }}" class="btn-unassigned-go">입찰하러 가기</a>
        </div>
    @endif
</div>