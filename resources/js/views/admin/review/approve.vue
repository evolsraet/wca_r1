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
                    <div class="card-body">
                        <p class="tc-light-gray">차량명(추가예정)</p>
                        <input class="input-dis form-control tc-light-gray" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="tc-light-gray">차량번호</p>
                        <input v-model="car_no" class="input-dis form-control tc-light-gray" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="tc-light-gray">매물번호(추가예정)</p>
                        <input class="input-dis form-control tc-light-gray" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="tc-light-gray">딜러명</p>
                        <input v-model="dealer_name" class="input-dis form-control tc-light-gray" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="tc-light-gray">판매가</p>
                        <input v-model="price" class="input-dis form-control tc-light-gray" readonly/>
                    </div>
                    <div class="card-body">
                        <p class="tc-light-gray">별점</p>
                        <div class="wrap">
                            <select class="form-select" :v-model="rv.star" id="starSelect" @change="changeStar($event)">
                                <option value="1">1점</option>
                                <option value="2">2점</option>
                                <option value="3">3점</option>
                                <option value="4">4점</option>
                                <option value="5">5점</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="tc-light-gray">후기</p>
                        <textarea class="custom-textarea mt-2" rows="2" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." id="content" v-model="rv.content"></textarea>
                    </div>
                    <button class="mt-5 btn btn-primary w-100"> 수정 완료 </button>
                </div>
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
const car_no = ref("");
const dealer_name = ref("");
const price = ref("");

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

function changeStar(event) {
  rv.star = event.target.value;
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
    //setInitialStarRating(response.star);
    initReviewSystem();
    watchEffect(() => {
        car_no.value = response.auction.car_no,
        price.value = amtComma(response.auction.win_bid.price),
        dealer_name.value = response.dealer.name,
        rv.auction_id = response.auction_id;
        rv.dealer_id = response.dealer.id;
        document.getElementById("starSelect").value = response.star;
        rv.user_id = response.user_id;
        rv.content = response.content;
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