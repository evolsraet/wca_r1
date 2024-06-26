<template>
    <div class="container mov-wide web-content-style02">
        <div class="container my-5 p-2">
            <SkeletonLoader v-if="isLoading" />
            <template v-else>
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
            </template>
        </div>
         <BottomSheet02 initial="half" :dismissable="true" class="mt-5">
                <div>
                    <div class="top-content-style wd-100">
                        <p class="tc-light-gray bold-18-font">현재 시세 <span class="normal-14-font">(무사고 기준)</span></p>
                        <span class="tc-red bold-18-font">{{ carDetails.priceNow }} 만원</span>
                    </div>
                    <div v-if="user?.name">
                        <div class="d-flex justify-content-between mt-4 align-items-center pointer">
                            <p>차량 정보가 다르신가요?
                                <span class="tooltip-toggle nomal-14-font" aria-label="일 1회 갱신 가능합니다, 갱신한 정보는 1주간 보관됩니다" tabindex="0"></span>
                            </p>
                            <div class="tc-red link refresh-style d-flex justify-content-between" @click="openModalIfNeeded">
                                <span>{{ refreshText }}</span>
                                <transition name="fade">
                                    <div class="image-container">
                                        <img v-if="!isRefreshDisabled" src="../../../../img/Icon-refresh.png" :class="{'fa-sync-alt': isRefreshing, 'transition-image': true ,'mx-2':true,'mb-1':true }" alt="Refresh" width="15px"/>
                                        <img v-else src="../../../../img/checkbox.png" :class="{'transition-image': true,'mx-2':true,'mb-1':true}" alt="Complete" width="15px"/>
                                    </div>
                                </transition>
                            </div> 
                        </div>
                        
                            <InfoModal v-if="showModal" @close="closeModal" @refresh="startLoading"/>
                    
                        <div class="flex items-center justify-end mt-5">
                            <router-link :to="{ path: '/selldt' }" class="btn primary-btn w-100">경매 신청하기</router-link>
                        </div>
                    </div>
                    <div v-if="!user?.name">
                        <div class="d-flex justify-content-between mt-5 align-items-center">
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
                            <router-link :to="{ path: '/selldt' }" class="btn primary-disable w-100" @click="applyAuction">경매 신청하기</router-link>
                        </div>
                    </div>
                </div>
            </BottomSheet02>
            </div>
</template>

<script setup>
import InfoModal from '@/views/modal/infoModal.vue';
import SkeletonLoader from '@/views/loader/SkeletonLoader.vue';
import useAuth from "@/composables/auth";
import { useRouter } from 'vue-router';
import { ref, onMounted, computed ,inject } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from "vuex";
import BottomSheet02 from '@/views/bottomsheet/Bottomsheet-type02.vue';
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';

const swal = inject('$swal');
const { wica } = cmmn();
const previousCarDetails = ref({});
const isRefreshDisabled = ref(false); // 버튼 비활성화 상태 변수
const isRefreshing = ref(false); // 로딩 상태 변수
const refreshText = ref("정보갱신하기"); 
const carInfoForm = ref({}); 
const showModal = ref(false);
const processing = ref(false);
const validationErrors = ref({});
const isLoading = ref(true); 
const openModal = () => {
    //showModal.value = true;
    const text= '  <div class="enroll_box" style="position: relative;">'+
                 ' <img src="http://localhost:5173/resources/img/drift.png" alt="자동차 이미지" width="160" height="160">'+
                  '<p class="overlay_text02">정보를 갱신 하시겠습니까?</p>'+
                  '<p class="overlay_text03">일 1회 갱신 가능합니다. <br>갱신한 정보는 1주간 보관됩니다.</p>'+
              '  </div>';
  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 인 경우 활성화
    .labelOk('갱신하기') // 확인 버튼 라벨 변경
    .labelCancel('취소') // 취소 버튼 라벨 변경
    .btnBatch('R') // 확인 버튼 위치 지정, 기본은 L
    .addClassNm('review-custom') // 클래스명 변경, 기본 클래스명: wica-salert
    .addOption({ padding: 20 }) // swal 기타 옵션 추가
    .callback(function (result) {
        if (result.isOk) {
            startLoading();
        }
    })
    .confirm(text);
};
const { submitCarInfo, refreshCarInfo } = useAuctions();



const startLoading = async () => {
    isLoading.value = true;
    await new Promise(resolve => setTimeout(resolve, 2000));
    await handleRefresh();
    isLoading.value = false;
};

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

