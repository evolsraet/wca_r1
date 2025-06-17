@extends('v2.layouts.app')
@section('content')

@php
    $hashid = request()->route('id');
    $id = Hashids::decode($hashid);
    $auction = \App\Models\Auction::where('id', $id)->first();

    $status = request()->get('status') ? request()->get('status') : $auction->status;
    $chose = request()->get('chose') ? request()->get('chose') : 0;
    $isUser = auth()->user()->hasRole('user') ? 'user' : 'dealer';
@endphp

<script>
    window.hashid = '{{ $hashid }}';
    window.carNumber = '{{ $auction->car_no }}';
    window.status = '{{ $status }}';
</script>

<div class="pt-3 gap-lg-3" x-data="auctionDetail">
    <x-layouts.split
        leftClass="col-lg-7"
        rightClass="col-lg-5"
        leftContainerClass=""
        rightContainerClass=""
        :initialRightPanelOpen="true">

        {{-- 왼쪽 컨텐츠 --}}
        <x-slot:leftContent>
            <x-auctions.auctionStatusStep :status="$status" />
            <x-auctions.auctionThumbnail :status="$status" :auction="$auction" :isUser="$isUser" />

            @if($isUser)
                <x-auctions.auctionCarInfo :status="$status" :auction="$auction" />
            @endif

            @if($isUser && ($status !== 'ask' && $status !== 'diag'))
                <x-auctions.auctionOptions />
                <x-auctions.auctionDatailInfo />
            @endif
        </x-slot:leftContent>

        {{-- 오른쪽 컨텐츠 --}}
        <x-slot:rightContent>
            @switch(true)
                @case($isUser == 'user' && $status == 'ask')
                    <x-auctions.auctionInfo title="매물 신청 완료" message="해당 매물 신청이 완료 되었습니다." subMessage="※ 경매진행까지 약간의 검토 시간이 소요됩니다." />
                    @break

                @case($isUser == 'user' && $status == 'diag')
                    <x-auctions.auctionInfo title="진단 대기" message="진단 대기 중입니다." subMessage="※ 진단 대기 중입니다." />
                    @break

                @case($isUser == 'user' && $status == 'cancel')
                    <x-auctions.auctionInfo title="경매취소" message="해당 매물의 경매가 취소 되었습니다." subMessage="" />
                    @break

                @case($isUser == 'user' && ($status == 'ing' && $chose == 0))
                    <x-auctions.auctionInfo title="경매 진행중" message="입찰한 딜러가 있으면 즉시 선택이 가능합니다" subMessage="" />
                    @break

                @case($isUser == 'user' && (($status == 'ing' && $chose) || $status == 'wait'))
                    <x-auctions.activeAuctionDealers />
                    @break

                @case($isUser == 'user' && $status == 'chosen')
                    <x-auctions.taksongProgress />
                    <x-auctions.taksongInfo />
                    <x-auctions.taksongStatus />
                    <x-auctions.taksongPreparation />
                    <x-auctions.taksongBuyerInfo />
                    <x-auctions.taksongFaq />
                    <x-auctions.taksongUserGuide />
                    @break

                @case($isUser == 'user' && $status == 'dlvr')
                    <x-auctions.taksongInfo />
                    <x-auctions.taksongStatus />
                    <x-auctions.taksongPreparation />
                    <x-auctions.taksongBuyerInfo />
                    <x-auctions.taksongFaq />
                    @break

                @case($isUser == 'user' && $status == 'dlvr_done')
                    <x-auctions.taksongBuyerInfo />
                    <x-auctions.taksongFaq />
                    @break

                @case($isUser == 'user' && $status == 'done')
                    <x-auctions.auctionDone title="거래는 어떠셨나요?" message="거래 후기를 남겨주세요." button1="후기 남기기" button1Link="/v2/board/review/form?id={{ $hashid }}" button2="명의이전 서류 확인" button2Link="#" />
                    @break

                @case($isUser == 'dealer' && ($status == 'ing' && $chose == 0))
                    <x-auctions.auctionDealerIng />
                    @break

                @case($isUser == 'dealer' && (($status == 'ing' && $chose) || $status == 'wait'))
                    <x-auctions.auctionDealerWait />
                    @break

                @case($isUser == 'dealer' && $status == 'chosen')
                    <x-auctions.taksongProgress />
                    <x-auctions.taksongConfirm />
                    <x-auctions.taksongInfo />
                    <x-auctions.taksongAddress />
                    @break

                @case($isUser == 'dealer' && $status == 'dlvr')
                    <x-auctions.taksongConfirm />
                    <x-auctions.taksongInfo />
                    <x-auctions.taksongStatus />
                    @break

                @case($isUser == 'dealer' && $status == 'dlvr_done')
                    <x-auctions.nameChange />
                    @break

                @case($isUser == 'dealer' && $status == 'done')
                    <x-auctions.AuctionDone title="낙찰 완료" message="차량에 문제가 있으신가요?" button1="클레인 신청" button1Link="/v2/board/claim/form?id={{ $hashid }}" button2="경락 확인서" button2Link="#" />
                    @break
            @endswitch
        </x-slot:rightContent>
    </x-layouts.split>
</div>

@endsection