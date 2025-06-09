
<div class="car-wrapper" x-data="myListings">
    <div class="car-header">
        <h5>내 매물관리</h5>
        <a href="{{ route('auction.list') }}" class="car-all-link">전체보기 &gt;</a>
    </div>

    <ul class="car-list">
        <template x-for="car in cars" :key="car.id">
            <a :href="'/v2/auction/' + car.hashid" class="car-item">
                <div class="car-thumb">
                    <img
                    :src="(() => {
                        if ((car.car_thumbnails) && car.car_thumbnails.length > 0) {
                            return car.car_thumbnails[0];
                        }else{
                            if(car.car_thumbnail){
                                return car.car_thumbnail;
                            }else{
                                return '/images/car_example.png';
                            }
                        }
                    })()"
                    :alt="car.number"
                    />
                </div>
                <div class="car-number" x-text="car.car_no"></div>
                <div
                class="badge car-status text-white"
                :class="$store.auctionStatus.get(car.status).class"
                x-text="$store.auctionStatus.get(car.status).label"
                ></div>
            </a>
        </template>
    </ul>

    <div class="car-add-box">
        <a href="#" class="btn btn-dark w-100">
            새 차량 등록하기 <span class="plus-icon">+</span>
        </a>
    </div>
</div>