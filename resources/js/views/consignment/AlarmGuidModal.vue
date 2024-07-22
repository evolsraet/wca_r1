<template>
  <transition name="fade">
    <div v-if="isOpen" class="modal-backdrop" @click="close">
      <div class="modal-dialog-ty04" key="modal">
        <div class="modal-content-ty03 shadow">
          <button type="button" class="btn-close" @click="close"></button>
          <div class="modal-body p-2">
            <div class="content p-0 mt-0">  
              <h3 class="mb-2">필요 서류</h3>
              <h4 class="text-secondary opacity-50 fw-normal mb-4">미리 준비하면 더 빠르게 진행돼요!</h4>
              <div class="text-start my-3 process"> 
                <h4 class="mb-3">개인</h4>
                <div class="d-flex justify-content-between">
                  <div class="info-block">
                    <p class="text-start">경매 등록시</p>
                    <img src="../../../img/modal/info3.png" class="info-img">
                    <div class="text-center">
                      <p>필요서류 없음</p>
                      <p class="text-secondary opacity-50">본인 소유 차량이 아닐경우<br>위임장 또는 소유자 인감<br>증명서가 필요합니다.</p>
                    </div>
                  </div>
                  <div class="info-block">
                    <p class="text-start">경매 완료시</p>
                    <img src="../../../img/modal/info2.png" class="info-img">
                    <p class="text-center">매도용 인감 증명서</p>
                  </div>
                </div>
              </div>
              <hr class="custom-hr" />
            </div>
            <div>
              <div class="text-start my-3 process"> 
              <h4 class="mb-3">딜러</h4>
              <div class="d-flex justify-content-between align-items-end">
                  <div class="info-block">
                    <p class="text-start">경매 등록시</p>
                    <img src="../../../img/modal/info1.png" class="info-img">
                  </div>
                  <div class="info-block">
                    <p>차량 이전서류</p>
                  </div>
                </div>
              </div>
              <router-link  :to="{ path: '/selldt2' }" class="btn w-100 btn-primary mt-4">확인</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['close']);

const close = () => {
  emit('close');
};
</script>

<style scoped>
@media screen and (max-width: 330px) {
  .modal-dialog-ty04 {
    max-width: 540px;
    height: calc(100% - 3.5rem);
    margin: 11.75rem auto;
    padding: 0 20px;
  }
}
.modal-dialog-ty04 {
  max-width: 540px;
    width: 100%;
    height: calc(100% - 3.5rem);
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 0 auto;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content-ty03 {
  background: white;
    padding: 20px;
    border-radius: 10px;
    max-width: 500px;
    width: 100%;
    max-height: calc(100% - 128px);
    overflow-y: auto;
    text-align: center;
}

.info-block {
  width: 48%;
}

.info-img {
  width: 100%;
  height: auto;
}
</style>

<style>
.modal-content-ty03 {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 100%;
  pointer-events: auto;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 0.3rem;
  outline: 0;
}
@media screen and (min-height: 1050px) {
  .modal-dialog-ty04 {
    height: calc(100% - 15.5rem) !important;
  }
}
.modal-dialog-ty04{
    margin: 180px 0 auto;
}
</style>
