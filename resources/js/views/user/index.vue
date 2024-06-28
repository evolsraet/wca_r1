<template>
    <div v-if="!isMobileView" class="banner-top">
        <div class="styled-div mt-0">
            <img src="../../../img/main_banner.png" class="styled-img" alt="배너 이미지">
            <div class="content d-flex">
                <div>
                    <h1 class="fw-bolder mb-4 lh-base animated-text">내 차 판매는 <br>위카에서!</h1>
                    <!--<router-link :to="{ name: 'auction.index' }" href="" class="btn-apply animated-button">더 알아보기</router-link>-->
                </div>
                <div>
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div v-if="isMobileView" class="d-flex justify-content-between align-items-sm-end p-3 mt-2">
            <h2 class="fw-bolder lh-base animated-text">내 차 판매는 <br>위카에서!</h2>
            <router-link :to="{ name: 'auction.index' }" href="" class="btn-apply p-0 m-0 animated-button">더 알아보기</router-link>
        </div>
        <div class="regiest-content">
            <div class="container my-4">
                <div class="layout-container02 mt-5">
                    <!-- 딜러 프로필 요약 정보 -->
                    <div class="p-2">
                        <div class="apply-top text-start mb-0">
                            <h3 class="review-title">이용후기</h3>
                            <router-link :to="{ name: 'user.review' }" href="" class="btn-apply">전체보기</router-link>
                        </div>
                        <div v-if="reviewsData.length > 0" class="container">
                            <div class="row">
                                <div class="col-md-6 p-2" v-for="review in reviewsData.slice(0,2)" :key="review.id">
                                    <div class="card my-auction mt-3">
                                        <div>
                                            <div class="card-img-top-ty02 border-rad"></div>
                                            <div class="card-body">
                                                <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                                <p>2020년 / 2.4km / 무사고</p>
                                                <p class="card-text tc-light-gray">(차량 기종 들어갈 예정) {{ review.id }}</p>
                                                <p class="card-text tc-light-gray">담당 딜러: {{ review.dealer.name }} 님</p>
                                                <div>
                                                    <span class="blue-box">보험 3건</span><span class="gray-box">재경매</span>
                                                </div>
                                                <div>
                                            <!--  시안상 별점 없음  <div class="rating">
                                                    <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                                                        <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                                                        <span :class="['star-icon', index <= review.star ? 'filled' : '']"></span>
                                                    </label>
                                                </div>
                                                <p>{{ review.content }}</p>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-md-1" v-else>
                            <div class="card my-auction mt-3">
                                <div class="card-body">
                                    <div class="none-complete">
                                        <span class="tc-light-gray">아직 작성된 이용후기가 없습니다.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-0" @click="toggleCard">
                        <div class="card-body">
                            <div v-if="!isMobileView" class="enter-view">
                                <h5>내 매물관리</h5>
                                <router-link :to="{ name: 'auction.index' }" class="btn-apply">전체보기</router-link>
                            </div>
                            <!-- 차량이 존재 할 경우 -->
                            <div v-if="auctionsData.length > 0" class="scrollable-content mt-4">
                                <div v-for="(auction, index) in auctionsData"
                                    :key="auction.id"
                                    @click="navigateToDetail(auction)"
                                    :style="getAuctionStyle(auction)"
                                    :class="['animated-auction', `delay-${index}`]">
                                    <div class="complete-car">
                                        <div class="my-auction">
                                            <div class="bid-bc p-2">
                                                <ul class="px-0 inspector_list max_width_900">
                                                    <li>
                                                        <div>
                                                            <div class="d-flex gap-4 align-items-center">
                                                                <div class="img_box">
                                                                    <img src="../../../img/car_example.png" alt="딜러 사진" class="mb-2 align-text-top">
                                                                </div>
                                                                <h5 class="mb-0">{{ auction.car_no }}</h5>
                                                                <p v-if="auction.status === 'chosen'" class="ml-auto"><span class="blue-box02 bg-opacity-50">선택완료</span></p>
                                                                <p v-if="auction.status === 'cancel'" class="ml-auto"><span class="box bg-secondary">경매 취소</span></p>
                                                                <p v-if="auction.status === 'wait'" class="ml-auto"><span class="blue-box02">딜러 선택</span></p>
                                                                <p v-if="auction.status === 'diag'" class="ml-auto"><span class="box bg-success bg-opacity-75">진단 대기</span></p>
                                                                <p v-if="auction.status === 'ask'" class="ml-auto"><span class="box bg-warning bg-opacity-75">신청 완료</span></p>
                                                                <p v-if="auction.status === 'ing'" class="ml-auto"><span class="box bg-danger">경매진행</span></p>
                                                                <p v-if="auction.status === 'done'" class="ml-auto"><span class="box bg-black">경매완료</span></p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <router-link :to="{ name: 'home' }" class="bid-bc p-3">
                                    <ul class="px-0 inspector_list max_width_900">
                                        <li>
                                            <div>
                                                <p class="tc-light-gray d-flex justify-content-center">새 차량 등록하기<span class="ms-2 icon-auction-plus"></span></p>
                                            </div>
                                        </li>
                                    </ul>
                                </router-link>
                            </div>
                            <!-- 선택 완료된 차량이 없는경우-->
                            <div v-else>
                                <div class="complete-car mt-4">
                                    <div class="my-auction none-content">
                                        <div class="none-complete-img"></div>
                                        <div class="d-flex align-items-center flex-column gap-3">   
                                            <div class="tc-light-gray d-flex align-items-center flex-column gap-5">                                    
                                                <h4>등록된 차가 없어요</h4>
                                                <h5>차량 등록후, 경매를 시작해보세요.</h5>
                                            </div>
                                            <p class="btn primary-btn btn-apply-ty02 justify-content-around">차량 등록하기</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>

