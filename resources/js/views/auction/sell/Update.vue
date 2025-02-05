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
              <button class="btn border certification">본인인증</button>
            </div>
          </div>
          <div class="form-group">
            <label for="carNumber">차량 번호</label>
            <input type="text" id="carNumber" v-model="carNumber" placeholder="12 삼 4567">
          </div>
          <!-- 지역 선택 -->
          <div class="form-group">
            <label for="sido1">지역</label>
            <div class="region">
                <select v-model="selectedRegion" @change="onRegionChange">
                <option value="">시/도 선택</option>
                <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
                </select>
            </div>
          </div>
          <!-- 주소 입력 -->
          <div class="form-group mb-5">
            <input type="text" @click="editPostCode('daumPostcodeInput')" class="text-secondary opacity-50" v-model="addrPost" placeholder="우편번호" readonly>
            <div>
                <input type="text" v-model="addr" placeholder="주소" class="searchadress text-secondary opacity-50" readonly>
                <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')">검색</button>
            </div>
            
            <input type="text" v-model="adddt" placeholder="상세주소">
            <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
            </div>
        </div>
          <h5><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
          <button type="button" class="btn btn-fileupload w-100 mt-3" @click="triggerFileUpload">
            파일 첨부
          </button>
          <input type="file" @change="handleFileUpload" ref="fileInputRef" style="display:none" id="file_user_photo">
          <div class="flex items-center justify-end mt-5">
            <router-link to="/sell" class="btn primary-btn w-100 normal-16-font">정보 수정하기</router-link>
          </div>
        </form>
      </div>
    </div>
  </template>
<script setup>
import { ref, computed,onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { regions, updateDistricts } from '@/hooks/selectBOX.js';
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';

const { openPostcode , closePostcode, wicac} = cmmn();
const { carInfoForm, updateAuction, processing, validationErrors } = useAuctions();

const ownerName = ref('');
const carNumber = ref('');
const selectedRegion = ref('');
const selectedDistrict = ref('');
const addrPost = ref('');
const addr = ref(''); // 주소
const addrdt = ref(''); // 상세 주소
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

  const response = wicac.conn()
  .url(`/api/auctions/${carNumber.value}`) 
  .param(updateData)
  .callback(function(result) {
      if(result.isError){
        wica.ntcn(swal)
        .title('오류가 발생하였습니다.')
        .useHtmlText()
        .icon('I') //E:error , W:warning , I:info , Q:question
        .alert('관리자에게 문의해주세요.');
      }else{
        router.push('/sell');
      }
  })
  .put();
}

function triggerFileUpload() {
  fileInputRef.value?.click();
}

function editPostCode(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
        addrPost.value = zonecode;
        addr.value = address;
    })
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
