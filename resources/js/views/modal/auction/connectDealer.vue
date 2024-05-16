<template>
    <section class="modal modal-section type-confirm alert-modal-type02">
      <div class="modal-dialog">
        <div class="modal-content shadow">
          <div class="modal-body">
            <div class="content">
              <div class="nav-header">
                <button type="button" class="btn-close" @click="closeModal"></button>
              </div>
              <div class="facturer">
                <div class="profile ps-2 pt-0 pb-2">
                  <div class="dealer-info">
                    <img src="../../../../img/myprofile_ex.png" alt="Profile Image" class="main-profile">
                    <div class="deal-info">
                      <div :class="[(bid.index === 1 ? 'red-box' : bid.index < 3 ? 'blue-box' : 'gray-box'), 'rounded-pill', 'me-0']">{{ bid.index }}순위</div>
                      <div class="bold-20-font">{{ bid?.price }} 만원</div>
                      <div class="bold-18-font">{{ userData?.dealer.name }}</div>
                      <p>{{ userData?.dealer?.company }}</p>
                    </div>
                  </div>
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
                  <div class="info-item m-0">
                    <p class="text-start">{{ userData?.dealer?.introduce }}</p>
                  </div>
                </div>
              </div>
              <div class="text-center mt-4">
                <h5>해당 딜러를 선택할까요?</h5>
                <p class="my-4">선택 완료 시, 선택한 딜러에게 문자가 발송됩니다.</p>
              </div>
              <div class="btn-group">
                <button class="btn btn-danger w-50 modal_close shadow" @click="confirmSelection">선택완료</button>
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
    bid: Object,
    userData: Object,
  });
  const emit = defineEmits(['close', 'confirm']);
  
  const closeModal = () => {
    emit('close');
  };
  
  const confirmSelection = () => {
    emit('confirm', { bid: props.bid, userData: props.userData });
  };
  </script>
  