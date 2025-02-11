<template>
    <div class="container web-content-style02 pt-5 p-3">
        <div class="container mt-4 p-2 width-container">
            <SkeletonLoader v-if="isLoading" />
            <template v-else>
                <h4>차량 정보 조회 되었어요</h4>
                <hr>
                <CarInfoItem label="차량번호" :value="carDetails.no" icon="car-icon" :isTitle="true" />
                <CarInfoItem label="제조사" :value="carDetails.maker" />
                <CarInfoItem label="모델" :value="carDetails.model" />
                <CarInfoItem label="세부모델" :value="carDetails.modelSub" />
                <CarInfoItem label="등급" :value="carDetails.grade" />
                <CarInfoItem label="세부등급" :value="carDetails.gradeSub" />

                <CarInfoItem label="최초등록일" :value="carDetails.firstRegDate" icon="car-aside-icon" :isTitle="true" />
                <CarInfoItem label="년식" :value="carDetails.year" />
                <CarInfoItem label="차량유형" value="종합 승용차" />

                <CarInfoItem label="배기량" value="2000cc" icon="gasoline-icon" :isTitle="true" />
                <CarInfoItem label="연료" :value="carDetails.fuel" />
                <CarInfoItem label="미션" :value="carDetails.mission" />

                <CarInfoItem label="용도변경이력" value="-" icon="clean-icon" :isTitle="true" />
                <CarInfoItem label="튜닝이력" value="1회" />
                <CarInfoItem label="리콜이력" value="-" />
            </template>
        </div>
         <BottomSheet02 class="mt-5" initial="half" :dismissable="true">
                    <div class="top-content-style wd-100">
                        <p class="text-secondary bold-18-font">현재 시세 <span class="normal-14-font">(소매가)</span></p>
                        <span class="tc-primary bold-18-font">{{ carDetails.priceNow }} 만원</span>
                    </div>
                    <div class="top-content-style wd-100 mt-4">
                        <p class="text-secondary bold-18-font">현재 시세 <span class="normal-14-font">(도매가)</span></p>
                        <span class="tc-primary bold-18-font">{{ carDetails.priceNowWhole }} 만원</span>
                    </div>
                    <p class="mt-3 text-secondary">※ 소매 시세는 나이스디엔알에서 제공하며, 도매시세는 오토허브셀카에서 제공합니다.</p>
                    <div v-if="user?.name">
                        <div class="d-flex justify-content-between mt-4 mb-3 align-items-center pointer">
                            <p>차량 정보가 다르신가요?
                                <span class="tooltip-toggle nomal-14-font" aria-label="일 1회 갱신 가능합니다, 갱신한 정보는 1주간 보관됩니다" tabindex="0"></span>
                            </p>
                            <div class="tc-primary link refresh-style d-flex justify-content-between" @click="openModalIfNeeded">
                                <transition name="fade">
                                    <div class="image-container">
                                        <img v-if="!isRefreshDisabled" src="../../../../img/Icon-refresh-red.png" :class="{'fa-sync-alt': isRefreshing, 'transition-image': true ,'mx-2':true,'mb-1':true }" alt="Refresh" width="15px"/>
                                        <img v-else src="../../../../img/checkbox.png" :class="{'transition-image': true,'mx-2':true,'mb-1':true}" alt="Complete" width="15px"/>
                                    </div>
                                </transition>
                                <span class="pointer">{{ refreshText }}</span>
                            </div> 
                        </div>
                        <div class="none-info">
                            <div class="complete-car">
                                <div class="card my-auction">
                                    <div class="none-complete-ty03">
                                        <span class="text-secondary opacity-50">차량 조회에 성공하였습니다.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <InfoModal v-if="showModal" @close="closeModal" @refresh="startLoading"/>
                        <div class="mov-col flex items-center justify-end mt-3 mb-2 gap-2">
                            <button class="btn btn-outline-primary w-100 expectedPrice" @click="ExpectationPrice">예상 가격 측정</button>
                            <button
                                class="btn btn-outline-primary w-100"
                                id="currentPriceBtn"
                                style="display: none;"
                            >
                                현재 예상 가격
                            </button>
                            <button class="btn primary-btn w-100 bolder" @click="UserapplyAuction">경매 신청하기</button>
                        </div>
                    </div>
                    <div v-if="!user?.name">
                        <div class="d-flex justify-content-between mt-5 mb-3 align-items-center">
                            <p>차량 정보가 다르신가요?<span class="tooltip-toggle nomal-14-font" aria-label="로그인을 하면 자세한 정보를 볼수있어요." tabindex="0"></span></p>
                            <div class="tc-primary link refresh-style d-flex justify-content-between" @click="loginerror">
                                <span class="bolder">{{ refreshText }}</span>
                                <transition name="fade">
                                    <div class="image-container">
                                        <img src="../../../../img/Icon-refresh.png" :class="{'fa-sync-alt': isRefreshing, 'transition-image': true ,'mx-2':true,'mb-1':true }" alt="Refresh" width="15px"/>
                                    </div>
                                </transition>
                            </div> 
                        </div>
                        <div class="none-info">
                            <div class="complete-car">
                                <div class="card my-auction">
                                    <div class="none-complete-ty02">
                                        <span class="text-secondary opacity-50">로그인을 하면 경매 신청이 가능해요.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mb-2 mt-3">
                            <button class="btn btn-primary w-100 bolder" @click="applyAuction">경매 신청하기</button>
                        </div>
                    </div>
              
            </BottomSheet02>
        </div>
