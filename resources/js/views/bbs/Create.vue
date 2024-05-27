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
                            <input type="hidden" id="dealer_id" :value=auction.bid_id>
                            <p class="card-title fs-5"><span class="blue-box">무사고</span>{{auction.car_no}}</p>
                            </div>
                            <p class="mt-2 card-text tc-light-gray fs-5 mov-text">매물번호 <span class="process ms-2">(자동지정)</span></p>
                            <div class="enter-view mt-2">
                                <p class="card-text tc-light-gray fs-5 mov-text">딜러명<span class="process ms-3"> (자동지정)</span></p>
                                <p class="card-text tc-light-gray fs-5 web-text">12 삼 4567</p>
                                <a href="#"><span class="red-box-type02 pass-red">상세보기</span></a>
                                </div>
                                <p class="mt-5 auction-deadline justify-content-sm-center">경매 마감일<span>2024년 2월 13일 오후 6시 00분</span></p>
                                <div class="container card-style p-0 mt-5">
                                <div class="card card-custom card-custom-ty">
                                    <div class="row flex-row">
                                    <div class="col col-line">
                                        <div class="item-label">년식</div>
                                        <div class="item-value mb-0">20년형</div>
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
                        <div class="style-view bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet">
                        <div class="sheet-content">
                            <div class="mt-3"  @click.stop="">
                                
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
                                <textarea class="custom-textarea mt-2" rows="4" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." v-model="review.content"></textarea>
                                
                                <div class="btn-group mt-3">
                                    <button class="btn btn-primary"> 작성 완료 </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router'; 
import { initReviewSystem } from '@/composables/review'; // 별점 js
import useAuctions from "@/composables/auctions";

const route = useRoute();
const showBottomSheet = ref(true); //바텀 시트
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' }); //바텀 시트 스타일
const { getAuctionById } = useAuctions();
const auctionId = parseInt(route.params.id); 
let auctionsData = ref();
const { review , submitReview } = initReviewSystem(); 

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
        auctionsData.value = [response.data]; 
        console.log(auctionsData.value); 
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
</style>