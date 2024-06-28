<template>
  <!--
    TODO: 게스트일떄 모든리뷰 가져오는거 해결하기.

  -->
    <div class="container">
      <!-- 회원가입 권장 섹션 -->
      <div class="main-contenter">
        <div class="my-5 app-specific-size">
          <div class="video-container d-sm-flex mb-3">
            <video autoplay loop muted>
              <source src="../../../img/video/mainvideo.mp4" type="video/mp4">
            </video>
          </div>
        </div>
        <div class="register-content">
          <div v-if="!isMobileView">
       <!--   <div class="any-content">
          <div class="review-any"></div>-->
          <div class="video-container02">
          <video width="90%" class="video_type03" autoplay loop muted style="opacity: 0.5;">
              <source src="../../../img/video/title.mp4" type="video/mp4">
          </video>
        </div>
        <div :class="animationClass" ref="animatedSection">
        <div class="css-ifyyt1 gap-5">
          <div class="font-title"><h5 class="tc-light-gray font-title">쉽고 빠른 내차팔기,</h5>
        <h5 class="font-title">{{ wicaLabel.title() }}과 함께해요.</h5>
      </div>
     <p class="tc-light-gray font-sub-title"><span class="mx-2">{{ emoji }}</span>요새 인기있는 매물은 "{{ carName }}" 이에요</p>
    </div>
  </div>
  </div>
   <!--  <div v-if="!isMobileView">
          <div class="content-container">
            <div class="any-content">
              <div class="review-any">
                <div class="grid-container">
                
                  <div class="cardtype02" v-for="n in 8" :key="n">
                    <h5 class="text-start process">현대 쏘나타 (DN8)</h5>
                    <img src="../../../img/car_example.png" alt="Car">
                    <div class="content">
                      <div class="rating">
                        <span>★</span>
                        <span>★</span>
                        <span>★</span>
                        <span>★</span>
                        <span>☆</span>
                      </div>
                      <div class="date">2024-03-18</div>
                      <p>차감아서 보다 안 아닐 그럴시다 다급하다 무슨 절망 아닌 자기게 달려가야 누구에 고은 발생한가.</p>
                      <div class="price">3,250만원</div>
                    </div>
                  </div>
             
                  <div class="cardtype02" v-for="n in 8" :key="'d' + n">
                    <h5 class="text-start process">현대 쏘나타 (DN8)</h5>
                    <img src="../../../img/car_example.png" alt="Car">
                    <div class="content">
                      <div class="rating">
                        <span>★</span>
                        <span>★</span>
                        <span>★</span>
                        <span>★</span>
                        <span>☆</span>
                      </div>
                      <div class="date">2024-03-18</div>
                      <p>차감아서 보다 안 아닐 그럴시다 다급하다 무슨 절망 아닌 자기게 달려가야 누구에 고은 발생한가.</p>
                      <div class="price">3,250만원</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div :class="animationClass" ref="animatedSection">
        <div class="css-ifyyt1 gap-5">
          <div class="font-title"><h5 class="tc-light-gray font-title">쉽고 빠른 내차팔기,</h5>
        <h5 class="font-title">위카와 함께해요.</h5>
      </div>
      <p class="tc-light-gray font-sub-title">13,314명이 위카와 함께 했어요!</p>
    </div>
  </div>
  </div>-->
          <!--    <div class="layout-container">
     
          <div class="banner" ref="bannerRef">
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="http://placehold.it/1920X720" class="d-block w-100" alt="...">
                  <div class="carousel-caption d-none d-md-block">
                    <h5>슬라이드 배너 step 1</h5>
                  </div>
                </div>
                <div class="carousel-item">
                  <img src="http://placehold.it/1920X720" class="d-block w-100" alt="...">
                  <div class="carousel-caption d-none d-md-block">
                    <h5>슬라이드 배너 step 2</h5>
                  </div>
                </div>
                <div class="carousel-item">
                  <img src="http://placehold.it/1920X720" class="d-block w-100" alt="...">
                  <div class="carousel-caption d-none d-md-block">
                    <h5>슬라이드 배너 step 3</h5>
                  </div>
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>-->
     
          <div v-if="isMobileView" class="review-content mb-5" ref="reviewContentRef">
            <div class="apply-top text-start">
              <h3 class="review-title">다른 사람들의 이용후기에요</h3>
              <router-link :to="{ name: 'index.allreview' }" class="btn-apply">전체보기</router-link>
            </div>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col mb-3" v-for="review in reviewsData.slice(0,4)" :key="review">
                <div class="card">
                  <div class="car-imges"></div>
                  <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="rating">
                        <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                            <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                            <span :class="['star-icon', index <= review.star ? 'filled' : '']"></span>
                        </label>
                    </div>
                    <div class="d-sm-flex justify-content-between text-muted">
                        <span class="deilname">담당 딜러 {{ review.dealer.name }} 님</span>
                        <span class="date">{{ splitDate(review.created_at) }}</span>
                    </div>
                    <p class="card-text">{{ review.content }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div v-if="isMobileView">
            <bottom-sheet initial="half" :dismissable="true">
              <template #default>
               
                <form @submit.prevent="submitCarInfo" class="d-flex flex-column justify-content-center">
                  <div class="row mb-1 mt-1">
                    <div class="col-12">
                      <div>
                        <input type="text" class="form-control border-0 border-bottom my-4" placeholder="소유자가 누구인가요?" v-model="carInfoForm.owner">
                      </div>
                      <div>
                        <input type="text" class="form-control border-0 border-bottom mb-4" placeholder="차량 번호를 입력해주세요. ( 12가 1234 )" v-model="carInfoForm.no">
                      </div>
                    </div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-12 d-flex flex-column">
                      <div class="d-flex justify-content-end my-2 flex-column">
                        <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">내 차 조회</button>
                      </div>
                      <div class="d-flex justify-content-end my-2 flex-column" v-if="!user?.name">
                        <router-link :to="{ path: '/login' }" class="btn btn-outline-primary tc-red">로그인</router-link>
                      </div>
                    </div>
                    <div class="text-muted mt-4 text-center">
                      <p class="fs-6">
                        <span @click="openModal('privacy')" class="link-style pointer">개인정보 처리방침</span> |
                        <span @click="openModal('terms')" class="link-style pointer">이용약관</span>
                      </p>
                      <p class="my-3 tc-light-gray">ⓒ Watosys all rights reserved.</p>
                    </div>
                  </div>
                </form>
              </template>
            </bottom-sheet>
          </div>
          <!-- 웹 화면에서 조회 폼 -->
          <div v-else class="card login-card border-0 overlay-style layover-style login-any" :class="{ 'expanded': expanded }" ref="loginCardRef">
            <div class="card-body">
              <!-- 조회 폼 -->
              <form @submit.prevent="submitCarInfo" class="d-flex flex-column justify-content-center">
                <div class="row mb-4 mt-4">
                  <div class="col-12">
                      <div class="video-container d-sm-flex">
                        <video autoplay loop muted>
                          <source src="../../../img/video/mainvideo.mp4" type="video/mp4">
                        </video>
                      </div>
                    <div>
                      <input type="text" class="form-control border-0 border-bottom my-4" placeholder="소유자가 누구인가요?" v-model="carInfoForm.owner">
                    </div>
                    <div>
                      <input type="text" class="form-control border-0 border-bottom mb-4" placeholder="차량 번호를 입력해주세요." v-model="carInfoForm.no">
                    </div>
                  </div>
                </div>
                <img src="../../../img/modal/car-objects-blur.png" alt="자동차 이미지" width="150" height="150" style="margin: auto !important;">
                <div class="row mt-4">
                  <div class="col-12">
                    <div class="d-flex justify-content-end my-2">
                      <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">내 차 조회</button>
                    </div>
                    <div class="d-flex justify-content-end my-2" v-if="!user?.name">
                      <router-link :to="{ path: '/login' }" class="btn btn-outline-primary tc-red">로그인</router-link>
                    </div>
                    <div class="text-muted mt-4 text-center">
                      <p class="fs-6">
                        <span @click="openModal('privacy')" class="link-style pointer">개인정보 처리방침</span> |
                        <span @click="openModal('terms')" class="link-style pointer">이용약관</span>
                      </p>
                      <p class="my-3 tc-light-gray">ⓒ Watosys all rights reserved.</p>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <transition name="fade" mode="out-in">
        <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
      </transition>
    </div>
  </template>
  
  <script setup>
  // Other imports
  import { useStore } from "vuex";
  import {createApp,h, computed, ref, onMounted, onBeforeUnmount, nextTick,inject  } from "vue";
  import useAuth from '@/composables/auth';
  import useAuctions from '@/composables/auctions';
  import LawGid from '@/views/modal/LawGid.vue';
  import BottomSheet from '@/views/bottomsheet/BottomSheet.vue';
  import { initReviewSystem } from '@/composables/review';
  import { setRandomPlaceholder } from '@/hooks/randomPlaceholder';
  import { cmmn } from '@/hooks/cmmn';

  
  const swal = inject('$swal');
  const { wica , wicaLabel } = cmmn();
  const carName = ref('');
  const emoji = ref('');
  const updateCarName = () => {
    const result = setRandomPlaceholder();
    carName.value = result.carName;
    emoji.value = result.emoji;
  };

  const loginCardRef = ref(null);
  const bannerRef = ref(null);
  const reviewContentRef = ref(null);
  const animatedSection = ref(null);
  const isModalOpen = ref(false);
  const modalContent = ref('');

  const store = useStore();
  const user = computed(() => store.getters['auth/user']);

  const { carInfoForm, submitCarInfo, processing } = useAuctions();

  const isMobileView = ref(window.innerWidth <= 640);
  const animationClass = ref('css-kzs0t hidden');

  const expanded = ref(false); // Define the expanded property

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

  const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };

  const { getHomeReview, reviewsData, splitDate } = initReviewSystem();

  onMounted(() => {
    updateCarName();
    getHomeReview();
    nextTick(() => {
      const animatedSectionElement = animatedSection.value;
      const loginCard = loginCardRef.value;
      setTimeout(() => {
        loginCard.classList.add('enter-active');
      }, 200);
      setTimeout(() => {
        if (animatedSectionElement) {
          animatedSectionElement.classList.add('enter-active');
          animatedSectionElement.classList.remove('hidden');
        }
      }, 600);
    });
    window.addEventListener('resize', checkScreenWidth);
    checkScreenWidth();
  });

  onBeforeUnmount(() => {
    window.removeEventListener('resize', checkScreenWidth);
  });
