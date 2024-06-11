<template>
  <div class="container">
    <div class="main-contenter">
      <div v-if="isMobileView" class="p-4 app-specific-size">
        <div class="text-center">
          <h2 class="my-4 fw-bold"><mark class="custom-highlight">회원가입시</mark> 판매가 빨라져요</h2>
          <p class="bold-link text-muted">소셜 로그인 및 이메일로 가입 할 수 있어요.</p>
        </div>
      </div>
      <div class="register-content">
      <div v-if="!isMobileView">
          <div class="any-content">
          <div class="review-any"></div>
        </div>
        <div :class="animationClass" ref="animatedSection">
        <div class="css-ifyyt1 gap-5">
          <div class="font-title"><h5 class="tc-light-gray font-title">쉽고 빠른 내차팔기,</h5>
        <h5 class="font-title">위카와 함께해요.</h5>
      </div>
      <p class="tc-light-gray font-sub-title">13,314명이 위카와 함께 했어요!</p>
    </div>
  </div>
</div>
   <!--   <div class="layout-container">
        <div class="banner">
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
        <!--   <div class="review-content">
          <div class="apply-top text-start">
            <h3 class="review-title">다른 사람들의 이용후기에요</h3>
            <router-link :to="{ name: 'index.allreview' }" href="" class="btn-apply">전체보기</router-link>
          </div>
          <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col" v-for="n in 4" :key="n">
              <div class="card">
                <div class="car-imges"></div>
                <div class="card-body">
                  <h5 class="card-title">현대 소나타 (DN8)</h5>
                  <div class="rating">
                    <label class="rating__label rating__label--full" for="star1">
                      <input type="radio" id="star1" class="rating__input" name="rating" value="">
                      <span class="star-icon filled"></span>
                    </label>
                    <label class="rating__label rating__label--full" for="star2">
                      <input type="radio" id="star2" class="rating__input" name="rating" value="">
                      <span class="star-icon filled"></span>
                    </label>
                    <label class="rating__label rating__label--full" for="star3">
                      <input type="radio" id="star3" class="rating__input" name="rating" value="">
                      <span class="star-icon filled"></span>
                    </label>
                    <label class="rating__label rating__label--full" for="star4">
                      <input type="radio" id="star4" class="rating__input" name="rating" value="">
                      <span class="star-icon"></span>
                    </label>
                    <label class="rating__label rating__label--full" for="star5">
                      <input type="radio" id="star5" class="rating__input" name="rating" value="">
                      <span class="star-icon"></span>
                    </label>
                  </div>
                  <div class="d-sm-flex justify-content-between text-muted">
                    <span class="deilname">담당 딜러 홍길동님</span>
                    <span class="date">2024-03-18</span>
                  </div>
                  <p class="card-text">차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                </div>
              </div>
            </div>
          </div>
        </div>-->
        <div v-if="!isMobileView" class="card login-card border-0 overlay-style login-any layover-style" ref="loginCardRef">
            <div class="card-body">
              <!-- 로그인 폼 -->
              <form @submit.prevent="submitLogin">
                <div class="">
                  <div class="video-container d-sm-flex">
                        <video autoplay loop muted>
                          <source src="../../../img/video/mainvideo.mp4" type="video/mp4">
                        </video>
                      </div>
                  <div class="my- text-left">
                    <router-link :to="{ path: '/' }" class="register-link text-muted ms-2">이미 위카 회원이신가요?</router-link>
                  </div>
                  <!-- 이메일 입력 -->
                  <div class="mb-2">
                    <label for="email" class="form-label"></label>
                    <input v-model="loginForm.email" id="email" type="email" class="form-control border-0 border-bottom" required autofocus autocomplete="username" placeholder="이메일을 입력해주세요.">
                  </div>
                  <!-- 비밀번호 입력 -->
                  <div class="mb-2">
                    <label for="password" class="form-label">
                    </label>
                    <input v-model="loginForm.password" id="password" type="password" class="form-control border-0 border-bottom" required autocomplete="current-password" placeholder="비밀번호를 입력해주세요.">
                  </div>
                  <!-- 백엔드 오류 메시지 -->
      
                  <!-- 소셜 로그인 섹션 -->
                  <div class="login-v2 mb-3">
                    <h3 class="my-4 text-muted"><span>또는 소셜 로그인</span></h3>
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
                <router-link :to="{name: 'auth.forgot-password'}">비밀번호 찾기</router-link>
                <div class="register-link text-center">
                  <router-link :to="{ path: '/register' }" class="register-link">회원가입하기</router-link>
                </div>
                <div class="text-muted mt-4 text-center">
                  <p class="fs-6">
                    <span @click="openModal('privacy')" class="link-style">개인정보 처리방침</span> |
                    <span @click="openModal('terms')" class="link-style">이용약관</span>
                  </p>
                  <p class="my-3 tc-light-gray">ⓒ Watosys all rights reserved.</p>
                  </div>
              </form>
            </div>
          </div>
          <AlarmModal ref="alarmModal" />
         
            <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
         
        </div>
    
        <div v-if="isMobileView" class="card login-card login-any border-0" ref="loginCardRef">
          <div class="card-body">
            <!-- 로그인 폼 -->
            <form @submit.prevent="submitLogin">
              <div class="">
                <div class="my-3 text-left">
                  <router-link :to="{ path: '/' }" class="register-link text-muted ms-2">이미 위카 회원이신가요?</router-link>
                </div>
                <!-- 이메일 입력 -->
                <div class="mb-3">
                  <label for="email" class="form-label">email</label>
                  <input v-model="loginForm.email" id="email" type="email" class="form-control border-0 border-bottom" required autofocus autocomplete="username" placeholder="이메일을 입력해주세요.">
                </div>
                <!-- 비밀번호 입력 -->
                <div class="mb-4">
                  <label for="password" class="form-label">
                    {{ $t("password") }}
                  </label>
                  <input v-model="loginForm.password" id="password" type="password" class="form-control border-0 border-bottom" required autocomplete="current-password" placeholder="비밀번호를 입력해주세요.">
                </div>
                <!-- 백엔드 오류 메시지 -->

                <!-- 소셜 로그인 섹션 -->
                <div class="login-v2 my-5">
                  <h3 class="mb-4 text-muted"><span>또는 소셜 로그인</span></h3>
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
              <router-link :to="{name: 'auth.forgot-password'}">비밀번호 찾기</router-link>
              <div class="register-link mt-5 text-center">
                <router-link :to="{ path: '/register' }" class="register-link">회원가입하기</router-link>
              </div>
              <div class="text-muted mt-4 text-center">
                <p class="fs-6">
                  <span @click="openModal('privacy')" class="link-style">개인정보 처리방침</span> |
                  <span @click="openModal('terms')" class="link-style">이용약관</span>
                </p>
                <p class="my-3 tc-light-gray">ⓒ Watosys all rights reserved.</p>
                </div>
            </form>
          </div>
        </div>
        <AlarmModal ref="alarmModal" />
          <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>

      </div>
    </div>
  
