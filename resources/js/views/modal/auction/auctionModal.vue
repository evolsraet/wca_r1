<!--
    TODO:popup창 클릭 안했는데 aution에 로드 되는현상 
-->
<template>
    <transition name="fade" mode="out-in">
      <section class="modal modal-section type-confirm alert-modal-type02" v-if="isVisible">
        <div class="modal-dialog-ty04">
          <div class="modal-content-ty03 shadow">
            <div class="modal-body">
              <div class="content p-0 mt-0">  
                <div class="enroll_box" style="position: relative;">
                  <img src="../../../../img/drift.png" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text02">경매를 취소하시겠습니까?</p>
                  <p class="overlay_text03">경매자가 마음에 들지 않으시다면<br>재경매를 진행 할 수 있어요.</p>
                </div>
                <div class="btn-group">
                  <button class="btn btn-outline-secondary modal_close shadow" @click="toggleModal">경매 취소</button>
                  <button class="btn btn_ok btn-dark shadow w-50">재경매</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </transition>
    <transition name="fade" mode="out-in">
      <section v-if="showSecondModal" class="modal modal-section type-confirm alert-modal-type02">
        <div class="modal-dialog-ty04">
          <div class="modal-content-ty03 shadow">
            <div class="modal-body">
              <div class="content p-0 mt-0">  
                <div class="enroll_box" style="position: relative;">
                  <img src="../../../../img/drift.png" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text04">경매를 취소되었습니다.</p>
                </div>
                <div class="btn-group">
                  <router-link :to="{ name: 'autction.index' }" class="btn btn_ok btn-danger tc-wh shadow" @click="toggleModal">확인</router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </transition>
  </template>
  
  <script setup>
  import { ref, defineProps, defineEmits } from 'vue';
  
  const showSecondModal = ref(false); 
  const props = defineProps({
  modelValue: Boolean
});

  </script>
  