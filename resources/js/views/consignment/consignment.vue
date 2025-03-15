<template>
  <div class="container mov-wide d-flex flex-column gap-5">
    <div v-if="!fileUploadView" class="p-3 my-4">
      <h4 class="my-1 mb-5">탁송 요청</h4>
      <div class="profile ms-0 p-0">
        <div class="dealer-info">
          <img :src="photoUrl(userData)" alt="Profile Photo" class="profile-photo" />
          <div class="deal-info">
            <p class="text-secondary opacity-50">낙찰액</p>
            <h4>{{ amtComma(selectedBid?.price ?? auctionDetail?.data?.final_price ??0) }}</h4>
            <p><span class="fw-medium">{{ userData?.dealer?.name ?? auctionDetail?.data?.dealer_name }}</span>&nbsp;딜러</p>
            <p class="restar">4.5점</p>
          </div>
        </div>
      </div>
      <hr class="custom-hr" />
      <div>
        <h4 class="fst-normal mb-5">원하는 탁송일을 선택해 주세요</h4>
        <div class="d-flex justify-content-between">
          <h5>{{ monthLabel }}</h5>
        </div>
        <p class="text-secondary opacity-50">&#8251;탁송일은 익일 9시 이후부터 3일 이내로 탁송이 가능해요.</p>
        <div class="date-time-picker overflow-x-auto">
          <div class="date-picker">
            <div v-for="(day, index) in days" :key="index" class="date-container">
              <div class="day-label">{{ day.label }}</div>
              <div :class="['date', { selected: selectedDay === index }]" @click="selectDay(index)">
                {{ day.date.getDate() }}
              </div>
            </div>
          </div>
          <div class="my-3 time-picker" v-if="selectedDay !== null">
            <h4>오전</h4>
            <div class="time-section">
              <div v-for="time in morningTimes" :key="time" :class="['time', { selected: selectedTime === time, disabled: isPastTime(time) || isBeyondEndTime(time) }]" @click="selectTime(time)">
                {{ time }}
              </div>
            </div>
            <h4 class="mt-3">오후</h4>
            <div class="time-section">
              <div v-for="time in afternoonTimes" :key="time" :class="['time', { selected: selectedTime === time, disabled: isPastTime(time) || isBeyondEndTime(time) }]" @click="selectTime(time)">
                {{ time }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr class="custom-hr" />
      <div>
        <h4 class="mt-4">입금받을 계좌를 알려주세요</h4>
        <div class="form-group mt-4">
          <label for="bankNumber">은행</label>
          <input type="text" id="bank" placeholder="은행 선택" @click="handleBankLabelClick" v-model="selectedBank" readonly>
          <label for="bankNumber" class="mt-3">계좌번호</label>
          <input type="text" v-model="account" placeholder="계좌번호 직접입력" :class="{'block': accountDetails}" class="account-num">
        </div>
      </div>

      <hr class="custom-hr" />
      <div>
        <h4 class="mt-4">탁송주소</h4>
        <p class="text-secondary opacity-50">탁송 주소는 탁송 출발지 배송 주소로 사용됩니다.</p>
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
      </div>

      <hr class="custom-hr" />
      <h4 class="mt-4"><span class="text-danger me-2">*</span>매도용 인감증명서</h4>
      <p class="text-secondary opacity-50">매도용 인감증명서를 준비해 주세요.</p>
    
      <div class="form-group">
        <!-- <input type="file" @change="handleFileUploadAuctionOwner" ref="fileInputRefAuctionOwner" style="display:none">
        <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadAuctionOwner">
          파일 첨부
        </button>
        <div class="text-start text-secondary opacity-50" v-if="fileAuctionOwnerName">매도용 인감증명서: {{ fileAuctionOwnerName }}</div> -->
        

        <div class="dropdown border-bottom" v-if="!isBizChecked">
        <button
          class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
          @click="toggleDropdown('general')"
        >
          이전에 필요한 서류 안내 (일반 고객)
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
        </div>
        </transition>
      </div>

      <div class="dropdown border-bottom" v-if="isBizChecked">
        <button
          class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
          @click="toggleDropdown('business')"
        >
          이전에 필요한 서류 안내 (사업자 또는 법인)
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

        </div>

        <div class="form-group">
          <h4 class="mt-4"><span class="text-danger me-2">*</span>고객 연락처</h4>
          <p class="text-secondary opacity-50">고객 연락처를 입력해 주세요.</p>
          <div>
            <input type="text" v-model="customTel1" placeholder="연락처1 (필수)" ref="customTel1Select">
            <input type="text" v-model="customTel2" placeholder="연락처2">
          </div>
        </div>


      <button type="button" class="btn btn-primary w-100" @click="toggleView">다음</button>
    </div>
    <div v-if="fileUploadView" class="card p-3 my-4">
      <div class="form-group">
        <img id="imagePreview" :src="imageSrc" class="image-preview mx-2 my-4" v-if="imageSrc" />
        <input type="file" @change="handleFileUpload" ref="fileInputRef" style="display: none;" id="file_user_photo">
        <div class="text-start text-secondary opacity-50 mt-2" v-if="registerForm.file_user_sign">사진 파일: {{ registerForm.file_user_sign }}</div>
      </div>
      <h4 class="mt-4">마지막으로 꼼꼼히 확인해 주세요!</h4>
      <h5 class="tc-red fs-6">&#8251; 매도용 인감증명서를 준비해주세요</h5>
      <div class="summary-box d-flex flex-column p-3 mt-3">
        <div class="d-flex justify-content-start gap-5 mb-2">
          <p class="mb-0">낙찰액</p>
          <p class="mb-0">{{ amtComma(selectedBid?.price ?? auctionDetail?.data?.final_price ??0) }}</p>
        </div>
        <div class="d-flex justify-content-start gap-5">
          <p class="mb-0"><span class="me-3">딜</span>러</p>
          <p class="mb-0">{{ userData?.dealer?.name ?? auctionDetail?.data?.dealer_name }}</p>
        </div>
        <div class="d-flex justify-content-start gap-5 mt-2">
          <p class="mb-0">탁송일</p>
          <p class="mb-0"><span>{{ yearLabel }}</span>&nbsp;{{ monthLabel }} {{ selectedDateLabel }} {{ selectedTime }}</p>
        </div>
        <div class="d-flex justify-content-start gap-5 mt-2">
          <p class="mb-0"><span class="me-3">은</span>행</p>
          <p class="mb-0"><span class="me-2">{{ selectedBank }}</span>|<span class="ms-2">{{ account }}</span></p>
        </div>
      </div>
      <p class="text-secondary opacity-75 text-center mt-3">취소와 변경이 어려우니 유의해 주세요.</p>
      <button class="btn btn-primary my-3 w-100" @click="confirmSelection">완료</button>
    </div>
  </div>
</template>
<script setup>
import iconUp from "../../../img/Icon-black-up.png";
import iconDown from "../../../img/Icon-black-down.png";
import { ref, onMounted, defineProps, defineEmits, watch, nextTick, createApp, inject } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { cmmn } from '@/hooks/cmmn';
import BankModal from '@/views/modal/bank/BankModal.vue';
import profileDom from '/resources/img/profile_dom.png';
import useAuctions from '@/composables/auctions';


// export default {
//   data() {
//     return {
//       openSection: null,
//     };
//   },
//   methods: {
//     toggleDropdown(type) {
//       this.openSection = this.openSection === type ? null : type;
//       console.log(`${type} 상태:`, this.openSection === type);
//     },
//   },
// }

const {setTacksong,  getAuctions, auctionsData, AuctionReauction, chosenDealer, getAuctionById, updateAuctionStatus, setdestddress, validationErrors } = useAuctions();
const selectedDay = ref(null);
const selectedTime = ref(null);
const morningTimes = ['9:00', '9:30', '10:00', '10:30', '11:00', '11:30'];
const afternoonTimes = ['13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];
const monthLabel = ref(getMonthLabel());
const yearLabel = ref(getYearLabel());
const fileUploadView = ref(false);
const selectedBank = ref('');
const account = ref('');
const showDetails = ref(false);
const accountDetails = ref(false);
const isActive = ref(false);
const fileInputRef = ref(null);
const registerForm = ref({ file_user_sign: null, file_user_sign_name: '' });
const imageSrc = ref('');
const router = useRouter();
const route = useRoute();
const { amtComma, wica, openPostcode, closePostcode, wicac } = cmmn();
const auctionDetail = ref(null);
const selectedBid = ref(null);
const userInfo = ref(null);
const days = ref([]);
const addrPost = ref('');
const addr = ref(''); // 주소
const addrdt = ref(''); // 상세 주소
const fileInputRefAuctionOwner = ref(null);
const fileAuctionOwner = ref(null); // 추가: 파일 저장 변수
const fileAuctionOwnerName = ref(''); // 추가: 파일 이름 저장 변수
const openSection = ref(null);
const isBizChecked = ref(false);
const customTel1 = ref('');
const customTel2 = ref('');
const customTel1Select = ref(null);
// const datePickerRef = ref(null);
const addrPostSelect = ref(null);
const addrdtSelect = ref(null);
const props = defineProps({
  bid: Object,
  userData: Object,
});

const emit = defineEmits(['close', 'confirm']);
const closeModal = () => {
  emit('close');
};

watch(() => props.userData, (newVal) => {
  if (newVal) {
    userInfo.value = newVal;
  }
}, { immediate: true });

watch(() => props.bid, (newVal) => {
  if (newVal) {
    selectedBid.value = newVal;
  }
}, { immediate: true });

const selectedDateLabel = ref('');
const confirmSelection = async () => {
  const auctionId = auctionDetail.value?.data?.id;

  // 매도용파일 업로드
  // if (fileAuctionOwner.value) {
  //   try {
  //     const formData = new FormData();
  //     formData.append('file_auction_owner', fileAuctionOwner.value);
  //     await wicac.conn()
  //     .url(`/api/auctions/${auctionId}/upload`)
  //     .param(formData)
  //     .multipart()
  //     .post();
  //   } catch (error) {
  //     // console.error('Error during file upload:', error);
  //     wica.ntcn(swal)
  //     .title('오류가 발생하였습니다.')
  //     .useHtmlText()
  //     .icon('E') //E:error , W:warning , I:info , Q:question
  //     .alert('관리자에게 문의해주세요.');
  //   }

  // }else{
  //   wica.ntcn(swal)
  //   .title('매도용 인감증명서를 준비해 주세요')
  //   .icon('W') //E:error , W:warning , I:info , Q:question
  //   .alert('매도용 인감증명서파일을 첨부해 주세요.');
  // }
  
  if (auctionId && selectedDay.value !== null && selectedTime.value) {
    const selectedDate = days.value[selectedDay.value].date;
    const year = selectedDate.getFullYear();
    const month = (selectedDate.getMonth() + 1).toString().padStart(2, '0');
    const day = selectedDate.getDate().toString().padStart(2, '0');
    
    const [hours, minutes] = selectedTime.value.split(':');
    const formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:00`;
    const data = {
      auction: {
        taksong_wish_at: formattedDate,
        bank: selectedBank.value,
        account: account.value,
        // bank: auctionDetail.value?.data?.bank,
        // account: auctionDetail.value?.data?.account,
        addr_post: addrPost.value,
        addr1: addr.value,
        addr2: addrdt.value,
        customTel1: customTel1.value,
        customTel2: customTel2.value,
      }
    };

    console.log('Sending the following data to the API:', data);

    try {
      await setTacksong(auctionId, data);
      console.log('Request successful');
    } catch (error) {
      console.error('Error during API request:', error);
    }
  } else {
    console.error('Auction ID, selected date, or time is missing');
  }
  
  emit('confirm', {
    bid: selectedBid.value,
    userData: userInfo.value,
    selectedDate: days.value[selectedDay.value].date,
    fileSignData: registerForm.value,
    selectedTime: selectedTime.value,
  });

};

const fetchAuctionDetail = async () => {
  try {
    const auctionId = route.params.id;  
    auctionDetail.value = await getAuctionById(auctionId);  
    isBizChecked.value = auctionDetail.value.data.is_biz;
    openSection.value = auctionDetail.value.data.is_biz ? 'business' : 'general';

    selectedBank.value = auctionDetail.value.data.bank;
    account.value = auctionDetail.value.data.account;

    days.value = getNextAvailableDays(auctionDetail.value.data.choice_at, auctionDetail.value.data.takson_end_at);  
    console.log('Auction Detail:', auctionDetail.value); 
  } catch (error) {
    console.error('Error fetching auction details:', error); 
  }
};

onMounted(async () => {
  const script = document.createElement('script');
  script.src = "https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js";
  script.onload = () => console.log('Daum Postcode script loaded');
  document.head.appendChild(script);

  await getAuctions();  // 모든 경매 데이터를 가져옴
  await fetchAuctionDetail();  // 현재 경매 상세 정보를 가져옴
  document.body.style.overflowX = 'hidden';
});


const navigateToCompletionSuccess = async () => {
  await selectDealer(selectedBid.value, selectedBid.value.index);
  router.push({ name: 'completionsuccess' });
};

const selectDealer = async (bid, index) => {
  selectedBid.value = { ...bid, index };
  connectDealerModal.value = true;

  try {
    const userData = await getUser(bid.user_id);
    userInfo.value = userData;
  } catch (error) {
    console.error('Error fetching dealer data:', error);
  }
};

function getYearLabel() {
  const today = new Date();
  const year = today.getFullYear();
  return `${year}년`;
}

function getMonthLabel() {
  const today = new Date();
  const month = today.getMonth() + 1;
  return `${month}월`;
}

function getNextAvailableDays(choiceAt, taksonEndAt) {
  const choiceDate = new Date(choiceAt);
  const endDate = new Date(taksonEndAt);
  const daysArray = [];
  const weekDays = ['일', '월', '화', '수', '목', '금', '토'];

  for (let i = 0; i <= 5; i++) {
    const nextDay = new Date(choiceDate);
    nextDay.setDate(choiceDate.getDate() + i);
    if (nextDay > endDate) break;

    const dayLabel = `${nextDay.getDate()} (${weekDays[nextDay.getDay()]})`;
    daysArray.push({ date: nextDay, label: dayLabel });
  }

  return daysArray;
}

const toggleView = () => {
  if (selectedDay.value === null || selectedTime.value === null) {
    // datePickerRef.value.focus(); // 포커스가 안되네, 해당 위치로 이동 가능 한가 ? 
    swal.fire({
      title: '탁송일을 선택해 주세요',
      text: '원하는 탁송일과 시간을 선택해 주세요.',
      icon: 'warning',
      confirmButtonText: '확인'
    }).then(() => {
      setTimeout(() => {
        const datePickerElement = document.querySelector('.container-fluid');
        if (datePickerElement) {
          datePickerElement.scrollIntoView({ behavior: 'smooth' });
        }
      }, 500);
    });
    
    return;
  }

  // 탁송주소 확인 
  if(addrPost.value === '') {
    addrPostSelect.value.focus();
    swal.fire({
      title: '탁송주소를 입력해 주세요',
      text: '탁송주소를 입력해 주세요.',
      icon: 'warning',
      confirmButtonText: '확인'
    });
    return;
  }

  // if (fileAuctionOwner.value === null) {
  //   swal.fire({
  //     title: '매도용 인감증명서를 준비해 주세요',
  //     text: '매도용 인감증명서파일을 첨부해 주세요.',
  //     icon: 'warning',
  //     confirmButtonText: '확인'
  //   });
  //   return;
  // }

  if(customTel1.value === '') {
    customTel1Select.value.focus();
    swal.fire({
      title: '고객 연락처를 입력해 주세요',
      text: '고객 연락처를 입력해 주세요.',
      icon: 'warning',
      confirmButtonText: '확인'
    });
    return;
  }
  // if (!selectedBank.value || !account.value) {
  //   swal.fire({
  //     title: '은행 정보 입력 필요',
  //     text: '은행과 계좌번호를 입력해 주세요.',
  //     icon: 'warning',
  //     confirmButtonText: '확인'
  //   });
  //   return;
  // }
  const textOk = `
    <h4 class="mt-4">마지막으로 꼼꼼히 확인해 주세요!</h4>
    <h5 class="tc-red fs-6">&#8251; 매도용 인감증명서를 준비해주세요</h5>
    <div class="summary-box d-flex flex-column p-3 mt-3">
      <div class="d-flex justify-content-start gap-5 mt-2">
        <p class="mb-0">탁송일</p>
        <p class="mb-0"><span>${yearLabel.value} </span>&nbsp;${monthLabel.value} ${selectedDateLabel.value} ${selectedTime.value}</p>
      </div>
      <div class="d-flex justify-content-start gap-5 mt-2">
        <p class="mb-0">탁송주소</p>
        <p class="mb-0"><span>${addrPost.value}</span>|<span>${addr.value}</span>|<span>${addrdt.value}</span></p>
      </div>
      <div class="d-flex justify-content-start gap-5 mt-2">
        <p class="mb-0"><span class="me-3">은</span>행</p>
        <p class="mb-0"><span class="me-2">${selectedBank.value ?? '선택 안됨'}</span>|<span class="ms-2">${account.value ?? '계좌번호 없음'}</span></p>
      </div>
      <div class="d-flex justify-content-start gap-5 mt-2">
        <p class="mb-0">고객 연락처</p>
        <p class="mb-0"><span>${customTel1.value}</span>|<span>${customTel2.value}</span></p>
      </div>
    </div>
    <p class="text-secondary opacity-75 text-center mt-3">취소와 변경이 어려우니 유의해 주세요.</p>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 인 경우 활성화
    .labelCancel('취소') // 취소 버튼 라벨 변경
    .btnBatch('R')
    .addClassNm('review-custom') // 클래스명 변경, 기본 클래스명: wica-salert
    .addOption({ padding: 20}) // swal 기타 옵션 추가
    .callback(function (result) {
      if (result.isOk) {
        confirmSelection();
        // window.location.href = '/auction';
        }
      })
    .confirm(textOk);
};

function selectDay(index) {
  selectedDay.value = index;
  selectedTime.value = null;
  selectedDateLabel.value = days.value[index].label;
}

function selectTime(time) {
  if (!isPastTime(time) && !isBeyondEndTime(time)) {
    selectedTime.value = time;
  }
}

function isPastTime(time) {
  const [hours, minutes] = time.split(':').map(Number);
  const now = new Date();
  const selectedDate = new Date(days.value[selectedDay.value].date);
  selectedDate.setHours(hours, minutes);

  if (selectedDay.value === 0 && selectedDate < now) {
    return true;
  }
  return false;
}

function isBeyondEndTime(time) {
  const [hours, minutes] = time.split(':').map(Number);
  const selectedDate = new Date(days.value[selectedDay.value].date);
  selectedDate.setHours(hours, minutes);

  const endDate = new Date(auctionDetail.value.data.takson_end_at);
  if (selectedDate > endDate) {
    return true;
  }
  return false;
}

const swal = inject('$swal');

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

function selectBank(bankName) {
  selectedBank.value = bankName;
  showDetails.value = false;
  accountDetails.value = true;
  isActive.value = false;
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
    registerForm.value.file_user_sign = file;
    registerForm.value.file_user_sign_name = file.name;
    const reader = new FileReader();
    reader.onload = (e) => {
      imageSrc.value = e.target.result;
    };
    reader.readAsDataURL(file);
    console.log("File:", file.name);
  }
}

const photoUrl = (userData) => {
  return userInfo.value && userInfo.value.files && userInfo.value.files.file_user_photo
    ? userInfo.value.files.file_user_photo[0].original_url
    : profileDom;
};

function editPostCode(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
        addrPost.value = zonecode;
        addr.value = address;

        // 포커스 
        addrdtSelect.value.focus();

    })
}