</script>

  
  <style scoped>
  .form-control{
    padding: 0.875rem 1rem;
  }
.bottom-sheet {
  overflow-y: hidden !important;
}
.card.no-shadow {
  box-shadow: none;
}
.card.no-hover:hover {
  box-shadow: none;
}
@media (max-width: 640px) {
  .card.login-card {
    box-shadow: none;
  }
  .card.login-card::before {
    content: none;
  }
  .card.login-card:hover {
    box-shadow: none;
  }
}
.rating__label .star-icon {
  width: 30px;
  height: 30px;
}

.login-any {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.5s ease-out, transform 0.4s ease-out;
}

.login-any.enter-active {
  opacity: 1;
  transform: translateY(0);
}
.login-card::before{
  display: none;
}
.banner,
.review-content,
.login-any {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 1s ease-out, transform 1s ease-out;
}
.review-content {
  transform: translateX(-20px);
}
.login-any {
  transform: translateX(20px);
}
.enter-active {
  opacity: 1;
  transform: translateY(0);
}

.register-content {
  display: flex;
  justify-content: space-between;
  gap: 20px;
}

.review-any {
  width: 100%;
  height: 100%;
  background-repeat: repeat-x;
  background-position: 0px 50%;
  background-size: auto 100%;
  background-image: url('../../../img/car_grid.png'); 
  animation: slide 30s linear infinite;
}
.any-content {
  height: 24.5rem;
  position: absolute;
  left: 0;
  width: 100%;
  bottom: 0rem;
  overflow: hidden;
  z-index: 0;
}
@keyframes slide {
  0% {
    background-position-x: 0px;
  }
  100% {
    background-position-x: -2734px;
  }
}
.css-ifyyt1 {
  display: grid;
  padding: 18vh 17px 5vh;
    color: rgb(39, 46, 64);
    align-content: space-evenly;
}
.css-kzs0t {
  transition: opacity 0.8s ease-in-out 0s, transform 0.8s ease-in-out 0s;
  opacity: 1;
  transform: translateY(0px);
}
.css-91307t {
  color: rgb(57, 110, 255);
}
.font-sub-title {
  font-size: 1.25rem;
  line-height: 1.75rem;
}
.font-title {
  font-size: 2.3rem;
  line-height: 3.3rem;
  -webkit-text-stroke: 0.01875rem currentcolor;
}
.hidden {
  opacity: 0;
  transform: translateY(20px);
}
@media (max-width: 768px) {
      .css-ifyyt1 {
          display: none;
      }
  }

