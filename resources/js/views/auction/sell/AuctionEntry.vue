<template>
  <div class="container mov-wide">
    <div class="main-contenter mt-4">
      <h4 class="top-title">경매 할 차량을 등록해볼까요?</h4>
    </div>
    <div class="form-body mt-5">
      <form @submit.prevent="auctionEntry">
        <!-- 소유자 입력 -->
        <div class="form-group">
          <label for="owner-name"><span class="text-danger me-2">*</span>소유자</label>
          <div class="owner-certificat" @click="confirmEditIfDisabled">
            <input type="text" id="owner-name" v-model="ownerName" placeholder="홍길동" :disabled="isVerified">
            <button class="btn certification" @click.stop="verifyOwner" :disabled="isVerified">본인인증</button>
          </div>
        </div>
        <!-- 차량 번호 입력 -->
        <div class="form-group">
          <label for="carNumber"><span class="text-danger me-2">*</span>차량 번호</label>
          <input type="text" id="carNumber" v-model="carNumber" placeholder="12 삼 4567" :disabled="true">
        </div>
        <!-- 지역 선택 -->
        <div class="form-group">
          <label for="sido1"><span class="text-danger me-2">*</span> 지역</label>
          <div class="region">
            <select class="w-100" v-model="selectedRegion" @change="onRegionChange">
              <option value="">시/도 선택</option>
              <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
            </select>
          </div>
          <div class="text-danger mt-1">
            <div v-for="message in validationErrors?.region">
              {{ message }}
            </div>
          </div>
        </div>
        <!-- 주소 입력 -->
        <div class="form-group mb-2 input-wrapper">
          <input type="text" @click="editPostCode('daumPostcodeInput')" class="input-dis form-control" v-model="addrPost" placeholder="우편번호" readonly>
          <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')">검색</button>
          <div class="text-danger mt-1">
            <div v-for="message in validationErrors?.addr_post">
              {{ message }}
            </div>
          </div>
          <div>
            <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
              <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
            </div>
            <input type="text" v-model="addr" placeholder="주소" class="input-dis form-control" readonly>
            <div class="text-danger mt-1">
              <div v-for="message in validationErrors?.addr1">
                {{ message }}
              </div>
            </div>
          </div>
          <input type="text" v-model="addrdt" placeholder="상세주소">
          <div class="text-danger mt-1">
            <div v-for="message in validationErrors?.addr2">
              {{ message }}
            </div>
          </div>
        </div>
        <!-- 은행 선택 -->
        <div class="form-group mt-4">
          <label for="carNumber"><span class="text-danger me-2">*</span>은행</label>
          <input type="text" id="bank" placeholder="은행 선택" @click="handleBankLabelClick" v-model="selectedBank" readonly>
          <input type="text" v-model="account" placeholder="계좌번호" :class="{'block': accountDetails}" class="account-num">
        </div>
        <!-- 주요 사항 입력 -->
        <div class="form-group mt-5">
          <label for="memo"><span class="text-danger me-2">*</span>주요사항</label>
          <textarea type="text" id="memo" v-model="memo" placeholder="침수 및 화재 이력 등 차량관련 주요사항이 있으면 적어주세요." rows="2"></textarea>
        </div>
        <div class="form-group mb-5">
        <label for="datetime">
          <span class="text-danger me-2">*</span>진단희망 날짜 및 시간
        </label>
        <input type="datetime-local" id="datetime" name="datetime" class="form-control" required>
      </div>
        <h5><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
        <input type="file" @change="handleFileUploadOwner" ref="fileInputRefOwner" style="display:none">
        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadOwner">
          파일 첨부
        </button>
        <div class="text-start text-secondary opacity-50" v-if="fileAuctionProxyName">위임장 / 소유자 인감 증명서: {{ fileAuctionProxyName }}</div>
        <div class="form-group dealer-check fw-bolder pb-2">
          <p for="dealer">법인 / 사업자차량</p>
          <div class="check_box">
            <input type="checkbox" id="ch2" v-model="isBizChecked" class="form-control">
            <label for="ch2"></label>
          </div>
        </div>
        <!-- 파일 첨부 -->
        <div class="flex items-center justify-end mt-5">
          <button type="submit" class="btn primary-btn normal-16-font w-100">경매 신청하기</button>
        </div>
        <Modal v-if="showAuctionModal" @close="handleAuctionClose" />
      </form>
    </div>
  </div>
