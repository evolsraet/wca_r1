@php
    $isUser = auth()->user()->hasRole('user') ? 'user' : 'dealer';
@endphp
<div class="col-md-4 mb-4">
    <a :href="'{{ route('auction.detail', '') }}' + '/' + auction.hashid" class="auction-item">
    <div class="auction-item-thumb position-relative">
        <img
        :src="(() => {
            if ((auction.car_thumbnails) && auction.car_thumbnails.length > 0) {
                return auction.car_thumbnails[0];
            }else{
                if(auction.car_thumbnail){
                    return auction.car_thumbnail;
                }else{
                    return '/images/car_example.png';
                }
            }
        })()"
        alt="차량이미지"
        />
        <div class="position-absolute top-0 start-0 z-2 p-2" x-show="auction.status === 'ing'">

            <div 
            class="bg-danger text-white rounded-3 px-2 py-1 fs-7 d-inline-flex align-items-center gap-1"
            x-init="window.dispatchEvent(new CustomEvent('start-countdown', { detail: { finalAt: auction.final_at } }))"
            >
                <i class="mdi mdi-clock-outline"></i>
                <span data-timer>--:--:--</span>
            </div>

        </div>

        @if($isUser === 'dealer')
        <div class="position-absolute top-0 end-0 z-3">
            <button
                class="btn fs-2 text-white"
                data-auction-id="4"
                onclick="dispatchLikeEvent(this)"
                >
                <i class="mdi mdi-heart" :class="auction.likes?.[0]?.id ? 'text-danger' : 'text-white'"></i>
            </button>
        </div>
        @endif

        <span class="auction-item-badge text-white" x-show="auction.status !== 'ing'" :class="$store.auctionStatus.get(auction.status).class" x-show="auction.status" x-text="$store.auctionStatus.get(auction.status).label"></span>
    </div>
    <div class="auction-item-body">
        <div class="auction-item-title" x-text="auction.car_maker + ' ' + auction.car_model + ' ' + auction.car_grade_sub + ' ' + auction.car_fuel + ' (' + auction.car_no + ')'"></div>
        <div class="auction-item-sub" x-text="auction.car_year + '년 | ' + auction.car_km + 'km'"></div>
        <div class="auction-item-desc" x-text="auction.car_model + ' ' + auction.car_grade"></div>
        <div class="auction-item-tags">
        <template x-if="auction.is_accident">
            <span class="tag border border-black text-black bg-transparent">무사고</span>
        </template>
        <template x-if="auction.is_biz">
            <span class="tag border border-danger text-danger bg-transparent">법인/사업자</span>
        </template>
        </div>
    </div>
    </a>
</div>

<script>
function dispatchLikeEvent(el) {
  // auctionId를 버튼에서 가져옴
  const auctionId = el.dataset.auctionId;
  
  // JS에서 auction 객체를 찾아야 함. 이건 너의 앱 구조에 따라 달라.
  // 여기선 예시로 전역 배열을 사용하는 경우를 가정
  const auction = window.auctions?.find(a => a.id == auctionId);

  if (!auction) {
    console.warn('Auction 데이터를 찾을 수 없습니다:', auctionId);
    return;
  }

  const likeId = auction.likes?.[0]?.id || null;

  window.dispatchEvent(new CustomEvent('toggle-like', {
    detail: {
      auctionId: auction.id,
      userId: 999999, // 임시 고정값 (실제 유저 ID로 대체해라 제발)
      likeId: likeId
    }
  }));
}
</script>