@php
    $d_flex = '';
    $mt_3 = 'mt-3';
    if(isset($style)){
        $d_flex = 'd-flex justify-content-between gap-3';
        $mt_3 = '';
    }
@endphp

<div>

<div class="row mb-4" x-show="window.userRole==='dealer'">

    {{-- 딜러 전용: 이용가이드, 진단오류 보상 --}}
    <div class="col-md-6 {{ $d_flex }}">

        <a href="#" 
           class="card border-0 shadow text-decoration-none" 
           @click.prevent="Alpine.store('modal').showHtmlFromUrl('/v2/docs/usageGuide?raw=1', {
                title: '이용가이드', 
                size: 'modal-lg', 
                footerButtons: [{ text: '닫기', class: 'btn-secondary', dismiss: true }]
            })">
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-9">
                <h5 class="card-title">이용가이드</h5>
                <p class="card-text fw-light">{{ config('app.name') }} 이렇게 이용하세요!</p>
              </div>
              <div class="col-md-3 text-end">
                <img src="{{ asset('images/use_icon1.png') }}" alt="이용가이드" class="img-fluid" style="width: 50px;">
              </div>
            </div>
          </div>
        </a>

        <a href="#" 
           class="card border-0 shadow text-decoration-none {{ $mt_3 }}"
           @click.prevent="Alpine.store('modal').showHtmlFromUrl('/v2/docs/claimPolicy?raw=1', {
                title: '진단오류 보상', 
                size: 'modal-lg', 
                footerButtons: [{ text: '닫기', class: 'btn-secondary', dismiss: true }]
            })"
        >
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-9">
                <h5 class="card-title">진단오류 보상</h5>
                <p class="card-text fw-light">진단오류시 보상 기준표를 안내합니다.</p>
              </div>
              <div class="col-md-3 text-end">
                <img src="{{ asset('images/use_icon3.png') }}" alt="진단오류 보상" class="img-fluid" style="width: 50px;">
              </div>
            </div>
          </div>
        </a>

    </div>

    {{-- 수수료, 패널티: 모든 사용자 공통 --}}
    <div class="col-md-6 {{ $d_flex }}">

        <a href="#" 
           class="card border-0 shadow text-decoration-none"
           @click.prevent="Alpine.store('modal').showHtmlFromUrl('/v2/docs/usageFee?raw=1', {
                title: '수수료 안내', 
                size: 'modal-lg', 
                footerButtons: [{ text: '닫기', class: 'btn-secondary', dismiss: true }]
            })"
        >
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-9">
                <h5 class="card-title">수수료 안내</h5>
                <p class="card-text fw-light">소정의 정보제공 수수료가 있어요.</p>
              </div>
              <div class="col-md-3 text-end">
                <img src="{{ asset('images/use_icon2.png') }}" alt="수수료 안내" class="img-fluid" style="width: 50px;">
              </div>
            </div>
          </div>
        </a>

        <a href="#" 
           class="card border-0 shadow text-decoration-none {{ $mt_3 }}"
           @click.prevent="Alpine.store('modal').showHtmlFromUrl('/v2/docs/penaltyGuide?raw=1', {
                title: '패널티 부과', 
                size: 'modal-lg', 
                footerButtons: [{ text: '닫기', class: 'btn-secondary', dismiss: true }]
            })"
        >
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-9">
                <h5 class="card-title">패널티 부과</h5>
                <p class="card-text fw-light">경매장 이용 오류시 패널티가 부과됩니다</p>
              </div>
              <div class="col-md-3 text-end">
                <img src="{{ asset('images/use_icon7.png') }}" alt="패널티 부과" class="img-fluid" style="width: 50px;">
              </div>
            </div>
          </div>
        </a>

    </div>

</div>



<div class="row mb-4" x-show="window.userRole==='user'">

  {{-- 일반 유저 전용 안내 --}}
  <div class="row mt-4">

    <div class="col-md-4">
      <a href="#" 
        class="card border-0 shadow text-decoration-none"
        @click.prevent="Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/auctionProcessSteps', {
              id: 'auctionProcessSteps',
              title: '경매 진행 순서 안내',
              footerButtons: [{ text: '닫기', class: 'btn-secondary', dismiss: true }]
          }, {
              content: { view: 1 }
          })"
      >
        <div class="card-body p-4">
          <div class="row">
            <div class="col-md-9">
              <h5 class="card-title">경매 절차 안내</h5>
              <p class="card-text fw-light">이런 순서대로 경매가 이루어져요</p>
            </div>
            <div class="col-md-3 text-end">
              <img src="{{ asset('images/use_icon4.png') }}" alt="경매 절차 안내" class="img-fluid" style="width: 50px;">
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="{{ route('docs.show', 'vehicleOwnershipTransfer') }}" 
        class="card border-0 shadow text-decoration-none"
      >
        <div class="card-body p-4">
          <div class="row">
            <div class="col-md-9">
              <h5 class="card-title">명의 이전시 필요 서류</h5>
              <p class="card-text fw-light">이런 서류들이 필요해요</p>
            </div>
            <div class="col-md-3 text-end">
              <img src="{{ asset('images/use_icon5.png') }}" alt="경매 절차 안내" class="img-fluid" style="width: 50px;">
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="#" 
        class="card border-0 shadow text-decoration-none"
        @click.prevent="Alpine.store('modal').showHtmlFromUrl('/v2/docs/usageNotice?raw=1', {
              id: 'usageNotice',
              title: '차량출품 조건 및 유의사항',
              showFooter: false
          })"
      >
        <div class="card-body p-4">
          <div class="row">
            <div class="col-md-9">
              <h5 class="card-title">차량출품 조건 및 유의사항 </h5>
              <p class="card-text fw-light">차량출품전 내용을 확인하세요</p>
            </div>
            <div class="col-md-3 text-end">
              <img src="{{ asset('images/use_icon1.png') }}" alt="차량출품 조건" class="img-fluid" style="width: 50px;">
            </div>
          </div>
        </div>
      </a>
    </div>

  </div>

</div>
</div>