<template>
    <div class="container">
        <div class="main-contenter mt-4">
            <h5 class="top-title">경매 할 차량을 등록해볼까요?</h5>
        </div>
        <div class="form-body mt-5">
            <form @submit.prevent="auctionEntry">
                <div class="form-group">
                    <label for="owner-name">소유자</label>
                    <div class="owner-certificat">
                        <input type="text" id="owner-name" v-model="ownerName" placeholder="홍길동">
                        <button class="btn certification">본인인증</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="carNumber">차량 번호</label>
                    <input type="text" id="carNumber" v-model="carNumber" placeholder="12 삼 4567">
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
                    <label for="carNumber">은행</label>
                    <select type="text" class="bank">
                        <option value="">은행 선택</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="carNumber">계좌 번호</label>
                    <input type="text" id="carNumber" v-model="carNumber" placeholder="계좌번호 직접입력">
                </div>
                <div class="form-group mb-5">
                    <label for="carNumber">주요사항</label>
                    <textarea type="text" id="carNumber" v-model="carNumber" placeholder="침수 및 화재 이력 등 차량관련 주요사항이 있으면 적어주세요." rows="2"></textarea>
                </div>
                <h5><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
                <button type="button"  class="btn btn-fileupload mt-3" @click="toggleModal" :style="buttonStyle">
                파일 첨부
                </button>
                <FilterModal v-if="showModal" @close="handleClose"/>
                <div class="flex items-center justify-end mt-5">
                <!-- 임시의 라우터 실제 폼작성 제출후 처리-->
                 <router-link :to="{ path: '/selldt2' }" class="btn primary-btn normal-16-font">경매 신청하기</router-link>
                </div>
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
import { ref } from 'vue';
import FilterModal from '@/views/modal/FileUpload.vue'; 

const showModal = ref(false);

function toggleModal() {
  showModal.value = !showModal.value; 
}

function handleClose() {
  showModal.value = false;
}

</script>
<style>

.region{
    display: flex;
    justify-content: space-between;
}
.owner-certificat{
    display: flex;
}
.btn-fileupload {
        display: block;
        font-weight: bold;
        width: 100%;
        margin-top: 20px;
        padding: 0.7rem 0.75rem;
        font-size: 0.875rem;
        color: #abadb6;
        background-color: #F5F5F6;
        border: 1px solid #F5F5F6;
        border-radius: 0.3rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
            border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn-fileupload:hover {
        background-color: #e7e7e7;
        border: 1px solid #ced4da;
        color: #abadb6;
    }
.certification {
    margin-left: 10px;
    margin-top: 10px;
    width: auto;
    padding: 10px;
    background-color: #9fa4b6;
    color: #fff;
    height: 50px;
}
.certification:hover{
    background-color: #878b9b !important;
    color: #fff !important;
}
    .form-body {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-size: 14px;
        color: #9c9c9c;
    }
    .form-group textarea {
        width: 100%;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-top: 10px;
        color: #9c9c9c;
        background-color: #fff !important;
        resize: none;
    }
    .form-group select {
        width: 48%;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-top: 10px;
        color: #9c9c9c;
        background-color: #fff !important;
    }
    .form-group input[type=owner-name]{
        width: 79%;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-top: 10px;
        background-color: #fff !important;
    }
    .form-group .bank,
    .form-group input[type=carNo],
    .form-group input[type=text] {
        width: 100%;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-top: 10px;
        background-color: #fff !important;
    }

</style>