</template>


<script setup>
import InfoModal from '@/views/modal/infoModal.vue';
import SkeletonLoader from '@/views/loader/SkeletonLoader.vue';
import CarInfoItem from '../../import/CarInfoItem.vue';
import useAuth from "@/composables/auth";
import { useRouter } from 'vue-router';
import { ref, onMounted, computed ,inject } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from "vuex";
import BottomSheet02 from '@/views/bottomsheet/Bottomsheet-type02.vue';
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';
import drift from '../../../../../resources/img/drift.png';
import imgInfo from'../../../../img/auction-detil.png';
import info1 from '../../../../../resources/img/modal/info3.png';
import info2 from '../../../../../resources/img/modal/info1.png';
import info3 from '../../../../../resources/img/modal/info2.png';


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
    const text= `<div class="enroll_box" style="position: relative;">
                 <img src="${drift}" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text02">정보를 갱신 하시겠습니까?</p>
                  <p class="overlay_text03">일 1회 갱신 가능합니다. <br>갱신한 정보는 1주간 보관됩니다.</p>
              </div>`;
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

let currentEstimatedPrice = null; // 예상 가격 초기값

// 모달 열기
const openPriceModal = () => {
    const modal = document.getElementById('priceModal');
    if (modal) {
        document.getElementById('priceDisplay').textContent =
            currentEstimatedPrice ? `${currentEstimatedPrice}원` : '데이터 없음';
        modal.style.display = 'block';
    }
};

// 모달 닫기
const closePriceModal = () => {
    const modal = document.getElementById('priceModal');
    if (modal) {
        modal.style.display = 'none';
    }
};
const ExpectationPrice = () => {
    const text = `
        <div>
            <div class="text-start">
                <h5 class="bolder mt-3">내 차, 예상가격을 확인합니다</h5>
                <p class="mb-3">보다 정확한 가격 산정을 위해 아래 사항을 확인해 주세요.</p>
            </div>
            <div class="proceeding"></div>
            <div class="text-start tc-gray">
               <div class="form-group">
                    <label>주행거리</label>
                    <input type="text" placeholder="km" name="mileage" class="mileage_input" value="">
                    <div class="error mileage-error" style="color: red;"></div>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">과거 사고이력</label>
                    <div class="manufacturer-model02 my-3">
                        <label class="item">
                            <input type="radio" name="accident" value="완전 무사고" onclick="selectItem(this)">
                            완전 무사고
                        </label>
                        <label class="item">
                            <input type="radio" name="accident" value="교환" onclick="selectItem(this)">
                            교환
                        </label>
                        <label class="item">
                            <input type="radio" name="accident" value="판금 사고" onclick="selectItem(this)">
                            판금 사고
                        </label>
                        <label class="item">
                            <input type="radio" name="accident" value="전손이력" onclick="selectItem(this)">
                            전손이력
                        </label>
                    </div>
                    <div class="error accident-error" style="color: red;"></div>
                </div>
                <div>
                    <p class="mb-3">※ 이 감가 기준은 일반적인 감가 기준이며, 실제 평가 금액과는 차이가 있을 수 있습니다. </p>    
                </div>
                <div class="form-group">
                    <label class="mb-2">수리필요</label>
                </div>
                <div class="form-group">
                    <label>키 갯수</label>
                    <input type="text" placeholder="개" name="keyCount" value="">
                    <div class="error keyCount-error" style="color: red;"></div>
                </div>
                <div class="form-group">
                    <label>휠스크래치</label>
                    <input type="text" placeholder="개" name="wheelScratch" value="">
                    <div class="error wheelScratch-error" style="color: red;"></div>
                </div>
                <div class="form-group">
                    <label class="mb-2">타이어 상태</label>
                    <div class="input-wrapper">
                        <span class="size_14">정상</span>
                        <input type="text" placeholder="개" name="tireStatusNormal" class="text-right" value="">
                    </div>
                    <div class="error tireStatusNormal-error" style="color: red;"></div>
                    <div class="input-wrapper">
                        <span class="size_14">교환</span>
                        <input type="text" placeholder="개" name="tireStatusReplaced" class="text-right" value="">
                    </div>
                    <div class="error tireStatusReplaced-error" style="color: red;"></div>
                </div>

                <button id="customSubmitButton" class="btn btn-primary mt-3 w-100">평가사 진단 신청</button>
            </div>
        </div>
    `;

    wica.ntcn(swal)
        .useHtmlText()
        .addClassNm('search-event')
        .useClose()
        .addOption({ padding: 20 })
        .confirm(text);
    setTimeout(() => {
        const selectItem = (input) => {
        document.querySelectorAll('input[name="accident"]').forEach((radio) => {
            const item = radio.closest('.item');
            if (item) {
                item.classList.remove('selected', 'active');
            }
        });
        const parentItem = input.closest('.item');
        if (parentItem) {
            parentItem.classList.add('selected', 'active');
        }
    };

        window.selectItem = selectItem;

        // 버튼 클릭 이벤트 설정
        const customSubmitButton = document.getElementById('customSubmitButton');
        customSubmitButton.addEventListener('click', async () => {
            try {
                await checkExpectedPriceClick();
            } catch (error) {
                console.error(error);
            }
        });
    }, 0);
};

