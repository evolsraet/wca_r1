<template>
    <div class="container mov-wide d-flex flex-column gap-5">
      <div class="p-2">
        <div class="my-5">
          <h4>탁송 확인</h4>
          <p class="text-center mt-5 tc-light-gray">탁송 기사분이 탁송 완료 처리 버튼을 눌러주세요!</p>
          <button 
            :disabled="isCompletedButtonClicked"
            @click="handleCompleteClick"
            class="btn btn-outline-primary w-100 mt-2 shadow-sm">
            탁송 완료 처리
          </button>
        </div>
        <div class="mt-5">
          <h4>탁송 서비스 이용고객안내</h4>
          <div>
            <ul class="timeline p-0 mt-4">
              <li :class="{ completed: isCompletedButtonClicked }">
                <div class="d-flex gap-2">
                  <div class="circle">1</div>
                  <span>키와 서류를 전달해 주세요.</span>
                </div>
              </li>
              <div class="small-circles my-3"></div>
              <li>
                <div class="d-flex gap-2">
                  <div class="circle">2</div>
                  <span>상단의 탁송 완료 처리 버튼을 탁송 <br>기사분에게 제시해 주세요.</span>
                </div>
              </li>
              <div class="small-circles mb-3"></div>
              <li>
                <div class="d-flex gap-2">
                  <div class="circle">3</div>
                  <span>차량대금 지급 후, 경매 완료 처리 됩니다.</span>
                </div>
              </li>
            </ul>
          </div>
          <div class="d-flex justify-content-center my-4">
            <img src="../../../img/modal/car-objects-blur.png" alt="자동차 이미지" width="150px">
          </div>
          <button 
            :disabled="!isCompletedButtonClicked"
            class="btn btn-primary w-100 my-4 shadow-sm" @click="completed">
            탁송 완료
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import { inject } from 'vue';
  import useAuctions from '@/composables/auctions';
  import carObjects from '../../../../resources/img/modal/car-objects-blur.png';
  import { cmmn } from '@/hooks/cmmn';
  
  const isCompletedButtonClicked = ref(false);
  const route = useRoute();
  const router = useRouter();
  const { AuctionReauction } = useAuctions();
  const swal = inject('$swal');
  const { wica } = cmmn();
  
  const handleCompleteClick = () => {
    isCompletedButtonClicked.value = true;
  };
  /* 임시 */
  const completed = async () => {
    const id = route.params.id;
   // console.log(">>>>>>>>",id)
    let data = {
      status: 'done',
    };
  
    try {
      await AuctionReauction(id, data);
      const textOk = `<div class="enroll_box" style="position: relative;">
        <img src="${carObjects}" alt="자동차 이미지" width="160" height="160">
        <p class="overlay_text02">탁송 완료 처리 되었습니다.</p>
        <p class="overlay_text03 tc-light-gray">※차량대금 지급후, 경매 완료 처리 됩니다.</p>
      </div>`;
      wica.ntcn(swal)
        .useHtmlText() // HTML 태그 인 경우 활성화
        .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
        .addOption({ padding: 20 }) // swal 기타 옵션 추가
        .callback(function (result) {
          router.push('/auction');
        })
        .confirm(textOk);
    } catch (error) {
      console.error('Error re-auctioning:', error);
      alert('재경매에 실패했습니다.');
    }
  };
  </script>
  
  <style>
  </style>
  