<template>
    <transition name="fade" mode="out-in">
    <section v-if="isVisible" class="modal modal-section type-confirm alert-modal-type02">
        <div class="modal-dialog-ty04" key="modal">
          <div class="modal-content-ty03 shadow">
            <div class="modal-body">
              <div class="content p-0 mt-0">  
                <div class="enroll_box" style="position: relative;">
                  <img src="../../../img/electric-car.png" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text04">해당 서비스는 개발 중 상태입니다.</p>
                </div>
                <div class="btn-group tc-wh">
                  <div class="btn btn-primary btn-lg shadow btn_ok" @click="closeModal">확인</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </transition>
  </template>

<script setup>
import { ref } from 'vue';

const isVisible = ref(false);

const openModal = () => {
  isVisible.value = true;
};

const closeModal = () => {
  isVisible.value = false;
};

defineExpose({ openModal, closeModal });
</script>

<style scoped>
.overlay_text04{
  font-weight: 500 !important;
}
.fade-enter-active, .fade-leave-active {
transition: opacity 0.5s ease, transform 0.5s ease;
}
.fade-enter, .fade-leave-to {
opacity: 0;
transform: translateY(20px);
}

@media screen and (max-width: 330px){
    .modal-dialog-ty04 {
        width: auto !important;
        margin: auto 0.5rem;
    } 
}
</style>
      
      