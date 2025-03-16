<template>
  <!--
    TODO: 게스트일떄 모든리뷰 가져오는거 해결하기.

  -->
    <div class="container">
      <!-- 회원가입 권장 섹션 -->
      <div class="review-any">
        <swiper
      :modules="[Autoplay]" 
      :slides-per-view="1" 
      :loop="true" 
      :autoplay="{ delay: 5000, disableOnInteraction: false }" 
      :allow-touch-move="false" 
      :speed="1000" 
      class="swiper-container"
    >
      <!-- Slide 1 -->
      <swiper-slide>
        <div class="slide-content">
          <img src="../../../img/main_banner02.png" class="styled-img" alt="배너 이미지" />
          <div class="content d-flex"></div>
        </div>
      </swiper-slide>

      <!-- Slide 2 -->
      <swiper-slide>
        <div class="slide-content">
          <img src="../../../img/main_p2.png" class="styled-img" alt="배너 이미지 2" />
          <div class="content02 d-flex text-center"></div>
        </div>
      </swiper-slide>

      <!-- Slide 3 -->
      <swiper-slide>
        <div class="slide-content">
          <img src="../../../img/main_p3.png" class="styled-img" alt="배너 이미지 3" />
          <div class="content d-flex"></div>
        </div>
      </swiper-slide>
  </swiper>
    </div>
    <div class="main-contenter">
        <!-- 텍스트 오버레이 -->
        <div class="web-style-overlay web-stlye">
          <div class="gap-5 tc-wh mb-4">
            <h1>쉽고 빠른 <span class="bolder">내차팔기,</span></h1>
            <h1 class="font-title"><span class="bolder">{{ wicaLabel.title() }}</span>에서 <span class="bolder">높은 가격</span>으로!</h1>
            <h5 class="mt-3 normal">자동차 진단 전문가 <span class="bolder">위카모빌리티</span>가 함께합니다.</h5>
          </div>
          <!-- <router-link
              class="btn-sell-car d-flex"
              :to="{ path: '/' }"
            >
          <div>
            <span class="icon-car me-1"><img src="../../../img/icon-small-car.png" width="20"></span><span class="size_14">내 차 판매하기</span> 
          </div>
          <img src="../../../img/Icon-right-wh.png" class="dash-wh" width="10">
          </router-link> -->

          <div class="btn-sell-car d-flex" @click="carSearch">
            <span class="icon-car me-1"><img src="../../../img/icon-small-car.png" width="20"></span><span class="size_14">내 차 판매하기</span> 
          </div>

          <div class="web-style-overlay02" v-if="!user?.name">
            <!-- <h5 class="size_18 tc-wh">저는 딜러에요! <router-link :to="{ path: '/register?type=dealer' }" class="ms-3 tc-wh underline">딜러회원가입</router-link></h5> -->
            <h5 class="size_18 tc-wh">저는 딜러에요! <a href="/register?type=dealer" class="ms-3 tc-wh underline">딜러회원가입</a></h5>
          </div>
        </div>
        <div class=" web-stlye">
        <!-- Swiper 섹션 -->
        <swiper
          class="custom-swiper"
          :modules="[Autoplay]"
          :slides-per-view="1"
          :loop="true"
          :autoplay="{ delay: 5000, disableOnInteraction: false }"
          :allow-touch-move="false"
          :speed="1000"
        >
          <!-- Slide 1 -->
          <swiper-slide>
            <div>
              <img src="../../../img/main_banner02.png" alt="배너 이미지" class="slide-image" />
            </div>
          </swiper-slide>

          <!-- Slide 2 -->
          <swiper-slide>
            <div>
              <img src="../../../img/main_p2.png" alt="배너 이미지 2" class="slide-image" />
            </div>
          </swiper-slide>

          <!-- Slide 3 -->
          <swiper-slide>
            <div>
              <img src="../../../img/main_p3.png" alt="배너 이미지 3" class="slide-image" />
            </div>
          </swiper-slide>
        </swiper>
      </div>
        <div class="my-5 app-specific-size">
          <div class="video-container d-sm-flex mb-">
            <video autoplay loop muted >
              <source src="../../../img/video/mainvideo02.mp4" type="video/mp4"  width="80">
            </video>
          </div>
        </div>
        <div class="web-stlye px-4 pb-5">
          <h3 class="text-center mb-4 bolder">내 차 판매, 한눈에 보기!</h3>
          <div class="d-flex mt-4 gap-3 justify-content-between">
            <div class="text-center">
              <img src="../../../img/contract.png" width="40px">
              <p>경매신청</p>
            </div>
            <div class="text-center">
              <img src="../../../img/car-insurance.png" width="41px">
              <p>차량진단</p>
            </div>
            <div class="text-center">
              <img src="../../../img/bid.png" width="43px">
              <p>경매</p>
            </div>
            <div class="text-center">
              <img src="../../../img/car-wash.png" width="43px">
              <p>탁송</p>
            </div>
            <div class="text-center">
              <img src="../../../img/care.png" width="43px">
              <p>사후처리</p>
            </div>
          </div>
        </div>
        <div class="register-content">
          <div v-if="!isMobileView">
       <!--   <div class="any-content">
          <div class="review-any"></div>-->
        <!--  <div class="video-container02">
          <video width="90%" class="video_type03" autoplay loop muted style="opacity: 0.5;">
              <source src="../../../img/video/title.mp4" type="video/mp4">
          </video>
        </div>-->
        <div :class="animationClass" ref="animatedSection">
        <div class="css-ifyyt1 gap-5 tc-wh">
          <div>
          <h1>쉽고 빠른 <span class="bolder">내차팔기,</span></h1>
          <h1 class="font-title"><span class="bolder">{{ wicaLabel.title() }}</span>에서 <span class="bolder">높은 가격</span>으로!</h1>
          <h4 class="mt-3 normal">자동차 진단 전문가 <span class="bolder">위카모빌리티</span>가 함께합니다.</h4>
        </div>
        <!-- <router-link
              class="btn-sell-car d-flex"
              :to="{ path: '/' }"
            >
          <div>
            <span class="icon-car me-1"><img src="../../../img/icon-small-car.png" width="20"></span><span class="size_14">내 차 판매하기</span> 
          </div>
          <img src="../../../img/Icon-right-wh.png" class="dash-wh" width="10">
          </router-link> -->

          <div class="btn-sell-car d-flex" @click="carSearch">
            <span class="icon-car me-1"><img src="../../../img/icon-small-car.png" width="20"></span><span class="size_14">내 차 판매하기</span> 
            <img src="../../../img/Icon-right-wh.png" class="dash-wh" width="10">
          </div>

      <!--<p class="text-secondary opacity-50 font-sub-title"><span class="mx-2">{{ emoji }}</span>요새 인기있는 매물은 "{{ carName }}" 이에요</p>-->
    </div>
      <div class="ps-3"> 
      <img src="../../../img/logo.png" width="110" class="mb-3 mobile-css">
      <div class="tc-wh mobile-css" v-if="!user?.name">
        <!-- <p class="size_18 ps-2">저는 딜러에요! <router-link :to="{ path: '/register?type=dealer' }" class="ms-3 tc-wh underline">딜러회원가입</router-link></p> -->
        <p class="size_18 ps-2">저는 딜러에요! <a href="/register?type=dealer" class="ms-3 tc-wh underline">딜러회원가입</a></p>
      </div>
    </div>
    </div>
  </div>
      <div v-if="isMobileView">
        <bottom-sheet initial="half" :dismissable="true">
          <template #default>
            
            <form @submit.prevent="submitCarInfoIsOk" class="d-flex flex-column justify-content-center">
              <div class="row mb-1 mt-1">
                <div class="col-12">
                  <div>
                    <input type="text" class="form-control border-0 border-bottom my-4" placeholder="소유자가 누구인가요?" v-model="carInfoForm.owner">
                    <div class="text-danger mt-1">
                      <div v-for="message in validationErrors?.owner">
                        {{ message }}
                      </div>
                    </div>
                  </div>
                  <div>
                    <input type="text" class="form-control border-0 border-bottom mb-4" placeholder="차량 번호를 입력해주세요. ( 12가 1234 )" v-model="carInfoForm.no">
                    <div class="text-danger mt-1">
                      <div v-for="message in validationErrors?.no">
                        {{ message }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <img src="../../../img/wecar_.png" alt="자동차 이미지" style="margin: auto !important; width: 130px !important;">
                <div class="col-12 d-flex flex-column mt-4">
                  <div class="d-flex justify-content-end my-2 flex-column">
                    <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">내 차 조회</button>
                  </div>
                  <div class="d-flex justify-content-end my-2 flex-column" v-if="!user?.name">
                    <router-link :to="{ path: '/login' }" class="btn btn-outline-primary tc-primary">로그인</router-link>
                  </div>
                </div>
                <div class="text-secondary opacity-50 mt-5 text-center">
                  <p class="fs-6">
                    <span @click="openModal('privacy')" class="link-style pointer">개인정보 처리방침</span> |
                    <span @click="openModal('terms')" class="link-style pointer">이용약관</span>
                  </p>
                  <p class="my-3 text-secondary opacity-50">ⓒ Wecarmobility all rights reserved.</p>
                </div>
              </div>
            </form>
          </template>
        </bottom-sheet>
      </div>
      <!-- 웹 화면에서 조회 폼 -->
      <div v-else class="card login-card border-0 overlay-style layover-style login-any" :class="{ 'expanded': expanded }" ref="loginCardRef">
  

       <!--   <div class="any-content">
          <div class="review-any"></div>-->
        <!--  <div class="video-container02">
          <video width="90%" class="video_type03" autoplay loop muted style="opacity: 0.5;">
              <source src="../../../img/video/title.mp4" type="video/mp4">
          </video>
        </div>-->
 
  
        <div class="card-body">
          
          <!-- 조회 폼 -->
          <form @submit.prevent="submitCarInfoIsOk" class="d-flex flex-column justify-content-center">
            <div class="row mb-4 mt-4">
              <div class="col-12">
                  <div class="video-container d-sm-flex mb-5">
                    <video autoplay loop muted>
                      <source src="../../../img/video/mainvideo02.mp4" type="video/mp4">
                    </video>
                  </div>
                <div>
                  <input type="text" class="form-control border-0 border-bottom mt-4" placeholder="소유자가 누구인가요?" v-model="carInfoForm.owner">
                  <div class="text-danger mt-1 mb-4">
                      <div v-for="message in validationErrors?.owner">
                        {{ message }}
                      </div>
                    </div>
                </div>
                <div>
                  <input type="text" class="form-control border-0 border-bottom" placeholder="차량 번호를 입력해주세요." ref="carNoInput" v-model="carInfoForm.no" @input="removeSpace">
                  <div class="text-danger mt-1  mb-4">
                    <div v-for="message in validationErrors?.no">
                      {{ message }}
                    </div>
                    <div v-if="!isValid && carInfoForm.no">
                        {{ validationMessage }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <img src="../../../img/wecar_.png" alt="자동차 이미지" width="110"  style="margin: auto !important;">
            <div class="row mt-4">
              <div class="col-12">
                <div class="d-flex justify-content-end my-2">
                  <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">내 차 조회</button>
                </div>
                <div class="d-flex justify-content-end my-2" v-if="!user?.name">
                  <router-link :to="{ path: '/login' }" class="btn btn-outline-primary tc-primary">로그인</router-link>
                </div>
                <div class="text-secondary opacity-50 mt-5 text-center">
                  <p class="fs-6">
                    <span @click="openModal('privacy')" class="link-style pointer">개인정보 처리방침</span> |
                    <span @click="openModal('terms')" class="link-style pointer">이용약관</span>
                  </p>
                  <p class="my-3 text-secondary opacity-50">ⓒ Wecarmobility all rights reserved.</p>
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
<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";
import { Navigation, Pagination, Autoplay } from "swiper/modules";


export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  data() {
    return {};
  },
};
</script>
  <script setup>
  // Other imports
  import { useStore } from "vuex";
  import {createApp,h, computed, ref, onMounted, onBeforeUnmount, nextTick,inject, watch  } from "vue";
  import useAuth from '@/composables/auth';
  import useAuctions from '@/composables/auctions';
  import LawGid from '@/views/modal/LawGid.vue';
  import BottomSheet from '@/views/bottomsheet/BottomSheet.vue';
  import { initReviewSystem } from '@/composables/review';
  import { setRandomPlaceholder } from '@/hooks/randomPlaceholder';
  import { cmmn } from '@/hooks/cmmn';
  import { routerKey } from "vue-router";
  import { useRoute, useRouter } from 'vue-router';
  import carInfo from '../../../../resources/img/electric-car.png';

  // route 가 필요함, 주소가 변경되면 화면이 변경되어야 함.
  const route = useRoute();
  // console.log('route',route.path);
  
  const swal = inject('$swal');
  const { wica , wicaLabel } = cmmn();
  const carName = ref('');
  const carNoInput = ref(null)
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

  const { carInfoForm, submitCarInfo, processing , validationErrors } = useAuctions();

  const isMobileView = ref(window.innerWidth <= 640);
  const animationClass = ref('css-kzs0t hidden');

  const expanded = ref(false); // Define the expanded property
  const router = useRouter();
  const openModal = (type) => {
  const container = document.createElement('div');

  const isCarinfo = computed(() => localStorage.getItem('carDetails') ? true : false);

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

const submitCarInfoIsOk = () => {
/*
  if(!user.value.name){
    const text= `<div class="enroll_box" style="position: relative;">
                   <img src="${carInfo}" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text04">로그인을 하면 경매 신청이 가능해요.</p>
               </div>`;
    wica.ntcn(swal)
      .useHtmlText() // HTML 태그 인 경우 활성화
      .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
      .addOption({ padding: 20 }) // swal 기타 옵션 추가
      .callback(function (result) {
        if(result.isOk){
          router.push('/login');
        }
      })
      .confirm(text);
  }
  else{
    submitCarInfo();
  }
    */
  //TODO: 여기는 위에보단 아래 유형이 맞지 않나?
  submitCarInfo().then((response) => {

    if(!isValid.value){
      wica.ntcn(swal).icon('E').title('올바른 차량번호 형식으로 입력해주세요.').fire();
      return false;
    }

    if(response.data.status == 'is_not'){
      wica.ntcn(swal).icon('E').title('경매중인 차량번호입니다.').fire();
      return false;
    }

    if(response.isError) {
      wica.ntcn(swal).icon('E').title('필수 입력정보가 필요합니다.').fire();
    } else {
      router.push({ name: 'sell' });
    }
  })

}
  const closeModal = () => {
    isModalOpen.value = false;
  };

  const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };

  const { getHomeReview, reviewsData, splitDate } = initReviewSystem();

const isValid = ref(false)
const validationMessage = ref('')

// 공백 제거
const removeSpace = () => {
  carInfoForm.no = carInfoForm.no.replace(/\s/g, '')
}

// 차량번호 유효성 검사
const validateCarNumber = (carNumber) => {
  if (!carNumber) {
    validationMessage.value = ''
    isValid.value = false
    return
  }

  const pattern1 = /^[0-9]{2}[가-힣]{1}[0-9]{4}$/  // 12가1234 형식
  const pattern2 = /^[0-9]{3}[가-힣]{1}[0-9]{4}$/  // 123가1234 형식
  
  isValid.value = pattern1.test(carNumber) || pattern2.test(carNumber)
  
  
  if (!isValid.value) {
    validationMessage.value = '올바른 차량번호 형식이 아닙니다. (예시: 12가1234)'
  } else {
    validationMessage.value = '올바른 차량번호 형식입니다.'
  }
}

const carSearch = () => {
  // const animatedSectionElement = animatedSection.value;
  // animatedSectionElement.classList.add('enter-active');

  // if(isCarinfo.value){
  //   router.push({ path: '/sell' });
  // }

  nextTick(() => {
      const animatedSectionElement = animatedSection.value;
      const loginCard = loginCardRef.value;
      setTimeout(() => {
        // loginCard.classList.remove('hidden');
        loginCard.classList.add('enter-active');
      }, 200);
      // setTimeout(() => {
      //   if (animatedSectionElement) {
      //     animatedSectionElement.classList.remove('enter-active');
      //     animatedSectionElement.classList.add('hidden');
      //   }
      // }, 600);
    });

}

// 차량번호 변경 감지
watch(() => carInfoForm.no, (newValue) => {
  validateCarNumber(newValue)
}, { immediate: true })

// submit 메소드를 외부에서 사용할 수 있도록 defineExpose
// defineExpose({
//   submit,
//   carInfoForm
// })

  onMounted(() => {
    updateCarName();
    getHomeReview();
    nextTick(() => {
      const animatedSectionElement = animatedSection.value;
      const loginCard = loginCardRef.value;
      setTimeout(() => {
        // if(loginCard!=null) loginCard.classList.add('enter-active');
        if(loginCard!=null) loginCard.classList.add('hidden');
        if(route.path === '/carfind-do'){
          loginCard.classList.add('enter-active');
        }
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
  padding: 0.875rem 1rem 0.575rem 1rem;
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
@media (max-width: 768px) {
    .register-content{
        justify-content: center !important;
    }
}
.register-content {
display: flex;
justify-content: space-between;
gap: 20px;
}

.review-any {
width: 100%;
height: 100%;
position: absolute;
top: 67px;
left: 0;
z-index: -1000;
overflow: hidden; 
}
.image-slider {
display: flex; /* 이미지 가로 배치 */
width: 300%; /* 이미지 3개를 모두 포함한 넓이 */
height: 100%;
animation: slideImages 15s infinite; /* 슬라이드 애니메이션 */
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
.slide-image {
width: 100%;
height: 100%;
flex-shrink: 0; /* 이미지 크기 고정 */
}
@keyframes slideImages {
0% {
  transform: translateX(0); /* 첫 번째 이미지 */
}
33% {
  transform: translateX(-100%); /* 두 번째 이미지 */
}
66% {
  transform: translateX(-200%); /* 세 번째 이미지 */
}
100% {
  transform: translateX(0); /* 처음으로 돌아옴 */
}
}
@media (max-width: 640px) {
    .container {
      --bs-gutter-x: 0rem !important; 
      max-width:none !important;
    }

    #app.mainPage > .user-nav-wrap + div {
      margin-top: 87px !important;
    }
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
padding: 30vh 17px 5vh;
  color: rgb(39, 46, 64);
  align-content: space-evenly;
}
.custom-swiper {
height: 370px; /* 원하는 높이로 조정 */
}

.custom-swiper .swiper-slide img {
height: 370px;
width: 100%;
object-fit: cover; /* 이미지 비율 유지하며 슬라이드 높이에 맞춤 */
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

.hidden {
opacity: 0;
transform: translateY(20px);
}
@media (max-width: 768px) {
    .css-ifyyt1 {
        display: none;
    }
    .mobile-css{
      display:none;
    }
}
@media (max-width: 991px){
    .css-ifyyt1{
        padding: 23vh 17px 5vh !important;
    }
}
.swiper-container {
width: 100vw; /* 전체 화면 너비 */
height: 100vh; /* 전체 화면 높이 */
position: absolute; /* 화면 배경처럼 작동 */
top: 0;
left: 0;
z-index: -1000; /* 다른 콘텐츠 아래로 */
overflow: hidden; /* 이미지 잘림 방지 */
}

.slide-content {
display: flex;
align-items: center;
justify-content: center;
height: 100%;
background-color: #f4f4f4; /* 배경 색상 */
}
.styled-img {
width: 100%;
height: 100%;
object-fit: cover;
}
.icon-car {
margin-right: 10px;
}

.arrow-icon {
margin-left: 10px;
}
.web-style-overlay{
  position: absolute;
  top: 135px;
  left:35px;
  z-index: 2;
}
.web-style-overlay02{
  position: relative;
    bottom: -70px;
    transform: translateX(20%);
    text-align: center;
    z-index: 2;
}
.slide-image {
  width: 100%;
  height: 100%;
  object-fit: cover; /* 이미지가 화면에 꽉 차도록 */
}

</style>