function displayValidationErrors(errors) {
    document.querySelectorAll('.error').forEach((errorDiv) => {
        errorDiv.textContent = ''; 
    });
    Object.keys(errors).forEach((field) => {
        const errorDiv = document.querySelector(`.${field}-error`);
        if (errorDiv) {
            errorDiv.textContent = errors[field].join(', '); 
        } else {
            console.warn(`Error field not found for: ${field}`); 
        }
    });
}


const { submitCarInfo, refreshCarInfo, checkExpectedPrice } = useAuctions();

const checkExpectedPriceClick = async () => {
    const mileage = document.querySelector('input[name="mileage"]').value;
    const accident = document.querySelector('input[name="accident"]:checked')?.value || '';
    const keyCount = document.querySelector('input[name="keyCount"]').value;
    const wheelScratch = document.querySelector('input[name="wheelScratch"]').value;
    const tireStatusNormal = document.querySelector('input[name="tireStatusNormal"]').value;
    const tireStatusReplaced = document.querySelector('input[name="tireStatusReplaced"]').value;
    // console.log(carDetails.value);

    const firstRegDate = carDetails.value.firstRegDate;
    const currentPrice = carDetails.value.priceNow;
    const maker = carDetails.value.maker;
    // console.log(currentPrice);

    const data = {
        mileage,
        accident,
        keyCount,
        wheelScratch,
        tireStatusNormal,
        tireStatusReplaced,
        firstRegDate,
        currentPrice,
        maker
    };

    try {
        isLoading.value = true;

        const result = await checkExpectedPrice(data);
        console.log('API 결과:', result);

        if (result.isSuccess) {
            console.log('성공 처리 실행'); // 디버깅용

            // 모달 숨기기
            setTimeout(() => {
                const popupElement = document.querySelector('.search-event-container');
                if (popupElement) {
                    popupElement.style.setProperty('display', 'none');
                } else {
                    console.error('.search-event-container 요소를 찾을 수 없습니다.');
                }
            }, 100);

            // 예상 가격 모달 즉시 표시
            showCurrentPriceModal(result.data.estimatedPrice);

            // "현재 예상 가격" 버튼 표시
            const currentPriceBtn = document.getElementById('currentPriceBtn');
            if (currentPriceBtn) {
                currentPriceBtn.style.display = 'inline-block'; 
                currentPriceBtn.addEventListener('click', () => {
                    showCurrentPriceModal(result.data.estimatedPrice); // 버튼 클릭 시 모달 다시 표시
                });
            }
            const estimatePriceBtn = document.querySelector('.expectedPrice');
            if (estimatePriceBtn) {
                estimatePriceBtn.style.display = 'none';
            }
        }
        if (result.isError) {
            console.log('에러 처리 실행'); 
            const errors = result.rawData?.response?.data?.errors || {};
            displayValidationErrors(errors);
        }
    } catch (error) {
        const errors = error.response?.data?.errors || {};
        displayValidationErrors(errors); 
        console.error('Error during checkExpectedPriceClick:', error);
    } finally {
        isLoading.value = false;
    }
};

