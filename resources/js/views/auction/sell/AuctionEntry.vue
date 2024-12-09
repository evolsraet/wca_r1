<template>
  <div class=" mov-wide">
  <div class="container">
    <div class="main-contenter mt-4">
      <h4 class="top-title mb-2">경매 할 차량을 등록해볼까요?</h4>
    </div>
    <div class="form-body mt-4">
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
        <input
      type="datetime-local"
      id="datetime"
      ref="datetimeInput"
      class="form-control"
      required
    />
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
        <div>
        <P class="bold size_16">첨부해야하는 서류를 확인해 주세요</P>
        <div>
      </div>
      </div>
    </form>
    </div>
    
    <!-- 사업자 또는 법인 섹션 -->
  </div>
  <!-- 파일 첨부 -->
  <!-- 일반 고객 섹션 -->
    <div class="dropdown border-bottom">
      <button
        class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
        @click="toggleDropdown('general')"
      >
        일반 고객
        <img
          :src="openSection === 'general' ? iconUp : iconDown"
          alt="Dropdown Icon"
          class="dropdown-icon"
          width="14"
        />
      </button>
      <transition name="slide">
      <div v-show="openSection === 'general'" class="dropdown-content mt-0 p-4">
        <p class="mb-1">1. 자동차등록증 원본</p>
        <p class="mb-1">2. 자동차매도용인감증명서 또는 본인 서명 사실 확인서 (매수자 인적사항 기재 필수)</p>
        <p class="ms-3 tc-gray mb-1">매수자 인적사항은 판매딜러가 결정되면 고객에게 인적사항이 통보됩니다.</p>
        <p class="mb-1">· 공동명의인 경우 명의자 각자 서류 필요</p>
        <p class="mb-1">· 수출 폐차딜러에게 판매된다면 인감증명서 대신 신분증 사진이 필요​합니다.</p>
      </div>
    </transition>
    </div>

      <div class="dropdown border-bottom">
        <button
          class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
          @click="toggleDropdown('business')"
        >
          사업자 또는 법인
          <img
            :src="openSection === 'business' ? iconUp : iconDown"
            alt="Dropdown Icon"
            class="dropdown-icon"
            width="14"
          />
        </button>
        <transition name="slide">
          <div v-show="openSection === 'business'" class="dropdown-content p-4">
            <p class="bold mb-2">사업용으로 사용한 경우, 기본 필수 서류</p>
            <div>
            <p>1. 자동차등록증 원본</p>
            <p>2. 자동차매도용 인감증명서 (매수자 인적사항 기재 필수)</p>
            <p>3. 사업자동등록증</p>
            <p class="bolder my-2">사업용으로 사용한 경우, 기본 필수 서류</p>
            <p>복식부기</p>
            <p>1. 자동차등록증 원본</p>
            <p>2. 자동차매도용 인감증명서 (매수자 인적사항 기재 필수)</p>
            <p>3. 일반 인감증명서 (사실확인 증빙용)</p>
            <p>4. 사업자동등록증 사본</p>
            <p>5. 비사업용 사실확인서 (세무사 명판 날인)</p>
            <p class="mb-3">6. ‘차량운반구 감가상각비명세서’ 또는 ‘고정자산명세서’</p>
            <p>간편등록부의 경우, 상기 1~5번 제출 서류들과 ‘총 수입금액 및 필요경비명세서’가 필요합니다.</p>
            <div class="my-4">
              <p class="bolder">간이과세자</p>
              <p>1. 기본 필수 서류</p>
            </div>
            <div class="my-4">
              <p class="bolder">간이과세자 (세금계산서 발급사업자)</p>
              <p>1. 기본 필수 서류</p>
              <p>2. 세금계산서 (부가세 포함)</p>
            </div>
            <div class="my-4">
              <p class="bolder">면세사업자</p>
              <p>1. 기본 필수 서류</p>
              <p>2. 세금계산서 (부가세 면제)</p>
            </div>
            <div class="mt-4">
              <p class="bolder">법인사업자</p>
              <p>1. 기본 필수 서류</p>
              <p>2. 세금계산서 (부가세 면제)</p>
              <p>3. 법인등기부등본 (15일내에 발급, 말소사항 포함)</p>
              <p>4. 양도계약서</p>
            </div>
          </div>
        </div>
      </transition>
        </div>
          <div class="px-2 mb-3 flex items-center justify-end mt-5">
            <button type="submit" class="btn primary-btn normal-16-font w-100">경매 신청하기</button>
          </div>
          <Modal v-if="showAuctionModal" @close="handleAuctionClose" />
      </div>
</template>
<script>
import iconUp from "../../../../img/Icon-black-up.png";
import iconDown from "../../../../img/Icon-black-down.png";

export default {
  data() {
    return {
      openSection: null, // 열려 있는 섹션 상태
      iconUp,
      iconDown,
    };
  },
  methods: {
    toggleDropdown(type) {
      this.openSection = this.openSection === type ? null : type; // 현재 섹션 토글
      console.log(`${type} 상태:`, this.openSection === type);
    },
  },
};
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
const holidays = [
  "2024-01-01",
  "2024-03-01",
  "2024-05-05",
  "2024-08-15",
  "2024-10-03",
  "2024-12-25",
];
const openSection = ref(null);

const toggleDropdown = (section) => {
  openSection.value = openSection.value === section ? null : section;
};
const datetimeInput = ref(null);

const isWeekend = (dateString) => {
  const date = new Date(dateString);
  const day = date.getDay(); // 0: 일요일, 6: 토요일
  return day === 0 || day === 6;
};

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
  if (datetimeInput.value) {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const date = String(now.getDate()).padStart(2, "0");

    // 기본 최소값 설정 (오늘 날짜의 오전 9시)
    datetimeInput.value.min = `${year}-${month}-${date}T09:00`;

    // change 이벤트 리스너 추가
    datetimeInput.value.addEventListener("change", (event) => {
      const selectedDateTime = event.target.value;
      const selectedDate = selectedDateTime.split("T")[0]; // YYYY-MM-DD
      const selectedTime = new Date(selectedDateTime).getHours(); // 시간 (24시간 형식)

      if (
        new Date(selectedDate) < new Date(`${year}-${month}-${date}`) || // 오늘 이전 날짜 선택 제한
        isWeekend(selectedDate) || // 주말 선택 제한
        selectedTime < 9 || // 오전 9시 이전 선택 제한
        selectedTime > 18 // 오후 6시 이후 선택 제한
      ) {
        alert(
          "주말, 오늘 이전 날짜 또는 오전 9시~오후 6시를 벗어난 시간은 선택할 수 없습니다."
        );
        event.target.value = ""; // 잘못된 값 초기화
      }
    });
  } else {
    console.error("datetimeInput을 찾을 수 없습니다.");
  }
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
.dropdown {
  margin-bottom: 10px;
}

.dropdown-btn {
  padding: 10px;
  width: 100%;
  text-align: left;
  font-size: 16px;
  cursor: pointer;
}

.dropdown-content {
  display: block;
  max-height:none;
  padding: 10px;
  border-top: none;
  background-color: #F5F5F6;
}
/* Slide animation */
.slide-enter-active,
.slide-leave-active {
  transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
  overflow: hidden;
}
.slide-enter-from,
.slide-leave-to {
  max-height: 0;
  opacity: 0;
}
.slide-enter-to,
.slide-leave-from {
  max-height: 500px; /* 적절한 최대 높이 값으로 조정 */
  opacity: 1;
}

</style>
