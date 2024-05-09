<template>
    <div class="container">
        <div class="main-contenter mt-4">
            <h5 class="top-title">경매 할 차량을 등록해볼까요?</h5>
        </div>
        <div class="form-body mt-5">
            <form @submit.prevent="auctionEntry">
                <!-- 소유자 입력 -->
                <div class="form-group">
                    <label for="owner-name">소유자</label>
                    <div class="owner-certificat" @click="confirmEditIfDisabled">
                        <input type="text" id="owner-name" v-model="ownerName" placeholder="홍길동" :disabled="isVerified">
                        <button class="btn certification" @click.stop="verifyOwner" :disabled="isVerified">본인인증</button>
                    </div>
                </div>
                <!-- 차량 번호 입력 -->
                <div class="form-group">
                    <label for="carNumber">차량 번호</label>
                    <input type="text" id="carNumber" v-model="carNumber" placeholder="12 삼 4567" :disabled="true">
                </div>
                <!-- 주행거리 입력 -->
            <!--    <div class="form-group">
                    <label for="mileage">주행거리</label>
                    <input type="text" id="mileage" v-model="mileage" placeholder="Km">
                </div>-->
                <!-- 지역 선택 -->
                <div class="form-group">
                    <label for="sido1">지역</label>
                    <div class="region">
                        <select v-model="selectedRegion" @change="onRegionChange">
                            <option value="">시/도 선택</option>
                            <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
                        </select>
                  <!--      <select v-model="selectedDistrict">
                            <option value="">구/군 선택</option>
                            <option v-for="district in availableDistricts" :key="district" :value="district">{{ district }}</option>
                        </select>-->
                    </div>
                </div>
                <!-- 주소 입력 -->
                <div class="form-group mb-5">
                    <label for="addr">주소</label>
                    <input type="text" id="addr" v-model="addr" placeholder="주소">
                    <input type="text" id="adddt" v-model="addrdt" placeholder="상세주소">
                </div>
                <!-- 은행 선택 -->
                <div class="form-group mt-4">
                    <label for="carNumber">은행</label>
                    <input type="text" id="bank" placeholder="은행 선택" @click="handleBankLabelClick" v-model="selectedBank" readonly>
                    <input type="text" v-model="account" placeholder="계좌번호" :class="{'block': accountDetails}" class="account-num">
                </div>
                <!-- 은행 선택 모달 -->
                <div class="show-content" :class="{ 'active': showDetails }">
                    <div class="detail-content02 mt-5 p-0" :class="{ 'active': isActive }" @click="toggleDetailContent">
                        <h3 class="title">은행 선택</h3>
                        <div class="nav-header">
                            <button type="button" class="btn-close p-3" @click.stop="closeDetailContent"></button>
                        </div>
                        <div class="content mt-0 p-0" @click.stop>
                            <ul class="menu">
                                <li class="menu-item active">은행</li>
                                <li class="menu-item">증권</li>
                            </ul>
                            <div class="grid bank-selection">
                                <div class="bank-item" data-bank="하나" @click="selectBank('하나')">
                                    <img src="../../../../img/bank-img/hana-logo.png" alt="하나은행">
                                    <span>하나</span>
                                </div>
                                <div class="bank-item" data-bank="국민" @click="selectBank('국민')">
                                    <img src="../../../../img/bank-img/kb-logo.png" alt="국민은행">
                                    <span>국민</span>
                                </div>
                                <div class="bank-item" data-bank="우리" @click="selectBank('우리')">
                                    <img src="../../../../img/bank-img/woori-logo.png" alt="우리은행">
                                    <span>우리</span>
                                </div>
                                <div class="bank-item" data-bank="신한" @click="selectBank('신한')">
                                    <img src="../../../../img/bank-img/shinhan-logo.png" alt="신한은행">
                                    <span>신한</span>
                                </div>
                                <div class="bank-item" data-bank="기업" @click="selectBank('기업')">
                                    <img src="../../../../img/bank-img/ibk-logo.png" alt="기업은행">
                                    <span>기업</span>
                                </div>
                                <div class="bank-item" data-bank="대구" @click="selectBank('대구')">
                                    <img src="../../../../img/bank-img/daegu-logo.png" alt="대구은행">
                                    <span>대구</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 주요 사항 입력 -->
                <div class="form-group mb-5">
                    <label for="memo">주요사항</label>
                    <textarea type="text" id="memo" v-model="memo" placeholder="침수 및 화재 이력 등 차량관련 주요사항이 있으면 적어주세요." rows="2"></textarea>
                </div>
                <h5><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
                <!-- 파일 첨부 -->
     <!--           <button type="button" class="btn btn-fileupload mt-3" @click="triggerFileUpload">
                    파일 첨부
                </button>
                <input type="file" @change="handleFileUpload" ref="fileInputRef" style="display:none" id="file_user_photo">
                <p v-if="uploadedFileName">첨부 파일 : {{ uploadedFileName }}</p>-->
                <div class="flex items-center justify-end mt-5">
                    <button type="submit" class="btn primary-btn normal-16-font">경매 신청하기</button>
                </div>
                <transition name="fade">
                    <Modal v-if="showAuctionModal" @close="handleAuctionClose" />
                </transition>
            </form>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, computed, nextTick } from 'vue';
