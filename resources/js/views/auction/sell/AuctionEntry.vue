<template>
  <div class="container mov-wide">
  <div class="container">
    <div class="main-contenter mt-4">
      <h4 class="top-title mb-2" v-if="auctionType !== '1'">경매 할 차량을 등록해볼까요?</h4>
      <h4 class="top-title mb-2" v-if="auctionType === '1'">공매 할 차량을 등록해볼까요?</h4>
    </div>
    <div class="form-body mt-4">
      <form @submit.prevent="auctionEntry">

        <!-- 공매&경매 구분 -->
        <!-- <div class="form-group">
          <label for="auction-type"><span class="text-danger me-2">*</span>공매&경매 구분</label>
          <select class="form-select" v-model="auctionType" id="auction-type">
            <option value="">공매&경매 구분 선택</option>
            <option value="1">공매</option>
            <option value="0">경매</option>
          </select>
        </div> -->

        <div class="form-group" v-if="auctionType === '1'">
          <label for="owner-name"><span class="text-danger me-2">*</span>회사명</label>
          <div class="owner-certificat" @click="confirmEditIfDisabled(carNumber)">
            <input type="text" id="owner-name" v-model="companyName" placeholder="회사명" :disabled="isVerified" ref="companyNameRef">
          </div>
        </div>

        <!-- 소유자 입력 -->
        <div class="form-group" v-if="auctionType !== '1'">
          <label for="owner-name"><span class="text-danger me-2">*</span>소유자</label>
          <div class="owner-certificat" @click="confirmEditIfDisabled(carNumber)">
            <input type="text" id="owner-name" v-model="ownerName" placeholder="홍길동" :disabled="isVerified">
            <!-- <button type="button" class="btn border certification" @click.stop="verifyOwner" :disabled="isVerified">본인인증</button> -->
            <SelfAuthModal id="selfAuth" :propData="showSelfAuthModal" :carNumber="carNumber" :ownerName="ownerName" v-model:isBusinessOwner="isBusinessOwner" v-model:isAuth="isAuth" @click.stop="verifyOwner" :disabled="isAuth" />
          </div>
          <div class="text-danger mt-2">※ 차량 소유자 확인을 위해 본인 인증 버튼을 클릭해주세요.</div>
        </div>

        <!-- 차량 번호 입력 -->
        <div class="form-group" v-if="auctionType !== '1'">
          <label for="carNumber"><span class="text-danger me-2">*</span>차량 번호</label>
          <input type="text" id="carNumber" v-model="carNumber" placeholder="12 삼 4567" :disabled="true">
        </div>
        
        <!-- 주소 입력 -->
        <label for="sido1" v-if="auctionType !== '1'"><span class="text-danger me-2">*</span> 주소 <br/><span class="text-danger">차량 실주소지를 입력해주세요. (차량 진단 가능 지역)</span></label>

        <div class="form-group mb-2 input-wrapper" v-if="auctionType !== '1'">
          <input type="text" @click="editPostCode('daumPostcodeInput')" class="input-dis form-control" v-model="addrPost" placeholder="우편번호" readonly ref="addrPostSelect">
          <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')" style="top:">검색</button>
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
          <input type="text" v-model="addrdt" placeholder="상세주소" ref="addrdtSelect" @keydown.enter="handleEnterPress('memoSelect')">
          <div class="text-danger mt-1">
            <div v-for="message in validationErrors?.addr2">
              {{ message }}
            </div>
          </div>
        </div>

        <!-- 지역 선택 -->
        <div class="form-group" v-if="auctionType !== '1'">
          <label for="sido1"><span class="text-danger me-2">*</span> 지역</label>
          <div class="region">
            <select class="w-100" v-model="selectedRegion" @change="onRegionChange" ref="regionSelect">
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

        <div class="form-group" v-if="auctionType !== '1'">
          <label for="memo"><span class="text-danger me-2">*</span>차량상태입력</label>

          <div class="form-check">
            <div class="row">
              <div class="col-3">
                <input class="form-check-input" type="checkbox" value="침수" id="flexCheckDefault1" @change="updateCarConditionValue()">
                <label class="form-check-label" for="flexCheckDefault1">
                  침수
                </label>
              </div>
              <div class="col-3">
                <input class="form-check-input" type="checkbox" value="화재" id="flexCheckDefault2" @change="updateCarConditionValue()">
                <label class="form-check-label" for="flexCheckDefault2">
                  화재 
                </label>
              </div>
              <div class="col-3">
                <input class="form-check-input" type="checkbox" value="엔진고장" id="flexCheckDefault3" @change="updateCarConditionValue()">
                <label class="form-check-label" for="flexCheckDefault3">
                  엔진고장
                </label>
              </div>
              <div class="col-3">
                <input class="form-check-input" type="checkbox" value="변속기고장" id="flexCheckDefault4" @change="updateCarConditionValue()">
                <label class="form-check-label" for="flexCheckDefault4">
                  변속기고장
                </label>
              </div>
            </div>
          </div>

          <div class="mt-1">
            <input type="text" id="car_condition" v-model="carCondition" placeholder="키로수">
          </div>

          <textarea type="text" id="memo" v-model="memo" placeholder="ex) 외관손상, 차량내부손상, 사고유무등 &#13;&#10;주의) 주요결함 미고지시 추후 환불등 불이익이 있을수 있습니다." rows="2" ref="memoSelect"></textarea>
        </div>

        <!-- 은행 선택 -->

        <h4 v-if="auctionType !== '1'">은행/진단 정보</h4>

        <div class="form-group mt-4">
          <label><span class="text-danger me-2">*</span>은행 <span class="text-secondary">( 매매대금 입금 받을 계좌 )</span></label>
          <input type="text" id="bank" placeholder="은행 선택" @click="handleBankLabelClick" v-model="selectedBank" readonly ref="bankSelect">
          <input type="text" v-model="account" placeholder="계좌번호" :class="{'block': accountDetails}" class="account-num" ref="accountSelect" @keydown.enter="handleEnterPress('diagFirstAtSelect')">
          <p class="text-danger">※ 계좌는 차량 소유주의 계좌번호만 입력가능 합니다.</p>
        </div>

        <div v-if="auctionType === '1'">
          <div class="form-group mt-4">
            <label>계좌소유자명</label>
            <input type="text" v-model="accountOwner" placeholder="계좌소유자명" ref="accountOwnerRef">
          </div>
        </div>

        <!-- 주요 사항 입력 -->
        <div class="form-group mb-5" v-if="auctionType !== '1'">
        <label for="datetime">
          <span class="text-danger me-2">*</span>진단희망 날짜 및 시간
        </label>
        <input id="datetimeInput" type="datetime-local" v-model="diagFirstAt" style="width: 100%; padding: 10px;" placeholder="진단희망일1" ref="diagFirstAtSelect" @keydown.enter="handleEnterPress('diagSecondAtSelect')" />
        <input id="datetimeInput" type="datetime-local" v-model="diagSecondAt" style="width: 100%; padding: 10px;" placeholder="진단희망일2" ref="diagSecondAtSelect" />
        <p class="text-danger">※ 진단희망은 신청일로 부터 2일후 부터 입력가능합니다. (진단시간 오전9시 ~ 오후6시)</p>
      </div>


      <h4>첨부파일</h4>

      <div v-if="auctionType === '1'">
        <div class="form-group mt-5">
          <label for="memo"><span class="text-danger me-2">*</span>사업자등록증</label>
          <input type="file" @change="handleFileUploadCompanyLicense" ref="fileInputRefCompanyLicense" style="display:none" >
          <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadCompanyLicense" ref="fileInputRefCompanyLicenseBtn">
            파일 첨부
          </button>
          <div class="text-start text-secondary opacity-50" v-if="fileAuctionCompanyLicenseName">사업자등록증: {{ fileAuctionCompanyLicenseName }}</div>
        </div>
      </div>

      <div class="form-group mt-5" v-if="auctionType !== '1'">
        <label for="memo"><span class="text-danger me-2">*</span>자동차등록증</label>
        <input type="file" @change="handleFileUploadCarLicense" ref="fileInputRefCarLicense" style="display:none" >
        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadCarLicense" ref="fileInputRefCarLicenseBtn">
          파일 첨부
        </button>
        <div class="text-start text-secondary opacity-50" v-if="fileAuctionCarLicenseName">자동차등록증: {{ fileAuctionCarLicenseName }}</div>
      </div>

      
      <div v-if="auctionType !== '1'">
      <div class="d-flex justify-content-between">
        <h5 class="mt-5"><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
        <div class="align-self-end">
          <button type="button" class="btn btn-success w-100 text-end" @click="openAlarmModal">위임장 양식</button>
        </div>
      </div>
        <input type="file" @change="handleFileUploadOwner" ref="fileInputRefOwner" style="display:none">
        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadOwner">
          파일 첨부
        </button>
        <div class="text-start text-secondary opacity-50" v-if="fileAuctionProxyName">위임장 / 소유자 인감 증명서: {{ fileAuctionProxyName }}</div>
      </div>
        
      <div v-if="auctionType !== '1'">   
        <div class="form-group dealer-check fw-bolder pb-1">
          <p for="dealer">법인 / 사업자차량</p>
          <div class="check_box">
            <input type="checkbox" id="ch2" v-model="isBizChecked" class="form-control">
            <label for="ch2"></label>
          </div>
        </div>
        <p class="text-danger">※ 법인 및 사업자 명의로 등록된 차량의 경우 체크해 주세요.</p>
      </div>

        <div>
        <div>   
      </div>
      </div>


      <div class="form-group mt-5" v-if="auctionType === '1'">
        <label for="memo"><span class="text-danger me-2">*</span>공매 엑셀파일 일괄등록 
          <span class="text-danger" @click="openModal('excel')"><img src="../../../../img/Icon-file.png" class="ms-2" alt="엑셀 파일 형식 참고"> 엑셀 파일 형식 참고</span>
        </label>
        <input type="file" @change="handleFileUploadAuctionPublic" ref="fileAuctionPublicRef" style="display:none" >
        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadAuctionPublic" ref="fileAuctionPublicBtn">
          파일 첨부
        </button>
        <div class="text-start text-secondary opacity-50" v-if="fileAuctionPublicName">공매 일괄등록: {{ fileAuctionPublicName }}</div>
      </div>

    </form>
    </div>
    
    <!-- 사업자 또는 법인 섹션 -->
  </div>
  <!-- 파일 첨부 -->
  <!-- 일반 고객 섹션 -->
    <!-- <div class="dropdown border-bottom">
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
    </div> -->

      <!-- <div class="dropdown border-bottom">
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
            <p class="mb-3">6. '차량운반구 감가상각비명세서' 또는 '고정자산명세서'</p>
            <p>간편등록부의 경우, 상기 1~5번 제출 서류들과 '총 수입금액 및 필요경비명세서'가 필요합니다.</p>
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
        </div> -->
          <div class="px-2 mb-3 flex items-center justify-end mt-5" v-if="auctionType !== '1'">
            <button type="submit" class="btn primary-btn normal-16-font w-100" @click="auctionEntry()" >경매 신청하기</button>
          </div>
          <div class="px-2 mb-3 flex items-center justify-end mt-5" v-if="auctionType === '1'">
            <button type="submit" class="btn primary-btn normal-16-font w-100" @click="auctionEntryPublic()" >공매 신청하기</button>
          </div>
          <Modal v-if="showAuctionModal" @close="handleAuctionClose" />
      </div>
