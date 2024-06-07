<template>
    <div class="container">
      <!-- 회원가입 권장 섹션 -->
      <div class="regiest-content">
        <div class="p-4 app-specific-size">
          <div class="text-center">
            <h2 class="my-4 fw-bold"><mark class="custom-highlight">회원가입시</mark> 판매가 빨라져요</h2>
            <p class="bold-link text-muted">소셜 로그인 및 이메일로 가입 할 수 있어요.</p>
          </div>
        </div>
        <div class="layout-container">
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
          </div>
          <div class="review-content">
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
          </div>
          <div class="card login-card login-any border-0" ref="loginCardRef">
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
                  <div v-if="errorMessage" class="text-danger my-2">
                    {{ errorMessage }}
                  </div>
                  <!-- 소셜 로그인 섹션 -->
                  <div class="login-v2 my-5">
                    <h3 class="mb-4 text-muted"><span>또는 소셜 로그인</span></h3>
                    <ul class="login-v2-area">
                      <li><a href="#" class="google" title="google">Google</a></li>
                      <li><a href="#" class="naver" title="naver">Naver</a></li>
                      <li><a href="#" class="kakao" title="kakao">Kakao</a></li>
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
                    <span @click="openModal('copywrite')" class="link-style">카피라이트</span> |
                    <span @click="openModal('privacy')" class="link-style">개인정보 처리방침</span> |
                    <span @click="openModal('terms')" class="link-style">이용약관</span>
                  </p>
                </div>
              </form>
            </div>
          </div>
          <transition name="fade">
            <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
          </transition>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, nextTick, computed } from 'vue';
  import { useStore } from 'vuex';
  import { useRouter } from 'vue-router';
  import Swal from 'sweetalert2';
  import LawGid from '@/views/modal/LawGid.vue';
  import useAuth from '@/composables/auth';
  
  const isModalOpen = ref(false);
  const modalContent = ref('');
  const loginCardRef = ref(null);
  const loginForm = ref({ email: '', password: '' });
  const validationErrors = ref({});
  const processing = ref(false);
  const store = useStore();
  const router = useRouter();
  const errorMessage = computed(() => store.getters['auth/errorMessage']);
  
  const openModal = (type) => {
    modalContent.value = type;
    isModalOpen.value = true;
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
        title: "로그인 성공",
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
      if (error.response?.data) {
        validationErrors.value = error.response.data.errors;
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
  </style>
  