const showCurrentPriceModal = (estimatedPrice) => {
    const price = estimatedPrice || '알 수 없음'; // 예상 가격 기본값 처리
    const text = `
    <div style="padding: 20px; text-align: center; border-radius: 10px;">
         <div class="top-content-style wd-100 my-4">
            <p class="text-secondary bold-18-font">예상 가격</p>
            <span class="tc-primary bold-18-font">${price} 만원</span>
        </div>
        <p style="font-size: 14px; color: #888;">예상 가격은 현재 차량 정보를 기준으로 산정된 값입니다.</p>
    </div>
`;
    wica.ntcn(swal)
        .useHtmlText()
        .addClassNm('current-price-modal')
        .addOption({ padding: 20 })
        .labelOk('재측정하기')
        .labelCancel('확인')
        .useBackCancel()
        .callback((result) => {
            if (result.isOk) {
                ExpectationPrice();

                setTimeout(() => {
                    const popupElement = document.querySelector('.search-event-container');
                    if (popupElement) {
                        popupElement.style.setProperty('display', 'block', 'important');
                        console.log('.search-event-container 표시 성공');
                    } else {
                        console.error('.search-event-container 요소를 찾을 수 없습니다.');
                    }
                }, 100); 
            }
        })
        .confirm(text);
};






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
const route = useRoute(); // 변경된 부분
const router = useRouter(); // 추가된 부분
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
const loginerror = () =>{
    const text = `<div class="enroll_box mb-2" style="position: relative;">
                 <img src="${drift}" alt="자동차 이미지" width="160" height="160">
                 <p class="overlay_text04">로그인이 필요한 서비스 입니다.</p>
                 </div>`;
    wica.ntcn(swal)
        .useHtmlText() // HTML 태그 인 경우 활성화
        .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
        .addOption({ padding: 20 }) // swal 기타 옵션 추가
        .callback(function (result) {
    })
    .confirm(text);  

}

/* 정보 갱신 */ 
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

const UserapplyAuction = () => {
    const text = `
    <h5 class="text-start mb-3">경매 진행 순서 안내</h5>
    <div class="sellInfo my-3 p-4" style="position: relative;">
        <img src="${imgInfo}" alt="Auction Detail" class="auction-detail-img">
        <div id="tooltip-area" class="tooltip-area" style="cursor: pointer; position: absolute; width: 50px; height: 50px;"></div>
        <div id="tooltip" class="p-4 tooltip2" style="display: none; position: absolute; background: white; color: black; padding: 10px; border-radius: 5px; border:1px solid #aaaebe;">
            <div class="text-center">
                <h5>※ 탁송 및 대금</h5>
                <div class="text-start mt-3">
                    <p>판매딜러가 선택되면 고객의 탁송 희망날짜에 <br>탁송이 진행됩니다.</p>
                    <p class="tc-gray">(탁송정보 낙찰확정 후 48시간 이내에 입력)</p>
                    <p class="mt-2">탁송진행전 2시간전까지 나이스 에스크로 <br>계좌로 판매대금이 입금됩니다.</p>
                    <p class="mt-2">입금이 완료되면 나이​스에서 입급 완료<br>증명서가 고객에게 송부됩니다.</p>
                </div>
            </div>
            <button id="close-tooltip" style="position: absolute; top: 5px; right: 5px; border: none; background: transparent; cursor: pointer;">X</button>
        </div>
    </div>`;

    wica.ntcn(swal)
        .useHtmlText()
        .labelCancel()
        .useClose()
        .addClassNm('primary-check')
        .addOption({ padding: 20 })
        .callback(function (result) {
            if (result.isOk) {
                console.log(result);
                secondModal();
            }
        })
        .confirm(text);

    setTimeout(() => {
        const tooltipArea = document.getElementById('tooltip-area');
        const tooltip = document.getElementById('tooltip');
        const closeTooltipButton = document.getElementById('close-tooltip');

        const showTooltip = () => {
            tooltip.style.display = 'block';
        };

        const hideTooltip = () => {
            tooltip.style.display = 'none';
        };

        // Tooltip 영역 클릭 이벤트
        tooltipArea.addEventListener('click', (event) => {
            event.stopPropagation(); // 클릭 이벤트가 전파되지 않도록 방지
            if (tooltip.style.display === 'block') {
                hideTooltip();
            } else {
                showTooltip();
            }
        });

        // Tooltip 닫기 버튼 클릭 이벤트
        closeTooltipButton.addEventListener('click', (event) => {
            event.stopPropagation();
            hideTooltip();
        });

        // 다른 곳 클릭 시 Tooltip 닫기
        document.addEventListener('click', () => {
            hideTooltip();
        });

        // 모바일 터치 이벤트
        tooltipArea.addEventListener('touchstart', (event) => {
            event.stopPropagation();
            event.preventDefault();
            if (tooltip.style.display === 'block') {
                hideTooltip();
            } else {
                showTooltip();
            }
        });

        closeTooltipButton.addEventListener('touchstart', (event) => {
            event.stopPropagation();
            event.preventDefault();
            hideTooltip();
        });

        document.addEventListener('touchstart', (event) => {
            if (!tooltip.contains(event.target)) {
                hideTooltip();
            }
        });
    }, 100);
};


