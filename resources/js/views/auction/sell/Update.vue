<template>
    <div class="container">
      <div class="main-contenter mt-4">
        <h5 class="top-title">차량 정보를 갱신합니다.</h5>
      </div>
      <div class="form-body mt-5">
        <form @submit.prevent="updateCurrentAuction">
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
          <button type="button" class="btn btn-fileupload mt-3" @click="triggerFileUpload">
            파일 첨부
          </button>
          <input type="file" @change="handleFileUpload" ref="fileInputRef" style="display:none" id="file_user_photo">
          <div class="flex items-center justify-end mt-5">
            <router-link to="/sell" class="btn primary-btn normal-16-font">정보 수정하기</router-link>
          </div>
        </form>
      </div>
    </div>
  </template>
<script setup>
import { ref, computed,onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { regions, updateDistricts } from '@/hooks/selectBOX.js';
import useAuctions from '@/composables/auctions';
const { carInfoForm, updateAuction, processing, validationErrors } = useAuctions();

const ownerName = ref('');
const carNumber = ref('');
const selectedRegion = ref('');
const selectedDistrict = ref('');
const addr = ref('');
const addrdt = ref('');
const fileInputRef = ref(null);
const router = useRouter();

const availableDistricts = ref([]);

const updateCurrentAuction = async () => {
  if (!processing.value) {
    await updateAuction(auctionId.value, carInfoForm);
  }
};
function onRegionChange() {
  availableDistricts.value = updateDistricts(selectedRegion.value);
}
async function auctionEntry() {
  const updateData = {
    ownerName: ownerName.value,
    carNumber: carNumber.value,
    region: selectedRegion.value,
    district: selectedDistrict.value,
    addr: addr.value,
    addrdt: addrdt.value
  };

  try {
    const response = await axios.put(`/api/auctions/${carNumber.value}`, updateData);
    console.log('Update successful:', response.data);
    router.push('/sell');
  } catch (error) {
    console.error('Failed to update auction:', error);
  }
}

function triggerFileUpload() {
  fileInputRef.value?.click();
}

function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    console.log("File uploaded:", file.name);
  }
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
