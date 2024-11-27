<template>
  <footer class="footer">
    <div class="content p-4 container has-text-centered">
      <div class="left fw-medium">
        <p class="mb-3">Copyrights 위카옥션 All Rights Reserved<br>Terms of Use / Privacy Policy</p>
        <p class="mb-4">
          <span @click="openModal('privacy')" class="link-style pointer">개인정보 처리방침</span> |
          <span @click="openModal('terms')" class="link-style pointer">이용약관</span>
        </p>
      </div>
      <div class="right">
        <p class="mb-4 right">
          <span class="size_20 bolder">위카옥션이 함께하면<br>내차판매 걱정없어요</span>
          <p class="mt-3">온라인 경매 서비스 ㅣ위카옥션(주) ㅣ대표이사 정태영</p>
          <p>사업자등록번호 : 755-81-02354 I 통신판매업신고 제2023-용인기흥-6932호</p>
          <p>경기도 용인시 기흥구 중부대로 242 동 W117호</p>
          <p>대표전화번호:  1544-2165 이메일 WECAR@WECR-M.CO.KR</p>
        </p>
        <img src="../../../img/footerlogo.png" width="64px" height="21px" class="mt-2">
      </div>
    </div>
    <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
  </footer>
</template>

<script>
export default {
  name: 'Footer'
}
</script>

<script setup>
import {createApp,h,ref,inject } from "vue";
import LawGid from '@/views/modal/LawGid.vue';
import { cmmn } from '@/hooks/cmmn';

const swal = inject('$swal');
const { wica , wicaLabel } = cmmn();
const isModalOpen = ref(false);
const modalContent = ref('');

const openModal = (type) => {
  const container = document.createElement('div');
  const app = createApp({
    render() {
      return h(LawGid, { content: type });
    }
  });
  
  app.mount(container);
  
  const text = container.innerHTML;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 인 경우 활성화
    .addOption({ padding: 20 }) // swal 기타 옵션 추가
    .addClassNm('intro-modal')
    .useClose()
    .callback(function (result) {
      // 추가적인 콜백 함수 내용이 필요하다면 여기에 작성
    })
    .confirm(text);

  app.unmount(); // Unmount the app after getting the HTML content
};
const openAlarmModal = () => {
  const text= `<div class="enroll_box" style="position: relative;">
                  <img src="${carInfo}" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text04">해당 서비스는 개발 중 상태입니다.</p>
               </div>`;
  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 인 경우 활성화
    .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
    .addOption({ padding: 20 }) // swal 기타 옵션 추가
    .callback(function (result) {

    })
    .confirm(text);
};


const closeModal = () => {
  isModalOpen.value = false;
};
</script>
