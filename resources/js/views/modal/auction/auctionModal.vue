<template>
  <div v-if="showModals">
    <transition name="fade" mode="out-in">
      <section class="modal modal-section type-confirm alert-modal-type02" v-if="!showSecondModal">
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
                  <button class="btn btn-outline-secondary modal_close shadow" @click="confirmCancel">경매 취소</button>
                  <button class="btn btn_ok btn-dark shadow w-50" @click="closeAllModals01">재경매</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section v-else class="modal modal-section type-confirm alert-modal-type02">
        <div class="modal-dialog-ty04">
          <div class="modal-content-ty03 shadow">
            <div class="modal-body">
              <div class="content p-0 mt-0">
                <div class="enroll_box" style="position: relative;">
                  <img src="../../../../img/drift.png" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text04">경매가 취소되었습니다.</p>
                </div>
                <div class="btn-group">
                  <router-link :to="{ name: 'auction.index' }" class="btn btn_ok btn-danger tc-wh shadow" @click="closeAllModals">확인</router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </transition>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
  showModals: Boolean,
  auctionId: String
});

const emit = defineEmits(['close', 'confirm']);

const showSecondModal = ref(false);

const closeAllModals01 = () => {
  emit('close');
};

const closeAllModals = () => {
  emit('confirm');
  emit('close');
};

const confirmCancel = async () => {
  showSecondModal.value = true; 
};
</script>


