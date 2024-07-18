<template>
  <div class="container d-flex flex-column gap-5">
    <div v-if="!fileuploadvue" class="p-3 my-4">
      <h4 class="my-1 mb-5">탁송 요청</h4>
      <div class="profile ms-0 p-0">
        <div class="dealer-info">
          <img :src="photoUrl(userData)" alt="Profile Photo" class="profile-photo" />
          <div class="deal-info">
            <p class="text-muted">낙찰액</p>
            <h4>{{ amtComma(selectedBid?.price ?? 0) }}</h4>
            <p><span class="fw-medium">{{ userData?.dealer.name }}</span>&nbsp;딜러</p>
            <p class="restar">4.5점</p>
          </div>
        </div>
      </div>
      <hr class="custom-hr" />
      <div>
        <h4 class="fst-normal mb-5">원하는 탁송일을 선택해 주세요</h4>
        <div class="d-flex justify-content-between">
          <h5>{{ monthLabel }}</h5>
          <p class="text-muted">익일 9시 이후부터 탁송이 가능해요.</p>
        </div>
        <div class="date-time-picker">
          <div class="date-picker">
            <div v-for="(day, index) in days" :key="index" class="date-container d">
              <div class="day-label">{{ day.label }}</div>
              <div :class="['date', { selected: selectedDay === index }]" @click="selectDay(index)">
                {{ day.date.getDate() }}
              </div>
            </div>
          </div>
          <div class="my-3 time-picker" v-if="selectedDay !== null">
            <h4>오전</h4>
            <div class="time-section">
              <div v-for="time in morningTimes" :key="time" :class="['time', { selected: selectedTime === time, disabled: isPastTime(time) }]" @click="selectTime(time)">
                {{ time }}
              </div>
            </div>
            <h4 class="mt-3">오후</h4>
            <div class="time-section">
              <div v-for="time in afternoonTimes" :key="time" :class="['time', { selected: selectedTime === time, disabled: isPastTime(time) }]" @click="selectTime(time)">
                {{ time }}
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <hr class="custom-hr" />
      
      <div>
        <h4 class="mt-4">탁송비를 입금받을 계좌를 알려주세요</h4>
        <!-- 은행 선택 -->
        <div class="form-group mt-4">
          <label for="bankNumber">은행</label>
          <input type="text" id="bank" placeholder="은행 선택" @click="handleBankLabelClick" v-model="selectedBank" readonly>
          <label for="bankNumber" class="mt-3">계좌번호</label>
          <input type="text" v-model="account" placeholder="계좌번호 직접입력" :class="{'block': accountDetails}" class="account-num">
        </div>
        <!-- 은행 선택 모달 -->
        <BankModal :showDetails="showDetails" @update:showDetails="showDetails = $event" @select-bank="selectBank" />
      </div>
      <hr class="custom-hr" />
      <p class="text-center mb-2">매도용 인감증명서를 <br> 준비해 주세요.</p>
      <button type="button" class="btn btn-primary w-100" @click="toggleView">다음</button>
    </div>
    <div v-if="fileuploadvue" class="card p-3 my-4">
      <h4>매도용 인감증명서를 첨부해 주세요</h4>
      <div class="form-group">
        <img id="imagePreview" :src="imageSrc" class="image-preview mx-2 my-4" v-if="imageSrc" />
        <button type="button" class="btn btn-fileupload w-100 mt-4 w-100" @click="triggerFileUpload">
          파일 첨부
        </button>
        <input type="file" @change="handleFileUpload" ref="fileInputRef" style="display: none;" id="file_user_photo">
        <div class="text-start text-muted mt-2" v-if="registerForm.file_user_sign">사진 파일: {{ registerForm.file_user_sign }}</div>
      </div>
      <h4 class="mt-4">마지막으로 꼼꼼히 확인해 주세요!</h4>
      <div class="summary-box d-flex flex-column p-3 mt-3">
        <div class="d-flex justify-content-start gap-5 mb-2">
          <p class="mb-0">낙찰액</p>
          <p class="mb-0">{{ amtComma(selectedBid?.price ?? 0) }}</p>
        </div>
        <div class="d-flex justify-content-start gap-5">
          <p class="mb-0"><span class="me-3">딜</span>러</p>
          <p class="mb-0">{{ userData?.dealer.name }}</p>
        </div>
        <div class="d-flex justify-content-start gap-5 mt-2">
          <p class="mb-0">탁송일</p>
          <p class="mb-0"><span>{{yearLabel}} </span>&nbsp;{{ monthLabel}} {{ selectedDateLabel }} {{ selectedTime }}</p>
        </div>
        <div class="d-flex justify-content-start gap-5 mt-2">
          <p class="mb-0"><span class="me-3">은</span>행</p>
          <p class="mb-0"><span class="me-2">{{ selectedBank }}</span>|<span class="ms-2">{{ account }}</span></p>
        </div>
      </div>
      <button class="btn btn-primary my-3 w-100" @click="confirmSelection">완료</button>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, defineProps, defineEmits, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { cmmn } from '@/hooks/cmmn';
import BankModal from '@/views/modal/bank/BankModal.vue';
import profileDom from '/resources/img/profile_dom.png';

const days = ref(getNextTwoDays());
const selectedDay = ref(null);
const selectedTime = ref(null);
const morningTimes = ['9:00', '9:30', '10:00', '10:30', '11:00', '11:30'];
const afternoonTimes = ['13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'];
const monthLabel = ref(getMonthLabel());
const yearLabel = ref(getYearLabel());
const fileuploadvue = ref(false);
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
const { amtComma } = cmmn();

const selectedBid = ref(null);
const userInfo = ref(null);

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

const confirmSelection = () => {
  emit('confirm', {
    bid: selectedBid.value,
    userData: userInfo.value,
    selectedDate: selectedDay.value !== null ? days.value[selectedDay.value].date : null,
    fileSignData: registerForm.value,
    selectedTime: selectedTime.value
  });
};

onMounted(async () => {
  const script = document.createElement('script');
  script.src = "https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js";
  script.onload = () => console.log('Daum Postcode script loaded');
  document.head.appendChild(script);

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
/* 현재 년도에 대한 함수*/
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

function getNextTwoDays() {
  const today = new Date();
  const daysArray = [];
  const weekDays = ['일', '월', '화', '수', '목', '금', '토'];
  
  for (let i = 1; i <= 2; i++) {
    const nextDay = new Date(today);
    nextDay.setDate(today.getDate() + i);
    const dayLabel = i === 1 ? `내일 (${weekDays[nextDay.getDay()]})` : `${nextDay.getDate()} (${weekDays[nextDay.getDay()]})`;
    daysArray.push({ date: nextDay, label: dayLabel });
  }
  return daysArray;
}

const toggleView = () => {
  fileuploadvue.value = true;
};

function selectDay(index) {
  selectedDay.value = index;
  selectedTime.value = null; // 선택된 시간을 초기화
  selectedDateLabel.value = days.value[index].label;
}

function selectTime(time) {
  if (!isPastTime(time)) {
    selectedTime.value = time;
  }
}

function isPastTime(time) {
  if (selectedDay.value === null) return true;

  const [hours, minutes] = time.split(':').map(Number);
  const now = new Date();
  const selectedDate = new Date(days.value[selectedDay.value].date);
  selectedDate.setHours(hours, minutes);

  return selectedDate < now;
}

function handleBankLabelClick() {
  showDetails.value = true;
}

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

</script>


<style scoped lang="scss">
.container {
  width: 400px;
  margin: 0 auto;
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
    width: 55vw;
  }
}
</style>