import { useStore } from 'vuex';
import Modal from '@/views/modal/modal.vue';
import useAuctions from '@/composables/auctions';
import { regions, updateDistricts } from '@/hooks/selectBOX.js';
import Swal from 'sweetalert2';

// Auction 관련 데이터와 함수 가져오기
const { createAuction } = useAuctions();
const store = useStore();
const user = computed(() => store.getters['auth/user']);

// 반응형 변수들 선언
const ownerName = ref(''); // 소유자 이름
const isVerified = ref(false); // 본인 인증 상태
const carNumber = ref(''); // 차량 번호
const mileage = ref(''); // 주행 거리
const selectedRegion = ref(''); // 선택된 지역
const selectedDistrict = ref(''); // 선택된 구/군
const availableDistricts = ref([]); // 선택된 지역에 따른 구/군 목록
const addr = ref(''); // 주소
const addrdt = ref(''); // 상세 주소
const account = ref(''); // 계좌 번호
const memo = ref(''); // 메모
const uploadedFileName = ref(''); // 업로드된 파일 이름
const showDetails = ref(false); // 은행 선택 모달 표시 여부
const accountDetails = ref(false); // 계좌 번호 입력 표시 여부
const selectedBank = ref(''); // 선택된 은행
const showAuctionModal = ref(false); // 경매 신청 모달 표시 여부
const isActive = ref(false); // 은행 선택 모달 활성화 상태
const fileInputRef = ref(null); // 파일 입력 참조

// 경매 등록 함수
const auctionEntry = async () => {
    // 필수 정보를 확인
    if (!isVerified.value || !ownerName.value.trim() || !carNumber.value.trim() || !selectedRegion.value.trim() || !addr.value.trim() || !addrdt.value.trim() || !selectedBank.value.trim() || !account.value.trim()) {
        Swal.fire({
            icon: 'error',
            title: '필수 정보를 입력해 주세요.',
            text: '소유자 본인 인증 및 필수 정보를 모두 입력해야 합니다.'
        });
        return;
    }

    const auctionData = {
        owner_name: ownerName.value,
        car_no: carNumber.value,
        region: selectedRegion.value,
        addr1: addr.value,
        addr2: addrdt.value,
        bank: selectedBank.value,
        account: account.value,
        memo: memo.value,
    };
    try {
        await createAuction({ auction: auctionData });
        showAuctionModal.value = true; // 성공 시 모달 표시
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Failed to create auction'
        });
        console.error('Failed to create auction:', error);
    }
};

// 지역 변경 시 구/군 목록 업데이트 함수
const onRegionChange = () => {
    availableDistricts.value = updateDistricts(selectedRegion.value);
    selectedDistrict.value = '';
};

// 소유자 본인인증 처리 함수
const verifyOwner = () => {
    if (ownerName.value.trim() === '') {
        alert('소유자 이름을 입력해 주세요.');
    } else {
        alert('본인인증되었습니다.');
        isVerified.value = true;
    }
};

// 본인인증 후 소유자 정보 수정 여부 확인 함수
const confirmEditIfDisabled = () => {
    if (isVerified.value) {
        if (confirm('소유자 정보를 수정하시겠습니까?')) {
            isVerified.value = false;
        }
    }
};

// 파일 업로드 트리거 함수
const triggerFileUpload = () => {
    if (fileInputRef.value) {
        fileInputRef.value.click();
    } else {
        console.error('파일을 찾을 수 없습니다.');
    }
};

// 파일 업로드 처리 함수
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        uploadedFileName.value = file.name;
    } else {
        console.error('No file selected');
    }
};

// 경매 신청 모달 닫기 함수
const handleAuctionClose = () => {
    showAuctionModal.value = false;
};

// 은행 선택 라벨 클릭 시 처리 함수
const handleBankLabelClick = () => {
    showDetails.value = true;
};

// 은행 선택 처리 함수
const selectBank = (bankName) => {
    selectedBank.value = bankName;
    showDetails.value = false;
    accountDetails.value = true;
    isActive.value = false;
};

// 은행 선택 모달 닫기 함수
const closeDetailContent = () => {
    showDetails.value = false;
    isActive.value = false;
};

// 은행 선택 모달 토글 함수
const toggleDetailContent = () => {
    if (!isActive.value) {
        nextTick(() => {
            isActive.value = true;
        });
    } else {
        isActive.value = false;
    }
};

