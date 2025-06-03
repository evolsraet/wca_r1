@extends('v2.layouts.app')
@section('content')

@php
    $hashid = request()->route('id');
    $id = Hashids::decode($hashid);
    $auction = \App\Models\Auction::where('id', $id)->first();
    

    // echo '<pre>';
    // print_r($auction->status);
    // echo '</pre>';

    /*
    'cancel' => '취소',
    'done'   => '경매완료',
    'chosen' => '선택완료',
    'wait'   => '선택대기',
    'ing'    => '경매진행',
    'diag'   => '진단대기',
    'dlvr'   => '탁송중',
    'ask'    => '신청완료',
    */
    // get 파라미터로 데이터 변경 
    $status = request()->get('status') ? request()->get('status') : $auction->status;
    $isUser = auth()->user()->hasRole('user') ? 'user' : 'dealer';
    $chose = request()->get('chose') ? request()->get('chose') : 0;

    

@endphp

<div class="container pt-4" x-data="auctionDetail()">
    <x-layouts.split
        leftClass="col-lg-7"
        rightClass="col-lg-5"
        leftContainerClass=""
        rightContainerClass=""
        :initialRightPanelOpen="true">

        {{-- 왼쪽 컨텐츠 --}}
        <x-slot:leftContent>

            {{-- 상태 바 --}}
            <x-auctions.auctionStatusStep :status="$status" />

            {{-- 썸네일 --}}
            <x-auctions.auctionThumbnail :status="$status" :auction="$auction" :isUser="$isUser" />

            {{-- 차량 정보 --}}
            @if($isUser)
            <x-auctions.auctionCarInfo :status="$status" :auction="$auction" />
            @endif

            {{-- 상세 정보 --}}
            @if($isUser && ($status !== 'ask' && $status !== 'diag'))

                {{-- 옵션 정보 --}}
                <x-auctions.auctionOptions />

               {{-- 상세 정보 --}}
                <x-auctions.auctionDatailInfo />
            @endif
            
        </x-slot:leftContent>


        {{-- 오른쪽 컨텐츠 --}}
        <x-slot:rightContent>

            {{-- 유저화면 --}}
            @if($isUser && ($status == 'ask'))
            {{-- 매물 신청 완료 --}}
            <x-auctions.auctionInfoBox title="매물 신청 완료" message="해당 매물 신청이 완료 되었습니다." subMessage="※ 경매진행까지 약간의 검토 시간이 소요됩니다." />
            @endif

            @if($isUser && ($status == 'cancel'))
            {{-- 경매 취소 --}}
            <x-auctions.auctionInfoBox title="경매취소" message="해당 매물의 경매가 취소 되었습니다." subMessage="" />
            @endif

            @if($isUser == 'user' && ($status == 'ing' && $chose == 0))
            {{-- 경매 진행중 --}}
            <x-auctions.auctionInfoBox title="경매 진행중" message="입찰한 딜러가 있으면 즉시 선택이 가능합니다" subMessage="" />
            @endif

            @if($isUser == 'user' && (($status == 'ing' && $chose) || $status == 'wait'))
            {{-- 경매 진행중 --}}
                <x-auctions.activeAuctionDealers />
            @endif


            @if($isUser == 'user' && ($status == 'chosen'))

                {{-- 탁송 진행 박스 --}}
                <x-auctions.taksongProgress />

                {{-- 탁송 정보 박스 --}}
                <x-auctions.deliveryInfoBox />

                {{-- 탁송 상태 박스 --}}
                <x-auctions.deliveryStatusBox />

                {{-- 선택완료 박스 --}}
                <x-auctions.deliveryAccordion />

                {{-- 매수자 정보 박스 --}}
                <x-auctions.deliveryBuyer />

                {{-- 자주묻는 질문 박스 --}}
                <x-auctions.deliveryFaq />

                {{-- 탁송 서비스 이용고객안내 박스 --}}
                <x-auctions.auctionServiceInfo />

            @endif


            @if($isUser == 'user' && ($status == 'dlvr'))

                {{-- 탁송 정보 박스 --}}
                <x-auctions.deliveryInfoBox />

                {{-- 탁송 상태 박스 --}}
                <x-auctions.deliveryStatusBox />

                {{-- 선택완료 박스 --}}
                <x-auctions.deliveryAccordion />

                {{-- 매수자 정보 박스 --}}
                <x-auctions.deliveryBuyer />

                {{-- 자주묻는 질문 박스 --}}
                <x-auctions.deliveryFaq />
            @endif


            @if($isUser == 'user' && ($status == 'dlvr_done'))

                {{-- 매수자 정보 박스 --}}
                <x-auctions.deliveryBuyer />

                {{-- 자주묻는 질문 박스 --}}
                <x-auctions.deliveryFaq />

            @endif


            @if($isUser == 'user' && $status == 'done')
                {{-- 거래 완료 --}}
                <x-auctions.auctionDone title="거래는 어떠셨나요?" message="거래 후기를 남겨주세요." button1="후기 남기기" button1Link="/v2/board/review/form?id={{ $hashid }}" button2="명의이전 서류 확인" button2Link="#" />

            @endif

            {{-- 딜러화면 --}}

            @if($isUser == 'dealer' && ($status == 'ing' && $chose == 0))
                {{-- 입찰 박스 --}}
                <x-auctions.auctionDealerIng />
            @endif

            @if($isUser == 'dealer' && (($status == 'ing' && $chose) || $status == 'wait'))
                {{-- 입찰 박스 --}}
                <x-auctions.auctionDealerWait />
            @endif


            @if($isUser == 'dealer' && ($status == 'chosen'))
                {{-- 탁송 진행 박스 --}}
                <x-auctions.taksongProgress />

                {{-- 경락 확인서 박스 --}}
                <x-auctions.deliveryConfirm />

                {{-- 탁송 정보 박스 --}}
                <x-auctions.deliveryInfoBox />

                {{-- 탁송 주소지 박스 --}}
                <x-auctions.deliveryTacsong />
            @endif  



            @if($isUser == 'dealer' && ($status == 'dlvr'))

                {{-- 경락 확인서 박스 --}}
                <x-auctions.deliveryConfirm />

                {{-- 탁송 정보 박스 --}}
                <x-auctions.deliveryInfoBox />

                {{-- 탁송 상태 박스 --}}
                <x-auctions.deliveryStatusBox />


            @endif


            @if($isUser == 'dealer' && ($status == 'dlvr_done'))
                {{-- 명의이전 서류 박스 --}}
                <x-auctions.nameChange />
            @endif


            @if($isUser == 'dealer' && ($status == 'done'))
                {{-- 거래 완료 --}}
                <x-auctions.AuctionDone title="낙찰 완료" message="차량에 문제가 있으신가요?" button1="클레인 신청" button1Link="/v2/board/claim/form?id={{ $hashid }}" button2="경락 확인서" button2Link="#" />
            @endif


        </x-slot:rightContent>

    </x-layouts.split>
</div>

@endsection