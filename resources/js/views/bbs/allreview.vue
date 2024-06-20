<template>
  <div class="container">
    <div class="review">
    </div>
    <div class="style-view bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet" v-if="isUser && !showFullView">
      <div class="sheet-content">
        <div class="mt-3" @click.stop="">
          <h5 class="text-center">만족스러운 거래였나요?</h5>
          <div class="btn-group mt-3">
            <router-link :to="{ name: 'user.review' }" class="btn btn-primary tc-wh">후기 남기기</router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="mov-review my-5">
        <div class="apply-top text-start">
          <h3 class="review-title">다른 사람들의 이용후기에요</h3>
          <div class="search-type">
            <input type="text" placeholder="모델명,차량번호,지역">
            <button type="button" class="search-btn">검색</button>
          </div>
        </div>
        <div class="row" >
          <div class="col-md-4 mb-4" v-if="reviewsData.length > 0" v-for="(card, index) in cards" :key="index">
            <div class="cardtype02" @click="navigateToDetail(card.id)">
              <h5 class="text-start process">현대 쏘나타 (DN8)</h5>
              <img src="../../../img/car_example.png" alt="Car">
              <div class="content p-0">
                <div class="mov-sort d-flex flex-lg-row-reverse align-items-start justify-content-between">
                <span class="date tc-light-gray">{{ splitDate(card.date) }}</span>
                <div class="rating m-0">
                  <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                      <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                      <span :class="['star-icon', index <= card.rating ? 'filled' : '']"></span>
                  </label>
                  <span class="d-flex mx-2 rating-score tc-red"></span>
                </div>
              </div>
              <!-- <h5 class="card-title">차량 종류 들어갈 예정:{{ card.title }}</h5>-->
      <!--          <div class="d-sm-flex justify-content-between text-muted">
                  <span class="deilname">담당 딜러 {{ card.dealer }} 님</span>
                  <span class="date">{{ splitDate(card.date) }}</span>
                </div>-->
                <div>
                <p class="text-start card-text">{{ card.text }}</p>
                </div>
                <p class="auction-deadline justify-content-sm-center tc-light-gray">판매가<span>12000만원</span></p>
              </div>
            </div>
          </div>
          <div v-else>
            <div class="complete-car">
                <div class="card my-auction mt-3">
                    <div class="none-complete">
                        <span class="tc-light-gray">아직 작성된 이용후기가 없습니다.</span>
                    </div>
                </div>
            </div>
        </div>
        </div>
        
      </div>
      <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: !reviewPagination.prev }">
            <a class="page-link prev-style" @click="loadPage(reviewPagination.current_page - 1)"></a>
            </li>
            <li v-for="n in reviewPagination.last_page" :key="n" class="page-item" :class="{ active: n === reviewPagination.current_page }">
            <a class="page-link" @click="loadPage(n)">{{ n }}</a>
            </li>
            <li class="page-item next-prev" :class="{ disabled: !reviewPagination.next }">
            <a class="page-link next-style" @click="loadPage(reviewPagination.current_page + 1)"></a>
            </li>
        </ul>
      </nav>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useStore } from "vuex";
import { initReviewSystem } from '@/composables/review';
import { useRouter } from 'vue-router';
import 'swiper/css';
import 'swiper/css/pagination';

const router = useRouter();
const store = useStore();
const user = computed(() => store.getters["auth/user"]);
const isUser = computed(() => user.value?.roles?.includes('user'));
const showBottomSheet = ref(true);
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' });
const { getHomeReview , reviewsData , splitDate, reviewPagination } = initReviewSystem(); 

const cards = ref([]);

const currentPage = ref(1); // 현재 페이지 번호

const totalPages = 3; // Adjust based on the number of cards and pagination logic


const toggleSheet = () => {
  const bottomSheet = document.querySelector('.bottom-sheet');
  if (showBottomSheet.value) {
    bottomSheetStyle.value = { position: 'static', bottom: '-100%' };
  } else {
    bottomSheetStyle.value = { position: 'fixed', bottom: '0px' };
  }
  showBottomSheet.value = !showBottomSheet.value;
};

const inputCardsValue = () => {
  cards.value = reviewsData.value.map(review => ({
    title: review.id,
    dealer: review.dealer.name,
    id: review.id,
    rating: review.star,
    date: review.created_at,
    text: review.content
  }));
}

function navigateToDetail(reviewId) {
  console.log(reviewId);
    router.push({ name: 'user.review-detail', params: { id: reviewId } });
}

