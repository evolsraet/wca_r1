<div class="delivery-info-box py-4 border-bottom p-2" x-show="auction?.taksong_wish_at">
    <div class="fw-bold mb-3 fs-5">탁송 상태 정보</div>

    <dl class="row mb-0">
        <dt class="col-3 text-secondary">상태:</dt>
        <dd class="col-9 text-danger fw-bold" x-text="auction?.is_taksong === 'ask' ? '대기중' : auction?.is_taksong === 'start' ? '배차' : auction?.is_taksong === 'ing' ? '탁송중' : auction?.is_taksong === 'done' ? '탁송완료' : '미정'"></dd>

        <dt class="col-3 text-secondary">탁송기사:</dt>
        <dd class="col-9" x-text="auction?.taksong_courier_name ?? '미정'"></dd>

        <dt class="col-3 text-secondary">출발 주소:</dt>
        <dd class="col-9" x-text="auction?.taksong_departure_address ?? '미정'"></dd>

        <dt class="col-3 text-secondary">도착 주소:</dt>
        <dd class="col-9" x-text="auction?.taksong_dest_address ?? '미정'"></dd>

        <dt class="col-3 text-secondary">출발 시간:</dt>
        <dd class="col-9" x-text="auction?.taksong_departure_at ?? '미정'"></dd>
    </dl>
</div>