@media (max-width: 991px) {
  .font-title {
    font-size: 1.8rem;
    line-height: 2rem;
    -webkit-text-stroke: 0.01875rem currentcolor;
}
   }


</style>
<!--

.bottom-sheet {
overflow-y: hidden !important;
}
.card.no-shadow {
box-shadow: none;
}
.card.no-hover:hover {
box-shadow: none;
}
@media (max-width: 640px) {
.card.login-card {
  box-shadow: none;
}
.card.login-card::before {
  content: none;
}
.card.login-card:hover {
  box-shadow: none;
}
}
.rating__label .star-icon {
width: 30px;
height: 30px;
}

.login-any {
opacity: 0;
transform: translateY(20px);
transition: opacity 0.5s ease-out, transform 0.4s ease-out;
}

.login-any.enter-active {
opacity: 1;
transform: translateY(0);
}
.login-card::before{
display: none;
}
.banner,
.review-content,
.login-any {
opacity: 0;
transform: translateY(20px);
transition: opacity 1s ease-out, transform 1s ease-out;
}
.review-content {
transform: translateX(-20px);
}
.login-any {
transform: translateX(20px);
}
.enter-active {
opacity: 1;
transform: translateY(0);
}

.register-content {
display: flex;
justify-content: space-between;
gap: 20px;
}
.overlay-style {
height: 100vh;
}

