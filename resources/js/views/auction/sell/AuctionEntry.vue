<template>
  <div class="container mov-wide">
    <div class="main-contenter mt-4">
      <h4 class="top-title">경매 할 차량을 등록해볼까요?</h4>
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
        <div class="form-group">
          <label for="finalAt">경매 종료 시간</label>
          <input type="datetime-local" id="finalAt" v-model="finalAt">
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
          <label for="addr">주소</label>
          <input type="text" id="addr_post" v-model="addrPost" placeholder="우편주소" @click="openPostcodePopup">
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
        <BankModal :showDetails="showDetails" @update:showDetails="showDetails = $event" @select-bank="selectBank" />
        <!-- 주요 사항 입력 -->
        <div class="form-group mb-5">
          <label for="memo">주요사항</label>
          <textarea type="text" id="memo" v-model="memo" placeholder="침수 및 화재 이력 등 차량관련 주요사항이 있으면 적어주세요." rows="2"></textarea>
        </div>
        <h5><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
        <input type="file" @change="handleFileUploadOwner" ref="fileInputRefOwner" style="display:none">
        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadOwner">
          파일 첨부
        </button>
        <div class="text-start tc-light-gray" v-if="fileUserOwnerName">위임장 / 소유자 인감 증명서: {{ fileUserOwnerName }}</div>
        <!-- 파일 첨부 -->
        <div class="flex items-center justify-end mt-5">
          <button type="submit" class="btn primary-btn normal-16-font w-100">경매 신청하기</button>
        </div>
          <Modal v-if="showAuctionModal" @close="handleAuctionClose" />
      </form>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, computed, nextTick, watch } from 'vue';
import { useStore } from 'vuex';
import Modal from '@/views/modal/modal.vue';
import BankModal from '@/views/modal/bank/BankModal.vue';
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
const finalAt = ref(''); // 경매 종료 시간
const mileage = ref(''); // 주행 거리
const selectedRegion = ref(''); // 선택된 지역
const selectedDistrict = ref(''); // 선택된 구/군
const availableDistricts = ref([]); // 선택된 지역에 따른 구/군 목록
const addrPost = ref('');
const addr = ref(''); // 주소
const addrdt = ref(''); // 상세 주소
const account = ref(''); // 계좌 번호
const selectedTab = ref('bank'); // 선택된 탭
const memo = ref(''); // 메모
const uploadedFileName = ref(''); // 업로드된 파일 이름
const showDetails = ref(false); // 은행 선택 모달 표시 여부
const accountDetails = ref(false); // 계좌 번호 입력 표시 여부
const selectedBank = ref(''); // 선택된 은행
const showAuctionModal = ref(false); // 경매 신청 모달 표시 여부
const isActive = ref(false); // 은행 선택 모달 활성화 상태
const fileInputRef = ref(null); // 파일 입력 참조
const startY = ref(0);
const currentY = ref(0);
const isDragging = ref(false);
const fileInputRefOwner = ref(null);
const fileUserOwner = ref(null); // 추가: 파일 저장 변수
const fileUserOwnerName = ref(''); // 추가: 파일 이름 저장 변수

// 경매 등록 함수
const auctionEntry = async () => {
  // 필수 정보를 확인
  if (
    !isVerified.value ||
    !ownerName.value.trim() ||
    !carNumber.value.trim() ||
    !selectedRegion.value.trim() ||
    !addr.value.trim() ||
    !addrdt.value.trim() ||
    !selectedBank.value.trim() ||
    !account.value.trim() ||
    !addrPost.value.trim() ||
    !finalAt.value.trim()
   // !fileUserOwner.value // 파일이 업로드되었는지 확인
  ) {
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
    final_at: finalAt.value,
    region: selectedRegion.value,
    addr1: addr.value,
    addr2: addrdt.value,
    bank: selectedBank.value,
    account: account.value,
    memo: memo.value,
    addr_post: addrPost.value,
  //  file_user_owner: fileUserOwner.value, // 업로드된 파일 추가
    status:"diag"
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
const handleFileUpload = event => {
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
  nextTick(() => {
    isActive.value = true;
  });
};

// 탭 선택 처리 함수
const selectTab = tab => {
  selectedTab.value = tab;
};

// 은행 선택 처리 함수
const selectBank = bankName => {
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

// 터치 시작 이벤트 핸들러
const handleTouchStart = event => {
  startY.value = event.touches[0].clientY;
  isDragging.value = true;
};

// 터치 이동 이벤트 핸들러
const handleTouchMove = event => {
  if (!isDragging.value) return;
  currentY.value = event.touches[0].clientY;
  const diffY = currentY.value - startY.value;
  if (diffY > 0) {
    document.querySelector('.detail-content02').style.transform = `translateY(${diffY}px)`;
  }
};

const handleFileUploadOwner = event => {
  const file = event.target.files[0];
  if (file) {
    fileUserOwner.value = file; // 파일 저장
    fileUserOwnerName.value = file.name; // 파일 이름 저장
    console.log("위임장 / 소유자 인감 증명서:", file.name);
  } else {
    console.error('No file selected');
  }
};

const triggerFileUploadOwner = () => {
  if (fileInputRefOwner.value) {
    fileInputRefOwner.value.click();
  } else {
    console.error('파일을 찾을 수 없습니다.');
  }
};

// 터치 종료 이벤트 핸들러
const handleTouchEnd = () => {
  isDragging.value = false;
  const diffY = currentY.value - startY.value;
  const halfHeight = window.innerHeight / 4;
  if (diffY > halfHeight) {
    closeDetailContent();
  } else {
    document.querySelector('.detail-content02').style.transform = 'translateY(0)';
  }
};

// Daum Postcode API를 활용한 주소 검색 팝업 열기
const openPostcodePopup = () => {
  const width = 500; // 팝업의 너비
  const height = 600; // 팝업의 높이
  const left = (window.innerWidth / 2) - (width / 2); // 화면 중앙에 위치하도록 좌측 좌표 계산
  const top = (window.innerHeight / 2) - (height / 2); // 화면 중앙에 위치하도록 상단 좌표 계산

  new daum.Postcode({
    oncomplete: data => {
      addrPost.value = data.zonecode; // 우편번호
      addr.value = data.address; // 주소
    },
    width: width,
    height: height,
    popupName: 'postcodePopup' // 팝업 이름 설정
  }).open({
    left: left + window.screenX,
    top: top + window.screenY
  });
};

watch(showDetails, val => {
  if (val) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});

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

  // Daum Postcode script 동적으로 로드
  const script = document.createElement('script');
  script.src = "//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js";
  script.onload = () => console.log('Daum Postcode script loaded');
  document.head.appendChild(script);
});
</script>