</template>
<script>
import iconUp from "../../../../img/Icon-black-up.png";
import iconDown from "../../../../img/Icon-black-down.png";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Korean } from 'flatpickr/dist/l10n/ko.js'
export default {
  data() {
    return {
      openSection: null,
      iconUp,
      iconDown,
    };
  },
  methods: {
    toggleDropdown(type) {
      this.openSection = this.openSection === type ? null : type;
      console.log(`${type} 상태:`, this.openSection === type);
    },
  },
  mounted() {
    flatpickr("#datetimeInput", {
      locale: Korean,
      enableTime: true,
      dateFormat: "Y-m-d H:i",
      // minDate: "today",
      minDate: new Date().fp_incr(2),
      disable: [
        function (date) {
          // 주말 비활성화
          return date.getDay() === 0 || date.getDay() === 6;
        },
      ],
      time_24hr: true,
      minTime: "09:00",
      maxTime: "18:00",
    });
  },
};
</script>


<script setup>
import { ref, onMounted, nextTick, inject, createApp,computed, reactive ,watch } from 'vue';
import { useStore } from 'vuex';
import Modal from '@/views/modal/modal.vue';
import BankModal from '@/views/modal/bank/BankModal.vue';
import useAuctions from '@/composables/auctions';
import { regions, updateDistricts } from '@/hooks/selectBOX.js';
import { cmmn } from '@/hooks/cmmn';
import carObjects from '../../../../../resources/img/modal/car-objects-blur.png';
import SelfAuthModal from '@/views/modal/auction/selfAuth.vue';

