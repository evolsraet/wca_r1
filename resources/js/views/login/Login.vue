<template>
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
            <router-link
              class="btn-sell-car d-flex"
              :to="{ path: '/' }"
            >
          <div>
            <span class="icon-car me-1"><img src="../../../img/icon-small-car.png" width="20" class="mb-1"></span><span class="size_14">내 차 판매하기</span> 
          </div>
          <img src="../../../img/Icon-right-wh.png" class="dash-wh" width="10">
          </router-link>
            <div class="web-style-overlay02">
              <h5 class="size_18 tc-wh ">저는 딜러에요! <router-link :to="{ path: '/register' }" class="ms-3 tc-wh underline">딜러회원가입</router-link></h5>
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
            <div class="video-container d-sm-flex mb-5">
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
          <router-link
              class="btn-sell-car d-flex"
              :to="{ path: '/' }"
            >
          <div>
            <span class="icon-car me-1"><img src="../../../img/icon-small-car.png" width="20" class="mb-1"></span><span class="size_14">내 차 판매하기</span> 
          </div>
          <img src="../../../img/Icon-right-wh.png" class="dash-wh" width="10">
          </router-link>
        <!--<p class="text-secondary opacity-50 font-sub-title"><span class="mx-2">{{ emoji }}</span>요새 인기있는 매물은 "{{ carName }}" 이에요</p>-->
      </div>
      <div class="ps-3"> 
        <img src="../../../img/logo.png" width="110" class="mb-3 mobile-css">
        <div class="tc-wh mobile-css">
          <p class="size_18 ps-2">저는 딜러에요! <router-link :to="{ path: '/login' }" class="ms-3 tc-wh underline">딜러회원가입</router-link></p>
        </div>
        </div>
      </div>
    </div>
        <div v-if="!isMobileView" class="card login-card border-0 overlay-style login-any layover-style" ref="loginCardRef">
            <div class="card-body">
              <!-- 로그인 폼 -->
              <form @submit.prevent="submitLogin">
                <div class="">
                  <div class="video-container d-sm-flex mb-5">
                        <video autoplay loop muted>
                          <source src="../../../img/video/mainvideo02.mp4" type="video/mp4">
                        </video>
                      </div>
                  <div class="my- text-left">
                    <router-link :to="{ path: '/' }" class="register-link text-secondary opacity-50 ms-2">이미 위카 회원이신가요?</router-link>
                  </div>
                  <!-- 이메일 입력 -->
                  <div class="mb-2">
                    <label for="email" class="form-label"></label>
                    <input v-model="loginForm.email" id="email" type="text" class="form-control border-0 border-bottom" required autofocus autocomplete="username" placeholder="휴대폰번호 또는 이메일을 입력해주세요.">
                  </div>
                  <!-- 비밀번호 입력 -->
                  <div class="mb-2">
                    <label for="password" class="form-label">
                    </label>
                    <input v-model="loginForm.password" id="password" type="password" class="form-control border-0 border-bottom pe-2" required autocomplete="current-password" placeholder="비밀번호를 입력해주세요.">
                  </div>
                  <!-- 백엔드 오류 메시지 -->
      
                  <!-- 소셜 로그인 섹션 -->
                  <div class="login-v2 mb-3">
                    <h3 class="my-4 text-secondary opacity-50"><span>또는 소셜 로그인</span></h3>
                    <ul class="login-v2-area">
                      <li><a href="#" class="google" title="google" @click.prevent="openAlarmModal">Google</a></li>
                      <li><a href="#" class="naver" title="naver" @click.prevent="openAlarmModal">Naver</a></li>
                      <li><a href="#" class="kakao" title="kakao" @click.prevent="openAlarmModal">Kakao</a></li>
                    </ul>
                  </div>
                  <!-- 로그인 버튼 -->
                  <div class="flex items-center justify-end my-4">
                    <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">
                      로그인
                    </button>
                  </div>
                </div>
                <router-link :to="{name: 'auth.forgot-password'}" class="tc-primary">비밀번호 찾기</router-link>
                <div class="register-link text-center">
                  <router-link :to="{ path: '/register' }" class="register-link">회원가입하기</router-link>
                </div>
                <div class="text-secondary opacity-50 mt-4 text-center">
                  <p class="fs-6">
                    <span @click="openModal('privacy')" class="link-style pointer">개인정보 처리방침</span> |
                    <span @click="openModal('terms')" class="link-style pointer">이용약관</span>
                  </p>
                  <p class="my-3 text-secondary opacity-50">ⓒ Wecarmobility all rights reserved.</p>
                  </div>
              </form>
            </div>
          </div>
          <AlarmModal ref="alarmModal" />
         
            <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
         
          </div>
          <div v-if="isMobileView">
            <bottom-sheet initial="half" :dismissable="true">
          
              <!-- 로그인 폼 -->
              <form @submit.prevent="submitLogin">
                <div class="">
                  <div class="my-3 text-left">
                    <router-link :to="{ path: '/' }" class="register-link text-secondary opacity-50 ms-2">이미 위카 회원이신가요?</router-link>
                  </div>
                  <!-- 이메일 입력 -->
                  <div class="mb-3">
                    <label for="email" class="form-label">phone/email</label>
                    <input v-model="loginForm.email" id="email" type="text" class="form-control border-0 border-bottom" required autofocus autocomplete="username" placeholder="전화번호 또는 이메일을 입력해주세요.">
                    <div v-for="message in validationErrors?.email">
                        {{ message }}
                    </div>
                  </div>
                  <!-- 비밀번호 입력 -->
                  <div class="mb-4">
                    <label for="password" class="form-label">
                      {{ $t("password") }}
                    </label>
                    <input v-model="loginForm.password" id="password" type="password" class="form-control border-0 border-bottom" required autocomplete="current-password" placeholder="비밀번호를 입력해주세요.">
                    <div v-for="message in validationErrors?.password">
                        {{ message }}
                    </div>
                  </div>
                  <!-- 백엔드 오류 메시지 -->
  
                  <!-- 소셜 로그인 섹션 -->
                  <div class="login-v2 my-5">
                    <h3 class="mb-4 text-secondary opacity-50"><span>또는 소셜 로그인</span></h3>
                    <ul class="login-v2-area">
                      <li><a href="#" class="google" title="google" @click.prevent="openAlarmModal">Google</a></li>
                      <li><a href="#" class="naver" title="naver" @click.prevent="openAlarmModal">Naver</a></li>
                      <li><a href="#" class="kakao" title="kakao" @click.prevent="openAlarmModal">Kakao</a></li>
                    </ul>
                  </div>
                  <!-- 로그인 버튼 -->
                  <div class="flex items-center justify-end my-4">
                    <button class="btn btn-primary w-100" :class="{ 'opacity-25': processing }" :disabled="processing">
                      로그인
                    </button>
                  </div>
                </div>
                <router-link :to="{name: 'auth.forgot-password'}" class="text-center tc-primary">비밀번호 찾기</router-link>
                <div class="register-link mt-3 text-center">
                  <router-link :to="{ path: '/register' }" class="register-link">회원가입하기</router-link>
                </div>
                <div class="text-secondary opacity-50 mt-3 text-center">
                  <p class="fs-6">
                    <span @click="openModal('privacy')" class="link-style">개인정보 처리방침</span> |
                    <span @click="openModal('terms')" class="link-style">이용약관</span>
                  </p>
                  <p class="mt-3 mb-4 text-secondary opacity-50">ⓒ Wecarmobility all rights reserved.</p>
                  </div>
              </form>
          
          </bottom-sheet >
          </div>
    
        
        <AlarmModal ref="alarmModal" />
          <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
      </div>
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
import {createApp,h, ref, onMounted, nextTick, computed, onBeforeUnmount, inject } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import LawGid from '@/views/modal/LawGid.vue';
import useAuth from '@/composables/auth';
import AlarmModal from '@/views/modal/AlarmModal.vue';
import { setRandomPlaceholder } from '@/hooks/randomPlaceholder';
import { cmmn } from '@/hooks/cmmn';
import carInfo from '../../../../resources/img/electric-car.png';
import BottomSheet from '@/views/bottomsheet/Bottomsheet-type04.vue';
const swal = inject('$swal');
const { wica , wicaLabel } = cmmn();
const carName = ref('');
const emoji = ref('');

