
<div class="car-wrapper" x-data="myListings">
    <div class="car-header">
        <h5>내 매물관리</h5>
        <a href="{{ route('auction.list') }}" class="car-all-link">전체보기 &gt;</a>
    </div>

    <ul class="car-list">
        <template x-for="auction in auction" :key="auction.id">
            <a :href="'/v2/auction/' + auction.hashid" class="car-item">
                <div class="car-thumb">
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
                    :alt="auction.number"
                    />
                </div>
                <div class="car-number" x-text="auction.car_no"></div>

                {{-- <span x-text="auction.final_at"></span> --}}
                <x-auctions.auctionStatusBadges :onlyStatus="true" />
            </a>
        </template>
    </ul>

    <div class="car-add-box">
        <a href="{{ route('sell') }}" class="btn btn-dark w-100">
            새 차량 등록하기 <span class="plus-icon">+</span>
        </a>
    </div>
</div>