<template>
  <section class="modal modal-section type-confirm alert-modal-type02 process">
    <div class="modal-dialog">
      <div class="modal-content shadow">
        <div class="modal-body">
          <div class="content mt-3">
            <div class="nav-header">
              <button type="button" class="btn-close" @click="closeModal"></button>
            </div>
            <div class="facturer">
              <div class="profile ps-2 pt-0 pb-2 ms-0">
                <div class="dealer-info">
                  <img src="../../../../img/myprofile_ex.png" alt="Profile Image" class="main-profile">
                  <div class="d-flex justify-content-center flex-column flex-nowrap align-center">
                    <h4>{{ userData?.dealer.name }}</h4>
                    <p>{{ userData?.dealer?.company }}</p>
                    <p class="mt-4 restar">(4.5점)</p>
                  </div>
                </div>
              </div>
              <div>
              </div>
              <div class="pt-2 pb-3">
                <div class="info-item m-0">
                  <div class="phone"></div>
                  <p>010-1234-1234</p>
                </div>
                <div class="info-item m-0">
                  <div class="location"></div>
                  <p><span>{{ userData?.dealer?.company_addr1 }}, {{ userData?.dealer?.company_addr2 }}</span></p>
                </div>
                <div class="introduce-area info-item m-0">
                  <p class="text-start process">{{ userData?.dealer?.introduce }}</p>
                </div>
              </div>
            </div>
            <div class="top-content-style wd-100">
                <p class="tc-light-gray">입찰 금액</p>
                <h4>{{ amtComma(bid?.price) }}</h4>
            </div>
            <div class="d-flex justify-content-center my-4">
              <img src="../../../../img/modal/car-objects-blur.png" alt="자동차 이미지" width="150px">
            </div>
            <div class="text-center">
              <p class="my-4 tc-light-gray">선택 완료 시, 선택한 딜러에게 문자가 발송됩니다.</p>
            </div>
            <div class="btn-group">
              <button class="btn btn-primary w-50 modal_close shadow bold-18-font" @click="confirmSelection">선택 하기</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { cmmn } from '@/hooks/cmmn';
const props = defineProps({
  bid: Object,
  userData: Object,
});
const emit = defineEmits(['close', 'confirm']);
const { amtComma } = cmmn();
const closeModal = () => {
  emit('close');
};

const confirmSelection = () => {
  emit('confirm', { bid: props.bid, userData: props.userData });
};
</script>

<style scoped>
.introduce-area {
  max-height: 200px;
  overflow-y: auto;
  padding: 10px;
  margin: 10px 0 10px 0 !important;
}
</style>
