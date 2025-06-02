<div class="delivery-progress-box py-4">
    <div class="mb-3 fw-bold fs-5">탁송전 진행상황</div>

    <div class="d-flex justify-content-between align-items-center text-center progress-steps flex-wrap">
        @php
            $steps = [
                ['label' => '판매자', 'desc' => '탁송정보'],
                ['label' => '구매자', 'desc' => '탁송정보'],
                ['label' => '구매자', 'desc' => '입금완료'],
            ];
            $currentStep = 2; // 현재 진행중인 스텝 (1~3)
        @endphp

        @foreach($steps as $index => $step)
            <div class="step-item flex-fill position-relative">
                <div class="step-circle {{ $index + 1 <= $currentStep ? 'active' : '' }}"></div>
                <div class="step-line {{ $index + 1 < $currentStep ? 'active' : '' }}"></div>
                <div class="step-label text-muted small mt-2">
                    <div class="fw-bold">STEP0{{ $index + 1 }}</div>
                    <div class="{{ $index + 1 <= $currentStep ? 'text-dark' : 'text-secondary' }}">
                        {{ $step['label'] }}<br>{{ $step['desc'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-light text-center mt-4 py-3 rounded">
        판매자가 탁송정보를 입력했습니다.
    </div>
</div>