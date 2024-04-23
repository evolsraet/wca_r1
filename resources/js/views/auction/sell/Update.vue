<template>
    <div class="container">
        <div class="main-contenter mt-4">
            <h5 class="top-title">경매 할 차량을 등록해볼까요?</h5>
        </div>
        <div class="form-body mt-5">
            <form @submit.prevent="auctionEntry" >
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
                <h5><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
                <button type="button"  class="btn btn-fileupload mt-3" @click="toggleModal" :style="buttonStyle">
                파일 첨부
                </button>
                <div class="flex items-center justify-end mt-5">
                    <router-link to="/sell" class="btn primary-btn normal-16-font">정보 수정하기</router-link>
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
import Modal from '@/views/modal/modal.vue';

const showModal = ref(false);
const showAuctionModal = ref(false);

function toggleModal() {
  showModal.value = !showModal.value; 
}

function handleClose() {
  showModal.value = false;
}

function openAuctionModal() {
  showAuctionModal.value = true;
}

function handleAuctionClose() {
  showAuctionModal.value = false;
}
</script>
