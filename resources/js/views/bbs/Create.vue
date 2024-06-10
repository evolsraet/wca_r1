<template>
    <!--
        * mov-text : 모바일 일때 보이는 뷰
        * web-text: : 웹 화면에서 보이는뷰
    --> 
    <div class="container">
        <form @submit.prevent="submitReview">
            <div class="create-review">
                    <div class="left-container" v-for="auction in auctionsData" :key="auction.id">
                        <div class="container-img mov-info02">
                            <div class="left-img">
                                <img src="../../../img/car_example2.png" alt="전체 이미지0 ">
                            </div>
                            <div class="right-img web-text">
                                <div class="detail detail1"></div>
                                <div class="detail detail2"></div>
                            </div>
                        </div>
                        <div class="enter-view align-items-baseline mt-3 bold-18-font">  
                            <input type="hidden" id="user_id" :value="auction.user_id">
                            <input type="hidden" id="auction_id" :value="auction.id">
                            <input type="hidden" id="dealer_id" :value="auction.win_bid.user_id">
                            <p class="card-title fs-5"><span class="blue-box">무사고</span>{{auction.car_no}}</p>
                            </div>
                            <p class="mt-2 card-text tc-light-gray fs-5 mov-text">매물번호 <span class="process ms-2">(자동지정)</span></p>
                            <div class="enter-view mt-2">
                                <p class="card-text tc-light-gray fs-5 mov-text">딜러명<span class="process ms-3">{{ auction.dealer_name }}</span></p>
                                <p class="card-text tc-light-gray fs-5 web-text">12 삼 4567</p>
                                <a href="#"><span class="red-box-type02 pass-red" @click.prevent="openAlarmModal">상세보기</span></a>
                            </div>
                            <p class="mt-5 auction-deadline justify-content-sm-center">경매 마감일<span>2024년 2월 13일 오후 6시 00분</span></p>
                                <div class="container card-style p-0 mt-5">
                                <div class="card card-custom card-custom-ty">
                                    <div class="row flex-row">
                                    <div class="col col-line">
                                        <div class="item-label">년식</div>
                                        <div class="item-value mb-0">{{ carInfo.year }} 년형</div>
                                    </div>
                                    <div class="col col-line">
                                        <div class="item-label">주행거리</div>
                                        <div class="item-value mb-0">103,000km</div>
                                    </div>
                                    <div class="col col-line">
                                        <div class="item-label">내사 피해</div>
                                        <div class="item-value mb-0">1건</div>
                                    </div>
                                    <div class="col col-line">
                                        <div class="item-label">타사 피해</div>
                                        <div class="item-value mb-0">3건</div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                            <div class="right-container">
                                <bottom-sheet initial="half" :dismissable="true">
                        <div class="sheet-content p-0">
                            <div class="mt-1" @click.stop="">
                                
                                <h5 calss="text-center">거래는 어떠셨나요?</h5>
                                <div class="wrap">
                                    <div class="rating">
                                        <label class="rating__label rating__label--full" for="star1">
                                            <input type="radio" id="star1" class="rating__input" name="rating" value="">
                                            <span class="star-icon ms-2"></span>
                                            <span class="rating-description">그저그래요</span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star2">
                                            <input type="radio" id="star2" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                            <span class="rating-description">괜찮아요</span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star3">
                                            <input type="radio" id="star3" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                            <span class="rating-description">좋아요</span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star4">
                                            <input type="radio" id="star4" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                            <span class="rating-description">만족해요</span>
                                        </label>
                                        <label class="rating__label rating__label--full" for="star5">
                                            <input type="radio" id="star5" class="rating__input" name="rating" value="">
                                            <span class="star-icon"></span>
                                            <span class="rating-description">최고에요!</span>
                                        </label>
                                        <span class="d-flex mx-2 rating-score tc-red"></span>
                                    </div>
                                </div>
                                <textarea class="custom-textarea mt-2" rows="4" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." id="content"></textarea>
                                
                                <div class="btn-group mt-3 mb-4">
                                    <button class="btn btn-primary"> 작성 완료 </button>
                                </div>
                            </div>
                        </div>
                    </bottom-sheet>
                    </div>
                 
            </div>
        </form>
        <AlarmModal ref="alarmModal" />
    </div>

</template>
<script>
    export default {
        name : 'params',
    }
</script>
<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router'; 
import { initReviewSystem } from '@/composables/review'; // 별점 js
import useAuctions from "@/composables/auctions";
import BottomSheet from '@/views/bottomsheet/BottomSheet.vue'
import AlarmModal from '@/views/modal/AlarmModal.vue';

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

const findAuctionDetail = async () => {
    try {
        const response = await getAuctionById(auctionId);
        //차량정보 불러오기
        carInfo.value = await getCarInfo(response.data.owner_name, response.data.car_no);
        auctionsData.value = [response.data]; 
        initReviewSystem(); 
    } catch (error) {
        console.error('Error fetching auction data:', error);
    }
};

onMounted(() => { 
    findAuctionDetail();
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
</style>