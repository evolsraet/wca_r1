@props([
    'leftContent',
    'rightContent',
    'leftClass' => 'col-lg-6', // PC에서 좌측 컬럼 기본 클래스
    'rightClass' => 'col-lg-6', // PC에서 우측 컬럼 기본 클래스
    'leftContainerClass' => '', // 좌측 콘텐츠 래퍼 추가 클래스
    'rightContainerClass' => '', // 우측 콘텐츠 래퍼 추가 클래스
    'initialRightPanelOpen' => false // 모바일에서 우측 패널 초기 열림 상태
])

<div class="split-container container-fluid">
    <div class="row">
        <!-- 좌측 영역 -->
        <div class="split-left-panel {{ $leftClass }}">
            <div class="{{ $leftContainerClass }}">
                {{ $leftContent }}
            </div>
        </div>

        <!-- 우측 영역 (Alpine.js로 제어) -->
        <div class="split-right-panel {{ $rightClass }}"
             x-data="{ isOpen: {{ $initialRightPanelOpen ? 'true' : 'false' }}, hasInit: false }"
             x-init="setTimeout(() => { hasInit = true }, 10)">

            <div class="split-right-content-wrapper"
                 x-bind:style="
                    isOpen
                        ? (hasInit
                            ? 'transform: translateY(0); transition: transform 0.3s cubic-bezier(0,0,0.2,1); bottom:0;'
                            : 'transform: translateY(0); transition: none; bottom:0;')
                        : (hasInit
                            ? 'transform: translateY(calc(100% - 48px)); transition: transform 0.3s cubic-bezier(0,0,0.2,1); bottom:0;'
                            : 'transform: translateY(calc(100% - 48px)); transition: none; bottom:0;')
                 ">
                <div class="split-right-content-box">
                    <header class="split-right-header d-lg-none" @click="isOpen = !isOpen" style="cursor:pointer;">
                        <template x-if="isOpen">
                            <i class="bi bi-chevron-down me-2"></i>
                        </template>
                        <template x-if="!isOpen">
                            <i class="bi bi-chevron-up me-2"></i>
                        </template>
                        <span>상세보기</span>
                    </header>
                    <div class="split-right-content-inner {{ $rightContainerClass }}">
                        {{ $rightContent }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

