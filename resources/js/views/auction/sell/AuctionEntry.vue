<template>
  <div class="container mov-wide">
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
            <button class="btn border certification" @click.stop="verifyOwner" :disabled="isVerified">본인인증</button>
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
        <!-- 주소 입력 -->
        <div class="form-group mb-2 input-wrapper">
          <input type="text" @click="editPostCode('daumPostcodeInput')" class="input-dis form-control" v-model="addrPost" placeholder="우편번호" readonly ref="addrPostSelect">
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
          <input type="text" v-model="addrdt" placeholder="상세주소" ref="addrdtSelect">
          <div class="text-danger mt-1">
            <div v-for="message in validationErrors?.addr2">
              {{ message }}
            </div>
          </div>
        </div>
        <!-- 은행 선택 -->
        <div class="form-group mt-4">
          <label for="carNumber"><span class="text-danger me-2">*</span>은행</label>
          <input type="text" id="bank" placeholder="은행 선택" @click="handleBankLabelClick" v-model="selectedBank" readonly ref="bankSelect">
          <input type="text" v-model="account" placeholder="계좌번호" :class="{'block': accountDetails}" class="account-num" ref="accountSelect">
          <p class="tc-gray">주의 : 계좌는 차량 소유주의 계좌번호만 입력가능 합니다.</p>
        </div>
        <!-- 주요 사항 입력 -->
        <div class="form-group mt-5">
          <label for="memo"><span class="text-danger me-2">*</span>주요사항</label>
          <textarea type="text" id="memo" v-model="memo" placeholder="ex)외관에 손상이 있어요, 시트에 구멍이 나있어요, 화재이력이 있어요, 뒤쪽 트렁크에 사고 있어요." rows="2" ref="memoSelect"></textarea>
        </div>
        <div class="form-group mb-5">
        <label for="datetime">
          <span class="text-danger me-2">*</span>진단희망 날짜 및 시간
        </label>
        <input id="datetimeInput" type="datetime-local" v-model="diagFirstAt" style="width: 100%; padding: 10px;" placeholder="진단희망일1" ref="diagFirstAtSelect" />
        <input id="datetimeInput" type="datetime-local" v-model="diagSecondAt" style="width: 100%; padding: 10px;" placeholder="진단희망일2" ref="diagSecondAtSelect" />
        <p class="tc-gray">※ 진단희망은 신청일로 부터 2일후 부터 입력가능합니다. (진단시간 오전9시 ~ 오후6시)</p>
      </div>

      <div class="form-group mt-5">
        <label for="memo"><span class="text-danger me-2">*</span>자동차등록증</label>
        <input type="file" @change="handleFileUploadCarLicense" ref="fileInputRefCarLicense" style="display:none" >
        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadCarLicense" ref="fileInputRefCarLicenseBtn">
          파일 첨부
        </button>
        <div class="text-start text-secondary opacity-50" v-if="fileAuctionCarLicenseName">자동차등록증: {{ fileAuctionCarLicenseName }}</div>
      </div>

        <h5 class="mt-5"><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
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
            <button type="submit" class="btn primary-btn normal-16-font w-100" @click="auctionEntry()" >경매 신청하기</button>
          </div>
          <Modal v-if="showAuctionModal" @close="handleAuctionClose" />
      </div>
</template>
<script>
import iconUp from "../../../../img/Icon-black-up.png";
import iconDown from "../../../../img/Icon-black-down.png";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
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
const fileInputRefCarLicense = ref(null);
const fileAuctionCarLicense = ref(null); // 추가: 파일 저장 변수
const fileAuctionCarLicenseName = ref(''); // 추가: 파일 이름 저장 변수

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

// 입력폼 검사 알림 
const checkInputForm = (id) => {

  const idValue = '';
  const msg = '';
  switch(id){
    case 'region':
      idValue = selectedRegion.value;
      msg = '지역번호를 선택해 주세요.';
    break;

    case 'addrPost':
      idValue = addrPost.value;
      msg = '우편번호를 입력해 주세요.';
    break;

    case 'addrdt':
      idValue = addrdt.value;
      msg = '상세주소를 입력해 주세요.';
    break;

    case 'bank':
      idValue = selectedBank.value;
      msg = '은행을 선택해 주세요.';
    break;

    case 'account':
      idValue = account.value;
      msg = '계좌번호를 입력해 주세요.';
    break;

    case 'diagFirstAt':
      idValue = diagFirstAt.value;
      msg = '진단희망일1을 입력해 주세요.';
    break;

    case 'diagSecondAt':
      idValue = diagSecondAt.value;
      msg = '진단희망일2을 입력해 주세요.';
    break;

    case 'fileAuctionCarLicense':
      idValue = fileAuctionCarLicense.value;
      msg = '자동차등록증을 첨부해 주세요.';
    break;
    
  }

  if(idValue === ''){
    wica.ntcn(swal)
    .icon('W')
    .addClassNm('cmm-review-custom')
    .addOption({ padding: 20})
    .callback(function(result) {
    })
    .alert(msg);
    return;
  }

}

const auctionEntry = async () => {

  // checkInputForm('region');
  // checkInputForm('addrPost');
  // checkInputForm('addrdt');
  // checkInputForm('bank');
  // checkInputForm('account');
  // checkInputForm('diagFirstAt');
  // checkInputForm('diagSecondAt');
  // checkInputForm('fileAuctionCarLicense');

  const auctionData = {
    auction_type: '0',
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
  };

  if(isVerified.value){
    

    // 지역번호 확인
    if(selectedRegion.value === ''){
      
      if(regionSelect.value){
        regionSelect.value.focus();
      }

      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('지역번호를 선택해 주세요.');
      // onRegionFocus 에 포커스 
      
      return;
    }

    // 우편번호 확인
    if(addrPost.value === ''){

      if(addrPostSelect.value){
        addrPostSelect.value.focus();
      }

      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('우편번호를 입력해 주세요.');
      return;
    }

    // 살세주소 확인
    if(addrdt.value === ''){

      if(addrdtSelect.value){
        addrdtSelect.value.focus();
      }

      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })  
      .alert('상세주소를 입력해 주세요.');
      return;
    }

    // 은행선택 확인 
    if(selectedBank.value === ''){

      if(bankSelect.value){
        bankSelect.value.focus();
      }

      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('은행을 선택해 주세요.');
      return;
    }

    // 계좌번호 확인
    if(account.value === ''){

      if(accountSelect.value){
        accountSelect.value.focus();
      }

      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('계좌번호를 입력해 주세요.');
      return;
    }
    
    // 진단희망일1 확인 
    if(diagFirstAt.value === ''){

      if(diagFirstAtSelect.value){
        diagFirstAtSelect.value.focus();
      }

      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('진단희망일1을 입력해 주세요.');
      return;
    }

    // 진단희망일2 확인 
    if(diagSecondAt.value === ''){

      if(diagSecondAtSelect.value){
        diagSecondAtSelect.value.focus();
      }

      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('진단희망일2을 입력해 주세요.');
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

const triggerFileUploadCarLicense = () => {
  if (fileInputRefCarLicense.value) {
    fileInputRefCarLicense.value.click();
  } else {
    console.error('파일을 찾을 수 없습니다.');
  }
};

const handleFileUploadCarLicense = event => {
  const file = event.target.files[0];
  if (file) {
    fileAuctionCarLicense.value = file; // 파일 저장
    fileAuctionCarLicenseName.value = file.name; // 파일 이름 저장
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

</style>
