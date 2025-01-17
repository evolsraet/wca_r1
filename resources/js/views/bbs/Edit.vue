<template>
    <!--
        * mov-text : 모바일 일때 보이는 뷰
        * web-text: : 웹 화면에서 보이는뷰
    --> 
    <div>
        <form @submit.prevent="submitForm" v-for="review in reviewData" :key="review">
            <div class="container mov-wide">
                    <div class="container-img">
                        <h5 class="my-3">후기 작성</h5>
                        <div class="left-img">
                            <div v-if ="!isMobileView" class="d-flex flex-row">

                                    <div v-if="review.auction.car_thumbnail">
                                      <img :src="review.auction.car_thumbnail" alt="Car Image">
                                    </div>
                                    <div v-else>
                                        <div class="w-50">
                                          <div class="card-img-top-ty02 review-img"></div>
                                        </div>
                                        <div class="w-50 d-flex flex-column">
                                            <div class="card-img-top-ty02 h-50 left-image background-auto"></div>
                                            <div class="card-img-top-ty02 h-50 right-image background-auto"></div>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                                <div v-if = "isMobileView">
                                    <div class="card-img-top-ty02"></div>
                                </div>
                            </div>
                    <div class="mx-2 enter-view align-items-baseline mt-3 bold-18-font">
                        <!--                        <input type="hidden" id="user_id" :value="review.auction.user_id">
                        <input type="hidden" id="auction_id" :value="review.auction.id">
                        <input type="hidden" id="dealer_id" :value="review.auction.win_bid.user_id">-->
                        <input type="hidden" id="user_id" :v-model="rv.user_id">
                        <input type="hidden" id="auction_id" :v-model="rv.auction_id">
                        <input type="hidden" id="dealer_id" :v-model="rv.dealer_id">
                      <!-- <p class="card-title fs-5"><span class="blue-box">무사고</span>{{review.auction.car_no}}</p>-->
                      <h5>{{ review.auction.car_model ? review.auction.car_model +' '+ review.auction.car_model_sub +' '+ review.auction.car_fuel + '('+ review.auction.car_no +')' : '더 뉴 그랜저 IG 2.5 가솔린 르블랑' }}</h5>
                    </div>
                  <!-- <p class="mt-2 card-text text-secondary opacity-50 fs-5 mov-text">매물번호 <span class="process ms-2">(자동지정)</span></p>
                    <div class="enter-view mt-2">
                        <p class="card-text text-secondary opacity-50 fs-5 mov-text">딜러명<span class="process ms-3">{{review.dealer.name}} 딜러</span></p>
                        <p class="card-text text-secondary opacity-50 fs-5 web-text">12 삼 4567</p>
                    </div>--> 
                    <div class="mx-2 text-secondary opacity-50 my-4">
                        <p>{{ review.auction.car_year ? review.auction.car_year : '2020' }}년 | {{ review.auction.car_km ? review.auction.car_km : '2.4' }}km | 무사고</p>
                        <p>{{ review.auction.car_maker ? review.auction.car_maker + review.auction.car_model : '현대 쏘나타' }} ({{ review.auction.car_grade ? review.auction.car_grade : 'DN8' }})</p>
                        <br>
                        <p>매물번호 / {{ review.auction.id }}</p>
                        <p>딜 러 명 / {{review.dealer.name}}</p>
                        </div>
                  <!-- <p class="mt-5 auction-deadline justify-content-sm-center">경매 마감일<span>{{ formatDateAndTime(review.auction.final_at )}}</span></p>-->
                   <p class="mt-4 auction-deadline justify-content-sm-center text-secondary opacity-50">판매가<span>{{ amtComma(review.auction.win_bid.price) }}</span></p>
                   <div class="container card-style p-0 mt-5">
                    </div>
                </div>
                <div class="right-container">
                    <bottom-sheet initial="half" :dismissable="true">
                        <div class="sheet-content p-0">
                            <div class="mt-3 mb-4" @click.stop="">
                                <h5 calss="text-center">거래는 어떠셨나요?</h5>
                                <div class="wrap">
                                    <input type="hidden" :v-model="rv.star">
                                    <div class="rating my-1">
                                        <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                                            <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                                            <span :class="['star-icon', index <= review.star ? 'filled' : '']"></span>
                                            <span class="rating-description" :value="index"></span>
                                        </label>
                                    </div>
                                    <span class="d-flex mx-2 rating-score tc-red"></span>
                                </div>
                                <textarea class="custom-textarea mt-2" rows="4" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." id="content" v-model="rv.content">{{ review.content }}</textarea>
                                <div class="btn-group mt-3">
                                    <button class="btn btn-primary"> 수정 완료 </button>
                                </div>
                            </div>
                        </div>
                    </bottom-sheet>
                </div>
            </div>
        </form>
        <AlarmModal ref="alarmModal" />
    </div>
    <Footer />
