@extends('v2.layouts.admin')

@section('title', '차량 상세 정보')

@section('content')

@php
    $hashid = request()->route('id');
    $id = Hashids::decode($hashid);
    $auction = \App\Models\Auction::where('id', $id)->first();

    $status = request()->get('status') ? request()->get('status') : $auction->status;
    $choose = request()->get('choose') ? request()->get('choose') : 0;
    $isUser = auth()->user()->hasRole('user') ? 'user' : 'dealer';
    $userId = auth()->user()->id;
@endphp

<script>
    window.hashid = '{{ $hashid }}';
    window.carNumber = '{{ $auction->car_no }}';
    window.status = '{{ $status }}';
    window.userId = '{{ $userId }}';
</script>

<div x-data="auctionDetail">
<div class="mb-4">
    <h4 class="fw-bold">매물 상세 정보</h4>
    <p class="text-muted">매물의 상세 정보를 확인할 수 있습니다.</p>
</div>

<div class="row g-4">
    {{-- 차량 이미지 --}}
    <div class="col-md-6">
        
        <x-auctions.auctionThumbnail :status="$status" :auction="$auction" :isUser="$isUser" />
        <x-auctions.auctionCarInfo :status="$status" :auction="$auction" />
        <x-auctions.auctionOptions />
        <x-auctions.auctionDatailInfo />
        
    </div>

    {{-- 차량 기본 정보 --}}
    <div class="col-md-6">
        <div class="card p-3">
            <h5 class="mb-3">기본 정보</h5>
            <dl class="row">
                <dt class="col-sm-4">차량번호</dt>
                <dd class="col-sm-8">12가3456</dd>

                <dt class="col-sm-4">제조사</dt>
                <dd class="col-sm-8">기아</dd>

                <dt class="col-sm-4">차종</dt>
                <dd class="col-sm-8">스포티지 R</dd>

                <dt class="col-sm-4">연식</dt>
                <dd class="col-sm-8">2018</dd>

                <dt class="col-sm-4">주행거리</dt>
                <dd class="col-sm-8">158,000km</dd>

                <dt class="col-sm-4">연료</dt>
                <dd class="col-sm-8">디젤</dd>

                <dt class="col-sm-4">변속기</dt>
                <dd class="col-sm-8">오토</dd>
            </dl>
        </div>

        <div class="card p-3 mt-3">
            <h5 class="mb-3">입찰 정보</h5>
            <dl class="row">
                <dt class="col-sm-2">입찰건수</dt>
                <dd class="col-sm-10">3건</dd>

                <dt class="col-sm-2">최고 입찰가</dt>
                <dd class="col-sm-10">5,550,000원</dd>

                <dt class="col-sm-2">상태</dt>
                <dd class="col-sm-10"><span class="badge bg-success">탁송중</span></dd>
            </dl>
        </div>

        <div class="card p-3 mt-3">
            <h5 class="mb-3">입찰 정보</h5>
            <dl class="row">
                <dt class="col-sm-2">입찰건수</dt>
                <dd class="col-sm-10">3건</dd>

                <dt class="col-sm-2">최고 입찰가</dt>
                <dd class="col-sm-10">5,550,000원</dd>

                <dt class="col-sm-2">상태</dt>
                <dd class="col-sm-10"><span class="badge bg-success">탁송중</span></dd>
            </dl>
        </div>

        <div class="card p-3 mt-3">
            <h5 class="mb-3">관리자 메모</h5>
            <p class="text-muted">외부판금 및 리어 휀더 도색 이력 있음. 클레임 접수 이력은 없음.</p>
        </div>

    </div>

</div>
</div>
@endsection