const { openPostcode, closePostcode } = cmmn();
const { wica } = cmmn();
const { createAuction, validationErrors, checkAuctionEntryPublic, checkBusinessStatus, getCertificationData, clearCertificationData } = useAuctions();
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
const carCondition = ref(''); // 차량 상태
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
const fileInputRefCarLicense = ref(null);
const fileAuctionCarLicense = ref(null); // 추가: 파일 저장 변수
const fileAuctionCarLicenseName = ref(''); // 추가: 파일 이름 저장 변수
const fileAuctionPublic = ref(null);
const fileAuctionPublicName = ref('');
const fileAuctionPublicRef = ref(null);
const fileInputRefCompanyLicense = ref(null);
const fileAuctionCompanyLicense = ref(null); // 추가: 파일 저장 변수
const fileAuctionCompanyLicenseName = ref(''); // 추가: 파일 이름 저장 변수
// const fileAuctionCompanyLicenseBtn = ref(null);
const fileInputRefCompanyLicenseBtn = ref(null);
const diagFirstAt = ref('');
const diagSecondAt = ref('');

const carMaker = ref(''); // 자동차 제조사
const carModel = ref(''); // 자동차 모델
const carModelSub = ref(''); // 자동차 세부모델
const carGrade = ref(''); // 자동차 등급
const carGradeSub = ref(''); // 자동차 세부등급
const carYear = ref(''); // 자동차 연식
const carFirstRegDate = ref(''); // 자동차 최초등록일
const carMission = ref(''); // 자동차 미션  
const carFuel = ref(''); // 자동차 연료
const carPriceNow = ref(''); // 자동차 소매 시세
const carPriceNowWhole = ref(''); // 자동차 도매 시세
const carKm = ref(''); // 자동차 주행거리
const carThumbnail = ref(''); // 자동차 썸네일

const regionSelect = ref(null);
const addrPostSelect = ref(null);
const addrdtSelect = ref(null);
const bankSelect = ref(null);
const accountSelect = ref(null);
const memoSelect = ref(null);
const diagFirstAtSelect = ref(null);
const diagSecondAtSelect = ref(null);
const fileInputRefCarLicenseBtn = ref(null);
const fileAuctionPublicBtn = ref(null);
const auctionType = ref('0');
const carStatus = ref('');
const companyName = ref('');
const companyNameRef = ref(null);
const accountOwner = ref('');
const accountOwnerRef = ref(null);

