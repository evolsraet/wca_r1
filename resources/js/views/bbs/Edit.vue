<template>
    <!--
        * mov-text : 모바일 일때 보이는 뷰
        * web-text: : 웹 화면에서 보이는뷰
    --> 
    <div class="container">
        <form @submit.prevent="editReview(reviewId)">
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
                        <div class="sheet-content" v-for="review in reviewsData" :key="review">
                            <div class="mt-3"  @click.stop="">
                                <h5 calss="text-center">거래는 어떠셨나요?</h5>
                                <div class="wrap">
                                    <input type="hidden" id="reviewStarValue">
                                    <div class="rating">
                                        <label v-for="index in 5" :key="index" :for="'star' + index" class="rating__label rating__label--full">
                                            <input type="radio" :id="'star' + index" class="rating__input" name="rating" :value="index">
                                            <span :class="['star-icon', index <= review.star ? 'filled' : '']"></span>
                                            <span class="rating-description" :value="index"></span>
                                        </label>
                                        <span class="d-flex mx-2 rating-score tc-red"></span>
                                    </div>
                                </div>
                                <textarea class="custom-textarea mt-2" rows="4" placeholder="다른 판매자들에게 알려주고 싶은 정보가 있으면 공유해주세요." id="content">{{ review.content }}</textarea>
                                <div class="btn-group mt-3">
                                    <button class="btn btn-primary"> 수정 완료 </button>
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
import useAuctions from "@/composables/auctions";
import { initReviewSystem } from '@/composables/review'; // 별점 js

const route = useRoute();
const reviewId = parseInt(route.params.id); 
const showBottomSheet = ref(true); //바텀 시트
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' }); //바텀 시트 스타일
const { getUserReviewInfo , editReview } = initReviewSystem(); 
const { getAuctionById } = useAuctions();
let auctionsData = ref();
let reviewsData = ref();

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

onMounted(async () => {
    const reviewResponse = await getUserReviewInfo(reviewId);
    reviewsData.value = [reviewResponse]; 

    const auctionResponse = await getAuctionById(reviewsData.value[0].auction_id);
    auctionsData.value = [auctionResponse.data]; 
    
    setInitialStarRating(reviewsData.value[0].star);
    initReviewSystem();
});

function setInitialStarRating(starRating) {
    const stars = document.querySelectorAll('.rating__input'); 
    const scoreDisplay = document.querySelector('.rating-score'); // 점수를 표시할 span 요소
    
    const reviewStarValue = document.getElementById('reviewStarValue');
    reviewStarValue.value = starRating;
    scoreDisplay.textContent = `( ${starRating} 점 )`; 
    
    const starDescription = document.querySelectorAll('.rating-description');
    stars.forEach(star => {
        if (parseInt(star.value) === starRating) {
            star.checked = true;
        }
    });
    starDescription.forEach(des => {
        const value = des.getAttribute('value');
        switch (value) {
            case '1':
                des.textContent = '그저그래요';
                break;
            case '2':
                des.textContent = '괜찮아요';
                break;
            case '3':
                des.textContent = '좋아요';
                break;
            case '4':
                des.textContent = '만족해요';
                break;
            case '5':
                des.textContent = '최고에요!';
                break;      
        }
    })
}

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