<script setup>
import Footer from "@/views/layout/footer.vue"
import { onMounted , onBeforeUnmount, ref } from 'vue';
import { useRouter } from 'vue-router';
import { setRandomPlaceholder } from '@/hooks/randomPlaceholder';
import useAuctions from "@/composables/auctions";
import { initReviewSystem } from '@/composables/review';
import { cmmn } from '@/hooks/cmmn';
import { useStore } from 'vuex';

const { auctionsData, getAuctions } = useAuctions();
const { getUserReview , deleteReviewApi , reviewsData } = initReviewSystem(); 
const { amtComma } = cmmn();
const router = useRouter();
const isMobileView = ref(window.innerWidth <= 640);
const store = useStore();
function navigateToDetail(auction) {
    console.log("디테일 :", auction.id);
    router.push({ name: 'AuctionDetail', params: { id: auction.id } });
}
const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
};
  
function getAuctionStyle(auction) {
    const validStatuses = ['done', 'wait', 'ing', 'diag'];
    return validStatuses.includes(auction.status) ? { cursor: 'pointer' } : {};
}

onMounted(async() => {
    await getAuctions();
    const user =store.getters['auth/user'];
    const userId = user.id;
    console.log(userId);
    await getUserReview(userId);
    console.log(reviewsData.value);
    setRandomPlaceholder();
    window.addEventListener('resize', checkScreenWidth);
    checkScreenWidth();
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkScreenWidth);
});
</script>

<style scoped>
p {
    margin-top: 0;
    margin-bottom: 0rem !important;
}

@media (max-width: 650px){
    .layout-container02 {
        display: flex !important;
        gap: 2rem !important;
        flex-direction: column;
        flex-wrap: nowrap;
    }
    .styled-div .content{
        margin-top: 0px !important;
    }
    .none-content{
        padding: 10px 40px 35.5px !important;
    }
    .scrollable-content{
        padding: 5px 5px 5px !important;
    }
    .blue-box02{
        margin-right:0px !important;
    }
    .box{
        margin-right:0px !important;
    }
}
@media (max-width: 991px){
    .app-style{
        display: none !important;
    }
    .content{
        margin-top: 0px !important;
    }
    .mt-5{
        margin-top: 0px !important;
    }
    .my-4{
        margin-top: 0px !important;
    }
}
@media (max-width: 1200px){
    .container {
        padding-right: 0px;
        padding-left: 0px;
    }
}
@media (max-width: 991px){
.container {
    --bs-gutter-x: 0rem;
}
.card-body {
        padding: 0px;
    }
}
@media (max-width: 650px){
.layout-container02{
    flex-direction: column-reverse;
}
}
.styled-div {
    width: 100%;
    height: 40vh;
    margin-top: 57px;
    border-radius: 0px;
    background-color: #f5f5f6;
    overflow: hidden; 
    position: relative; 
}
.ml-auto{
    margin-left: auto;
}
.styled-div .styled-img {
    width: 100%;
    height: 100%;
    object-fit: cover; 
    object-position: center; 
}
.input-container-search {
    position: relative;
    display: flex;
    align-items: center;
}


