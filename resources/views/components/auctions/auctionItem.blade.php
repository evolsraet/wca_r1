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

        <div class="position-absolute top-0 start-0 m-3 z-2" >
            <x-auctions.auctionStatusBadges />
        </div>

        <x-auctions.auctionLikeButton />

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

