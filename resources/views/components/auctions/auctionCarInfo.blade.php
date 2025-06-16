<div x-data="auctionCarInfo">
<div class="vehicle-title-box mb-3 mt-4">
    {{-- 차량명 + 번호 --}}
    <div class="vehicle-title mt-3">
        <h3 class="fw-bold mb-2">{{ $auction->car_maker }} {{ $auction->car_model }} {{ $auction->car_grade }} {{ $auction->car_fuel }} ({{ $auction->car_no }})</h3>
    </div>

    {{-- 조회수, 관심, 입찰 --}}
    <div class="d-flex align-items-center flex-wrap gap-3 text-secondary small">
        <div><i class="bi bi-eye me-1"></i>조회수 <span x-text="auction?.hit"></span></div>
        <div><i class="bi bi-heart me-1"></i>관심 <span x-text="auction?.likes.length"></span></div>
        <div class="text-danger"><i class="bi bi-hand-index me-1"></i>입찰 <span x-text="auction?.bids.length"></span></div>
    </div>

    {{-- 상태 뱃지 --}}
    <div class="mt-2">
        <span class="badge bg-light text-primary border border-primary-subtle px-3 py-1">무사고</span>
    </div>
</div>


{{-- 차량 정보 --}}
<div class="vehicle-info border-bottom pb-4">
    <h5 class="fw-bold mb-4">차량 정보</h5>

    
    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 gy-3 text-muted">

        <div class="col">차대번호</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="diag?.data?.diag_car_id ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">차량번호</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="auction?.car_no ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">최초등록일</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="auction?.car_first_reg_date ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">주행거리</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="auction?.car_km ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">엔진형식</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="auction?.car_engine_type ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">미션</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="auction?.car_mission ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">제조사</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="auction?.car_maker ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">년식</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="auction?.car_year ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">용도변경</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="diag?.data?.diag_history_use ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">모델</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="auction?.car_model ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">배기량</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="diag?.data?.diag_displacement ? diag.data.diag_displacement + ' cc' : '-'" :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">연료</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="auction?.car_fuel ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>
    </div>

    <hr class="my-4">

    <div class="row row-cols-2 gy-3 text-muted">
        <div class="col">세부모델</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="diag?.data?.diag_submodel ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>

        <div class="col">세부등급</div>
        <div class="col text-dark fw-semibold">
            <span class="text-secondary" x-text="diag?.data?.diag_subgrade ?? '-' " :style="{ display: auction?.status == 'ask' || auction?.status == 'diag' ? 'none' : 'block' }"></span>
        </div>
    </div>

</div>
</div>