<template>
    <!-- all view랑 reciew-->
    <div class="container">
        <div class="main-contenter">
            <div class="review">
                <div class="review-content mov-review my-5" v-if="loading">
                    <h3 class="review-title">이용후기 관리</h3>
                    <div class="tab-nav my-4 overflow-x-auto">
                        <ul>
                            <li><a href="#" @click="setActiveTab('available')" :class="{ 'active': activeTab === 'available' }" title="작성한 이용후기">작성 가능한 이용 후기( <span>{{ totalAuction }}</span> )</a></li>
                            <li><a href="#" @click="setActiveTab('written')" :class="{ 'active': activeTab === 'written' }" title="작성 가능한 이용후기">작성한 이용후기( <span>{{ totalReview || 0 }}</span> )</a></li>
                        </ul>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    </div>
                    <div v-if="activeTab === 'available'" class="row">
                        <div class="col-md-3 p-2 mb-2 hover-ac pointer" v-if="auctionsData.length > 0" v-for="auction in auctionsData" :key="auction.id" @click="navigateToDetail(auction.id)">
                            <div class="p-2 card my-auction"> 
                                    <div class="card-img-top-placeholder">
                                        <img src="../../../img/car_example.png">
                                    </div> 
                                    <p class="review-date">{{ splitDate(auction.updated_at) }} ({{ getDayOfWeek(auction.updated_at) }})</p>
                                <div class="card-body">
                                    <div class="popup-menu" v-show="isMenuVisible">
                                        <ul>
                                            <li><button @click="editReview" class="tc-blue">수정</button></li>
                                            <li><button @click="deleteReviewApi" class="tc-red">삭제</button></li>
                                        </ul>
                                    </div>
                                    <!--<h3 class="review-title">{{ auction.car_no }}</h3>-->
                                    <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                        <p>2020년 | 2.4km | 무사고</p>
                                        <div class="d-flex justify-content-between align-items-baseline">
                                            <div>
                                                <span class="blue-box">보험 3건</span><span class="gray-box">재경매</span>
                                            </div>
                                                <h5 class="tc-red fs-5">{{ amtComma(auction.win_bid.price) }}</h5>
                                            </div>
                                       <!-- <a class="btn-review" @click="navigateToDetail(auction.id)">후기작성</a>-->
                                    </div>
                                </div>
                        </div>
                        <div v-else>
                            가 없습니다.
                        </div>
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item" :class="{ disabled: !pagination.prev }">
                                <a class="page-link prev-style" @click="auctionLoadPage(pagination.current_page - 1)"></a>
                                </li>
                                <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === pagination.current_page }">
                                <a class="page-link" @click="auctionLoadPage(n)">{{ n }}</a>
                                </li>
                                <li class="page-item next-prev" :class="{ disabled: !pagination.next }">
                                <a class="page-link next-style" @click="auctionLoadPage(pagination.current_page + 1)"></a>
                                </li>
                            </ul>
                        </nav>
                        
                    </div>
                    
                    <div v-if="activeTab === 'written'"  class="row">
                        <div class="col-md-3 p-2 mb-2" v-if="reviewsData.length > 0" v-for="review in reviewsData" :key="review">
                            <div class="card my-auction p-2">
                                <div class="review-image02">
                                    <p class="review-date">{{ splitDate(review.updated_at) }} ({{ getDayOfWeek(review.updated_at) }})</p>
                                    <div class="card-img-top-placeholder mt-2">
                                        <img src="../../../img/car_example.png">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="more-view" @click="toggleMenu(review.id)">moreview</p>
                                    <div class="popup-menu" :id="'toggleMenu' + review.id" style="display : none">
                                        <ul>
                                            <li><button @click="editReview(review.id,'user')" class="tc-blue">수정</button></li>
                                            <li><button @click="userDeleteReview(review.id, userId)" class="tc-red">삭제</button></li>
                                        </ul>
                                    </div>
                                    <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                    <p>2020년 | 2.4km | 무사고</p>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="blue-box">보험 3건</span><span class="gray-box">재경매</span>
                                        </div>
                                        <!--<p class="tc-red">{{ amtComma(review.auction.win_bid.price) }}</p>-->
                                    </div>
                                    <div class="mb-2 justify-content-between flex align-items-center bold-18-font">
                                        <!-- <p>{{ review.auction.car_no }}</p>
                                            <p class="tc-red">{{ amtComma(review.auction.win_bid.price) }}</p>-->
                                        </div>
                                        <div class="rating">
                                            <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                                                <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                                                <span :class="['star-icon', index <= review.star ? 'filled' : '']"></span>
                                            </label>
                                        </div>
                                    <p class="over-text">{{ review.content }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            작성한 이용 후기가 없습니다.
                        </div>
                        <ul class="pagination justify-content-center">
                            <li class="page-item" :class="{ disabled: !reviewPagination.prev }">
                            <a class="page-link prev-style" @click="reviewLoadPage(reviewPagination.current_page - 1)"></a>
                            </li>
                            <li v-for="n in reviewPagination.last_page" :key="n" class="page-item" :class="{ active: n === reviewPagination.current_page }">
                            <a class="page-link" @click="reviewLoadPage(n)">{{ n }}</a>
                            </li>
                            <li class="page-item next-prev" :class="{ disabled: !reviewPagination.next }">
                            <a class="page-link next-style" @click="reviewLoadPage(reviewPagination.current_page + 1)"></a>
                            </li>
                        </ul>
                    </div>
                    <router-link to="/alllist-do" class="anymation mt-5 btn shadow-sm border w-100 hp-50 d-flex align-items-center justify-content-center gap-2">다른 분들의 이용후기도 둘러보세요!<span class="black-right-icon"></span></router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted , computed, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { ref } from 'vue';
import useAuctions from "@/composables/auctions";
import { initReviewSystem } from '@/composables/review';
import { cmmn } from '@/hooks/cmmn';

let userId ;
const router = useRouter();
const activeTab = ref('available'); //nav 탭 바
const isMenuVisible = ref(false);
const { auctionsData, getAuctions , pagination } = useAuctions();
const { getUserReview , userDeleteReview , reviewsData , reviewPagination } = initReviewSystem(); 
const { amtComma , splitDate , getDayOfWeek} = cmmn();
const loading = ref(false);
const store = useStore();
const user = computed(() => store.getters['auth/user']);

const auctionCurrentPage = ref(1); // 현재 페이지 번호
const reviewCurrentPage = ref(1); // 현재 페이지 번호

let totalAuction;
let totalReview;

function toggleMenu(id) {
    let targetObj = document.getElementById('toggleMenu'+id);
    if (targetObj.style.display === 'none') {
        targetObj.style.display = 'block';
    } else {
        targetObj.style.display = 'none';
    }
}

// ... 더보기 누르면 나오는 탭
function setActiveTab(tab) {
    console.log(activeTab.value);
    if (activeTab.value !== tab) { // 탭이 실제로 변경될 때만 메뉴를 숨김
        isMenuVisible.value = false;
    }
    activeTab.value = tab;
}

function editReview(reviewId) {
    router.push({ name: 'user.edit-review', params: { id: reviewId } });
}

function navigateToDetail(auctionId) {
    router.push({ name: 'user.create-review', params: { id: auctionId } });
}

const deleteMessageVisible = ref(false);

//삭제후 메시지 띄우기
function showDeleteMessage() {
    deleteMessageVisible.value = true;
    setTimeout(() => {
        deleteMessageVisible.value = false;
        location.reload();
    }, 1000);
}
async function auctionLoadPage(page) { // 페이지 로드
    if (page < 1 || page > pagination.value.last_page) return;
    auctionCurrentPage.value = page;
    await getAuctions(page,true);



}
async function reviewLoadPage(page) { // 페이지 로드
    if (page < 1 || page > reviewPagination.value.last_page) return;
    reviewCurrentPage.value = page;
    await getUserReview(user.value.id,page);
}

onMounted(async () => {

    await getAuctions(1,true);

    userId = user.value.id;
    await getUserReview(userId);
    
    loading.value = true;

    totalAuction = pagination.value.total;
    totalReview = reviewPagination.value.total;

});
</script>


<style scoped>
.rating__label .star-icon {
    width: 30px;
    height: 30px;
}
@media (max-width: 360px) {
    .flex{
    display: block !important;
    }
}
</style>
<style>
.popup-menu {
    position: absolute;
    text-align: center;
    right: 35px;
    top: 20px;
    background-color: white;
    border: 1px solid #d3d3d3;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
    z-index: 100;
    width: 130px;
    border-radius: 6px;
    transform: translateY(-20px); 
    transition: opacity 0.3s ease, transform 0.3s ease; 
}
.review-image02 img {
    width: 100%;
    display: block;
    border-right: 1px solid #ddd;
    object-fit: cover;
    object-position: center;
    border-radius: 6px;
}

.review-info02 {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    padding: 10px;
}
.btn-review:hover::after {
    transform: translateX(5px);
}
.btn-review::after {
    content: "";
    display: block;
    width: 14px;
    height: 14px;
    margin-top: 2px;
    background-image: url('../../../img/Icon-red-right.png');
    background-size: contain;
    background-repeat: no-repeat;



    margin-left: 15px;
    transition: transform 0.3s ease;
}

.popup-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.popup-menu li button {
    display: block;
    padding: 8px 12px;
    width: 100%;
    text-align: center;
    border: none;
    background: none;
    cursor: pointer;
}

.popup-menu li button:hover {
    background-color: #f5f5f5;
}
@media (min-width: 300px) and (max-width:540px ){
.col-md-3{
    flex: 0 0 auto;
    width: 100% !important;
}
}
@media(min-width:641px) and (max-width: 991px){
    .col-md-3 {
        flex: 0 0 auto;
        width: 49.3333% !important;
    }
}
@media(min-width:541px) and (max-width: 640px){
    .col-md-3 {
        flex: 0 0 auto;
        width: 47.3333% !important;
    }
}
@media (min-width: 768px){
.col-md-3 {
    flex: 0 0 auto;
    width: 25%;
}
}
@media (max-width: 640px){
.mov-review > .row {
    display: flex;
    flex-direction: row !important;
    align-content: center;
    gap: 20px;
}
}
</style>