const showSelfAuthModal = ref(false);
const isBusinessOwner = ref(false);
const isAuth = ref(false);
const isAgree = ref(false);
// 체크박스 변경 시 호출되는 함수
const updateCarConditionValue = () => {
  // 모든 체크된 체크박스 선택
  const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
  
  // 선택된 값들을 배열에 담기
  const selectedValues = Array.from(checkboxes).map(checkbox => checkbox.value);
  
  // 배열을 JSON 문자열로 변환하여 저장
  carStatus.value = JSON.stringify(selectedValues);
  
  console.log('차량 상태:', carStatus.value); // 디버깅용
};




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

  if(isAuth.value){
    isVerified.value = true;
    isAgree.value = '1';
  }

  const auctionData = {
    auction_type: auctionType.value,
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
    file_auction_car_license: fileAuctionCarLicense.value, // 업로드된 파일 추가
    is_biz : is_biz.value,
    status: "diag",
    diag_first_at: diagFirstAt.value,
    diag_second_at: diagSecondAt.value,
    car_maker: carMaker.value,
    car_model: carModel.value,
    car_model_sub: carModelSub.value,
    car_grade: carGrade.value,
    car_grade_sub: carGradeSub.value,
    car_year: carYear.value,
    car_first_reg_date: carFirstRegDate.value,
    car_mission: carMission.value,
    car_fuel: carFuel.value,
    car_price_now: carPriceNow.value,
    car_price_now_whole: carPriceNowWhole.value,
    car_thumbnail: carThumbnail.value,
    car_km: carKm.value,
    car_condition: carCondition.value,
    car_status: carStatus.value,
    is_business_owner: isBusinessOwner.value,
    is_agree: isAgree.value
  };

  if(isVerified.value){
    
    // 지역번호 확인
    if(selectedRegion.value === ''){
      focusAndAlert(regionSelect, '지역번호를 선택해 주세요.');
      return;
    }

    // 우편번호 확인
    if(addrPost.value === ''){
      focusAndAlert(addrPostSelect, '우편번호를 입력해 주세요.');
      return;
    }

    // 살세주소 확인
    if(addrdt.value === ''){
      focusAndAlert(addrdtSelect, '상세주소를 입력해 주세요.');
      return;
    }

    // 은행선택 확인 
    if(selectedBank.value === ''){
      focusAndAlert(bankSelect, '은행을 선택해 주세요.');
      return;
    }

    // 계좌번호 확인
    if(account.value === ''){
      focusAndAlert(accountSelect, '계좌번호를 입력해 주세요.');
      return;
    }
    
    // 진단희망일1 확인 
    if(diagFirstAt.value === ''){
      focusAndAlert(diagFirstAtSelect, '진단희망일1을 입력해 주세요.');
      return;
    }

    // 진단희망일2 확인 
    if(diagSecondAt.value === ''){
      focusAndAlert(diagSecondAtSelect, '진단희망일2을 입력해 주세요.');
      return;
    }

    // 필수 정보를 확인
    if (!fileAuctionCarLicense.value) {

      if(fileInputRefCarLicenseBtn.value){
        fileInputRefCarLicenseBtn.value.click();
      }

      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('자동차등록증을 첨부해 주세요.');
      return;
    }
    
    try {
      const result = await createAuction({ auction: auctionData });
      const uniqueNumber = result.unique_number;
      if(result.isSuccess){
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
          window.location.href = '/auction/'+uniqueNumber;
        })
        .confirm(textOk);
      }
    } catch (error) {
    }
  }else{

    // certification 클래스에 포커스 
    const certification = document.querySelector('.certification');
    if(certification){
      certification.focus();
      // 버튼의 테두리 색상을 잠깐 주고 원래대로 돌리기
      certification.style.borderColor = 'red';
      setTimeout(() => {
        certification.style.borderColor = '#000';
      }, 2000);
    }
    
    wica.ntcn(swal)
    .title('')
    .useHtmlText()
    .icon('E')
    .callback(function(result) {
        //console.log(result);
    }).alert('소유자인중 후에 이용 가능한 서비스입니다.');

  }
};

