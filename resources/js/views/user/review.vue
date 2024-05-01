<template>
    <div class="container">
        <div class="main-contenter">
            <div class="review">
                <div class="review-content mov-review my-5">
                    <div class="proceeding"></div>
                    <h3 class="review-title">이용후기 관리</h3>
                    <div class="tab-nav my-4">
                        <ul>
                            <li class="col-4"><a href="#" @click="setActiveTab('available')" :class="{ 'active': activeTab === 'available' }" title="작성한 이용후기">작성 가능한 이용 후기(2)</a></li>
                            <li class="col-4"><a href="#" @click="setActiveTab('written')" :class="{ 'active': activeTab === 'written' }" title="작성 가능한 이용후기">작성한 이용후기(3)</a></li>
                        </ul>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    </div>
                    <!-- 작성가능한 이용후기-->  
                    <div class="review-card" v-if="activeTab === 'available'">
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
                            <h3 class="review-title">현대 쏘나타 (DN8)</h3>
                            <p>12 삼 4567 | 딜러명</p>
                            <div class="justify-content-between flex align-items-center">
                            <p class="review-price">1,000 만원</p>
                            <router-link :to="{ name: 'home' }" class="btn-review">후기작성</router-link>
                        </div>
                    </div>
                </div>
                <!-- 작성한 이용후기-->
                    <div class="review-card02"  v-if="activeTab === 'written'">
                        <div class="review-image02">
                            <p class="review-date">3.18 (월)</p>
                            <img src="../../../img/car_example.png" alt="현대 쏘나타 (DN8)">
                        </div>
                        <div class="review-info02">
                            <p class="more-view" @click="toggleMenu">moreview</p>
                            <div class="popup-menu" v-show="isMenuVisible">
                                    <ul>
                                        <li><button @click="editReview" class="tc-blue">수정</button></li>
                                        <li><button @click="deleteReview" class="tc-red">삭제</button></li>
                                    </ul>
                                </div>
                            <div class="mb-2 justify-content-between flex align-items-center bold-18-font">
                            <p >현대 소나타 (DN8)</p>
                            <p class="tc-red">1,000 만원</p>
                            </div>
                            <div class="rating">
                                <div v-for="index in rating" :key="'filled-' + index" class="star filled-star"></div>
                                <div v-for="index in 5 - rating" :key="'empty-' + index" class="star empty-star"></div>
                             </div>
                            <p>차갑아서 보다 안 아닐 그럽시다 다급하다 떨어지어무슨 절망 아닌 자기에 달려가아 누구에 고스톱은 발생한가.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
import { ref } from 'vue';
import { useReview } from '@/composables/review';
const { rating, setRating } = useReview();
const activeTab = ref('available');
const isMenuVisible = ref(false);
function toggleMenu() {
    isMenuVisible.value = !isMenuVisible.value;
}
// ... 더보기 누르면 나오는 탭
function setActiveTab(tab) {
    if (activeTab.value !== tab) { // 탭이 실제로 변경될 때만 메뉴를 숨김
        isMenuVisible.value = false;
    }
    activeTab.value = tab;
}
function editReview() {
    alert("수정하시겠습니까?");
}

function deleteReview() {
    alert("삭제되었습니다");
}
</script>
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
    height: auto;
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



.more-view {
    position: absolute; 
    top: 10px; 
    right: 10px;
    display: inline-block; 
    width: 20px; 
    height: 20px; 
    background-image: url('../../../img/moreview.png');
    background-size: cover;
    background-position: center; 
    background-repeat: no-repeat; 
    font-size: 0;
    cursor: pointer;
}

.flex{
    display: flex;
}
.review-card {
    position: relative; 
    display: flex;
    background-color: white;
    border-bottom: 2px solid #ddd;
    overflow: hidden;
    margin: 10px;
    align-items: center;
    padding-bottom: 20px;
    gap: 20px;
}
.review-card02{
    position: relative;
    background-color: white;
    border-bottom: 2px solid #ddd;
    overflow: hidden;
    margin: 10px;
    align-items: center;
    padding-bottom: 20px;
    gap: 20px;
    flex-direction: column;
}
.more-view {
    position: absolute; /* 절대 위치 설정 */
    top: 10px; /* 상단에서 10px */
    right: 10px; /* 오른쪽에서 10px */
    display: inline-block; 
    width: 20px; 
    height: 20px; 
    background-image: url('../../../img/moreview.png');
    background-size: cover;
    background-position: center; 
    background-repeat: no-repeat; 
    font-size: 0; /* 텍스트 숨김 */
    cursor: pointer;
}


.review-image img {
    width: 150px;
    height: 150px;
    display: block;
    border-right: 1px solid #ddd;
    object-fit: cover; 
    object-position: center; 
    border-radius: 6px;
}

.review-info {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    gap: 10px;
    margin-top: 20px;
}

.review-date, .review-description, .review-price {
    margin: 5px 0;
}

.review-title {
    margin: 5px 0;
    font-size: 1.2em;
    color: #333;
}

.btn-review {
    position: relative;
    color: red !important;
    border: none;
    cursor: pointer;
    display: inline-flex;
    line-height: 20px;
}

</style>