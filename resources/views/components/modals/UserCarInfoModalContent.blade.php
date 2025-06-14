@php
    $lastDay = config('days.car_info_cache_ttl');
@endphp

<div x-data="UserCarInfoModalContent()">
    <p>조회된 차량정보는 {{ $lastDay }}일 동안 보관됩니다.</p>
    <div class="list-group">
        <template x-for="car in cars" :key="car.no">
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" @click="selectCar(car)">
                <div>
                    <h6 class="mb-1" x-text="'차량번호: ' + car.no"></h6>
                    <small x-text="'소유자: ' + car.owner"></small>
                </div>
            </a>
        </template>
    </div>

    <button class="btn btn-primary mt-3 w-100" @click="closeModal">새로 조회하기</button>
</div>