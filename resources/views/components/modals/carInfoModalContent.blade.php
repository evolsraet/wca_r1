<div x-data="carInfoModalContent">
    <template x-for="car in cars" :key="car.id">
        <div class="border rounded p-3 mb-3 d-flex align-items-start cursor-pointer" @click="goToWrite(car.hashId)">
            <div class="me-3">
                <img
                    :src="car.car_thumbnail || 'https://via.placeholder.com/100x70?text=Car'"
                    alt="차량 이미지"
                    class="rounded border"
                    style="width: 100px; height: 70px; object-fit: cover;"
                >
            </div>
            <div class="flex-grow-1">
                <p class="mb-1"><strong>차량번호:</strong> <span x-text="car.car_no"></span></p>
                <p class="mb-1"><strong>소유자:</strong> <span x-text="car.owner_name"></span></p>
                <p class="mb-1 text-muted small" x-text="`${car.car_year}년식 · ${car.car_model} · ${car.car_fuel}`"></p>
            </div>
        </div>
    </template>

    <div class="text-center mt-3" x-show="cars.length === 0">
        <p>작성 가능한 차량이 없습니다.</p>
        <button class="btn btn-primary" @click="closeModal">닫기</button>
    </div>

</div>