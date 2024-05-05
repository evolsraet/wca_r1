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
                <div class="review-content mb-5">
                    <div class="apply-top text-start">
                        <h3 class="review-title">다른 사람들의 이용후기에요</h3>
                        <router-link :to="{ name: 'index.allreview' }" href="" class="btn-apply">전체보기</router-link>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <div class="col">
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
                        <div class="col">
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
                        <div class="col">
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
                        <div class="col">
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
                <div class="card login-card border-0" :class="{ 'expanded': expanded }" @click="expandCard">
                    <div class="card-body">
                        <!-- 조회 폼 -->
                        <form @submit.prevent="submitCarInfo">
                            <div>
                                <input type="text" class="form-control border-0 border-bottom my-4" placeholder="소유자가 누구인가요?" v-model="carInfoForm.owner">
                            </div>
                            <div>
                                <input type="text" class="form-control border-0 border-bottom mb-4" placeholder="차량 번호를 입력해주세요." v-model="carInfoForm.no">
                            </div>
                            <!--영상-->
                            <div class="d-sm-flex web-video-container video-container my-4">
                                <video autoplay loop muted>
                                    <source src="../../../img/video/mainvideo.mp4" type="video/mp4">
                                </video>
                            </div>
                          
                            <div class="flex items-center justify-end my-2">
                                <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">내 차 조회
                                </button>
                            </div>
                            <div class="flex items-center justify-end my-2" v-if="!user?.name">
                                <router-link :to="{ path: '/login' }" class="btn btn-outline-primary">로그인</router-link>
                            </div>
                            <div class="text-muted mt-4 text-center">
                                <p class="fs-6">
                                    <span @click="openModal('copywrite')" class="link-style">카피라이트</span> |
                                    <span @click="openModal('privacy')" class="link-style">개인정보 처리방침</span> |
                                    <span @click="openModal('terms')" class="link-style">이용약관</span>
                                </p>
                                <LawGid v-if="isModalOpen" :content="modalContent" @close="closeModal"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
       </div>
    </div>
</template>

<script setup>
import { useStore } from "vuex";
import { computed,ref,onMounted } from "vue"
import useAuth from '@/composables/auth';
import useAuctions from '@/composables/auctions';
import LawGid from '@/views/modal/LawGid.vue'; 


const isModalOpen = ref(false);
const modalContent = ref('');

const store = useStore();
const user = computed(() => store.getters['auth/user']);

const { loginForm, validationErrors: loginErrors, processing: loginProcessing, submitLogin } = useAuth();
const { carInfoForm, submitCarInfo, processing: auctionProcessing, validationErrors: auctionErrors } = useAuctions();


const openModal = (type) => {
  modalContent.value = type;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

</script>

<style scoped>
.rating__label .star-icon {
    width: 30px;
    height: 30px;
}

</style>