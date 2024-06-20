<template>
    <div class="container mov-wide">
      <div class="d-flex flex-column">
        <div class="mt-3">
          <h4 class="ms-5 my-5">이런 단계를 거쳐요</h4>
        </div>
        <div>
          <div class="introduce-action">
            <img src="../../../../img/auction-detil.png" alt="Auction Detail" class="auction-detail-img">
          </div>
          <div class="mb-4 p-5 flex items-center justify-end container justify-content-center">
            <button @click="infomodal" class="btn primary-btn btn-style w-100">다음</button>
          </div>
        </div>
      </div>
      <AlarmGuidModal :isOpen="modalinfo" @close="closeModal" />
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import { useStore } from 'vuex';
  import { gsap } from 'gsap';
  import useUsers from '@/composables/users';
  import AlarmGuidModal from '@/views/consignment/AlarmGuidModal.vue';
  
  const modalinfo = ref(false);
  
  const infomodal = () => {
    modalinfo.value = true;
  };
  
  const closeModal = () => {
    modalinfo.value = false;
  };
  </script>
  
  <style>
  .btn-style {
    width: 422px;
  }
  </style>
  