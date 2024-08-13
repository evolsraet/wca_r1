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
  import { ref, inject } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import { useStore } from 'vuex';
  import { gsap } from 'gsap';
  import useUsers from '@/composables/users';
  import { cmmn } from '@/hooks/cmmn';
  import AlarmGuidModal from '@/views/consignment/AlarmGuidModal.vue';
  import info1 from '../../../../../resources/img/modal/info3.png';
  import info2 from '../../../../../resources/img/modal/info1.png';
  import info3 from '../../../../../resources/img/modal/info2.png';
  const swal = inject('$swal');
  const router = useRouter(); 
  const modalinfo = ref(false);
  const { wica } = cmmn();
  
  const infomodal = () => {
    
      const text = `<div class="content p-2 mt-0"> 
            <h3 class="mb-2">필요 서류</h3>
            <h5 class="text-secondary opacity-50 fw-normal mb-4">미리 준비하면 더 빠르게 진행돼요!</h5>
            <div class="text-start my-3 process"> 
              <h4 class="mb-3">개인</h4>
              <div class="d-flex justify-content-between">
                <div class="info-block">
                  <p class="text-start">경매 등록시</p>
                  <img src="${info1}" class="info-img">
                  <div class="text-center">
                    <p>필요서류 없음</p>
                    <p class="text-secondary opacity-50">본인 소유 차량이 아닐경우<br>위임장 또는 소유자 인감<br>증명서가 필요합니다.</p>
                  </div>
                </div>
                <div class="info-block">
                  <p class="text-start">경매 완료시</p>
                  <img src="${info3}" class="info-img2">
                  <p class="text-center">매도용 인감 증명서</p>
                </div>
              </div>
            </div>
            <hr class="custom-hr" />
            <div>
              <div class="text-start my-3 process"> 
                <h4 class="mb-3">딜러</h4>
                <div class="d-flex justify-content-between align-items-end">
                  <div class="info-block">
                    <p class="text-start">경매 등록시</p>
                    <img src="${info2}" class="info-img3">
                  </div>
                  <div class="info-block">
                    <p>차량 이전서류</p>
                  </div>
                </div>
              </div>
            </div>
            </div>`;
  
    wica.ntcn(swal)
      .useClose()
      .useHtmlText()
      .addClassNm('primary-check')
      .addOption({ padding: 20, height: 265 })
      .callback(function (result) {
        if (result.isOk) {
          router.push({ path: '/selldt2' }); 
        }
      })
      .confirm(text);
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
  <style scoped>

</style>