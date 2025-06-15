@php
    $status = $status ?? 'ask';
@endphp

<script>
    window.auction = {
        status: '{{ $status }}'
    };
</script>

<div id="step-progress-box" class="step-progress-box border border-gray-200 shadow-sm rounded p-2 mb-3" x-data="auctionStatusStep">
    {{-- PC 버전 --}}
    <div class="d-none d-md-flex justify-content-between align-items-center position-relative pc-step-bar">
      <template x-for="(stepKey, index) in steps" :key="index">
        <div class="text-center flex-fill step-item" :class="{ 'active': isActive(stepKey) }">
          <div class="step-badge mb-2" x-text="'STEP' + (index + 1)"></div>
          <div class="step-dot mx-auto"></div>
          <div class="step-label mt-2" x-text="getLabel(stepKey)"></div>
        </div>
      </template>
      <div class="step-line position-absolute top-50 start-0 w-100"></div>
    </div>
  
    {{-- 모바일 버전 --}}
    <div class="d-flex d-md-none flex-wrap justify-content-center gap-2 mobile-step-badges">
      <template x-for="(stepKey, index) in steps" :key="index">
        <div class="step-badge-mobile" :class="{ 'active': isActive(stepKey) }" x-text="'STEP' + (index + 1)" :title="getLabel(stepKey)"></div>
      </template>
    </div>
  </div>