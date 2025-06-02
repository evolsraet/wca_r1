<div class="option-icons-box mb-3 mt-3">
    <h5 class="fw-bold mb-4">옵션정보</h5>

    <div class="row row-cols-4 row-cols-sm-4 row-cols-md-6 g-3">
        @php
            $options = [
                ['label' => '키(스마트키)', 'active' => false],
                ['label' => '키(접근키)', 'active' => false],
                ['label' => '선루프', 'active' => false],
                ['label' => '파노라마선루프', 'active' => false],
                ['label' => '네비게이션', 'active' => false],
                ['label' => '어라운드뷰', 'active' => false],
                ['label' => 'S-코쿠쵸컨트롤', 'active' => false],
                ['label' => 'HUD', 'active' => false],
                ['label' => '전동트렁크', 'active' => false],
                ['label' => '뒷좌석모니터', 'active' => false],
                ['label' => '파워슬라이딩 도어', 'active' => false],
                ['label' => '후측방경보시스템', 'active' => false],
                ['label' => '차선이탈경보시스템(LDWS)', 'active' => false],
                ['label' => '고정형 하이패스', 'active' => false],
                ['label' => '전자제어장치(ECS)', 'active' => false],
                ['label' => '스페어타이어', 'active' => false],
            ];
        @endphp

        @foreach($options as $option)
        <div class="col text-center option-item {{ $option['active'] ? 'active' : 'inactive' }}">
            <div class="option-icon mb-1">
                {{-- 이미지 들어갈 자리 --}}
                <img src="{{ asset('images/options/option-icon-01.svg') }}" alt="옵션 아이콘" class="img-fluid">
            </div>
            <div class="option-label small">{{ $option['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>