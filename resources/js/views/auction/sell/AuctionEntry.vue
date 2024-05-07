<template>
    <div class="container">
        <div class="main-contenter mt-4">
            <h5 class="top-title">경매 할 차량을 등록해볼까요?</h5>
        </div>
        <div class="form-body mt-5">
            <form @submit.prevent="auctionEntry">
                <div class="form-group">
                    <label for="owner-name">소유자</label>
                    <div class="owner-certificat" @click="confirmEditIfDisabled">
                        <input type="text" id="owner-name" v-model="ownerName" placeholder="홍길동" :disabled="isVerified">
                        <button class="btn certification" @click.stop="verifyOwner" :disabled="isVerified">본인인증</button>
                    </div>
                </div>
                <div class="form-group">    
                    <label for="carNumber">차량 번호</label>
                    <input type="text" id="carNumber" v-model="carNumber" placeholder="12 삼 4567" :disabled="true">
                </div>
                <div class="form-group">
                    <label for="mileage">주행거리</label>
                    <input type="text" id="mileage" v-model="mileage" placeholder="Km">
                </div>
                <div class="form-group">
                    <label for="sido1">지역</label>
                    <div class="region">
                    <select v-model="selectedRegion" @change="onRegionChange">
                    <option value="">시/도 선택</option>
                    <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
                </select>
                <select v-model="selectedDistrict">
                    <option value="">구/군 선택</option>
                    <option v-for="district in availableDistricts" :key="district" :value="district">{{ district }}</option>
                </select>
                </div>
                </div>
                <div class="form-group mb-5">
                    <label for="addr">주소</label>
                    <input type="text" id="addr" v-model="addr" placeholder="주소">
                    <input type="text" id="adddt" v-model="addrdt" placeholder="상세주소">
                </div>
                <h5>경매 시 사용할 계좌가 필요해요</h5>
                <div class="form-group mt-4">
                    <label for="bank">은행</label>
                    <select type="text" class="bank">
                        <option value="">은행 선택</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bankNumber">계좌 번호</label>
                    <input type="text" id="bankNumber" v-model="bankNumber" placeholder="계좌번호 직접입력">
                </div>
                <div class="form-group mb-5">
                    <label for="memo">주요사항</label>
                    <textarea type="text" id="memo" v-model="memo" placeholder="침수 및 화재 이력 등 차량관련 주요사항이 있으면 적어주세요." rows="2"></textarea>
                </div>
                <h5><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
                <button type="button"  class="btn btn-fileupload mt-3" @click="triggerFileUpload">
                파일 첨부
                </button>
                <input type="file" @change="handleFileUpload" ref="fileInputRef" style="display:none" id="file_user_photo">
                <p v-if="uploadedFileName">첨부 파일 : {{ uploadedFileName }}</p>
                <div class="flex items-center justify-end mt-5">
                <!-- 임시의 라우터 실제 폼작성 제출후 처리-->
                <button @click.prevent="openAuctionModal" class="btn primary-btn normal-16-font">경매 신청하기</button>
                </div>
                <transition name="fade">                
                    <Modal v-if="showAuctionModal" @close="handleAuctionClose" />
                </transition>
            </form>
        </div>
    </div>
</template>

<script>
import {regions, updateDistricts } from'@/hooks/selectBOX.js';

export default {
    data() {
        return {
            ownerName: '',
            carNumber: '',
            mileage: '',
            selectedRegion: '',
            selectedDistrict: '',
            regions: regions,
            availableDistricts: []
        };
    },
    methods: {
        auctionEntry() {
            console.log("Auction Entry Submitted", this.ownerName, this.carNumber, this.mileage);
        },
        onRegionChange() {
            this.availableDistricts = updateDistricts(this.selectedRegion);
            this.selectedDistrict = ''; 
        }
    }
};
</script>
<script setup>
import { ref, computed,onMounted } from 'vue';
import Modal from '@/views/modal/modal.vue';
import { useStore } from "vuex";

const store = useStore();
const user = computed(() => store.getters["auth/user"]);

const ownerName = ref('');// 사용자 이름을 초기값으로 설정
const isVerified = ref(false); // 본인인증 상태
const carNumber = ref('');
const showAuctionModal = ref(false);
const fileInputRef = ref(null);
const uploadedFileName = ref('');

// 본인인증 함수
function verifyOwner() {
    if (ownerName.value.trim() === '') {
        alert('소유자 이름을 입력해 주세요.');
    } else {
        alert('본인인증되었습니다.');
        isVerified.value = true; // 본인인증 상태를 참으로 설정하여 비활성화
    }
}

// 비활성화 상태에서 입력 필드 클릭 시 처리
function confirmEditIfDisabled() {
    if (isVerified.value) {
        if (confirm('소유자 정보를 수정하시겠습니까?')) {
            isVerified.value = false; // 재활성화
        }
    }
}

function triggerFileUpload() {
    if (fileInputRef.value) {
        fileInputRef.value.click();
    } else {
        console.error("파일을 찾을수 없습니다.");
    }
}

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file) {
        uploadedFileName.value = file.name;
        console.log("File uploaded:", file.name);
    } else {
        console.error("No file selected");
    }
}

function openAuctionModal() {
  showAuctionModal.value = true;
}

function handleAuctionClose() {
  showAuctionModal.value = false;
}
onMounted(() => {
  // 로컬 스토리지에서 차량 세부 정보 불러오기
  const carDetailsJSON = localStorage.getItem('carDetails');
  if (carDetailsJSON) {
    const carDetails = JSON.parse(carDetailsJSON);  
    if (carDetails.owner) {
      ownerName.value = carDetails.owner;  // 소유자 이름 설정
    }
    if (carDetails.no) {
      carNumber.value = carDetails.no;  // 차량 번호 설정
    }
  }
});
</script>



