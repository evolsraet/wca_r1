@php
    $auction = $auction ?? null;
@endphp

@if($isUser && ($status == 'ask' || $status == 'diag'))
    <div class="text-center bg-gray-300 py-5 px-3 rounded position-relative" style="background-color: #f8f9fa;">
        {{-- 상태 뱃지 --}}
        <div class="badge bg-secondary position-absolute top-0 start-0 m-3">
            신청완료
        </div>

        {{-- 썸네일 이미지 (빈 썸네일 아이콘) --}}
        <div class="my-4">
            <img src="{{ asset('images/diag.png') }}" alt="진단 대기" style="width: 120px; opacity: 0.4;">
        </div>

        {{-- 텍스트 안내 --}}
        <p class="text-muted fs-5 mb-0">{{ config('app.name') }} 이 진단대기 상태에요</p>
    </div>
@endif

@if($isUser && ($status !== 'ask' && $status !== 'diag'))
    {{-- 경매 차량 이미지 --}}
    <div class="auction-thumbnail carousel-wrapper mb-3" style="">
        <div id="customCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://diag-dev.wecarmobility.co.kr//uploads/storage2/diag/18571/618171e2ac3044e67cb123a4bfc71381.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="https://diag-dev.wecarmobility.co.kr//uploads/storage2/diag/18571/8330c9ed1953c998aed974898a0e967f.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="https://diag-dev.wecarmobility.co.kr//uploads/storage2/diag/18571/e1bdc1382129592196ce5db17577241c.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="https://diag-dev.wecarmobility.co.kr//uploads/storage2/diag/18571/8330c9ed1953c998aed974898a0e967f.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="https://diag-dev.wecarmobility.co.kr//uploads/storage2/diag/18571/e1bdc1382129592196ce5db17577241c.jpg" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#customCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#customCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
            </button>
        </div>

        {{-- 썸네일 하단 표시 --}}
        <div class="carousel-thumbnails d-flex justify-content-center flex-wrap mt-3 gap-2">
            <img src="https://diag-dev.wecarmobility.co.kr//uploads/storage2/diag/18571/618171e2ac3044e67cb123a4bfc71381.jpg" class="thumb-img" data-bs-target="#customCarousel" data-bs-slide-to="0" aria-current="true" aria-label="Slide 1">
            <img src="https://diag-dev.wecarmobility.co.kr//uploads/storage2/diag/18571/8330c9ed1953c998aed974898a0e967f.jpg" class="thumb-img" data-bs-target="#customCarousel" data-bs-slide-to="1" aria-label="Slide 2">
            <img src="https://diag-dev.wecarmobility.co.kr//uploads/storage2/diag/18571/e1bdc1382129592196ce5db17577241c.jpg" class="thumb-img" data-bs-target="#customCarousel" data-bs-slide-to="2" aria-label="Slide 3">
            <img src="https://diag-dev.wecarmobility.co.kr//uploads/storage2/diag/18571/8330c9ed1953c998aed974898a0e967f.jpg" class="thumb-img" data-bs-target="#customCarousel" data-bs-slide-to="3" aria-label="Slide 4">
        </div>
    </div>
@endif