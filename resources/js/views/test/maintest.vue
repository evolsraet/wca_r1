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
                <div class="video-container">
                    <video width="100%" autoplay loop muted>
                        <source src="../../img/video/mainvideo.mp4" type="video/mp4">
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
                <div class="review-content my-5">
                    <div class="apply-top text-start">
                        <h3 class="review-title">다른 사람들의 이용후기에요</h3>
                        <a href="" class="btn-apply">전체보기 <img src="../../img/Icon-right.png" class="ms-2" alt="설명"></a>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <div class="col">
                            <div class="card">
                                <div class="car-imges"></div>
                                <div class="card-body">
                                    <h5 class="card-title">현대 소나타 (DN8)</h5>
                                    <div class="rating">
                                        <div v-for="index in rating" :key="'filled-' + index" class="star filled-star"></div>
                                        <div v-for="index in 5 - rating" :key="'empty-' + index" class="star empty-star"></div>
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
                                        <div v-for="index in rating" :key="'filled-' + index" class="star filled-star"></div>
                                        <div v-for="index in 5 - rating" :key="'empty-' + index" class="star empty-star"></div>
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
                                        <div v-for="index in rating" :key="'filled-' + index" class="star filled-star"></div>
                                        <div v-for="index in 5 - rating" :key="'empty-' + index" class="star empty-star"></div>
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
                                        <div v-for="index in rating" :key="'filled-' + index" class="star filled-star"></div>
                                        <div v-for="index in 5 - rating" :key="'empty-' + index" class="star empty-star"></div>
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
                        <!-- 로그인 폼 -->
                        <form @submit.prevent="submitLogin">
                            <div>
                                <input type="text" class="form-control border-0 border-bottom my-4" placeholder="소유자가 누구인가요?" v-model="username">
                            </div>
                            <div>
                                <input type="text" class="form-control border-0 border-bottom mb-4" placeholder="차량 번호를 입력해주세요." v-model="carNumber">
                            </div>
                            <!--영상-->
                            <div class="web-video-container video-container my-4">
                                <video width="100%" autoplay loop muted>
                                    <source src="../../img/video/mainvideo.mp4" type="video/mp4">
                                </video>
                            </div>
                            <!-- 로그인 버튼 -->
                            <div class="flex items-center justify-end my-2">
                                <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">
                                    {{ $t('내 차 조회') }}
                                </button>
                            </div>
                            <div class="flex items-center justify-end my-2">
                                <router-link :to="{ path: '/test' }" class="btn btn-outline-primary">{{ $t('로그인') }}</router-link>
                            </div>
                            <div class="text-muted mt-4 text-center">
                                <p class="fs-6">
                                    <a href="/copywrite" class="text-muted spacing">카피라이트</a> |
                                    <a href="/privacy-policy" class="text-muted spacing">개인정보 처리방침</a> |
                                    <a href="/terms" class="text-muted spacing">이용약관</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import {
        useReview
    } from '@/composables/review';
    import useAuth from '@/composables/auth'

    const {
        loginForm,
        validationErrors,
        processing,
        submitLogin
    } = useAuth(); //로그인양식데이터, 유효성 검사오류,로그인상태처리, 로구인 함수 할당

    const {
        rating,
        setRating
    } = useReview();
</script>