<template>
    <div class="container">
        <div class="main-contenter mt-3 p-2">
            <h5>차량 정보 조회 되었어요</h5>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">차량번호</li>
                <li class="info-num">{{ carDetails.no }}</li>
                <li class="car-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">제조사</li>
                <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">모델</li>
                <li class="sub-title">{{ carDetails.model }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">세부모델</li>
                <li class="sub-title">{{ carDetails.modelSub }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">등급</li>
                <li class="sub-title">{{ carDetails.grade }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">세부등급</li>
                <li class="sub-title">{{ carDetails.gradeSub }}</li>
            </ul>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">최초등록일</li>
                <li class="info-num"></li>
                <li class="car-aside-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">년식</li>
                <li class="sub-title">{{ carDetails.year }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">차량유형</li>
                <li class="sub-title">종합 승용차</li>
            </ul>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">배기량</li>
                <li class="info-num">2000cc</li>
                <li class="gasoline-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">연료</li>
                <li class="sub-title">{{ carDetails.fuel }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">미션</li>
                <li class="sub-title">{{ carDetails.mission }}</li>
            </ul>
            <ul class="machine-inform-title">
                <li class="tc-light-gray">용도변경이력</li>
                <li class="info-num">-</li>
                <li class="clean-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">튜닝이력</li>
                <li class="sub-title">1회</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">리콜이력</li>
                <li class="sub-title">-</li>
            </ul>
        </div>
        <!--<div class="detail-content mt-5" :class="{ 'active': isActive }" @click="toggleDetailContent">-->
        <div class="style-view bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet">
            <div class="sheet-content">
                <div @click.stop="">
                    <div class="top-content wd-100">
                        <p class="tc-light-gray bold-18-font">현재 시세 <span class="normal-14-font">(무사고 기준)</span></p>
                        <span class="tc-red bold-18-font">{{ carDetails.priceNow }} 만원</span>
                    </div>
                <div v-if="user?.name">
                    <div class="middle">
                    <p>차량 정보가 다르신가요?<span class="tooltip-toggle nomal-14-font" aria-label="일 1회 갱신 가능합니다, 갱신한 정보는 1주간 보관됩니다" tabindex="0"></span></p>
                    <div class="tc-red link">정보갱신하기</div>
                    </div>
                <!-- TODO: 일치하는 차량 없다는 데이터의 기준? 
                        <div class="none-info">
                            <div class="complete-car">
                            <div class="card my-auction mt-3">
                                <div class="none-complete-ty02">
                                    <span class="tc-light-gray">일치하는 차량이 없습니다.</span>
                                </div>
                            </div>
                        </div>
                    </div>-->

                    <div class="flex items-center justify-end my-5">
                        <router-link :to="{ path: '/selldt' }" class="btn primary-btn ">경매 신청하기</router-link></div>
                    </div>
                    <div v-if="!user?.name">
                    <div class="middle">
                    <p>차량 정보가 다르신가요?<span class="tooltip-toggle nomal-14-font" aria-label="로그인을 하면 자세한 정보를 볼수있어요." tabindex="0"></span></p>
                    <p class="tc-light-gray link">정보갱신하기</p>
                    </div>
                    <div class="none-info">
                    <div class="complete-car">
                            <div class="card my-auction mt-3">
                                <div class="none-complete-ty02">
                                    <span class="tc-light-gray">로그인을 하면 경매 신청이 가능해요.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end my-5">
                        <router-link :to="{ path: '/selldt' }" class="btn primary-disable" @click="applyAuction">경매 신청하기</router-link></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import useAuth from "@/composables/auth";
import { ref, onMounted,computed } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from "vuex";


const store = useStore();
const user = computed(() => store.getters["auth/user"]);

const showBottomSheet = ref(true); //바텀 시트
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' }); //바텀 시트 스타일

const isActive = ref(false); 
const router = useRoute();
const carDetails = ref({});
function saveCarNumberToLocalStorage() {
  localStorage.setItem('carNumber', carDetails.value.no);
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
  const storedData = localStorage.getItem('carDetails');
  if (storedData) {
    carDetails.value = JSON.parse(storedData);
    saveCarNumberToLocalStorage();  
  } else {
    console.log('No car details');
  }
});
function toggleDetailContent() {
    isActive.value = !isActive.value;  
}

const applyAuction = () => {
  alert('로그인이 필요한 서비스입니다.');
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