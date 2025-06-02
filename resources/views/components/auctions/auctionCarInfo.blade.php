<div class="vehicle-title-box d-flex justify-content-between align-items-start flex-wrap mb-3 mt-3">
    {{-- 차량명 + 번호 --}}
    <div class="vehicle-title mt-2">
        <h4 class="fw-bold mb-2">DS3 SA 디젤 <span class="text-secondary">(68거1375)</span></h4>
    </div>

    {{-- 조회수, 관심, 입찰 --}}
    <div class="d-flex align-items-center flex-wrap gap-3 text-secondary small">
        <div><i class="bi bi-eye me-1"></i>조회수 9</div>
        <div><i class="bi bi-heart me-1"></i>관심 2</div>
        <div class="text-danger"><i class="bi bi-hand-index me-1"></i>입찰 3</div>
    </div>

    {{-- 상태 뱃지 --}}
    <div class="mt-2">
        <span class="badge bg-light text-primary border border-primary-subtle px-3 py-1">무사고</span>
    </div>
</div>


{{-- 차량 정보 --}}
<div class="vehicle-info border-bottom pb-4">
    <h5 class="fw-bold mb-4">차량 정보</h5>

    <div class="row row-cols-4 gy-3 text-muted">
        <div class="col">차대번호</div>
        <div class="col text-dark fw-semibold" colspan="2">{{ $status == 'ask' || $status == 'diag' ? '-' : 'VF7SA9HP8EW500939' }}</div>

        <div class="col">차량번호</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '68거1375' }}</div>

        <div class="col">최초등록일</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '2014-04-29' }}</div>

        <div class="col">주행거리</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '70,000 km' }}</div>

        <div class="col">엔진형식</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '1.6 e-HDi' }}</div>

        <div class="col">미션</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '자동' }}</div>

        <div class="col">제조사</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '시트로엥/DS' }}</div>

        <div class="col">년식</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '2014' }}</div>

        <div class="col">용도변경</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '없음' }}</div>

        <div class="col">모델</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : 'DS3' }}</div>

        <div class="col">배기량</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '1560 cc' }}</div>

        <div class="col">연료</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '경유' }}</div>
    </div>

    <hr class="my-4">

    <div class="row row-cols-2 gy-3 text-muted">
        <div class="col">세부모델</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : 'DS3 (10년~16.6)' }}</div>

        <div class="col">세부등급</div>
        <div class="col text-dark fw-semibold">{{ $status == 'ask' || $status == 'diag' ? '-' : '없음' }}</div>
    </div>
</div>