const auctionEntryPublic = async () => {
  // 유효성 검사 코드 (기존 코드 유지)
  if(companyName.value === ''){
    focusAndAlert(companyNameRef, '회사명을 입력해 주세요.');
    return;
  }

  // 은행선택 확인 
  if(selectedBank.value === ''){
    focusAndAlert(bankSelect, '은행을 선택해 주세요.');
    return;
  }

  // 계좌번호 확인
  if(account.value === ''){
    focusAndAlert(accountSelect, '계좌번호를 입력해 주세요.');
    return;
  }

  // 계좌소유자명 확인
  if(accountOwner.value === ''){
    focusAndAlert(accountOwnerRef, '계좌소유자명을 입력해 주세요.');
    return;
  }

  // 사업자등록증 파일확인 
  if(fileAuctionCompanyLicense.value === null){
    if(fileInputRefCompanyLicenseBtn.value){
      fileInputRefCompanyLicenseBtn.value.click();
    }
    wica.ntcn(swal)
    .icon('W')
    .addClassNm('cmm-review-custom')
    .addOption({ padding: 20})
    .callback(function(result) {
    })
    .alert('사업자등록증을 첨부해 주세요.');
    return;
  }

  const file = fileAuctionPublic.value;
  if(!file){
    // focusAndAlert(fileAuctionPublicBtn, '공매 엑셀파일을 첨부해 주세요.');
    // return;
    if(fileAuctionPublicBtn.value){
      fileAuctionPublicBtn.value.click();
    }
    wica.ntcn(swal)
    .icon('W')
    .addClassNm('cmm-review-custom')
    .addOption({ padding: 20})
    .callback(function(result) {
    })
    .alert('공매 엑셀파일을 첨부해 주세요.');
    return;
  }

  try {
    console.log('파일 업로드 시작');
    const result = await checkAuctionEntryPublic(file);
    console.log('받은 데이터:', result.data);
    
    let fileResult = result.data;

    // 유효한 값만 필터링 (null, undefined, 빈 객체 제거)
    fileResult = fileResult.filter(item => {
      if (item === null || item === undefined) {
        return false;
      }
      
      if (typeof item !== 'object') {
        return false;
      }
      
      if (!item.car_no || item.car_no === 'null') {
        return false;
      }
      
      return true;
    });

    console.log('필터링 후 데이터 개수:', fileResult.length);
    console.log('필터링 후 데이터:', fileResult);

    if (fileResult.length === 0) {
      console.log('유효한 데이터가 없습니다.');
      wica.ntcn(swal)
        .icon('W')
        .addOption({ padding: 20 })
        .alert('처리할 유효한 데이터가 없습니다.');
      return;
    }

    // 처리 결과를 저장할 배열
    const results = [];
    let successCount = 0;

    // for...of 루프를 사용하여 순차적으로 처리
    for (const item of fileResult) {
      console.log('처리 중인 데이터:', item);
      
      if(item.part === '공매'){

        const setData = {
          auction_type: '1',
          company_name: companyName.value,
          owner_name: item.orner,
          car_no: item.car_no,
          bank: selectedBank.value,
          account: account.value,
          account_name: accountOwner.value,
          region: item.addr1.split(' ')[0],
          addr1: item.addr1 || '',
          addr2: item.addr2 || '',
          addr_post: item.addr_code || '',
          hope_price: item.hope_price || '',
          customTel1: item.tel || '',
          car_maker: item.maker || '',
          car_model: item.model || '',
          car_model_sub: item.modelSub || '',
          car_grade: item.grade || '',
          car_grade_sub: item.gradeSub || '',
          car_year: item.year || '',
          car_first_reg_date: item.firstRegDate || '',
          car_mission: item.mission || '',
          car_fuel: item.fuel || '',
          car_price_now: item.priceNow || '',
          car_price_now_whole: item.priceNowWhole || '',
          car_thumbnail: item.thumbnail || '',
          car_km: item.km || '',
          file_auction_company_license: fileAuctionCompanyLicense.value
        };
        
        try {
          console.log('등록 시도:', setData.car_no);
          const auctionResult = await createAuction({ auction: setData });
          console.log('등록 결과:', auctionResult);
          results.push(auctionResult);
          successCount++;
        } catch (innerError) {
          console.error('항목 처리 중 오류 발생:', innerError);
          results.push({ error: true, message: innerError.message });
        }
      }
    }

    console.log('처리 완료된 항목 수:', successCount);
    console.log('모든 처리 결과:', results);
    
    // 성공 메시지 표시
    const textOk = `<div class="enroll_box" style="position: relative;">
                      <img src="${carObjects}" alt="자동차 이미지" width="160" height="160">
                      <p class="overlay_text02">공매 신청이 완료되었습니다.</p>
                      <p class="overlay_text03">총 ${fileResult.length}건 중 ${successCount}건이 등록되었습니다.</p>
                    </div>`;
    wica.ntcn(swal)
      .useHtmlText()
      .addClassNm('primary-check')
      .addOption({ padding: 20 })
      .callback(function (result) {
        // 성공 후 처리 로직
        window.location.href = '/auction'; // 필요시 페이지 이동
      })
      .confirm(textOk);
      
  } catch (error) {
    console.error('전체 처리 중 오류 발생:', error);
    wica.ntcn(swal)
      .icon('E')
      .addOption({ padding: 20 })
      .alert('데이터 처리 중 오류가 발생했습니다: ' + (error.message || '알 수 없는 오류'));
  }
}

const focusAndAlert = (inputRef, message) => {
  if (inputRef.value) {
    inputRef.value.focus();
  }
  wica.ntcn(swal)
    .icon('W')
    .addClassNm('cmm-review-custom')
    .addOption({ padding: 20 })
    .callback(function(result) {})
    .alert(message);
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
    // wica.ntcn(swal)
    // .icon('I')
    // .addClassNm('cmm-review-custom')
    // .addOption({ padding: 20})
    // .callback(function(result) {
    // })
    // .alert('본인인증되었습니다.');
    // isVerified.value = true;

    showSelfAuthModal.value = true;

    console.log('showSelfAuthModal',showSelfAuthModal.value);

    // const text = `
    // <div>
    //   <h2>소유자 정보 확인</h2>
    //   <p>소유자 정보를 입력해주세요.</p>
    //   <div>
    //     <input type="text" id="ownerName" class="form-control">
    //   </div>
    //   <div>
    //     <button class="btn btn-primary" id="Business-button">확인</button>
    //   </div>
    // </div>
    // `;

    // wica.ntcn(swal)
    // .useHtmlText() // HTML 태그 활성화
    // .useClose()
    // .addClassNm('intromodal') // 클래스명 설정
    // .addOption({ padding: 20, height:840, width: 300 }) // swal 옵션 추가
    // .callback(function (result) {
    //   // 결과 처리 로직
    // })
    // .confirm(text); // 모달 내용 설정


    // setTimeout(() => {

    //   const checkBusinessButton = document.getElementById('Business-button');
    //   //if (checkBusinessButton) {
    //     checkBusinessButton.addEventListener('click', () => {
    //       const businessNumber = document.getElementById('businessNumber').value;
    //       console.log(businessNumber);
    //       if (businessNumber) {
    //         const result = checkBusinessStatus(businessNumber);
    //         console.log(businessNumber);
    //         // await checkBusinessStatus(checkBusinessEvent);
    //       }
    //     });
    // // }

    // }, 500);

  }
};

