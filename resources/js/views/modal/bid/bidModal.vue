<template>
  <transition name="fade" mode="out-in">
    <section v-if="!bidSuccess" class="modal modal-section type-confirm alert-modal-type02">
      <div class="modal-dialog-ty04">
        <div class="modal-content-ty03 shadow">
          <div class="modal-body fade-in-transition">
            <div class="content p-0 mt-0">
              <div class="enroll_box_top p-4">
                <h5 class="auction-deadline text-secondary opacity-50 mb-3">나의 입찰 금액 <span class="tc-red">{{ formattedAmount }}</span></h5>
              </div>
              <hr>
              <div class="enroll_box p-4">
                <h5 class="">해당 금액으로 입찰하시겠습니까?</h5> 
                <p class="">걱정마세요! 입찰 한 뒤에도<br>취소 후 재 입찰이 가능합니다.(1회 한정)</p>
              </div>
              <div class="btn-group">
                <button class="btn btn_ok btn-primary shadow w-50" @click="confirmBid">입찰하기</button>
                <button class="btn btn-secondary modal_close shadow" @click="cancelBid">취소</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--
    <section v-else class="modal modal-section type-confirm alert-modal-type02">
      <div class="modal-dialog-ty04">
        <div class="modal-content-ty03 shadow">
          <div class="modal-body fade-in-transition">
            <div class="content p-0 mt-0">
              <div class="enroll_box" style="position: relative;">
                <h5 class="p-4">입찰이 완료되었습니다.</h5>
              </div>
              <div class="btn-group">
                <button class="btn btn_ok btn-primary tc-wh shadow" @click="closeAllModals">확인</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    -->
  </transition>
</template>

  
  <script setup>
  import { defineProps, defineEmits, computed, ref } from 'vue';
  
  const props = defineProps({
    amount: String,
    highestBid: Number,
    lowestBid: Number,
    isHopePriceBid: Boolean
  });
  
  const emit = defineEmits(['close', 'confirm']);
  const bidSuccess = ref(false);
  
  const formattedAmount = computed(() => {
    return parseFloat(props.amount).toLocaleString('ko-KR') + ' 만원';
  });
  
  const confirmBid = () => {
    bidSuccess.value = false;
    emit('confirm');
  };
  
  const cancelBid = () => {
    emit('close');
  };
  
  const closeAllModals = () => {
    bidSuccess.value = false;
    emit('confirm');
  };
  
  const showSuccessModal = () => {
    bidSuccess.value = true;
  };
  </script>
 
  