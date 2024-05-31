<template>
    <div class="container">
      <!-- 회원가입 권장 섹션 -->
      <div class="main-contenter">
        <div class="my-5 app-specific-size">
          <div class="video-container d-sm-flex">
            <video autoplay loop muted>
              <source src="../../../img/video/mainvideo.mp4" type="video/mp4">
            </video>
          </div>
        </div>
        <div class="layout-container">
          <!-- 슬라이드 배너 -->
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
          </div>
          <!-- 이용 후기 섹션 -->
          <div class="review-content mb-5" ref="reviewContentRef">
            <div class="apply-top text-start">
              <h3 class="review-title">다른 사람들의 이용후기에요</h3>
              <router-link :to="{ name: 'index.allreview' }" class="btn-apply">전체보기</router-link>
            </div>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col mb-3" v-for="review in reviewsData.slice(0,4)" :key="review">
                <div class="card">
                  <div class="car-imges"></div>
                  <div class="card-body">
                    <h5 class="card-title">{{ review.auction.car_no }}</h5>
                    <div class="rating">
                        <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                            <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                            <span :class="['star-icon', index <= review.star ? 'filled' : '']"></span>
                        </label>
                    </div>
                    <div class="d-sm-flex justify-content-between text-muted">
                        <span class="deilname">담당 딜러 {{ review.auction.dealer_name }} 님</span>
                        <span class="date">{{ splitDate(review.created_at) }}</span>
                    </div>
                    <p class="card-text">{{ review.content }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- 모바일 화면에서 BottomSheet -->
          <div v-if="isMobileView">
            <bottom-sheet initial="half" :dismissable="true">
              <template #default>
                <!-- 조회 폼 -->
                <form @submit.prevent="submitCarInfo" class="d-flex flex-column">
                  <div class="row mb-1">
                    <div class="col-12">
                      <div>
                        <input type="text" class="form-control border-0 border-bottom my-4" placeholder="소유자가 누구인가요?" v-model="carInfoForm.owner">
                      </div>
                      <div>
                        <input type="text" class="form-control border-0 border-bottom mb-4" placeholder="차량 번호를 입력해주세요." v-model="carInfoForm.no">
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
                        <span @click="openModal('privacy')" class="link-style">개인정보 처리방침</span> |
                        <span @click="openModal('terms')" class="link-style">이용약관</span>
                      </p>
                      <p class="my-3 tc-light-gray">ⓒ Watosys all rights reserved.</p>
                    </div>
                  </div>
                </form>
              </template>
            </bottom-sheet>
          </div>
          <!-- 웹 화면에서 조회 폼 -->
          <div v-else class="card login-card border-0" :class="{ 'expanded': expanded }">
            <div class="card-body">
              <!-- 조회 폼 -->
              <form @submit.prevent="submitCarInfo" class="d-flex flex-column">
                <div class="row mb-4">
                  <div class="col-12">
                    <div>
                      <input type="text" class="form-control border-0 border-bottom my-4" placeholder="소유자가 누구인가요?" v-model="carInfoForm.owner">
                    </div>
                    <div>
                      <input type="text" class="form-control border-0 border-bottom mb-4" placeholder="차량 번호를 입력해주세요." v-model="carInfoForm.no">
                    </div>
                  </div>
                </div>
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
                        <span @click="openModal('copywrite')" class="link-style">카피라이트</span> |
                        <span @click="openModal('privacy')" class="link-style">개인정보 처리방침</span> |
                        <span @click="openModal('terms')" class="link-style">이용약관</span>
                      </p>
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
  import { useStore } from "vuex";
  import { computed, ref, onMounted, onBeforeUnmount, nextTick } from "vue";
  import useAuth from '@/composables/auth';
  import useAuctions from '@/composables/auctions';
  import LawGid from '@/views/modal/LawGid.vue';
  import BottomSheet from '@/views/bottomsheet/BottomSheet.vue';
  import { initReviewSystem } from '@/composables/review';
  
  const bannerRef = ref(null);
  const reviewContentRef = ref(null);
  
  const isModalOpen = ref(false);
  const modalContent = ref('');
  
  const store = useStore();
  const user = computed(() => store.getters['auth/user']);
  
  const { carInfoForm, submitCarInfo, processing } = useAuctions();
  
  const isMobileView = ref(window.innerWidth <= 640);
  const { getAllReview, reviewsData, splitDate } = initReviewSystem(); 

  const openModal = (type) => {
    modalContent.value = type;
    isModalOpen.value = true;
  };
  
  const closeModal = () => {
    isModalOpen.value = false;
  };
  
  const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };
  
  onMounted(async () => {
    await getAllReview();

    nextTick(() => {
      const banner = bannerRef.value;
      const reviewContent = reviewContentRef.value;
      setTimeout(() => {
        banner.classList.add('enter-active');
      }, 100);
      setTimeout(() => {
        reviewContent.classList.add('enter-active');
      }, 500);
    });
    window.addEventListener('resize', checkScreenWidth);
    checkScreenWidth();
  });
  
  onBeforeUnmount(() => {
    window.removeEventListener('resize', checkScreenWidth);
  });
  </script>
  
  <style scoped>
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
  </style>
  