// 컴포넌트가 마운트될 때 실행되는 함수
onMounted(() => {
    const carDetailsJSON = localStorage.getItem('carDetails');
    if (carDetailsJSON) {
        const carDetails = JSON.parse(carDetailsJSON);
        if (carDetails.owner) {
            ownerName.value = carDetails.owner;
        }
        if (carDetails.no) {
            carNumber.value = carDetails.no;
        }
    }
});
</script>


<style>
.account-num {
    display: none;
}
.block {
    display: block !important;
}
.grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
}
.bank-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    text-align: center;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: transform 0.3s;
}
.bank-item:hover {
    transform: scale(1.05);
}
.bank-item img {
    width: 50px;
    height: 50px;
    margin-bottom: 5px;
}
.bank-item span {
    font-size: 14px;
    color: #333;
}
.show-content {
    visibility: hidden;
    opacity: 0;
    transition: visibility 0s linear 0.5s, opacity 0.5s linear;
}
.show-content.active {
    visibility: visible;
    opacity: 1;
    transition: opacity 0.5s linear;
}
.no-scroll {
    overflow: hidden;
}
.link {
    text-decoration: underline !important;
}
.none-complete-ty02 span::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 91px;
    height: 91px;
    background-image: url('../../../../img/electric-car.png');
    background-size: contain;
    background-repeat: no-repeat;
    z-index: 1;
}
.none-complete-ty02 {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    height: 160px;
    background-color: #f5f5f6;
}
.detail-content02.active {
    bottom: 0px;
}
.middle {
    display: flex;
    margin-top: 35px;
    justify-content: space-between;
}
.detail-content02 .top-content {
    display: flex;
    margin-top: 35px;
    height: 50px;
    background-color: #ebedf1;
    border-radius: 6px;
    justify-content: space-between;
    padding: 13px;
}
.detail-content02 {
    background-color: #f8f9fa;
    box-shadow: 0px -7px 16px 0px rgba(212, 212, 212, 0.53);
    border-top-right-radius: 30px;
    border-top-left-radius: 30px;
    position: absolute;
    width: 100%;
    left: 0;
    transition: top 0.5s ease;
    padding: 5px 20px;
    box-sizing: border-box;
    top: auto;
}
@media (max-width: 400px) {
    .detail-content02.active {
        top: calc(640px - 540px);
        height: auto;
    }
}
@media (min-width: 401px) and (max-width: 768px) {
    .detail-content02 {
        top: auto;
    }
    .detail-content02.active {
        top: calc(640px - 530px);
        height: auto;
    }
}
@media (min-width: 769px) and (max-width: 1024px) {
    .detail-content02 {
        top: auto;
    }
    .detail-content02.active {
        top: calc(600px - 250px);
        height: auto;
    }
}
@media (min-width: 1025px) {
    .detail-content02.active {
        top: calc(600px - 250px);
        height: auto;
    }
}
.machine-inform-title {
    list-style: none;
    padding: 0;
    margin-top: 30px;
    display: flex;
    justify-content: flex-start;
}
.machine-inform {
    list-style: none;
    padding: 0;
    margin-top: 10px;
    display: flex;
    justify-content: flex-start;
}
.sub-title {
    margin-right: 20px;
}
.machine-inform-title li,
.machine-inform li {
    flex: 1;
}
.machine-inform-title li.car-icon {
    flex: 0 0 20px;
    height: 20px;
    background-image: url('../../../../img/clean.png');
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    background-color: #fff;
    margin-left: auto;
}
.machine-inform-title li.info-num,
.machine-inform li.sub-title {
    flex: 1.5;
}
.btn-fileupload::before {
    content: '';
    width: 13px;
    height: 20px;
    background-image: url('../../../img/Icon-upload.png');
    margin-right: 8px;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    display: inline-block;
    vertical-align: text-bottom;
}
.image-file {
    display: flex;
    width: 100%;
    overflow-x: auto;
    flex-direction: row;
    flex-wrap: nowrap;
    gap: 10px;
}
.file-upload {
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.image-container {
    width: 100px;
}
.image-preview {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    padding: 10px 0;
}
.image-preview .image-container {
    flex-shrink: 0;
    position: relative;
    margin-right: 40px;
    margin-bottom: 10px;
    width: 200px;
    height: 150px;
    overflow: hidden;
    border-radius: 20px;
}
.image-preview img {
    width: 100px;
    height: auto;
    display: block;
    object-fit: fill;
    width: 100%;
    height: 100%;
}
.modal-dialog-ty02 {
    height: 100%;
    width: 100%;
    margin: 0px !important;
    padding: 0px !important;
}
.image-preview .delete-button {
    position: absolute;
    top: 8px;
    right: 11px;
    padding: 2px 5px;
    background-color: white;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    color: black;
    border: none;
    cursor: pointer;
}
</style>