const updateCarName = () => {
  const result = setRandomPlaceholder();
  carName.value = result.carName;
  emoji.value = result.emoji;
};

const isModalOpen = ref(false);
const modalContent = ref('');
const loginCardRef = ref(null);
const loginForm = ref({ email: '', password: ''});
const validationErrors = ref({});
const processing = ref(false);
const alarmModal = ref(null);
const store = useStore();
const router = useRouter();
const errorMessage = computed(() => store.getters['auth/errorMessage']);
const isMobileView = ref(window.innerWidth <= 640);

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

const checkScreenWidth = () => {
  if (typeof window !== 'undefined') {
    isMobileView.value = window.innerWidth <= 640;
  }
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

const submitLogin = async () => {
  if (processing.value) return;

  processing.value = true;
  validationErrors.value = {};

  const returnUserData = await store.dispatch('auth/login', loginForm.value);
  if(returnUserData.isError) {
    if(returnUserData.isAlert) {
      wica.ntcn(swal)
      .title(returnUserData.msg)
      .icon('E')
      .alert('계속 될 경우 관리자에게 문의해주세요.');   
    } else {
      //validationErrors.value = returnUserData.rawData.response.data.errors;
      wica.ntcn(swal)
      .title(returnUserData.rawData.response.data.message)
      .icon('E')
      .alert();   
    }
  } else {
    if(returnUserData.isSuccess) {
      wica.ntcn(swal).icon('S').title('로그인 성공').fire();

      let userData = returnUserData.data.user;
      await store.dispatch("auth/getUser");
      await store.dispatch("auth/getAbilities");
      await store.dispatch("enums/getData");
      await store.dispatch("fields/getData");
      
      // 로컬 스토리지에서 carDetails와 게스트 클릭 플래그 확인
      const localCarDetails = localStorage.getItem('carDetails');
      const guestClickedAuction = localStorage.getItem('guestClickedAuction');

      // 페이지 리디렉션 조건
      if (userData.roles.includes('admin')) {
        router.push({ name: "admin.index" });
      } else if (userData.roles.includes('dealer')) {
        router.push({ name: "dealer.index" });
      }else if (guestClickedAuction !== 'true' &&userData.roles.includes('user')) {
        router.push({ name: "user.index" });
      } else if (guestClickedAuction === 'true' && userData.roles.includes('user')) {
        router.push({ name: "sell" });
        localStorage.removeItem('guestClickedAuction');  // 플래그 삭제
      } else {
        router.push({ name: "home" });
      }
    } else {
      wica.ntcn(swal).icon('E').title('로그인 실패').fire();
    }
  }

  processing.value = false;

};


const animationClass = computed(() => {
  return loginCardRef.value ? 'enter-active' : '';
});

onMounted(() => {
  updateCarName();
  store.commit('auth/CLEAR_ERROR_MESSAGE'); 
  nextTick(() => {
    const loginCard = loginCardRef.value;
    setTimeout(() => {
      loginCard.classList.add('enter-active');
    }, 200);
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
  padding-right: 0px !important;
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


.enter-active {
  opacity: 1;
  transform: translateY(0);
}


.register-content {
  display: flex;
  justify-content: space-between;
  gap: 20px;
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
@media (max-width: 991px){
    .css-ifyyt1{
        padding: 23vh 17px 5vh !important;
    }
}
@media (max-width: 768px) {
  .mobile-css{
      display:none;
    }
    .register-content{
        justify-content: center;
    }
  }

@media (max-width: 991px) {
  .font-title {
    font-size: 1.8rem;
    line-height: 2.8rem;
    -webkit-text-stroke: 0.01875rem currentcolor;
}
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
}

.swiper-container {
  width: 100%; /* 전체 화면 너비 */
  height: 100vh;
  position: absolute; /* 화면 배경처럼 작동 */
  top: 68px;
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
