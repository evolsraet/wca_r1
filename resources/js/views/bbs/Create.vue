<template>
    <!--
        * mov-text : 모바일 일때 보이는 뷰
        * web-text: : 웹 화면에서 보이는뷰
    --> 
    <div class="p-3">
        <form @submit.prevent="submitForm">
            <div class="container mov-wide">
                    <div v-for="auction in auctionsData" :key="auction.id">
                        <div class="container-img">
                            <h5 class="my-3">후기 작성</h5>
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
                            <input type="hidden" id="user_id" :v-model="rv.user_id">
                            <input type="hidden" id="auction_id" :v-model="rv.auction_id">
                            <input type="hidden" id="dealer_id" :v-model="rv.dealer_id">
                            <h5>더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                        </div>
                        <div class="mx-2 text-secondary opacity-50 my-4">
                        <p> {{ carInfo.year }}년 | 2.4km | 무사고</p>
                        <p>현대 쏘나타 (DN8)</p>
                        <br>
                        <p>매물번호 | 564514</p>
                        <p>딜 러 명 | {{ auction.dealer_name }}</p>
                        </div>
                      <!--  <p class="card-title fs-5"><span class="blue-box">무사고</span>{{auction.car_no}}</p>-->
                            <!--<p class="mt-2 card-text text-secondary opacity-50 fs-5 mov-text">매물번호 <span class="process ms-2">(자동지정)</span></p>
                            <div class="enter-view mt-2">
                                <p class="card-text text-secondary opacity-50 fs-5 mov-text">딜러명<span class="process ms-3">{{ auction.dealer_name }}</span></p>
                                <p class="card-text text-secondary opacity-50 fs-5 web-text">12 삼 4567</p>
                                <a href="#"><span class="red-box-type02 pass-red" @click.prevent="openAlarmModal">상세보기</span></a>
                            </div>-->
                            <p class="mt-4 auction-deadline justify-content-sm-center text-secondary opacity-50">판매가<span class="fw-bolder fs-5 tc-primary">{{ amtComma(auction.win_bid.price) }}</span></p>
                            </div>
                            <div class="right-container">
                            <bottom-sheet initial="half" :dismissable="true">
                            <div class="sheet-content p-0">
                                <div class="mt-3" @click.stop="">
                                
                                <h5 calss="text-center">거래는 어떠셨나요?</h5>
                                <div class="wrap">
                                    <div class="rating my-3">
                                        <label class="rating__label rating__label--full" for="star1">
                                            <input type="radio" id="star1" class="rating__input" name="rating" value="1" v-model="rv.star">
                                            <span class="star-icon ms-2"></span>
                                            <span class="rating-description">별로에요</span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star2">
                                            <input type="radio" id="star2" class="rating__input" name="rating" value="2" v-model="rv.star">
                                            <span class="star-icon"></span>
                                            <span class="rating-description">괜찮아요</span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star3">
                                            <input type="radio" id="star3" class="rating__input" name="rating" value="3" v-model="rv.star">
                                            <span class="star-icon"></span>
                                            <span class="rating-description">좋아요</span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star4">
                                            <input type="radio" id="star4" class="rating__input" name="rating" value="4" v-model="rv.star">
                                            <span class="star-icon"></span>
                                            <span class="rating-description">만족해요</span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star5">
                                            <input type="radio" id="star5" class="rating__input" name="rating" value="5" v-model="rv.star">
                                            <span class="star-icon"></span>
                                            <span class="rating-description">최고에요!</span>
                                        </label>
                                    </div>
                                    <span class="d-flex mx-2 rating-score tc-red"></span>
                                </div>
                                <textarea class="custom-textarea mt-2" rows="6" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." id="content" v-model="rv.content"></textarea>
                                
                                <div class="btn-group mt-3 mb-4">
                                    <button class="btn btn-primary mt-2"> 작성 완료 </button>
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
        name : 'params',
    }
</script>
<script setup>
import { ref, onMounted , reactive ,onBeforeUnmount , nextTick} from 'vue';
import { useRoute } from 'vue-router'; 
import { initReviewSystem } from '@/composables/review'; // 별점 js
import useAuctions from "@/composables/auctions";
import BottomSheet from '@/views/bottomsheet/BottomSheet.vue'
import AlarmModal from '@/views/modal/AlarmModal.vue';
import Footer from "@/views/layout/footer.vue"
import { cmmn } from '@/hooks/cmmn';
const { amtComma } = cmmn();

const isMobileView = ref(window.innerWidth <= 640);
const route = useRoute();
const showBottomSheet = ref(true); //바텀 시트
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' }); //바텀 시트 스타일
const { getAuctionById } = useAuctions();
const auctionId = parseInt(route.params.id); 
let auctionsData = ref();
const carInfo = ref();
const { review , submitReview , getCarInfo } = initReviewSystem(); 

const alarmModal = ref(null);

const openAlarmModal = () => {
    console.log("openAlarmModal called");
    if (alarmModal.value) {
        alarmModal.value.openModal();
    }
};

const rv = reactive({
    user_id:'',
    auction_id:'',
    dealer_id:'',
    star:'',
    content:'',
})
const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };
function submitForm(){
    submitReview(rv);
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

onMounted(async() => { 
    
    const response = await getAuctionById(auctionId);

    rv.user_id = response.data.user_id;
    rv.auction_id = response.data.id;
    rv.dealer_id = response.data.win_bid.user_id;

    //차량정보 불러오기
    carInfo.value = await getCarInfo(response.data.owner_name, response.data.car_no);
    auctionsData.value = [response.data]; 
    
    window.addEventListener('resize', checkScreenWidth);
    checkScreenWidth();

    await nextTick();
    initReviewSystem();    
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
.review-img{
    height: 377px !important;
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
@media (min-width: 1200px) {
  .container-xl, .container-lg, .container-md, .container-sm, .container {
    max-width: 1140px;
  }
}
.mov-wide{
    width:auto !important;
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
                border-radius: 0; 
                overflow: visible;
                height: auto !important;
                margin-top: 35px;
            }
            .header{
                pointer-events: none; 
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