const triggerFileUploadAuctionOwner = () => {
  if (fileInputRefAuctionOwner.value) {
    fileInputRefAuctionOwner.value.click();
  } else {
    console.error('파일을 찾을 수 없습니다.');
  }
}

const handleFileUploadAuctionOwner = event => {
  const file = event.target.files[0];
  if (file) {
    fileAuctionOwner.value = file;
    fileAuctionOwnerName.value = file.name;
  } else {
    console.error('No file selected');
  }
}

const toggleDropdown = (section) => {
  openSection.value = openSection.value === section ? null : section;
};

</script>

<style scoped lang="scss">
.container {
  width: 400px;
  margin: 0 auto;
}

.input-wrapper {
  position: relative;
}

.date-time-picker {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 20px;
  border-radius: 8px;
  background-color: #fff;
}

.date-picker {
  display: flex;
  width: 100%;
  gap: 18px;
  margin-bottom: 20px;
  flex-direction: row;
}

.date-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

.day-label {
  font-size: 14px;
  margin-bottom: 5px;
}

.date {
  padding: 10px 20px;
  margin: 0 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
  text-align: center;
  width: 100%;
  transition: color 0.3s, border 0.3s;
}

.date.disabled {
  background-color: #f0f0f0;
  color: #ccc;
  cursor: not-allowed;
}

.time-picker {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.time-section {
  margin: 10px 0;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 5px;
}

.time {
  padding: 10px 20px;
  margin: 5px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
  width: 31%;
  text-align: center;
  transition: color 0.3s, border 0.3s;
}

.time.disabled {
  background-color: #f0f0f0;
  color: #ccc;
  cursor: not-allowed;
}

.time:hover,
.date:hover {
  color: blue;
  border: 1px solid blue;
}

.account-num.block {
  display: block;
}

.image-preview {
  max-width: 100%;
  max-height: 300px;
  display: block;
  margin: 0 auto;
}

.summary-box {
  background-color: #f5f6f5;
  padding: 20px;
  border-radius: 5px;
  margin-top: 20px;
}

.summary-box p {
  margin: 0 0 10px;
  font-size: 16px;
  color: #333;
}

.container {
  width: auto;
}

@media (min-width: 992px) {
  .mov-wide {
    // width: 55vw;
    width: 35rem !important;
  }
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
