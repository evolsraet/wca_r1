@props([
    'leftContent',
    'rightContent',
    'leftClass' => 'col-lg-6', // PC에서 좌측 컬럼 기본 클래스
    'rightClass' => 'col-lg-6', // PC에서 우측 컬럼 기본 클래스
    'leftContainerClass' => '', // 좌측 콘텐츠 래퍼 추가 클래스
    'rightContainerClass' => '', // 우측 콘텐츠 래퍼 추가 클래스
    'initialRightPanelOpen' => false // 모바일에서 우측 패널 초기 열림 상태
])

<div class="split-container">
    <div class="row">
        <!-- 좌측 영역 -->
        <div class="split-left-panel {{ $leftClass }}">
            <div class="{{ $leftContainerClass }}">
                {{ $leftContent }}
            </div>
        </div>

        <!-- 우측 영역 (Alpine.js로 제어) -->
        <div class="split-right-panel {{ $rightClass }}"
             x-data="{
                isOpen: {{ $initialRightPanelOpen ? 'true' : 'false' }},
                hasInit: false,
                // 모바일 모달 내부 스크롤이 끝나도 body가 스크롤되지 않도록 막는 함수
                lockBodyScroll(e) {
                    const el = e.currentTarget;
                    const scrollTop = el.scrollTop;
                    const scrollHeight = el.scrollHeight;
                    const offsetHeight = el.offsetHeight;
                    // 터치 방향 계산
                    const direction = e.touches[0].clientY - (el._lastTouchY || 0);
                    el._lastTouchY = e.touches[0].clientY;
                    // 스크롤이 맨 위에서 아래로, 맨 아래에서 위로 더 스크롤하려 할 때 body로 이벤트 전파 방지
                    if (
                        (scrollTop === 0 && direction > 0) ||
                        (scrollTop + offsetHeight >= scrollHeight && direction < 0)
                    ) {
                        e.preventDefault();
                    }
                }
             }"
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
                            <span class="mdi mdi-chevron-down fs-2"></span>
                        </template>
                        <template x-if="!isOpen">
                            <span class="mdi mdi-chevron-up fs-2"></span>
                        </template>
                    </header>
                    <div class="split-right-content-inner {{ $rightContainerClass }} position-relative"
                        x-ref="scrollArea"
                        x-init="() => {
                            const el = $refs.scrollArea;
                            hasScroll = el.scrollHeight > el.clientHeight;
                            new ResizeObserver(() => {
                                hasScroll = el.scrollHeight > el.clientHeight;
                            }).observe(el);
                        }">
                        {{ $rightContent }}

                        {{-- 화면스크롤기능 --}}
                        <span class="position-fixed top-50 end-0 translate-middle-y"
                            x-show="hasScroll"
                            x-transition.opacity>
                            <span class="mdi mdi-unfold-more-horizontal fs-2"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