.row-cols-md-2 .complete-car {
flex: 0 0 50%; 
max-width: 50%; 
}


@media (max-width: 768px) {
.row-cols-md-2 .complete-car {
    flex: 0 0 50%;
    max-width: 50%;
}
}

.card-img-top-ty02 {
width: 100%;
height: 190px;
object-fit: cover;
object-position: center;
border-radius: 10px;
}
@media (max-width: 767px){
.col-md-6 {
    flex: 0 0 auto;
    width: 50%;
}
}
@media (max-width: 400px){
.col-md-6 {
    flex: 0 0 auto;
    width: 100%;
}
}
@media (min-width: 768px) {
.card-img-top-ty02 {
    height: 250px; 
}
}
.bid-bc {
background: none;
}

.scrollable-content {
    height: auto;
    overflow-y: hidden;
    padding: 30px 30px 30px 30px;
    background-color: #f7f8fb;
}

.styled-div .content {
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
display: flex;
flex-direction: row;
justify-content: space-evenly;
padding: 50px;
color: white;
align-items: center;
}

.layout-container02 {
display: grid;
grid-template-columns: repeat(2, 1fr); /* 2열 설정 */
gap: 20px; /* 행과 열 사이의 간격 */
}

.styled-div .top-content {
display: flex;
justify-content: space-between; /* 양쪽으로 나눔 */
align-items: center;
margin-bottom: 10px; /* 아래 공간 확보 */
}

.styled-div .right-content input {
padding: 10px;
border-radius: 5px;
border: none;
}

.styled-div .bottom-content {
text-align: center;
}

.styled-input {
width: 439px;
height: 40px;
padding: 10px 10px 10px 55px;
border-radius: 5px;
border: 1px solid #ccc;
font-size: 16px;
color: black;
background-color: #fff;
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
outline: none;
transition: border-color 0.3s;
}

.styled-input:focus {
border-color: #66afe9;
}

.tags {
display: flex;
gap: 10px;
margin-top: 10px;
justify-content: flex-start;
width: 439px;
flex-wrap: wrap;
}

.tag {
display: inline-block;
padding: 5px 19px;
border-radius: 20px;
background-color: white;
color: #5d5d5d;
font-size: 14px;
font-weight: bold;
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
border: 1px solid #ccc;
transition: background-color 0.3s, color 0.3s;
}

.tag:hover {
background-color: #fae0e0;
color: white;
}

.blue-box02 {
padding: 0 15px !important;
width: auto !important;
border-radius: 6px !important;
}
.box {
padding: 0 15px !important;
width: auto !important;
border-radius: 6px !important;
}
@media (min-width: 992px){

.col-md-6 {}
}

.animated-text {
    opacity: 0;
    transform: translateY(20px);
    animation: slideUp 1s forwards;
    animation-delay: 0.5s;
}

.animated-button {
    opacity: 0;
    transform: translateX(-20px);
    animation: slideIn 1s forwards;
    animation-delay: 1s;
}

.animated-auction {
    opacity: 0;
    transform: scale(0.9);
    animation: scaleUp 0.5s forwards;
}

@keyframes slideUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes scaleUp {
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.scrollable-content .complete-car:nth-child(1) .animated-auction { animation-delay: 1.2s; }
.scrollable-content .complete-car:nth-child(2) .animated-auction { animation-delay: 1.4s; }
.scrollable-content .complete-car:nth-child(3) .animated-auction { animation-delay: 1.6s; }
.scrollable-content .complete-car:nth-child(4) .animated-auction { animation-delay: 1.8s; }
.scrollable-content .complete-car:nth-child(5) .animated-auction { animation-delay: 2s; }
</style>