const confirmEditIfDisabled = (carNumber) => {
  console.log('carNumber',carNumber);
  if (isVerified.value) {
      wica.ntcn(swal)
      .icon('W')
      .param({
        carNumber: carNumber
      })
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
        if(result.isOk){
          console.log('result',result.rawData.carNumber);
          isVerified.value = false;
          isBusinessOwner.value = false;
          isAuth.value = false;

          // 인증 캐시정보 초기화 
          clearCertificationData(result.rawData.carNumber).then(res => {
            console.log('clearCertificationData',res);
            wica.ntcn(swal)
            .icon('S')
            .addClassNm('cmm-review-custom')
            .addOption({ padding: 20})
            .callback(function(result) {
            })
            .alert('인증 정보가 초기화되었습니다.');
          });
          
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

const triggerFileUploadCompanyLicense = () => {
  if (fileInputRefCompanyLicense.value) {
    fileInputRefCompanyLicense.value.click();
  } else {
    console.error('파일을 찾을 수 없습니다.');
  }
};

const handleFileUploadOwner = event => {
  const file = event.target.files[0];
  if (file) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('최대 10MB 이하의 파일만 첨부할 수 있습니다.');
      return;
    }else{
      fileAuctionProxy.value = file; // 파일 저장
      fileAuctionProxyName.value = file.name; // 파일 이름 저장
      console.log("위임장 / 소유자 인감 증명서:", file.name);
    }
  } else {
    console.error('No file selected');
  }
};

const handleFileUploadCompanyLicense = event => {
  const file = event.target.files[0];
  if (file) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('최대 10MB 이하의 파일만 첨부할 수 있습니다.');
      return;
    }else{
      fileAuctionCompanyLicense.value = file; // 파일 저장
      fileAuctionCompanyLicenseName.value = file.name; // 파일 이름 저장
    }
  } else {
    console.error('No file selected');
  }
};

const triggerFileUploadCarLicense = () => {
  if (fileInputRefCarLicense.value) {
    fileInputRefCarLicense.value.click();
  } else {
    console.error('파일을 찾을 수 없습니다.');
  }
};

const triggerFileUploadAuctionPublic = () => {
  if (fileAuctionPublicRef.value) {
    fileAuctionPublicRef.value.click();
  } else {
    console.error('파일을 찾을 수 없습니다.');
  }
};

const handleFileUploadCarLicense = event => {
  const file = event.target.files[0];
  if (file) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('최대 10MB 이하의 파일만 첨부할 수 있습니다.');
      return;
    }else{
      fileAuctionCarLicense.value = file; // 파일 저장
      fileAuctionCarLicenseName.value = file.name; // 파일 이름 저장
    }

  } else {
    console.error('No file selected');
  }
};

const handleFileUploadAuctionPublic = event => {
  const file = event.target.files[0];
  console.log('공매 엑셀파일',file);
  if (file) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('최대 10MB 이하의 파일만 첨부할 수 있습니다.');
      return;
    }else{
      fileAuctionPublic.value = file; // 파일 저장
      fileAuctionPublicName.value = file.name; // 파일 이름 저장
    }

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

              setTimeout(() => {
                if (accountSelect.value) {
                  accountSelect.value.focus();
                }
              }, 500); // 100ms 지연
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
        // address 에서 시/도 추출
        const sido = address.split(' ')[0];
        selectedRegion.value = sido;

        // 주소 입력 후 포커스 이동
        if (addrdtSelect.value) {
          addrdtSelect.value.focus();
        }

    })
}

const handleEnterPress = (id) => {
  if(id === 'memoSelect'){
    memoSelect.value.focus();
  }else if(id === 'diagFirstAtSelect'){
    diagFirstAtSelect.value.focus();
  }else if(id === 'diagSecondAtSelect'){
    diagSecondAtSelect.value.focus();
  }
  // handleBankLabelClick();
  return;

};


