<div class="delivery-progress-box py-4" x-data="taksongProgress()" x-init="initWithWatch()">
    <div class="mb-3 fw-bold fs-5">탁송전 진행상황</div>

    <div class="d-flex justify-content-between align-items-center text-center progress-steps flex-wrap">
        <template x-for="(step, index) in steps" :key="index">
            <div class="step-item flex-fill position-relative">
                <div class="step-circle" :class="getStepClass(index)"></div>
                <div class="step-line"></div>
                <div class="step-label text-muted small mt-2">
                    <div class="fw-bold" x-text="'STEP0' + (index + 1)"></div>
                    <div :class="getLabelClass(index)">
                        <span x-text="step.label"></span><br>
                        <span x-text="step.desc"></span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="bg-light text-center mt-4 py-3 rounded" x-show="window.userRole === 'user'">
        <button class="btn btn-primary w-100 mb-3" :disabled="auction?.taksong_wish_at" @click="openModal()">
            탁송정보 입력
        </button>
        탁송정보를 입력해 주세요.
    </div>
</div>