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
                        <a href="" class="btn-apply">전체보기 <img src="../../../img/Icon-right.png" class="ms-2" alt="설명"></a>
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
                        <!-- 조회 폼 -->
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
                                    <source src="../../../img/video/mainvideo.mp4" type="video/mp4">
                                </video>
                            </div>
                            <!-- 로그인 버튼 -->
                            <div class="flex items-center justify-end my-2">
                                <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">
                                    {{ $t('내 차 조회') }}
                                </button>
                            </div>
                            <div class="flex items-center justify-end my-2">
                                <router-link :to="{ path: '/login' }" class="btn btn-outline-primary">{{ $t('로그인') }}</router-link>
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
    } = useAuth(); 

    const {
        rating,
        setRating
    } = useReview();
</script>

<style scoped>
body {
    font-family: 'Nunito', sans-serif;
}
/*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
</style>