const openAlarmModal = () => {
  // 년 월일 정보를 현재 날짜로 설정
  const currentDate = new Date();
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth() + 1;
  const day = currentDate.getDate();

  // 초기 데이터 설정 - 차량 데이터가 있다면 사용
  const initialCarData = {
    ownerName: ownerName.value || '',
    carNumber: carNumber.value || ''
  };

  const text = `
  <div id="printArea" style="font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; padding: 20px; border: 1px solid #ddd; text-align: left;">
    <h2 style="text-align: center; text-decoration: underline;">위 &nbsp; 임 &nbsp; 장</h2>
    
    <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9;">
        <h3 style="margin-bottom: 10px; color: #333;">수임자 정보</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">성명</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 70%;">
                <input type="text" class="form-control print-input" id="recipient-name" placeholder="성명">
              </td>
            </tr>
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">주민등록번호</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 70%;">
                <input type="text" class="form-control print-input" id="recipient-id" placeholder="주민등록번호">
              </td>
            </tr>
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">주소</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 70%;">
                <input type="text" class="form-control print-input" id="recipient-address" placeholder="주소">
              </td>
            </tr>
        </table>

        <h3 style="margin-bottom: 10px; color: #333; margin-top: 20px;">위임받은 사항</h3>
        <p>자동차(신규, 전입, 말소, 변경, 이전, 근저당 설정, 근저당 말소, 등록증 재교부, 말소사실 증명서 발급 등) 등록(신청)에 관한 사항을 위임합니다.</p>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; margin-top: 10px;">
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">자동차등록번호</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 70%;">
                <input type="text" class="form-control print-input" id="car-number" placeholder="차량번호" value="${initialCarData.carNumber}">
              </td>
            </tr>
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">신규 차대번호</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 70%;">
                <input type="text" class="form-control print-input" id="car-vin" placeholder="차대번호">
              </td>
            </tr>
        </table>
        <p>첨부서류 : 위임인의 인감증명서</p>
    </div>

    <div style="margin-top: 20px; margin-bottom: 20px;">
      <h3 style="margin-bottom: 10px; color: #333; text-align: center;">${year}년 ${month}월 ${day}일</h3>
    </div>
    
    <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9;">
        <h3 style="margin-bottom: 10px; color: #333;">위임자 정보</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">성명</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 70%; position: relative;">
                <input type="text" class="form-control print-input" id="delegator-name" placeholder="성명" value="${initialCarData.ownerName}" style="width: calc(100% - 90px);">
                <span style="color: red; font-weight: bold; position: absolute; right: 8px; top: 50%; transform: translateY(-50%);">(인감날인)</span>
              </td>
            </tr>
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">주민등록번호</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 70%;">
                <input type="text" class="form-control print-input" id="delegator-id" placeholder="주민등록번호">
              </td>
            </tr>
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">주소</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 70%;">
                <input type="text" class="form-control print-input" id="delegator-address" placeholder="주소" value="${addr.value} ${addrdt.value}">
              </td>
            </tr>
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">연락처</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 70%;">
                <input type="text" class="form-control print-input" id="delegator-phone" placeholder="연락처">
              </td>
            </tr>
        </table>
    </div>
    
    <div style="font-size: 0.9em; color: #555; padding: 10px; border-top: 2px solid #ddd;">
        <strong>※ 유의사항</strong><br>
        타인의 서명 또는 인장을 도용 등에 의해 위임장을 작성할 경우에는 형법 제231조와 제232조의 규정에 의하여 사문서 위·변조로 5년 이하의 징역 또는 1천만원 이하의 벌금형에 처해질 수 있습니다.
    </div>
  </div>

  <div class="d-flex justify-content-center">
    <button id="print-button" class="btn btn-primary" style="padding: 10px 20px 10px 20px; margin-top: 20px;">프린트</button>
  </div>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intromodal') // 클래스명 설정
    .addOption({ padding: 20, height: 840 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
    })
    .confirm(text); // 모달 내용 설정

  // SweetAlert 모달이 렌더링된 후 프린트 버튼에 이벤트 리스너 추가
  setTimeout(() => {
    const printButton = document.getElementById('print-button');
    if (printButton) {
      printButton.addEventListener('click', () => {
        const printArea = document.getElementById('printArea');
        if (printArea) {
          printSpecificArea(printArea);
        }
      });
    }
  }, 300); // 모달이 완전히 렌더링될 시간을 조금 더 길게 줌
};

// 특정 영역만 프린트하는 함수
function printSpecificArea(elementToPrint) {
  // 현재 페이지의 스타일을 저장
  const originalStyles = document.querySelectorAll('style, link[rel="stylesheet"]');
  const styles = Array.from(originalStyles).map(style => style.outerHTML).join('');
  
  // 입력된 데이터를 수집
  const inputData = {};
  const inputs = elementToPrint.querySelectorAll('.print-input');
  inputs.forEach(input => {
    inputData[input.id] = input.value;
  });
  
  // 새 창 열기
  const printWindow = window.open('', '_blank', 'width=800,height=600');
  
  if (!printWindow) {
    alert('팝업이 차단되었습니다. 팝업 차단을 해제해주세요.');
    return;
  }
  
  // 입력 필드에 값을 채운 HTML 생성
  let printHTML = elementToPrint.outerHTML;
  
  // 입력 필드를 정적 텍스트로 변환
  Object.keys(inputData).forEach(id => {
    const value = inputData[id] || '';
    const inputRegex = new RegExp(`<input[^>]*id="${id}"[^>]*>`, 'g');
    printHTML = printHTML.replace(inputRegex, `<div style="padding: 8px; min-height: 20px;">${value}</div>`);
  });
  
  // HTML 구조를 문자열로 작성
  const printContent = `
    <html>
      <head>
        <title>위임장 프린트</title>
        ${styles}
        <style>
          body { margin: 0; padding: 0; }
          @media print {
            body { margin: 0; padding: 0; }
            button { display: none !important; }
          }
        </style>
      </head>
      <body>
        ${printHTML}
      </body>
    </html>
  `;
  
  printWindow.document.write(printContent);
  printWindow.document.close();
  
  // 스크립트를 별도로 추가
  printWindow.onload = function() {
    setTimeout(function() {
      printWindow.print();
      setTimeout(function() { 
        printWindow.close(); 
      }, 100);
    }, 300);
  };
}

const openModal = (modalName) => {
  if(modalName === 'excel'){
    const text = `
  <div style="font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; padding: 20px; border: 1px solid #ddd; text-align: left;">
    <h2 style="text-align: center; text-decoration: underline;"> 공매 엑셀파일 형식 참고 </h2>
    
    <div style="margin-bottom: 20px; padding: 10px; border-radius: 5px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 10%; font-size:13px; background: #f9f9f9; font-weight: bold;">구분</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 15%; font-size:13px; background: #f9f9f9; font-weight: bold;">차량번호</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 15%; font-size:13px; background: #f9f9f9; font-weight: bold;">소유주</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px; background: #f9f9f9; font-weight: bold;">진단희망일</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px; background: #f9f9f9; font-weight: bold;">진단지(우편번호)</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%; font-size:13px; background: #f9f9f9; font-weight: bold;">진단지(주소)</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px; background: #f9f9f9; font-weight: bold;">전단지(상세주소)</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px; background: #f9f9f9; font-weight: bold;">희망가</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 25%; font-size:13px; background: #f9f9f9; font-weight: bold;">연락처</td>
            </tr>
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 10%; font-size:13px;">일반</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 15%; font-size:13px;">32다1451</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 15%; font-size:13px;">김우진</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px;">03월 13일</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px;">31122</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%; font-size:13px;">진대전 광역시 서구</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px;">11번지</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px;">124000</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 25%; font-size:13px;">010-1234-5678</td>
            </tr>
            <tr>
              <td style="padding: 8px; border: 1px solid #ddd; width: 10%; font-size:13px;">공매</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 15%; font-size:13px;">43나4112</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 15%; font-size:13px;">나연주</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px;">03월 15일</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px;">51231</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 30%; font-size:13px;">대전 광역시 동구</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px;">22번지</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 20%; font-size:13px;">410000</td>
              <td style="padding: 8px; border: 1px solid #ddd; width: 25%; font-size:13px;">010-1234-5678</td>
            </tr>
        </table>
        <br/>
        <p>※  엑셀파일의 컬럼 순서는 위와 같습니다. 차량번호, 소유주, 진단희망일, 진단지-우편번호, 진단지-주소, 전단지-상세주소, 연락처 순입니다.</p>
        <p>구분이 공매인 데이터만 공매 엑셀파일로 인식합니다.</p>
    </div>

    
  </div>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intromodal') // 클래스명 설정
    .addOption({ padding: 20, height:840 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
    })
    .confirm(text); // 모달 내용 설정
  }
}

