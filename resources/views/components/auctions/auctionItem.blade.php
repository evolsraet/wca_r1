<div class="col-md-4 mb-4">
    <a :href="'{{ route('auction.detail', '') }}' + '/' + auction.hashid" class="auction-item">
    <div class="auction-item-thumb">
        <img :src="auction.car_thumbnail" alt="차량이미지" />
        <span class="auction-item-badge text-white" :class="$store.auctionStatus.get(auction.status).class" x-show="auction.status" x-text="$store.auctionStatus.get(auction.status).label"></span>
    </div>
    <div class="auction-item-body">
        <div class="auction-item-title" x-text="auction.car_maker + ' ' + auction.car_model + ' ' + auction.car_grade_sub + ' ' + auction.car_fuel"></div>
        <div class="auction-item-sub" x-text="auction.car_year + '년 | ' + auction.car_km + 'km'"></div>
        <div class="auction-item-desc" x-text="auction.car_model + ' ' + auction.car_grade"></div>
        <div class="auction-item-tags">
        <template x-if="auction.is_accident">
            <span class="tag tag-blue opacity-75">무사고</span>
        </template>
        <template x-if="auction.is_biz">
            <span class="tag tag-red opacity-75">법인/사업자</span>
        </template>
        </div>
    </div>
    </a>
</div>