</template>
<script>
  // 현재 날짜와 시간을 가져와 `min` 속성에 설정
  const datetimeInput = document.getElementById("datetime");
  
  // 현재 날짜 및 시간 포맷팅 (YYYY-MM-DDTHH:MM)
  const now = new Date();
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0'); // 월은 0부터 시작
  const date = String(now.getDate()).padStart(2, '0');
  const hours = now.getHours();
  const minutes = now.getMinutes();
  
  // 오늘 날짜와 오전 9시를 기본 최소값으로 설정
  datetimeInput.min = `${year}-${month}-${date}T09:00`;

  // 오늘 날짜와 오후 6시를 최대값으로 설정
  datetimeInput.max = `${year}-${month}-${date}T18:00`;

  // 시간 제한: 오전 9시 ~ 오후 6시
  datetimeInput.addEventListener("change", (event) => {
    const selectedDateTime = new Date(event.target.value);

    const selectedHours = selectedDateTime.getHours();
    if (selectedHours < 9 || selectedHours > 18) {
      alert("시간은 오전 9시부터 오후 6시까지만 선택 가능합니다.");
      event.target.value = ''; // 잘못된 값은 초기화
    }
  });
</script>

<script setup>
import { ref, onMounted, nextTick, inject, createApp,computed  } from 'vue';
import { useStore } from 'vuex';
import Modal from '@/views/modal/modal.vue';
import BankModal from '@/views/modal/bank/BankModal.vue';
import useAuctions from '@/composables/auctions';
import { regions, updateDistricts } from '@/hooks/selectBOX.js';
import { cmmn } from '@/hooks/cmmn';
import carObjects from '../../../../../resources/img/modal/car-objects-blur.png';

const { openPostcode, closePostcode } = cmmn();
const { wica } = cmmn();
const { createAuction, validationErrors } = useAuctions();
const store = useStore();
const swal = inject('$swal');

const ownerName = ref(''); // 소유자 이름
const isVerified = ref(false); // 본인 인증 상태
const carNumber = ref(''); // 차량 번호
const finalAt = ref(''); // 경매 종료 시간
const selectedRegion = ref(''); // 선택된 지역
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
const fileAuctionProxy = ref(null); // 추가: 파일 저장 변수
const fileAuctionProxyName = ref(''); // 추가: 파일 이름 저장 변수
const isBizChecked = ref(false); // 체크박스 상태를 저장하는 변수
const is_biz = computed({
  get() {
    return isBizChecked.value ? 1 : 0;
  },
  set(value) {
    isBizChecked.value = value === 1;
  }
});

const auctionEntry = async () => {
  // 필수 정보를 확인
  const auctionData = {
    owner_name: ownerName.value,
    car_no: carNumber.value,
    region: selectedRegion.value,
    addr1: addr.value,
    addr2: addrdt.value,
    bank: selectedBank.value,
    account: account.value,
    memo: memo.value,
    addr_post: addrPost.value,
    file_auction_proxy: fileAuctionProxy.value, // 업로드된 파일 추가
    is_biz : is_biz.value,
    status: "diag"
  };
  console.log(auctionData);
  if(isVerified.value){
    try {
      const result = await createAuction({ auction: auctionData });
      if(result){
          const textOk = `<div class="enroll_box" style="position: relative;">
                    <img src="${carObjects}" alt="자동차 이미지" width="160" height="160">
                    <p class="overlay_text02">경매 신청이 완료되었습니다.</p>
                    <p class="overlay_text03">진단평가 완료까지 조금만 기다려주세요!</p>
                  </div>`;
        wica.ntcn(swal)
        .useHtmlText() // HTML 태그 인 경우 활성화
        .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
        .addOption({ padding: 20 }) // swal 기타 옵션 추가
        .callback(function (result) {
          window.location.href = '/auction';
        })
        .confirm(textOk);
      }
    } catch (error) {
    }
  }else{
    wica.ntcn(swal)
    .title('')
    .useHtmlText()
    .icon('E')
    .callback(function(result) {
        //console.log(result);
    }).alert('본인인증 후에 이용 가능한 서비스입니다.');
  }
};

