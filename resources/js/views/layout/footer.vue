<template>
  <footer class="footer">
    <div class="content p-4 container has-text-centered">
      <div class="left fw-medium">
        <p class="mb-3">경매등록하면,<br>위카가 알아서 내 차판매!</p>
        <p>위카 대표명 홍길동 · 사업자등록번호 1234-5678-1234 · 메일문의 example@naver.com</p>
      </div>
      <div class="right">
        <p class="mb-4">
          <span @click="openModal('privacy')" class="link-style">개인정보 처리방침</span> |
          <span @click="openModal('terms')" class="link-style">이용약관</span>
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
import { ref } from "vue";
import LawGid from '@/views/modal/LawGid.vue';

const isModalOpen = ref(false);
const modalContent = ref('');

const openModal = (type) => {
  modalContent.value = type;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};
</script>
