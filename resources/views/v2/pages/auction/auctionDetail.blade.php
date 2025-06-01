@extends('v2.layouts.app')
@section('content')

<div class="container pt-4" x-data="auctionDetail()">
    <x-layouts.split
        leftClass="col-lg-7"
        rightClass="col-lg-5"
        leftContainerClass=""
        rightContainerClass=""
        :initialRightPanelOpen="true">

        <x-slot:leftContent>

            <div class="step-progress-box border border-danger rounded p-3 mb-3">
                {{-- PC 레이아웃 --}}
                <div class="d-none d-md-flex justify-content-between align-items-center position-relative pc-step-bar">
                    @php
                    $steps = ['신청완료', '진단대기', '경매진행', '선택완료', '탁송중', '탁송완료', '경매완료'];
                    $currentStep = $currentStep ?? 1;
                    @endphp

                    @foreach($steps as $i => $label)
                    @php $stepNumber = $i + 1; @endphp
                    <div class="text-center flex-fill step-item {{ $stepNumber <= $currentStep ? 'active' : '' }}">
                        <div class="step-badge mb-2">STEP{{ $stepNumber }}</div>
                        <div class="step-dot mx-auto"></div>
                        <div class="step-label mt-2">{{ $label }}</div>
                    </div>
                    @endforeach
                    <div class="step-line position-absolute top-50 start-0 w-100"></div>
                </div>

                {{-- 모바일 레이아웃 --}}
                <div class="d-flex d-md-none flex-wrap justify-content-center gap-2 mobile-step-badges">
                    @foreach($steps as $i => $label)
                    @php $stepNumber = $i + 1; @endphp
                    <div class="step-badge-mobile {{ $stepNumber <= $currentStep ? 'active' : '' }}">
                        STEP{{ $stepNumber }}
                    </div>
                    @endforeach
                </div>
            </div>


            <div class="text-center bg-gray-300 py-5 px-3 rounded position-relative" style="background-color: #f8f9fa; display: none;">
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
            <div class="vehicle-info p-4 border-bottom">
                <h5 class="fw-bold mb-4">차량 정보</h5>

                <div class="row row-cols-3 gy-3 text-muted">
                    <div class="col">차대번호</div>
                    <div class="col text-dark fw-semibold" colspan="2">VF7SA9HP8EW500939</div>

                    <div class="col">차량번호</div>
                    <div class="col text-dark fw-semibold">68거1375</div>

                    <div class="col">최초등록일</div>
                    <div class="col text-dark fw-semibold">2014-04-29</div>

                    <div class="col">주행거리</div>
                    <div class="col text-dark fw-semibold">70,000 km</div>

                    <div class="col">엔진형식</div>
                    <div class="col text-dark fw-semibold">1.6 e-HDi</div>

                    <div class="col">미션</div>
                    <div class="col text-dark fw-semibold">자동</div>

                    <div class="col">제조사</div>
                    <div class="col text-dark fw-semibold">시트로엥/DS</div>

                    <div class="col">년식</div>
                    <div class="col text-dark fw-semibold">2014</div>

                    <div class="col">용도변경</div>
                    <div class="col text-dark fw-semibold">없음</div>

                    <div class="col">모델</div>
                    <div class="col text-dark fw-semibold">DS3</div>

                    <div class="col">배기량</div>
                    <div class="col text-dark fw-semibold">1560 cc</div>

                    <div class="col">연료</div>
                    <div class="col text-dark fw-semibold">경유</div>
                </div>

                <hr class="my-4">

                <div class="row row-cols-2 gy-3 text-muted">
                    <div class="col">세부모델</div>
                    <div class="col text-dark fw-semibold">DS3 (10년~16.6)</div>

                    <div class="col">세부등급</div>
                    <div class="col text-dark fw-semibold">없음</div>
                </div>
            </div>

            
        </x-slot:leftContent>

        <x-slot:rightContent>

            <div class="bg-white rounded shadow-sm p-4 text-center custom-shadow">
                {{-- 상단 타이틀 영역 --}}
                <div class="bg-light py-3 mb-4" style="background-color: #f8f9fa;">
                    <span class="fs-5">매물 신청 완료</span>
                </div>

                {{-- 본문 안내 메시지 --}}
                <p class="mb-2 text-dark fw-semibold">해당 매물 신청이 완료 되었습니다.</p>
                <p class="mb-0 text-muted small">※ 경매진행까지 약간의 검토 시간이 소요됩니다.</p>
            </div>

        </x-slot:rightContent>

    </x-layouts.split>
</div>

@endsection