const onRegionChange = () => {
  updateDistricts(selectedRegion.value);
};

const verifyOwner = () => {
  if (ownerName.value.trim() === '') {
    wica.ntcn(swal)
    .icon('W')
    .addOption({ padding: 20})
    .callback(function(result) {
    })
    .alert('소유자 이름을 입력해 주세요.');
  } else {
    wica.ntcn(swal)
    .icon('I')
    .addClassNm('cmm-review-custom')
    .addOption({ padding: 20})
    .callback(function(result) {
    })
    .alert('본인인증되었습니다.');
    isVerified.value = true;
  }
};

const confirmEditIfDisabled = () => {
  if (isVerified.value) {
      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
        if(result.isOk){
          isVerified.value = false;
        }
      })
    .alert('소유자 정보를 수정하시겠습니까?');
  }
};

const triggerFileUploadOwner = () => {
  if (fileInputRefOwner.value) {
    fileInputRefOwner.value.click();
  } else {
    console.error('파일을 찾을 수 없습니다.');
  }
};

const handleFileUploadOwner = event => {
  const file = event.target.files[0];
  if (file) {
    fileAuctionProxy.value = file; // 파일 저장
    fileAuctionProxyName.value = file.name; // 파일 이름 저장
    console.log("위임장 / 소유자 인감 증명서:", file.name);
  } else {
    console.error('No file selected');
  }
};

const handleBankLabelClick = async () => {
  const module = await import('@/views/modal/bank/BankModal.vue');
  const BankModalComponent = module.default;

  swal.fire({
    html: '<div id="modal-content"></div>',
    customClass: 'bank-modal',
    showCloseButton: true,
    showConfirmButton: false,
    width: '600px',
    padding: '20px',
    didOpen: () => {
      nextTick(() => {
        const modalContent = document.getElementById('modal-content');
        if (modalContent) {
          const app = createApp(BankModalComponent, {
            onSelectBank: (bankName) => {
              selectedBank.value = bankName;
              swal.close();
            }
          });
          app.mount(modalContent);
        }
      });
    }
  });
};

function editPostCode(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
        addrPost.value = zonecode;
        addr.value = address;
    })
}

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

<style scoped>
@media (min-width: 992px){
  .mov-wide {
      width: 45rem !important;
  }
}
.input-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

.input-dis {
    padding-right: 50px; 
}

.search-btn {
    cursor: pointer;
    position: absolute;
    right: 0;
    top: 38px;
    transform: translateY(-49%);
    height: 40px;
    width: 40px;
    border: none;
    background-color: transparent;
    background-image: url('../../../../img/search.png');
    background-repeat: no-repeat;
    background-position: center;
    background-size: 20px 20px;
    font-size: 0;
}
.flatpickr-calendar {
  font-family: "Arial", sans-serif;
}

.flatpickr-time .flatpickr-time-separator {
  color: #7a3535;
}

.flatpickr-day.flatpickr-disabled {
  cursor: not-allowed;
  color: rgba(57, 57, 57, 0.3);
  background: transparent;
  border-color: transparent;
}
/* 비활성화된 날짜 */
.flatpickr-day.flatpickr-disabled {
  cursor: not-allowed;
  color: #d3d3d3;
  background-color: #f9f9f9;
}

/* 활성화된 날짜 */
.flatpickr-day {
  cursor: pointer;
  color: #333333;
}

</style>
