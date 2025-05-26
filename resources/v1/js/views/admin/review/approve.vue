<template>
    <!--
        * mov-text : 모바일 일때 보이는 뷰
        * web-text: : 웹 화면에서 보이는뷰
    -->
    <h4><span class="admin-icon admin-icon-menu"></span>후기 관리</h4>
    <div class="container my-5">
    <div class="container mov-wide">
        <form @submit.prevent="submitForm" v-for="review in reviewData" :key="review">
            <div class="create-review">
                <div class="left-container">
                    <div class="container-img">
                        <div class="left-img">
                            <div v-if="!isMobileView" class="img-container">
                                <div class="img-wrapper">
                                    <img :src="car_thumbnail" alt="차량 사진" class="mb-2">
                                </div>
                                </div>
                            </div>
                            <div v-if="isMobileView" class="img-container">
                                <div class="img-wrapper">
                                <img :src="car_thumbnail" alt="차량 사진" class="mb-2">
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
                    <div class="card-body">
                        <p class="text-secondary opacity-50">차량명</p>
                        <input v-model="car_name" class="input-dis form-control text-secondary opacity-50" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary opacity-50">차량번호</p>
                        <input v-model="car_no" class="input-dis form-control text-secondary opacity-50" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary opacity-50">매물번호</p>
                        <input v-model="item_id" class="input-dis form-control text-secondary opacity-50" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary opacity-50">딜러명</p>
                        <input v-model="dealer_name" class="input-dis form-control text-secondary opacity-50" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary opacity-50">판매가</p>
                        <input v-model="price" class="input-dis form-control text-secondary opacity-50" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary opacity-50">별점</p>
                        <div class="wrap">
                            <!-- <select class="form-select" :v-model="rv.star" id="starSelect" @change="changeStar($event)">
                                <option value="1">1점</option>
                                <option value="2">2점</option>
                                <option value="3">3점</option>
                                <option value="4">4점</option>
                                <option value="5">5점</option>
                            </select> -->

                            <input v-model="star" class="input-dis form-control text-secondary opacity-50" readonly/>

                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary opacity-50">후기</p>
                        <textarea class="custom-textarea mt-2" rows="2" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." id="content" v-model="rv.content"></textarea>
                    </div>
                    <button class="mt-5 btn btn-primary w-100">저장</button>
                </div>
                <div class="btn-group mt-3">
                </div>
            </div>
        </form>
    </div>
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
const car_no = ref("");
const car_name = ref("");
const item_id = ref("");
const dealer_name = ref("");
const price = ref("");
const car_thumbnail = ref("");
const star = ref("");
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

// function changeStar(event) {
//   rv.star = event.target.value;
// }

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
    
    console.log('responseDATA',response);

    reviewData.value = [response];
    //carInfo.value = await getCarInfo(response.auction.owner_name, response.auction.car_no);
    await nextTick();
    //setInitialStarRating(response.star);
    initReviewSystem();
    watchEffect(() => {
        car_no.value = response.auction.car_no,
        car_name.value = response.auction.car_maker + " " + response.auction.car_model + " " + response.auction.car_model_sub,
        price.value = amtComma(response.auction.win_bid?.price || response.auction.final_price),
        dealer_name.value = response.dealer.name,
        // item_id.value = response.auction.unique_number,
        item_id.value = response.auction.id,
        rv.auction_id = response.auction_id;
        rv.dealer_id = response.dealer.id;
        // document.getElementById("starSelect").value = response.star;
        // rv.star = response.star;
        rv.user_id = response.user_id;
        rv.content = response.content;
        star.value = response.star;
        car_thumbnail.value = response.auction.car_thumbnail;
    })

});
onBeforeUnmount(() => {
  window.removeEventListener('resize', checkScreenWidth);
}); 
</script>
<style scoped>
@media screen and (max-width: 767px) {
    .left-container{
        padding: 0px;
    }
    .container{
        --bs-gutter-x: 0rem !important;
        max-width: none;
    }
    .img-container{
        height: auto !important;
    }
}
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
/* 이미지 스타일 */
.img-wrapper {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
}
.img-container {
  width: 100%; 
  height: 330px;
  display: flex; 
  justify-content: center;
  align-items: center;
  overflow: hidden; 
}
.img-wrapper img {
  width: 100%; 
  height: 100%; 
  object-fit: cover; 
}
</style>