.grid-container {
display: flex;
gap: 10px;
width: calc(200%); /* Ensure the width is enough to cover the animation */
animation: scroll 20s linear infinite;
}

@keyframes scroll {
0% {
  transform: translateX(0);
}
100% {
  transform: translateX(-50%);
}
}

.cardtype02 {
border: 1px solid #e5e7eb;
border-radius: 6px;
margin: 0 18px;
padding: 24px 20px;
background-color: #fff;
width: 285px;
height: 363px;
box-shadow: 0 0 6px 0 rgba(27, 50, 142, 0.2);
overflow: hidden;
text-align: center;
}

.cardtype02 img {
width: 245px;
height: 90px;
object-fit: cover;
margin: 14px 0 14px;
border-radius: 6px;
}

.content {
padding: 15px;
}

.content h2 {
font-size: 18px;
margin: 10px 0;
}

.rating {
color: #f39c12;
margin: 10px 0;
}

.date {
font-size: 14px;
color: #999;
}

.content p {
font-size: 14px;
color: #333;
margin: 10px 0;
}

.price {
font-size: 16px;
font-weight: bold;
color: #333;
margin: 10px 0;
}

/* Additional styles */
.hidden {
opacity: 0;
transform: translateY(20px);
}
.enter-active {
opacity: 1;
transform: translateY(0);
}

.register-content {
display: flex;
justify-content: space-between;
gap: 20px;
}

.any-content {
height: 24.5rem;
position: absolute;
left: 0;
width: 100%;
bottom: 0rem;
overflow: hidden;
z-index: 0;
}
@keyframes slide {
0% {
  transform: translateX(0);
}
50% {
  transform: translateX(-100%);
}
100% {
  transform: translateX(0);
}
}
.css-ifyyt1 {
display: grid;
padding: 7rem 17px 9rem;
color: rgb(39, 46, 64);
align-content: space-evenly;
}
.css-kzs0t {
transition: opacity 0.8s ease-in-out 0s, transform 0.8s ease-in-out 0s;
opacity: 1;
transform: translateY(0px);
}
.css-91307t {
color: rgb(57, 110, 255);
}
.font-sub-title {
font-size: 1.25rem;
line-height: 1.75rem;
}
.font-title {
font-size: 2.3rem;
line-height: 3.3rem;
-webkit-text-stroke: 0.01875rem currentcolor;
}
.hidden {
opacity: 0;
transform: translateY(20px);
}
@media (max-width: 768px) {
    .css-ifyyt1 {
        display: none;
    }
}

@media (max-width: 991px) {
.font-title {
  font-size: 1.8rem;
  line-height: 2rem;
  -webkit-text-stroke: 0.01875rem currentcolor;
}

-->