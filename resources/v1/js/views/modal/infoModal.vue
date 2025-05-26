<template>
  <div class="modal">
    <transition name="fade" mode="out-in">
      <section class="modal modal-section type-confirm alert-modal-type02">
        <div class="modal-dialog-ty04">
          <div class="modal-content-ty03">
            <div class="modal-body">
              <div class="content p-0 mt-0">
                <div class="enroll_box" style="position: relative;">
                  <img src="../../../img/drift.png" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text02">정보를 갱신 하시겠습니까?</p>
                  <p class="overlay_text03">일 1회 갱신 가능합니다. <br>갱신한 정보는 1주간 보관됩니다.</p>
                </div>
                <div class="btn-group">
                  <button class="btn btn-outline-primary modal_close shadow" @click="confirmCancel">취소</button>
                  <button class="btn btn_ok btn-primary shadow w-50" @click="handleRefresh">갱신하기</button>
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
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  showModals: Boolean,
  auctionId: String
});

const emit = defineEmits(['close', 'refresh']);

const confirmCancel = async () => {
  emit('close');
};

const handleRefresh = () => {
  emit('refresh');
  emit('close');
};
</script>