const checkDiagDates = (newDate) => {
  if (!diagFirstAt.value || !diagSecondAt.value) return;
  
  // Date 객체로 변환 (날짜 비교를 위해)
  const firstDate = new Date(diagFirstAt.value);
  const secondDate = new Date(diagSecondAt.value);
  
  // 년/월/일만 비교 (시간 제외)
  const isSameDay = 
    firstDate.getFullYear() === secondDate.getFullYear() &&
    firstDate.getMonth() === secondDate.getMonth() &&
    firstDate.getDate() === secondDate.getDate();
  
  if (isSameDay) {
    // 경고 메시지 표시
    wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
        // 경고 후 두 번째 날짜 초기화
        diagSecondAt.value = '';
      })
      .alert('진단희망일1과 진단희망일2는 다른 날짜를 선택해 주세요.');
  }
};

// diagSecondAt이 변경될 때 중복 체크
watch(diagSecondAt, (newValue) => {
  if (newValue) {
    checkDiagDates(newValue);
  }
});

// 첫 번째 날짜가 변경되고 두 번째 날짜가 이미 있는 경우도 체크
watch(diagFirstAt, (newValue) => {
  if (newValue && diagSecondAt.value) {
    checkDiagDates(newValue);
  }
});

onMounted(() => {

  const urlParams = new URLSearchParams(window.location.search);
  const typeParam = urlParams.get('type');

  if (typeParam === 'public') {
    auctionType.value = '1'; // 공매
  } else if (typeParam === 'normal') {
    auctionType.value = '0'; // 일반경매
  }

  if (addrPostSelect.value) {
    addrPostSelect.value.focus();
  }

  const carDetailsJSON = localStorage.getItem("carDetails");
  if (carDetailsJSON) {
    const carDetails = JSON.parse(carDetailsJSON);
    if (carDetails.owner) {
      ownerName.value = carDetails.owner;
    }
    if (carDetails.no) {
      carNumber.value = carDetails.no;
    }

    carMaker.value = carDetails.maker;
    carModel.value = carDetails.model;
    carModelSub.value = carDetails.modelSub;
    carGrade.value = carDetails.grade;
    carGradeSub.value = carDetails.gradeSub;
    carYear.value = carDetails.year;
    carFirstRegDate.value = carDetails.firstRegDate;
    carMission.value = carDetails.mission;
    carFuel.value = carDetails.fuel;
    carPriceNow.value = carDetails.priceNow;
    carPriceNowWhole.value = carDetails.priceNowWhole;
    carThumbnail.value = carDetails.thumbnail;
    carKm.value = carDetails.km;
    carCondition.value = localStorage.getItem('mileage');

  }

  getCertificationData(carNumber.value).then(res => {
    console.log('?res:',res);
    const data = res.data;

    console.log('?data:',data);

    if(data.isAuth === true){
      // 인증여부 확인하기  
      if(data.isBusinessOwner === 1){
        isBusinessOwner.value = true;        
      }

      isAuth.value = true;
      isVerified.value = true;
    }
    
  });

});

</script>

<style scoped>
@media (min-width: 992px){
  .mov-wide {
      width: 35rem !important;
      padding-top: 20px !important;
  }
}

@media (max-width: 768px) {
  #app.mainPage > .user-nav-wrap + div {
    margin-top: 100px !important;
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
input[type="datetime-local"]::-webkit-datetime-edit { 
    color: #aaa; /* 비활성화된 입력에 회색 스타일 적용 */
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
input[type="datetime-local"] {
  width: 100%;
  padding: 8px;
  font-size: 16px;
  box-sizing: border-box;
}

.cmm-review-custom {
  z-index: 10000 !important;
}

.intromodal {
  width: 300px !important;
  /* z-index: 10000 !important; */
}

</style>