async function loadPage(page) { // 페이지 로드
  if (page < 1 || page > reviewPagination.value.last_page) return;
  currentPage.value = page;
  await getHomeReview(page); // getHomeReview 호출을 대기
  inputCardsValue();
  window.scrollTo(0,0);
}


onMounted(async () => {
  await getHomeReview();
  inputCardsValue();
});


onUnmounted(() => {
});
</script>
<style scoped>
.btn-apply::after {
  margin-top: 0 !important;
  margin-bottom: 2px;
}
.content-container {
  display: flex;
  flex-direction: column;
}
.card-text {
  word-wrap: break-word;
  word-break: break-word;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  max-height: 4.5em;
  line-height: 1.5em;
  white-space: normal;
}
.bottom-sheet::before {
  content: "";
  position: absolute;
  left: 50%;
  top: unset !important;
  margin-top: 10px;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background-color: #dbdbdb;
}
.rating label {
  margin-left: 5px;
}
.rating__label:first-child {
  margin-left: 0px;
}
.sub-style {
  height: 155px;
}
.rating__label .star-icon {
  width: 17px !important;
  height: 17px !important;
}
.over-text {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  width: auto;
}
.swiper {
  width: 100%;
  height: auto;
}
@media (max-width: 640px) {
  .review-title {
    margin-left: 0px !important;
  }
}
@media (max-width: 640px) {
  .mov-review > .row {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    flex-wrap: wrap !important;
    gap: 20px;
    justify-content: left;
}
  .search-type {
    margin-left: 13px;
    width: 94%;
  }
  .review-title {
    margin-left: 17px;
  }
  .apply-top {
    display: flex;
    margin-bottom: 30px;
    justify-content: flex-end;
    align-items: flex-start;
    flex-direction: column-reverse;
    flex-wrap: nowrap;
    padding: 0px;
    gap: 20px;
    margin-left: 10px;
  }
  .my-5 {
    margin-top: 1rem !important;
  }
  .search-type input {
    width: 100% !important;
  }
}

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

.register-content {
display: flex;
justify-content: space-between;
gap: 20px;
}


.grid-container {
display: flex;
gap: 10px;
width: calc(200%); /* Ensure the width is enough to cover the animation */
animation: scroll 20s linear infinite;
}

@keyframes scroll {
0% {
  transform: translateX(0);
}
100% {
  transform: translateX(-50%);
}
}

.cardtype02 {
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 24px 20px;
  background-color: #fff;
  width: 250px;
  height: 100%;
  box-shadow: 0 0 6px 0 rgba(27, 50, 142, 0.2);
  overflow: hidden;
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  box-sizing: border-box;
}
.cardtype02 img {
width: 100%;
height: 90px;
object-fit: cover;
margin: 14px 0 14px;
border-radius: 6px;

}

.content {
padding: 15px;
}

.content h2 {
font-size: 18px;
margin: 10px 0;
}

.rating {
color: #f39c12;
margin: 10px 0;
}

.date {
font-size: 14px;
color: #999;
}

.content p {
font-size: 14px;
color: #333;
margin: 10px 0;
}

.price {
font-size: 16px;
font-weight: bold;
color: #333;
margin: 10px 0;
}
.auction-deadline {
    width: 100%;
    height: 40px;
    background-color: #f5f5f6;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: space-between !important;
    gap: 5px;
    border-radius: 6px;
    padding: 25px;
    margin-top: auto; 
}
.cardtype02{
  width: 100% !important;
}
.row {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  gap: 20px;
}
@media (max-width: 650px){
.content {
   margin-top: 0px !important;
}
}
/* 각 카드의 기본 크기 설정 */
.col-md-4 {
  flex: 0 0 calc(25% - 20px); /* 4개의 카드가 한 줄에 나란히 */
  box-sizing: border-box;
}

/* 작은 화면에서 각 카드의 크기 조정 */
@media (max-width: 1200px) {
  .col-md-4 {
    flex: 0 0 calc(33.3333% - 20px); /* 3개의 카드가 한 줄에 나란히 */
  }
}
@media (max-width: 480px) {
  .col-md-4 {
    flex: 0 0 auto !important;
    width: 100% !important;
}
}
@media (max-width: 513px) {
  .mov-sort{
    flex-direction: column !important;
  }
}
@media (max-width: 992px) {
  .col-md-4 {
    flex: 0 0 calc(51% - 20px);
}
}
@media (max-width: 767px){
.col-md-4 {
    flex: 0 0 calc(53% - 20px);
}
}
@media (max-width: 768px) {
  .row {
    gap: 0px !important;
  }
}
</style>

