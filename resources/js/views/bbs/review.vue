<template>
    <!-- all view랑 reciew-->
    <div class="container">
        <div class="main-contenter">
            <div class="review">
                <div class="review-content mov-review my-5">
                    <div class="proceeding"></div>
                    <h3 class="review-title">이용후기 관리</h3>
                    <div class="tab-nav my-4">
                        <ul>
                            <li class="col-4"><a href="#" @click="setActiveTab('available')" :class="{ 'active': activeTab === 'available' }" title="작성한 이용후기">작성 가능한 이용 후기(<span>{{ filteredAuctions.length }}</span>)</a></li>
                            <li class="col-4"><a href="#" @click="setActiveTab('written')" :class="{ 'active': activeTab === 'written' }" title="작성 가능한 이용후기">작성한 이용후기(<span>{{ reviewsData.length }}</span>)</a></li>
                        </ul>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    </div>
                    <!-- 작성가능한 이용후기-->
                    <div v-if="activeTab === 'available'">
                        <div v-if="filteredAuctions.length > 0">
                            <div class="review-card" v-for="auction in filteredAuctions" :key="auction.id"> 
                                <div class="review-image">
                                    <p class="review-date">3.18 (월)</p>
                                    <img src="../../../img/car_example.png" alt="현대 쏘나타 (DN8)">
                                </div>
                                <div class="review-info">
                                    <div class="popup-menu" v-show="isMenuVisible">
                                        <ul>
                                            <li><button @click="editReview" class="tc-blue">수정</button></li>
                                            <li><button @click="deleteReview" class="tc-red">삭제</button></li>
                                        </ul>
                                    </div>
                                    <h3 class="review-title">{{ auction.car_no }}</h3>
                                    <p class="tc-light-gray">12 삼 4567 | 딜러명</p>
                                    <div class="justify-content-between flex align-items-center">
                                        <p class="tc-light-gray review-price">{{ formattedAmount(auction.final_price) }}</p>
                                        <!--<router-link :to="{ name: 'user.create-review' , params: { id: auction.id } }" class="btn-review">후기작성</router-link>-->
                                        <a class="btn-review" @click="navigateToDetail(auction.id)">후기작성</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            작성 가능한 이용 후기가 없습니다.
                        </div>
                    </div>
                    <!-- 작성한 이용후기-->
                    <div v-if="activeTab === 'written'">
                        <div v-if="reviewsData.length > 0">
                            <div class="review-card02" v-for="review in reviewsData" :key="review">
                                <div class="review-image02">
                                    <p class="review-date">3.18 (월)</p>
                                    <img src="../../../img/car_example.png" alt="현대 쏘나타 (DN8)">
                                </div>
                                <div class="review-info02">
                                    <p class="more-view" @click="toggleMenu(review.id)">moreview</p>
                                    <div class="popup-menu" :id="'toggleMenu' + review.id" style="display : none">
                                        <ul>
                                            <li><button @click="editReview(review.id)" class="tc-blue">수정</button></li>
                                            <li><button @click="deleteReview(review.id)" class="tc-red">삭제</button></li>
                                        </ul>
                                    </div>
                                    <div class="mb-2 justify-content-between flex align-items-center bold-18-font">
                                        <p>현대 소나타 (DN8)</p>
                                        <p class="tc-red">1,000 만원</p>
                                    </div>
                                    <div class="rating">
                                        <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                                            <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                                            <span :class="['star-icon', index <= review.star ? 'filled' : '']"></span>
                                        </label>
                                    </div>
                                    <p>차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            작성한 이용 후기가 없습니다.
                        </div>
                    </div>
                </div>
                <div class="bottom-message" :class="{ 'show': deleteMessageVisible }">
                    삭제되었습니다.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted , computed } from 'vue';
import { useRouter } from 'vue-router';
import { ref } from 'vue';
import useAuctions from "@/composables/auctions";
import { initReviewSystem } from '@/composables/review';

let userId =  ref();
const router = useRouter();
const activeTab = ref('available'); //nav 탭 바
const showDeleteConfirmation = ref(false); // "삭제되었습니다," 띄우기
const isMenuVisible = ref(false);
const { auctionsData, getAuctions } = useAuctions();
const { getUserReview , deleteReviewApi , reviewsData , formattedAmount } = initReviewSystem(); 
const filteredAuctions = computed(() => {
    const reviewedAuctionIds = reviewsData.value.map(review => review.auction_id);
    return auctionsData.value.filter(auction => 
        auction.status === 'done' && 
        auction.bid_id != null &&
        !reviewedAuctionIds.includes(auction.id)
    ); 
});

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
    if (activeTab.value !== tab) { // 탭이 실제로 변경될 때만 메뉴를 숨김
        isMenuVisible.value = false;
    }
    activeTab.value = tab;
}

function editReview(reviewId) {
    if (confirm("수정하시겠습니까?")) {   
        router.push({ name: 'user.edit-review', params: { id: reviewId } });
    }
}

function deleteReview(id) {
    if (confirm("삭제하시겠습니까?")) {   
        if(deleteReviewApi(id)){
            showDeleteMessage();
        }
    }
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

onMounted(async () => {
    await getAuctions(1);
    console.log(auctionsData.value);
    userId = auctionsData.value[0].user_id;
    await getUserReview(userId);
    //console.log(reviewsData.value);
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
.review-image02{
    padding: 10px;
}
.review-image02 img {
    width: 100%;
    height: 11rem;
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
</style>