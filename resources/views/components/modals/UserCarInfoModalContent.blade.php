@php
    $lastDay = config('days.car_info_cache_ttl');
@endphp

<div x-data="userCarInfoModalContent()" x-init="console.log('Blade 템플릿에서 Alpine 컴포넌트 초기화 시작')">
    <p>조회된 차량정보는 {{ $lastDay }}일 동안 보관됩니다.</p>
    <div class="list-group">
        <template x-for="car in cars" :key="car.no">
            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div class="flex-grow-1" @click="selectCar(car)" style="cursor: pointer;">
                    <h6 class="mb-1" x-text="'차량번호: ' + car.no"></h6>
                    <small x-text="'소유자: ' + car.owner"></small>
                </div>
                <div class="ms-2">
                    <button 
                        class="btn btn-outline-danger btn-sm" 
                        @click.stop="deleteCar(car)"
                        title="차량 정보 삭제"
                    >
                        ✕
                    </button>
                </div>
            </div>
        </template>
    </div>

    <button class="btn btn-primary mt-3 w-100" @click="closeModal">새로 조회하기</button>
    
    <script>
        console.log('Blade 스크립트 실행');
        console.log('window.userCarInfo:', window.userCarInfo);
        console.log('Alpine:', typeof Alpine !== 'undefined' ? 'loaded' : 'not loaded');
        
        // Alpine이 로드되었는지 확인
        if (typeof Alpine !== 'undefined') {
            console.log('Alpine 컴포넌트 확인:', typeof Alpine.components?.userCarInfoModalContent);
        }
    </script>
</div>