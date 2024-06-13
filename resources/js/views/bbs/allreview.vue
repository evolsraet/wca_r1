<template>
  <div class="main-contenter">
    <div class="review" v-if="!showFullView">
      <div class="review-content mov-review my-5">
        <div class="justify-content-between flex align-items-center p-2">
          <h3 class="review-title">이용후기 둘러보기</h3>
          <button class="btn-apply mt-0" @click="toggleView">전체보기</button>
        </div>
        <div class="review-swiper">
          <swiper :slidesPerView="slidesPerView" :spaceBetween="30" :pagination="{ clickable: true }" class="mySwiper">
            <swiper-slide v-for="(slide, index) in slides" :key="index">
              <div class="review-card">
                <div class="car-imges"></div>
                <div class="card-body sub-style">
                  <h5 class="card-title">차량 종류 들어갈 예정: {{ slide.title }}</h5>
                  <div class="rating">
                    <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                      <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                      <span :class="['star-icon', index <= slide.rating ? 'filled' : '']"></span>
                    </label>
                    <div class="d-sm-flex justify-content-between text-muted">
                      <p class="card-text over-text process">{{ slide.text }}</p>
                      <span class="tc-light-gray">더보기</span>
                    </div>
                  </div>
                </div>
              </div>
            </swiper-slide>
          </swiper>
        </div>
        <div class="content-container mt-4 p-3">
          <div class="main-text">더 다양한 이용 후기를 확인하세요!</div>
          <div class="tags p-2">
            <span class="tag" v-for="(tag, index) in tags" :key="index">#{{ tag }}</span>
          </div>
        </div>
      </div>
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
    <div class="review" v-if="showFullView">
      <div class="review-content mov-review my-5">
        <div class="apply-top text-start">
          <h3 class="review-title">다른 사람들의 이용후기에요</h3>
          <div class="search-type">
            <input type="text" placeholder="모델명,차량번호,지역">
            <button type="button" class="search-btn">검색</button>
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3 mb-4" v-if="reviewsData.length > 0" v-for="(card, index) in cards" :key="index">
            <div class="card" @click="navigateToDetail(card.id)">
              <div class="car-imges"></div>
              <div class="card-body">
                <h5 class="card-title">차량 종류 들어갈 예정:{{ card.title }}</h5>
                <div class="rating">
                  <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                      <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                      <span :class="['star-icon', index <= card.rating ? 'filled' : '']"></span>
                  </label>
                  <span class="d-flex mx-2 rating-score tc-red"></span>
                </div>
                <div class="d-sm-flex justify-content-between text-muted">
                  <span class="deilname">담당 딜러 {{ card.dealer }} 님</span>
                  <span class="date">{{ splitDate(card.date) }}</span>
                </div>
                <p class="card-text">{{ card.text }}</p>
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
import { Swiper, SwiperSlide } from 'swiper/vue';
import { initReviewSystem } from '@/composables/review';
import { useRouter } from 'vue-router';
import 'swiper/css';
import 'swiper/css/pagination';

const showFullView = ref(false);
const router = useRouter();
const store = useStore();
const user = computed(() => store.getters["auth/user"]);
const isUser = computed(() => user.value?.roles?.includes('user'));
const test = [];
const showBottomSheet = ref(true);
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' });
const { getHomeReview , reviewsData , splitDate, reviewPagination } = initReviewSystem(); 
const slidesPerView = ref(1); // 슬라이드를 한 번에 하나만 보이도록 설정

const slides = ref([]);

const tags = ['자유문의1', '자유문의2', '댓글', '삭제'];

const cards = ref([]);

const currentPage = ref(1); // 현재 페이지 번호

const totalPages = 3; // Adjust based on the number of cards and pagination logic

const toggleView = () => {
  showFullView.value = !showFullView.value;
};

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
  slides.value = reviewsData.value.slice(0,4).map(review => ({
    title: review.id,
    id: review.id,
    rating: review.star,
    text: review.content
  }));
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
  console.log(page);
  console.log(reviewPagination.value.last_page)
  if (page < 1 || page > reviewPagination.value.last_page) return;
  currentPage.value = page;
  await getHomeReview(page); // getHomeReview 호출을 대기
  inputCardsValue();
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
  width: 20px;
  height: 20px;
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
    flex-direction: column;
    align-content: center;
    flex-wrap: wrap !important;
    gap: 20px;
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
@media (max-width: 991px) and (min-width: 768px) {
  .container {
    max-width: none !important;
  }
}
</style>

