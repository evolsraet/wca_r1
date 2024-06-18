<template>
    <!--
        * mov-text : 모바일 일때 보이는 뷰
        * web-text: : 웹 화면에서 보이는뷰
    --> 
    <div class="container">
        <form>
            <div class="container mov-wide" v-for="review in reviewsData" :key="review">
                    <div class="left-container">
                        <div class="container-img">
                            <div class="left-img">
                                <div v-if ="!isMobileView" class="d-flex flex-row">
                                    <div class="w-50">
                                        <div class="card-img-top-ty02 review-img"></div>
                                    </div>
                                    <div class="w-50 d-flex flex-column">
                                        <div class="card-img-top-ty02 h-50 left-image background-auto"></div>
                                        <div class="card-img-top-ty02 h-50 right-image background-auto"></div>
                                    </div>
                                </div>
                                <div v-if = "isMobileView">
                                    <div class="card-img-top-ty02"></div>
                                </div>
                            </div>
                            <div class="web-text">
                                <div class="detail detail1"></div>
                                <div class="detail detail2"></div>
                            </div>
                        </div>
                        <div class="mx-2 enter-view align-items-baseline mt-3 bold-18-font">  
                            <h5>더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                            </div>
                            <div class="mx-2 tc-light-gray my-4">
                            <p> {{ carInfo.year }}년 / 2.4km / 무사고</p>
                            <p>현대 쏘나타 (DN8)</p>
                            <br>
                            <p>매물번호 / 564514</p>
                            <p>딜 러 명 / {{review.auction.dealer_name}}</p>
                        </div>
                        <p class="mt-4 auction-deadline justify-content-sm-center tc-light-gray">판매가<span>{{ amtComma(review.auction.final_price) }}</span></p>
                    </div>
                    <div class="right-container">
                        <bottom-sheet initial="half" :dismissable="true" class="mt-2">
                        <div class="sheet-content p-0">
                            <div class="mt-3 mb-5"  @click.stop="">
                                <h5 calss="text-center">거래는 어떠셨나요?</h5>
                                <div class="wrap">
                                    <div class="rating my-3">
                                        <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                                            <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                                            <span :class="['star-icon', index <= review.star ? 'filled' : '']"></span>
                                            <span class="rating-description" :value="index"></span>
                                        </label>
                                        <span class="d-flex mx-2 rating-score tc-red"></span>
                                    </div>
                                </div>
                                <textarea class="custom-textarea mt-2" rows="8" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." readonly>{{ review.content }}</textarea>
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
        name : 'params',
    }
</script>
<script setup>
import { ref, onMounted, nextTick,onBeforeUnmount } from 'vue';
import { routerViewLocationKey, useRoute } from 'vue-router'; 
import { initReviewSystem } from '@/composables/review'; // 별점 js
import BottomSheet from '@/views/bottomsheet/BottomSheet.vue'
import AlarmModal from '@/views/modal/AlarmModal.vue';
import Footer from "@/views/layout/footer.vue"
import { cmmn } from '@/hooks/cmmn';
const { amtComma } = cmmn();
const isMobileView = ref(window.innerWidth <= 640);
const route = useRoute();
const reviewId = parseInt(route.params.id); 
const showBottomSheet = ref(true); //바텀 시트
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' }); //바텀 시트 스타일
const { getUserReviewInfo , getCarInfo , setInitialStarRating} = initReviewSystem(); 
let reviewsData = ref();
const carInfo = ref();
const alarmModal = ref(null);

const openAlarmModal = () => {
console.log("openAlarmModal called");
if (alarmModal.value) {
  alarmModal.value.openModal();
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
    reviewsData.value = [response]; 
    carInfo.value = await getCarInfo(response.auction.owner_name, response.auction.car_no);
    await nextTick();
    setInitialStarRating(response.star);
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
            flex: 1 1 25%; /* 기본적으로 각 항목이 차지할 넓이 설정 */
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
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); /* 작은 화면에서 격자 배치 */
                gap: 8px;
            }
            .col {
                flex: 1 1 100%; /* 작은 화면에서는 각 항목이 전체 너비를 차지 */
            }
            .card-style .col-line:not(:last-child)::after {
            display: none;
            }
            .col {
                flex: 1 1 50%; /* 작은 화면에서는 각 항목이 전체 너비를 차지 */
                border-bottom: 1px solid #dddddd; /* 작은 화면에서 아래쪽에 선 추가 */
                padding-bottom: 16px;
                margin-bottom: 16px;
            }
            .col:last-child {
                border-bottom: none; /* 마지막 항목은 아래쪽 선을 제거 */
                margin-bottom: 0;
            }
        }
        @media (min-width: 1200px) {
  .container-xl, .container-lg, .container-md, .container-sm, .container {
    max-width: 1140px;
  }
}

.mov-wide{
    width:auto !important;
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
</style>