</template>

<script setup>
import { ref, onMounted, nextTick, computed, onBeforeUnmount } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import LawGid from '@/views/modal/LawGid.vue';
import useAuth from '@/composables/auth';
import AlarmModal from '@/views/modal/AlarmModal.vue';

const isModalOpen = ref(false);
const modalContent = ref('');
const loginCardRef = ref(null);
const loginForm = ref({ email: '', password: '' });
const validationErrors = ref({});
const processing = ref(false);
const alarmModal = ref(null);
const store = useStore();
const router = useRouter();
const errorMessage = computed(() => store.getters['auth/errorMessage']);
const isMobileView = ref(window.innerWidth <= 640);
const openModal = (type) => {
  modalContent.value = type;
  isModalOpen.value = true;
};
const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };
const openAlarmModal = () => {
console.log("openAlarmModal called");
if (alarmModal.value) {
  alarmModal.value.openModal();
}
};
const closeModal = () => {
  isModalOpen.value = false;
};

const submitLogin = async () => {
  if (processing.value) return;

  processing.value = true;
  validationErrors.value = {};

  try {
    const userData = await store.dispatch('auth/login', loginForm.value);
    await store.dispatch("auth/getUser");
    await store.dispatch("auth/getAbilities");
    Swal.fire({
      icon: "success",
      title: "로그인",
      showConfirmButton: false,
      timer: 1500,
    });
    if (userData.roles.includes('admin')) {
      router.push({ name: "admin.index" });
    } else if (userData.roles.includes('dealer')) {
      router.push({ name: "dealer.index" });
    } else {
      router.push({ name: "home" });
    }
  } catch (error) {
    if (!error.response) {
      // 네트워크 오류
      Swal.fire({
        icon: "error",
        title: "Network Error",
        text: "Please check your internet connection and try again.",
        showConfirmButton: true,
      });
    } else if (error.response.data.status === 'fail') {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: error.response.data.message,
        showConfirmButton: true,
      });
    } else {

      Swal.fire({
        icon: "error",
        text: error.response.data.message || "관리자에게 문의해 주세요.",
        showConfirmButton: true,
      });
      if (error.response?.data) {
        validationErrors.value = error.response.data.errors;
      }
    }
    console.log(error);
    console.log(error.response?.data.message);
    console.log(error.response?.data.errors);
  } finally {
    processing.value = false;
  }
};

onMounted(() => {
  store.commit('auth/CLEAR_ERROR_MESSAGE'); // 페이지 로드 시 오류 메시지 초기화
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
.overlay-style {
  height: 100vh;
}

.register-content {
  display: flex;
  justify-content: space-between;
  gap: 20px;
}
.overlay-style {
  height: 100vh;
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
        }
</style>
