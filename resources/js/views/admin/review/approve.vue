<template>
    <!--
        * mov-text : 모바일 일때 보이는 뷰
        * web-text: : 웹 화면에서 보이는뷰
    -->
    <div class="container mov-wide">
        <form @submit.prevent="submitForm" v-for="review in reviewData" :key="review">
            <div class="create-review">
                <div class="left-container">
                    <div class="container-img mov-info02">
                        <div class="left-img">
                            <div v-if="!isMobileView" class="d-flex flex-row">
                                <div class="w-50">
                                    <div class="card-img-top-ty02 review-img"></div>
                                </div>
                                <div class="w-50 d-flex flex-column">
                                    <div class="card-img-top-ty02 h-50 left-image background-auto"></div>
                                    <div class="card-img-top-ty02 h-50 right-image background-auto"></div>
                                </div>
                            </div>
                            <div v-if="isMobileView">
                                <div class="card-img-top-ty02"></div>
                            </div>
                        </div>
                        <div class="web-text">
                            <div class="detail detail1"></div>
                            <div class="detail detail2"></div>
                        </div>
                    </div>
                    <div class="mx-2 enter-view align-items-baseline mt-3 bold-18-font">
                        <input type="hidden" id="user_id" :v-model="rv.user_id">
                        <input type="hidden" id="auction_id" :v-model="rv.auction_id">
                        <input type="hidden" id="dealer_id" :v-model="rv.dealer_id">
                    </div>
                    <div class="mx-2">
                        <h5>더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                        <p class="card-title">차량 번호: {{review.auction.car_no}}</p>
                    </div>
                    <div class="mx-2 tc-light-gray ">
                        <p> 2020년 / 2.4km / 무사고</p>
                        <p>현대 쏘나타 (DN8)</p>
                        <br>
                        <p>매물번호 / 564514</p>
                        <p>딜 러 명 / {{review.dealer.name}}</p>
                    </div>
                    <p class="mt-4 auction-deadline tc-light-gray">판매가<span>{{ amtComma(review.auction.final_price) }}</span></p>
                    <div class="card-body">
                        <p class="tc-light-gray">별점</p>
                        <input v-model="rv.star" class="form-control bg-secondary bg-opacity-10" />
                    </div>
                    <div class="card-body">
                        <p class="tc-light-gray">후기</p>
                        <textarea class="custom-textarea mt-2 h-25" rows="2" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." id="content" v-model="rv.content">{{ review.content }}</textarea>
                    </div>
                    <button class="mt-5 btn btn-primary w-100"> 수정 완료 </button>
                </div>
                <!--  <div class="right-container">
                        <div class="style-view bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet">
                        <div class="sheet-content">
                            <div class="mt-3"  @click.stop="">
                                <h5 calss="text-center">거래는 어떠셨나요?</h5>
                                <div class="wrap">
                                    <input type="hidden" :v-model="rv.star">
                                    <div class="rating">
                                        <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                                            <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                                            <span :class="['star-icon', index <= review.star ? 'filled' : '']"></span>
                                            <span class="rating-description" :value="index"></span>
                                        </label>
                                        <span class="d-flex mx-2 rating-score tc-red"></span>
                                    </div>
                                </div>
                                <textarea class="custom-textarea mt-2" rows="4" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." id="content" v-model="rv.content">{{ review.content }}</textarea>   -->
                <div class="btn-group mt-3">
                </div>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        name : 'params',
    }
</script>
<script setup>
import { ref, onMounted , nextTick , reactive , watchEffect, onBeforeUnmount } from 'vue';
import { useRoute } from 'vue-router'; 
import { initReviewSystem } from '@/composables/review'; // 별점 js
import { cmmn } from '@/hooks/cmmn';
const { amtComma } = cmmn();
const route = useRoute();
const reviewId = parseInt(route.params.id); 
const showBottomSheet = ref(true); //바텀 시트
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' }); //바텀 시트 스타일
const { getUserReviewInfo , editReview , getCarInfo , setInitialStarRating } = initReviewSystem(); 
let reviewData = ref();
const carInfo = ref();
const isMobileView = ref(window.innerWidth <= 640);
const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };
//바텀 시트 토글시 스타일변경
function toggleSheet() {
    const bottomSheet = document.querySelector('.bottom-sheet');   
    if (showBottomSheet.value) {
        bottomSheetStyle.value = { position: 'static', bottom: '-100%' };
    } else {
        bottomSheetStyle.value = { position: 'fixed', bottom: '0px' };
    }
    showBottomSheet.value = !showBottomSheet.value;
}


function submitForm(){
    editReview(reviewId, rv, 'admin');
}

const rv = reactive({
    user_id:route.params.id,
    auction_id:'',
    dealer_id:'',
    star:'',
    content:'',
})

onMounted(async () => {
    window.addEventListener('resize', checkScreenWidth);
    checkScreenWidth();
    const response = await getUserReviewInfo(reviewId);
    reviewData.value = [response];
    //carInfo.value = await getCarInfo(response.auction.owner_name, response.auction.car_no);
    await nextTick();
    setInitialStarRating(response.star);
    initReviewSystem();
    watchEffect(() => {
        rv.auction_id = response.auction_id,
        rv.dealer_id = response.dealer_id,
        rv.star = response.star;
        rv.user_id = response.user_id,
        rv.content = response.content
    })

});
onBeforeUnmount(() => {
  window.removeEventListener('resize', checkScreenWidth);
}); 
</script>
<style scoped>
.bottom-sheet::before {
    content: "";
    position: absolute;
    margin-top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: #dbdbdb;
}
</style>