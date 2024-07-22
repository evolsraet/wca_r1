<!-- src/views/modal/BankModal.vue -->
<template>
      <div>
        <div class="detail-content02" :class="{ 'active': isActive }" @touchstart="handleTouchStart" @touchmove="handleTouchMove" @touchend="handleTouchEnd">
          <div class="nav-header">

          </div>
          <div class="content mt-0 p-0">
            <div>
              <h5 class="text-center p-2 mx-4 mt-4">은행을 선택해주세요</h5>
              <ul class="menu p-0">
                <li class="menu-item" :class="{ active: selectedTab === 'bank' }" @click="selectTab('bank')">은행</li>
                <li class="menu-item" :class="{ active: selectedTab === 'securities' }" @click="selectTab('securities')">증권</li>
              </ul>
            </div>
            <div class="bank-content" v-if="selectedTab === 'bank'">
              <div class="bank-scroll">
                <div class="grid bank-selection p-2">
                  <div class="bank-item" data-bank="하나" @click="selectBank('하나')">
                    <img src="../../../../img/bank-img/hana-logo.png" alt="하나은행">
                    <span>하나</span>
                  </div>
                  <div class="bank-item" data-bank="국민" @click="selectBank('국민')">
                    <img src="../../../../img/bank-img/kb-logo.png" alt="국민은행">
                    <span>국민</span>
                  </div>
                  <div class="bank-item" data-bank="우리" @click="selectBank('우리')">
                    <img src="../../../../img/bank-img/woori-logo.png" alt="우리은행">
                    <span>우리</span>
                  </div>
                  <div class="bank-item" data-bank="신한" @click="selectBank('신한')">
                    <img src="../../../../img/bank-img/shinhan-logo.png" alt="신한은행">
                    <span>신한</span>
                  </div>
                  <div class="bank-item" data-bank="농협" @click="selectBank('농협')">
                    <img src="../../../../img/bank-img/nonghyeop.png" alt="농협은행">
                    <span>농협</span>
                  </div>
                  <div class="bank-item" data-bank="카카오뱅크" @click="selectBank('카카오뱅크')">
                    <img src="../../../../img/bank-img/kakaobank.png" alt="카카오뱅크">
                    <span>카카오뱅크</span>
                  </div>
                  <div class="bank-item" data-bank="토스뱅크" @click="selectBank('토스뱅크')">
                    <img src="../../../../img/bank-img/toss.png" alt="카카오뱅크">
                    <span>토스뱅크</span>
                  </div>
                  <div class="bank-item" data-bank="기업" @click="selectBank('기업')">
                    <img src="../../../../img/bank-img/ibk-logo.png" alt="기업은행">
                    <span>기업</span>
                  </div>
                  <div class="bank-item" data-bank="대구" @click="selectBank('대구')">
                    <img src="../../../../img/bank-img/daegu-logo.png" alt="대구은행">
                    <span>대구</span>
                  </div>
                  <div class="bank-item" data-bank="부산" @click="selectBank('부산')">
                    <img src="../../../../img/bank-img/bnk.png" alt="부산은행">
                    <span>부산</span>
                  </div>
                  <div class="bank-item" data-bank="경남" @click="selectBank('경남')">
                    <img src="../../../../img/bank-img/bnk.png" alt="경남은행">
                    <span>경남</span>
                  </div>
                  <div class="bank-item" data-bank="광주" @click="selectBank('광주')">
                    <img src="../../../../img/bank-img/gwangju.png" alt="광주은행">
                    <span>광주</span>
                  </div>
                  <div class="bank-item" data-bank="전북" @click="selectBank('전북')">
                    <img src="../../../../img/bank-img/gwangju.png" alt="전북은행">
                    <span>전북</span>
                  </div>
                  <div class="bank-item" data-bank="제주" @click="selectBank('제주')">
                    <img src="../../../../img/bank-img/shinhan-logo.png" alt="제주은행">
                    <span>제주</span>
                  </div>
                  <div class="bank-item" data-bank="산업" @click="selectBank('산업')">
                    <img src="../../../../img/bank-img/sanup.png" alt="산업은행">
                    <span>산업</span>
                  </div>
                  <div class="bank-item" data-bank="수협" @click="selectBank('수협')">
                    <img src="../../../../img/bank-img/suhyeop.png" alt="수협은행">
                    <span>수협</span>
                  </div>
                  <div class="bank-item" data-bank="한국씨티" @click="selectBank('한국씨티')">
                    <img src="../../../../img/bank-img/ssiti.png" alt="한국씨티">
                    <span>한국씨티</span>
                  </div>
                  <div class="bank-item" data-bank="SC제일" @click="selectBank('SC제일')">
                    <img src="../../../../img/bank-img/jeil.png" alt="SC제일은행">
                    <span>SC제일</span>
                  </div>
                  <div class="bank-item" data-bank="hsbc" @click="selectBank('hsbc')">
                    <img src="../../../../img/bank-img/hsbc.png" alt="hsbc은행">
                    <span>HSBC</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="bank-content" v-if="selectedTab === 'securities'">
              <div class="bank-scroll">
                <div class="grid bank-selection p-2">
                  <div class="bank-item" data-bank="토스증궝" @click="selectBank('토스증궝')">
                    <img src="../../../../img/bank-img/toss.png" alt="토스증궝">
                    <span>토스증궝</span>
                  </div>
                  <div class="bank-item" data-bank="카카오페이증권" @click="selectBank('카카오페이증권')">
                    <img src="../../../../img/bank-img/kakaopay.png" alt="카카오페이증권">
                    <span>카카오페이증권</span>
                  </div>
                  <div class="bank-item" data-bank="미래에셋" @click="selectBank('미래에셋')">
                    <img src="../../../../img/bank-img/miraeeset .png" alt="미래에셋증권">
                    <span>미래에셋</span>
                  </div>
                  <div class="bank-item" data-bank="키움" @click="selectBank('키움')">
                    <img src="../../../../img/bank-img/kium.png" alt="키움증권">
                    <span>키움</span>
                  </div>
                  <div class="bank-item" data-bank="한국투자" @click="selectBank('한국투자')">
                    <img src="../../../../img/bank-img/hanguktuja .png" alt="한국투자증권">
                    <span>한국투자</span>
                  </div>
                  <div class="bank-item" data-bank="신한투자" @click="selectBank('신한투자')">
                    <img src="../../../../img/bank-img/shinhan-logo.png" alt="신한투자증권">
                    <span>신한투자</span>
                  </div>
                  <div class="bank-item" data-bank="삼성증권" @click="selectBank('삼성증권')">
                    <img src="../../../../img/bank-img/samsung.png" alt="삼성증권">
                    <span>삼성증권</span>
                  </div>
                  <div class="bank-item" data-bank="KB증권" @click="selectBank('KB증권')">
                    <img src="../../../../img/bank-img/kb-logo.png" alt="KB증권">
                    <span>KB증권</span>
                  </div>
                  <div class="bank-item" data-bank="NH투자" @click="selectBank('NH투자')">
                    <img src="../../../../img/bank-img/nonghyeop.png" alt="NH투자증권">
                    <span>NH투자</span>
                  </div>
                  <div class="bank-item" data-bank="유안타" @click="selectBank('유안타')">
                    <img src="../../../../img/bank-img/yuan.png" alt="유안타증권">
                    <span>유안타</span>
                  </div>
                  <div class="bank-item" data-bank="대신" @click="selectBank('대신')">
                    <img src="../../../../img/bank-img/daesin.png" alt="대신증권">
                    <span>대신</span>
                  </div>
                  <div class="bank-item" data-bank="IBK투자" @click="selectBank('IBK투자')">
                    <img src="../../../../img/bank-img/ibk-logo.png" alt="IBK투자">
                    <span>IBK투자</span>
                  </div>
                  <div class="bank-item" data-bank="하나증권" @click="selectBank('하나증권')">
                    <img src="../../../../img/bank-img/hana-logo.png" alt="하나증권">
                    <span>하나증권</span>
                  </div>
                  <div class="bank-item" data-bank="DB금융투자" @click="selectBank('DB금융투자')">
                    <img src="../../../../img/bank-img/db.png" alt="DB금융투자">
                    <span>DB금융투자</span>
                  </div>
                  <div class="bank-item" data-bank="BNK투자" @click="selectBank('BNK투자')">
                    <img src="../../../../img/bank-img/bnk.png" alt="BNK투자">
                    <span>BNK투자</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </template>
  
  <script setup>
  import { ref, defineProps, onMounted, onUnmounted } from 'vue';
  
  const props = defineProps({
    onSelectBank: {
      type: Function,
      required: true
    }
  });
  
  const isActive = ref(false);
  const selectedTab = ref('bank');
  const startY = ref(0);
  const currentY = ref(0);
  const isDragging = ref(false);
  
  const closeDetailContent = () => {
    isActive.value = false;
  };
  
  const selectTab = (tab) => {
    selectedTab.value = tab;
  };
  
  const selectBank = (bankName) => {
    if (props.onSelectBank) {
      props.onSelectBank(bankName);
    }
    closeDetailContent();
  };
  
  const handleTouchStart = (event) => {
    startY.value = event.touches[0].clientY;
    isDragging.value = true;
  };
  
  const handleTouchMove = (event) => {
    if (!isDragging.value) return;
    currentY.value = event.touches[0].clientY;
    const diffY = currentY.value - startY.value;
    if (diffY > 0) {
      document.querySelector('.detail-content02').style.transform = `translateY(${diffY}px)`;
    }
  };
  
  const handleTouchEnd = () => {
    isDragging.value = false;
    const diffY = currentY.value - startY.value;
    const halfHeight = window.innerHeight / 4;
    if (diffY > halfHeight) {
      closeDetailContent();
    } else {
      document.querySelector('.detail-content02').style.transform = 'translateY(0)';
    }
  };
  
  // Event binding on mount and unmount
  onMounted(() => {
    document.addEventListener('touchstart', handleTouchStart);
    document.addEventListener('touchmove', handleTouchMove);
    document.addEventListener('touchend', handleTouchEnd);
  });
  
  onUnmounted(() => {
    document.removeEventListener('touchstart', handleTouchStart);
    document.removeEventListener('touchmove', handleTouchMove);
    document.removeEventListener('touchend', handleTouchEnd);
  });
  </script>
  
  
  <style scoped>
  .show-content {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    top: -30px;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: flex-end;
    z-index: 9999;
  }
  
  
  .detail-content02 {
    background: #fff;
    width: 100%;
    border-radius: 25px;
    transition: transform 0.3s ease-in-out;
  }
  
  .detail-content02.active {
    transform: translateY(-4px);
  }
  
  .nav-header {
    display: flex;
    justify-content: flex-end;
    padding: 10px;
  }
  
  .grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
  }
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.8s ease;
  }
  
  .fade-enter, .fade-leave-to {
    opacity: 0;
  }
  
  .menu-item {
    cursor: pointer;
  }
  
  .menu-item.active {
    font-weight: bold;
    border-bottom: 2px solid #000;
  }
  </style>