    TODO:이용후기,슬라이드 배너 추가
    <template>
        <div class="container">
            <!-- 회원가입 권장 섹션 -->
            <div class="main-contenter">
                <div class="my-5 app-specific-size">
                    <!--  <div class="text-left">
                    <h1 class="mb-4 fw-bold ms-2">빠르고 간편한</h1>
                    <img src="../../img/logo.png" class="logo mb-3 mr-2">
                    <p class="bold-link ms-2">내 차 판매, <mark class="custom-highlight">위카</mark>에서 도와드릴게요!</p>
                </div> -->
                    <div class="video-container d-sm-flex">
                        <video autoplay loop muted>
                            <source src="../../../img/video/mainvideo.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
                <div class="layout-container">
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
                                <h5>슬라이드 배너 step 2</h5>
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
                    <div class="review-content mb-5 " ref="reviewContentRef">
                        <div class="apply-top text-start">
                            <h3 class="review-title">다른 사람들의 이용후기에요</h3>
                            <router-link :to="{ name: 'index.allreview' }" href="" class="btn-apply">전체보기</router-link>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <div class="col mb-3">
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
                                                <span class="star-icon"></span>
                                            </label>
                                            <label class="rating__label rating__label--full" for="star3">
                                                <input type="radio" id="star3" class="rating__input" name="rating" value="">
                                                <span class="star-icon"></span>
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
                            <div class="col mb-3">
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
                                                <span class="star-icon"></span>
                                            </label>
                                            <label class="rating__label rating__label--full" for="star3">
                                                <input type="radio" id="star3" class="rating__input" name="rating" value="">
                                                <span class="star-icon"></span>
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
                            <div class="col mb-3">
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
                                                <span class="star-icon filled"></span>
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
                                <div class="col mb-3">
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
                                                    <span class="star-icon"></span>
                                                </label>
                                                <label class="rating__label rating__label--full" for="star3">
                                                    <input type="radio" id="star3" class="rating__input" name="rating" value="">
                                                    <span class="star-icon"></span>
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
                            <div v-if="isMobileView" class="bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet">
                                <div class="sheet-content">
                                    <div :class="['card login-card border-0', { 'expanded': expanded, 'no-shadow': isMobileView, 'no-hover': isMobileView }]" @click.stop="">
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
                        <transition name="fade" mode="out-in">
                        <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
                        </transition>
                    </div>
                </div>
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
                                    <!--영상-->
                                    <div class="row mt-5">
                                        <div class="col-12">
                                        <div class="d-flex justify-content-end my-2">
                                            <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">내 차 조회</button>
                                        </div>
                                        <div class="d-flex justify-content-end my-2" v-if="!user?.name">
                                            <router-link :to="{ path: '/login' }" class="btn btn-outline-primary tc-primary">로그인</router-link>
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
                        <transition name="fade" mode="out-in">
                        <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
                        </transition>
                    </div>
                </div>
        </template>

    <script setup>
    import { useStore } from "vuex";
    import { computed, ref, onMounted, onBeforeUnmount,nextTick } from "vue";
    import useAuth from '@/composables/auth';
    import useAuctions from '@/composables/auctions';
    import LawGid from '@/views/modal/LawGid.vue';

    const bannerRef = ref(null);
    const reviewContentRef = ref(null);
    const loginCardRef = ref(null);

    const isModalOpen = ref(false);
    const modalContent = ref('');

    const store = useStore();
    const user = computed(() => store.getters['auth/user']);

    const { loginForm, validationErrors: loginErrors, processing: loginProcessing, submitLogin } = useAuth();
    const { carInfoForm, submitCarInfo, processing: auctionProcessing, validationErrors: auctionErrors } = useAuctions();

    const bottomSheetStyle = ref({
    position: 'fixed', 
    bottom: '0px', 
    marginBottom: '0px', 
    borderBottomRightRadius: '0px',
    borderBottomLeftRadius: '0px'
});
    const showBottomSheet = ref(false);
    const scrollButtonStyle = ref({ display: 'block' });
    const isMobileView = ref(window.innerWidth <= 640);

    const openModal = (type) => {
    modalContent.value = type;
    isModalOpen.value = true;
    };

    const closeModal = () => {
    isModalOpen.value = false;
    };

    const toggleSheet = () => {
    const bottomSheet = document.querySelector('.bottom-sheet');
    const screenWidth = window.innerWidth;

    if (showBottomSheet.value) {
        bottomSheetStyle.value = { 
            position: 'static', 
            bottom: '-100%', 
            marginBottom: '20px', 
            borderBottomRightRadius: '20px',
            borderBottomLeftRadius: '20px'
        };
        scrollButtonStyle.value = { display: 'block' };
    } else {
        bottomSheetStyle.value = {
            position: screenWidth >= 1200 ? 'static' : 'fixed',
            bottom: '0px',
            marginBottom: '0px',
            borderBottomRightRadius: '0px',
            borderBottomLeftRadius: '0px'
        };
        scrollButtonStyle.value = { display: 'none' };
    }
    showBottomSheet.value = !showBottomSheet.value;
};

const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
        isMobileView.value = window.innerWidth <= 640;
    }
};


    onMounted(() => {
        nextTick(() => {
        const banner = bannerRef.value;
        const reviewContent = reviewContentRef.value;
        const loginCard = loginCardRef.value;

        // Banner 먼저 보이기
        setTimeout(() => {
        banner.classList.add('enter-active');
        }, 100);

        // review-content를 1초 후에 보이기
        setTimeout(() => {
        reviewContent.classList.add('enter-active');
        }, 600);

        // login-card를 2초 후에 보이기
        setTimeout(() => {
        loginCard.classList.add('enter-active');
        }, 1100);
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