</template>

<script>
export default {
    name: 'params',
}
</script>

<script setup>
import { ref, onMounted, nextTick, reactive , watchEffect,onBeforeUnmount } from 'vue';
import { useRoute } from 'vue-router';
import { initReviewSystem } from '@/composables/review'; // 별점 js
import BottomSheet from '@/views/bottomsheet/BottomSheet.vue';
import AlarmModal from '@/views/modal/AlarmModal.vue';
import Footer from "@/views/layout/footer.vue"
import { cmmn } from '@/hooks/cmmn';
const { amtComma } = cmmn();

const route = useRoute();
const reviewId = parseInt(route.params.id);
const showBottomSheet = ref(true); //바텀 시트
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' }); //바텀 시트 스타일
const { getUserReviewInfo, editReview, setInitialStarRating , getCarInfo } = initReviewSystem();
let reviewData = ref();
const alarmModal = ref(null);
const carInfo = ref();
const { formatDateAndTime } = cmmn();
const isMobileView = ref(window.innerWidth <= 640);
const openAlarmModal = () => {
console.log("openAlarmModal called");
if (alarmModal.value) {
  alarmModal.value.openModal();
}
};

const rv = reactive({
    user_id:route.params.id,
    auction_id:'',
    dealer_id:'',
    star:'',
    content:'',
})

function submitForm(){
    editReview(reviewId, rv , 'user');
}
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
const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };
onMounted(async () => {
    window.addEventListener('resize', checkScreenWidth);
    checkScreenWidth();
    const response = await getUserReviewInfo(reviewId);
    console.log(response);
    reviewData.value = [response];
    carInfo.value = await getCarInfo(response.auction.owner_name, response.auction.car_no);
    console.log(carInfo.value);
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
@media (max-width:451px) {
        .rating{
        display: flex;
        justify-content: space-evenly;
        flex-direction: row;
    }
}
.mov-wide{
    width:auto !important;
}
.review-img{
    height: 377px !important;
}
@media (min-width: 992px) {
            .container.mov-wide {
                display: flex;
                flex-direction: row;
                align-items: stretch;
                justify-content: space-between;
                gap: 10px; 
            }

            .container.mov-wide > div:first-child {
                flex: 1.5;
            }

            .sheet {
                position: static; 
                flex: 0.5;
                display: flex;
                flex-direction: column;
                max-height: none;
                border-radius: 0; 
                overflow: visible;
                height: auto !important;
                margin-top: 55px;
            }
            .header{
                pointer-events: none; 
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
.card {
            border: 1px solid #ccc;
            padding: 16px;
            margin: 16px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
        }
        .col {
            flex: 1 1 25%; 
            box-sizing: border-box; 
            padding: 8px;
        }
        .item-label {
            font-weight: bold;
        }
        .item-value {
            margin-top: 4px;
        }

        @media (max-width: 600px) {
            .row {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); 
                gap: 8px;
            }
            .col {
                flex: 1 1 100%; 
            }
            .card-style .col-line:not(:last-child)::after {
            display: none;
            }
            .col {
                flex: 1 1 50%;
                border-bottom: 1px solid #dddddd; 
                padding-bottom: 16px;
                margin-bottom: 16px;
            }
            .col:last-child {
                border-bottom: none; 
                margin-bottom: 0;
            }
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
    padding: 35px;
}
</style>
