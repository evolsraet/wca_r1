<template>
    <!-- modal step02 Start : 경매 취소 팝업 -->
    <section class="modal modal-section type-confirm alert-modal-type02">
        <!-- 모달 컨텐츠 -->
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-body">
                    <div class="content">
                        <div class="nav-header">
                            <button type="button" class="btn-close" @click="closeModal">
                            </button>
                        </div>
                        <div class="facturer">
                            <div class="profile">
                                <div class="dealer-info">
                                    <img src="../../../../img/myprofile_ex.png" alt="Profile Image" class="main-profile">
                                    <div class="deal-info">
                                        <div class="red-box rounded-pill me-0">
                                            1순위
                                        </div>
                                        <div class="bold-20-font">1,000만원</div>
                                        <div class="bold-18-font">홍길동</div>
                                        <p>파트너 점</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="info-item m-0">
                                    <div class="phone"></div>
                                    <p>010-1234-1234</p>
                                </div>
                                <div class="info-item m-0">
                                    <div class="location"></div>
                                    <p>
                                        <span>대전 둔산동 하이스트빌</span>
                                    </p>
                                </div>
                                <div class="info-item m-0">
                                    <p class="text-start">소개</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <h5>해당 딜러를 선택할까요?</h5>
                            <p class="my-4">선택 완료 시, 선택한 딜러에게 문자가 발송됩니다.</p>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-danger w-50 modal_close shadow"  @click="confirmSelection">선택완료</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
  
<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  show: Boolean,
  bid: Object
});

const emit = defineEmits(['confirm', 'close']);

function confirmSelection() {
  emit('confirm', props.bid);
}

function closeModal() {
  emit('close');
}
</script>