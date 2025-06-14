@php
    $status = $status ?? 'ask';
@endphp
<div class="step-progress-box border border-gray-300 rounded p-3 mb-3">
    {{-- PC 레이아웃 --}}
    <div class="d-none d-md-flex justify-content-between align-items-center position-relative pc-step-bar">
        @php
         $statusToStep = [
            'ask' => 1,
            'diag' => 2,
            'ing' => 3,
            'wait' => 3,
            'chosen' => 4,
            'dlvr' => 5,
            'dlvr_done' => 6,
            'done' => 7,
        ];
        $steps = ['신청완료', '진단대기', '경매진행', '선택완료', '탁송중', '탁송완료', '경매완료'];
        $currentStep = $statusToStep[$status] ?? 1;
        @endphp

        @foreach($steps as $i => $label)
        @php $stepNumber = $i + 1; @endphp
        <div class="text-center flex-fill step-item {{ $stepNumber <= $currentStep ? 'active' : '' }}">
            <div class="step-badge mb-2">STEP{{ $stepNumber }}</div>
            <div class="step-dot mx-auto"></div>
            <div class="step-label mt-2">{{ $label }}</div>
        </div>
        @endforeach
        <div class="step-line position-absolute top-50 start-0 w-100"></div>
    </div>

    {{-- 모바일 레이아웃 --}}
    <div class="d-flex d-md-none flex-wrap justify-content-center gap-2 mobile-step-badges">
        @foreach($steps as $i => $label)
        @php $stepNumber = $i + 1; @endphp
        <div class="step-badge-mobile {{ $stepNumber <= $currentStep ? 'active' : '' }}">
            STEP{{ $stepNumber }}
        </div>
        @endforeach
    </div>
</div>