const secondModal = () => {
    
    const text = `<div class="content p-2 mt-0"> 
          <h3 class="mb-2 fw-semibold">필요 서류</h3>
          <h5 class="text-secondary opacity-50 fw-normal mb-4">미리 준비하면 더 빠르게 진행돼요!</h5>
          <div class="text-start my-3 process"> 
            <h4 class="mb-3">개인</h4>
            <div class="d-flex justify-content-between">
              <div class="info-block">
                <p class="text-start">경매 등록시</p>
                <img src="${info1}" class="info-img">
                <div class="text-center">
                  <p>필요서류 없음</p>
                  <p class="text-secondary opacity-50">본인 소유 차량이 아닐경우<br>위임장 또는 소유자 인감<br>증명서가 필요합니다.</p>
                </div>
              </div>
              <div class="info-block">
                <p class="text-start">경매 완료시</p>
                <img src="${info3}" class="info-img2">
                <p class="text-center">매도용 인감 증명서</p>
              </div>
            </div>
          </div>
          <hr class="custom-hr" />
          <div>
            <div class="text-start my-3 process"> 
              <h4 class="mb-3">딜러</h4>
              <div class="d-flex justify-content-between align-items-end">
                <div class="info-block">
                  <p class="text-start">경매 등록시</p>
                  <img src="${info2}" class="info-img3">
                </div>
                <div class="info-block">
                  <p>차량 이전서류</p>
                </div>
              </div>
            </div>
          </div>
          </div>`;

  wica.ntcn(swal)
    .useClose()
    .useHtmlText()
    .addClassNm('primary-check')
    .addOption({ padding: 20, height: 265 })
    .callback(function (result) {
      if (result.isOk) {
        router.push({ path: '/selldt2' }); 
      }
    })
    .confirm(text);
};

const applyAuction = () => {
    const text = `<div class="enroll_box" style="position: relative;">
                 <img src="${drift}" alt="자동차 이미지" width="160" height="160">
                 <p class="overlay_text04">로그인이 필요한 서비스 입니다.</p>
                 <p>로그인 창으로 이동하시겠습니까?</p>
                 </div>`;
    wica.ntcn(swal)
        .useHtmlText() // HTML 태그 인 경우 활성화
        .labelCancel()
        .labelOk('확인 ') // 확인 버튼 라벨 변경
        .labelCancel('취소') // 취소 버튼 라벨 변경
        .btnBatch('R') // 확인 버튼 위치 지정, 기본은 L
        .addClassNm('review-custom') // 클래스명 변경, 기본 클래스명: wica-salert
        .addOption({ padding: 20 }) // swal 기타 옵션 추가
        .callback(function (result) {
        if (result.isOk) {
            localStorage.setItem('guestClickedAuction', 'true');  // 게스트 플래그 생성
            router.push({ name: 'auth.login' }); // 변경된 부분
        }
    })
    .confirm(text);
}
</script>

<style scoped>
.none-complete-ty02{
    height: 300px;
}
.none-complete-ty03{
    height: 300px;
}
@media (max-width: 1335px){
.mov-wide {
    width: 75vw;
}
}
@media (max-width: 575px){
.mov-wide {
    width: 100%;
}
}
@media (max-width: 991px){
.none-complete-ty02{
    height: 150px !important;
}
.none-complete-ty03{
 height: 130px !important;
}
}
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
    border-radius: 25px !important;
    max-height: none !important;
}
}

.sellInfo img{
    width: 80%;
}
.sheet.half{
  max-height: none !important;
  height: calc(fit-content + env(safe-area-inset-bottom)) !important;
}
.side-sheet-style{
    margin-top: 70px;
}
@media (min-width: 1400px) {
    .container-xxl, .container-xl, .container-lg, .container-md, .container-sm, .container {
        max-width: 1000px;
    }
}

</style>