function toggleSheet() {
    const bottomSheet = document.querySelector('.bottom-sheet');

    if (showBottomSheet.value) {
        bottomSheetStyle.value = { position: 'static', bottom: '-100%' };
    } else {
        bottomSheetStyle.value = { position: 'fixed', bottom: '0px' };
    }
    showBottomSheet.value = !showBottomSheet.value;
}
// 정보 갱신 재로드, 로컬저장
const handleRefresh = async () => {
    try {
        isRefreshing.value = true;
        await refreshCarInfo();
        const updatedCarDetails = JSON.parse(localStorage.getItem('carDetails'));
        if (updatedCarDetails) {
            carDetails.value = updatedCarDetails;
            refreshText.value = "정보 갱신완료";
            isRefreshDisabled.value = true;
            isRefreshing.value = false;

            const lastRefreshTimes = JSON.parse(localStorage.getItem('lastRefreshTimes')) || {};
            lastRefreshTimes[`${carDetails.value.owner}-${carDetails.value.no}`] = new Date().toISOString();
            localStorage.setItem('lastRefreshTimes', JSON.stringify(lastRefreshTimes)); // 갱신 시간 저장

            setTimeout(() => {
                isRefreshDisabled.value = false;
                refreshText.value = "정보갱신하기";
            }, 5000);
        }
    } catch (error) {
        console.error("Failed to refresh car info:", error);
        isRefreshing.value = false;
    }
};

const openModalIfNeeded = () => {
    if (isRefreshDisabled.value) {
        return;
    }
    const lastRefreshTimes = JSON.parse(localStorage.getItem('lastRefreshTimes')) || {};
    const lastRefreshTime = lastRefreshTimes[`${carDetails.value.owner}-${carDetails.value.no}`];
    if (!lastRefreshTime) {
        openModal();
        return;
    }
    const lastRefreshDate = new Date(lastRefreshTime);
    const now = new Date();
    const timeDiff = now - lastRefreshDate;
    const fiveSeconds = 5000;
    if (timeDiff >= fiveSeconds) {
        openModal();
    } else {
        isRefreshDisabled.value = true;
        refreshText.value = "정보 갱신완료";

        setTimeout(() => {
            isRefreshDisabled.value = false;
            refreshText.value = "정보갱신하기";
        }, fiveSeconds - timeDiff);
    }
};

onMounted(async () => {
    await new Promise(resolve => setTimeout(resolve, 2000));

    const storedData = localStorage.getItem('carDetails');
    if (storedData) {
        carDetails.value = JSON.parse(storedData);
        saveCarNumberToLocalStorage();
        previousCarDetails.value = { ...carDetails.value };
    } else {
        console.log('No car details');
    }
    checkRefreshAvailability();
    isLoading.value = false;
});


const checkRefreshAvailability = () => {
    const lastRefreshTimes = JSON.parse(localStorage.getItem('lastRefreshTimes')) || {};
    const lastRefreshTime = lastRefreshTimes[`${carDetails.value.owner}-${carDetails.value.no}`];
    if (lastRefreshTime) {
        const lastRefreshDate = new Date(lastRefreshTime);
        const now = new Date();
        const timeDiff = now - lastRefreshDate;
        const fiveSeconds = 5000;
        if (timeDiff < fiveSeconds) {
            isRefreshDisabled.value = true;
            refreshText.value = "정보 갱신완료";

            setTimeout(() => {
                isRefreshDisabled.value = false;
                refreshText.value = "정보갱신하기";
            }, fiveSeconds - timeDiff);
        }
    }
};
function toggleDetailContent() {
    isActive.value = !isActive.value;
}

const applyAuction = () => {
    alert('로그인이 필요한 서비스입니다.');
    localStorage.setItem('guestClickedAuction', 'true');  // 게스트 플래그 생성
    router.push({ name: 'login' });  // 로그인 페이지로 리디렉션
}
</script>

<style scoped>


@media (min-width: 992px) {
  .web-content-style02[data-v-d5e42825] {
    display: flex;
    gap: 20px;
    padding: 15px;
    justify-content: center;
  }
}
@media (min-width: 992px){
    .handle{
      display: none;
    }
    .sheet-content{
      width: 100% !important;
      padding: 0px !important;
    }
    .sheet{
    position: relative !important;
    border-radius: 10px !important;
}
    .web-content-style02{
        display: flex;
        gap: 20px;
        padding: 15px;
        justify-content: center;
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
.refresh-indicator {
    text-align: center;
    overflow: hidden; 
    height: auto; 
    width: 100%;
}
.fa-sync-alt {
  animation: spin 1s linear infinite; 
}
.refresh-style {
    background-color: #ebedf1;
    border-radius: 20px;
    padding-left: 10px;
}

.transition-image {
    transition: opacity 0.5s ease-in-out;
    opacity: 1;
}

.image-container {
    position: relative;
}

.transition-image-hidden {
    opacity: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
@media (min-width: 992px){
.sheet {
    height: auto !important;
    transition: none;
}

}
.sheet{
    max-height: none;
}
</style>
