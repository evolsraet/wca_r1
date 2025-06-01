
<div class="car-wrapper" x-data="myListings">
    <div class="car-header">
        <h5>내 매물관리</h5>
        <a href="#" class="car-all-link">전체보기 &gt;</a>
    </div>

    <ul class="car-list">
        <template x-for="car in cars" :key="car.id">
            <li class="car-item">
                <div class="car-thumb">
                    <img :src="car.car_thumbnail" alt="car.number">
                </div>
                <div class="car-number" x-text="car.car_no"></div>
                <div
                class="badge car-status text-white"
                :class="{
                    'text-bg-danger': car.status === 'cancel',
                    'text-bg-secondary': car.status === 'done',
                    'text-bg-primary': car.status === 'chosen',
                    'text-bg-warning': car.status === 'wait',
                    'text-bg-info': car.status === 'ing',
                    'text-bg-light': car.status === 'diag',
                    'text-bg-primary': car.status === 'dlvr',
                    'text-bg-success': car.status === 'ask'
                }"
                x-text="status[car.status]"
                ></div>
            </li>
        </template>
    </ul>

    <div class="car-add-box">
        <a href="#" class="btn btn-dark w-100">
            새 차량 등록하기 <span class="plus-icon">+</span>
        </a>
    </div>
</div>