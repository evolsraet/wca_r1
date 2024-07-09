<template>
  <h4><span class="admin-icon admin-icon-menu03"></span>매물 관리</h4>
  <div class="row justify-content-center my-5">
  <div class="container-fluid" v-if="auctionDetails">
    <form @submit.prevent="updateAuction(auctionId, auction)">
      <div>
        <div class="container my-4 mov-wide">
          <div>
            <div class="mb-4">
              <div class="card my-auction">
                <div v-if="!isMobileView" class="d-flex flex-row">
                    <div class="w-50">
                        <div class="card-img-top-ty02 review-img"></div>
                    </div>
                    <div class="w-50 d-flex flex-column">
                        <div class="card-img-top-ty02 h-50 left-image background-auto"></div>
                        <div class="card-img-top-ty02 h-50 right-image background-auto"></div>
                    </div>
                </div>
                <div v-if="isMobileView">
                    <div class="card-img-top-ty02"></div>
                </div>
                <!--
                <div class="card-body">
                  <p class="tc-light-gray">수정일자</p>
                  <input v-model="auction.updated_at" id="updatedAt" class="form-control" type="datetime-local">
                </div>-->
                <div class="card-body">
                  <p class="tc-light-gray">차량번호</p>
                  <input v-model="auction.car_no" id="car_no" class="form-control"/>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">등록일자</p>
                  <input v-model="created_at" id="bank" class="input-dis form-control" readonly/>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">최종 수정일자</p>
                  <input v-model="updated_at" id="bank" class="input-dis form-control" readonly/>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">소유자명</p>
                  <input v-model="auction.owner_name" id="owner_name" class="form-control"/>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">상태</p>
                  <select class="form-select" :v-model="auction.status" @change="changeStatus($event)" id="status">
                    <option v-for="(label, value) in statusLabel" :key="value" :value="value">{{ label }}</option>
                  </select>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">은행</p>
                  <input v-model="auction.bank" type="text" id="bank" placeholder="은행 선택" @click="handleBankLabelClick" class="input-dis form-control" readonly>
                </div>
                <BankModal :showDetails="showDetails" @update:showDetails="showDetails = $event" @select-bank="selectBank" />
                <div class="card-body">
                  <p class="tc-light-gray">계좌번호</p>
                  <input v-model="auction.account" id="account" class="form-control"/>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">메모</p>
                  <input v-model="auction.memo" id="memo" class="form-control"/>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">지역</p>
                  <select class="form-select" :v-model="auction.region" @change="changeRegion" id="region">
                    <option value="" selected>시/도 선택</option>
                    <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
                  </select>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">우편주소</p>
                  <input v-model="auction.addr_post" placeholder="우편번호" class="input-dis form-control" readonly>
                  <div>
                    <input v-model="auction.addr1" class="input-dis form-control" readonly>
                    <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')">검색</button>
                  </div>
                  <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
                  </div>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">상세주소</p>
                  <input v-model="auction.addr2" class="form-control" id="addr2">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">경매마감일</p>
                  <input v-model="auction.final_at" id="finalAt" class="form-control" type="datetime-local">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">선택일</p>
                  <input v-model="auction.choice_at" id="choiceAt" class="form-control" type="datetime-local">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">완료일</p>
                  <input v-model="auction.done_at" id="doneAt" class="form-control" type="datetime-local">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">성공수수료</p>
                  <input v-model="auction.success_fee" id="successFee" class="form-control" @input="updateKoreanAmount('successFee')">
                  <p class="d-flex justify-content-end tc-light-gray p-2">{{ successFeeKorean }}</p>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">진단수수료</p>
                  <input v-model="auction.diag_fee" id="diagFee" class="form-control" @input="updateKoreanAmount('diagFee')">
                  <p class="d-flex justify-content-end tc-light-gray p-2">{{ diagFeeKorean }}</p>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">총 비용</p>
                  <input v-model="auction.total_fee" id="totalFee" class="form-control" @input="updateKoreanAmount('totalFee')">
                  <p class="d-flex justify-content-end tc-light-gray p-2">{{ totalFeeKorean }}</p>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">희망가</p>
                  <input v-model="auction.hope_price" id="hopePrice" class="form-control" @input="updateKoreanAmount('hopePrice')">
                  <p class="d-flex justify-content-end tc-light-gray p-2">{{ hopePriceFeeKorean }}</p>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">낙찰가</p>
                  <input v-model="auction.final_price" id="finalPrice" class="form-control" @input="updateKoreanAmount('finalPrice')">
                  <p class="d-flex justify-content-end tc-light-gray p-2">{{ finalPriceFeeKorean }}</p>
                </div>
                <div></div>
                <div class="mb-3">
                  <label for="user-title" class="form-label">위임장 or 소유자 인감 증명서</label>
                  <input type="file" @change="handleFileUploadOwner" ref="fileInputOwner" style="display:none">
                  <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadOwner">
                      파일 첨부
                  </button>
                  <div class="text-start mb-5 tc-light-gray" v-if="auction.file_user_owner_name">매매업체 대표증 / 종사원증 : {{ auction.file_user_owner_name }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-3" @click.stop="">
              <div class="btn-group mt-3">
                <button class="btn btn-primary tc-wh"> 저장 </button>
              </div>
          </div>
          <!--
          <div @click="toggleVisibility" class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h5>차량정보</h5>
            <img :src="isVisible ? hideIcon : showIcon" :alt="isVisible ? '숨기기' : '더보기'" class="toggle-icon" width="20px" height="10px" />
          </div>-->
        </div>

        <div>
          <transition name="slide">
            <div v-show="isVisible" class="container card-style">
              <div class="card card-custom">
                <div class="row">
                  <div class="col-6">
                    <div class="item-label">년식</div>
                    <div class="item-value"></div>
                  </div>
                  <div class="col-6">
                    <div class="item-label">주행거리</div>
                    <div class="item-value">103,000km</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="item-label">내사 피해</div>
                    <div class="item-value">1건</div>
                  </div>
                  <div class="col-6">
                    <div class="item-label">타사 피해</div>
                    <div class="item-value">3건</div>
                  </div>
                </div>
              </div>
            </div>
          </transition>
          <transition name="slide">
            <div v-show="isVisible" class="container p-4">
              
              <ul class="machine-inform-title">
                <li class="tc-light-gray">차량번호</li>
                <li class="info-num"></li>
                <li class="car-icon"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">제조사</li>
                <li class="sub-title"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">모델</li>
                <li class="sub-title"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">세부모델</li>
                <li class="sub-title"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">등급</li>
                <li class="sub-title"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">세부등급</li>
                <li class="sub-title"></li>
              </ul>
              <ul class="machine-inform-title">
                <li class="tc-light-gray">최초등록일</li>
                <li class="info-num"></li>
                <li class="car-aside-icon"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">년식</li>
                <li class="sub-title"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">차량유형</li>
                <li class="sub-title">종합 승용차</li>
              </ul>
              <ul class="machine-inform-title">
                <li class="tc-light-gray">배기량</li>
                <li class="info-num">2000cc</li>
                <li class="gasoline-icon"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">연료</li>
                <li class="sub-title"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">미션</li>
                <li class="sub-title"></li>
              </ul>
              <ul class="machine-inform-title">
                <li class="tc-light-gray">용도변경이력</li>
                <li class="info-num">-</li>
                <li class="clean-icon"></li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">튜닝이력</li>
                <li class="sub-title">1회</li>
              </ul>
              <ul class="machine-inform">
                <li class="tc-light-gray">리콜이력</li>
                <li class="sub-title">-</li>
              </ul>
              <ul class="machine-inform-title">
                <li class="tc-light-gray">옵션정보</li>
              </ul>
              <div></div>
              <ul class="machine-inform-title">
                <li class="tc-light-gray">추가옵션</li>
                <li class="info-num">-</li>
              </ul>
              <div class="contour-style"></div>
              <div class="container px-4 py-5">
                <h5>이력</h5>
                <div class="p-4 rounded text-body-emphasis bg-body-secondary">
                  <ul class="mt-0 machine-inform-title">
                    <li class="tc-light-gray">용도 변경이력</li>
                    <li class="info-num">-</li>
                  </ul>
                  <ul class="mt-0 machine-inform-title">
                    <li class="tc-light-gray">소유자 변경</li>
                    <li class="info-num">1</li>
                  </ul>
                  <ul class="mt-0 machine-inform-title">
                    <li class="tc-light-gray">압류/저당</li>
                    <li class="info-num">-</li>
                  </ul>
                  <ul class="mt-0 mb-0 machine-inform-title">
                    <li class="tc-light-gray">특수사고 이력</li>
                    <li class="info-num">전손 0 침수0 도난0</li>
                  </ul>
                </div>
                <h5 class="mt-5">내차피해 (<span class="tc-red">1</span>건)</h5>
                <div class="o_table_mobile">
                  <div class="tbl_basic">
                    <table>
                      <tbody>
                        <tr>
                          <th>일시</th>
                          <th>부품</th>
                          <th>공임</th>
                          <th>조회</th>
                          <th>날짜</th>
                        </tr>
                        <tr>
                          <td>2024-03-22</td>
                          <td>12,000</td>
                          <td>10,000</td>
                          <td>7</td>
                          <td>2022-05-01</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <h5 class="mt-5">타차피해 (<span class="tc-red">1</span>건)</h5>
                <div class="o_table_mobile">
                  <div class="tbl_basic">
                    <table>
                      <tbody>
                        <tr>
                          <th>일시</th>
                          <th>부품</th>
                          <th>공임</th>
                          <th>조회</th>
                          <th>날짜</th>
                        </tr>
                        <tr>
                          <td>2024-03-22</td>
                          <td>12,000</td>
                          <td>10,000</td>
                          <td>7</td>
                          <td>2022-05-01</td>
                        </tr>
                        <tr>
                          <td>2024-03-22</td>
                          <td>12,000</td>
                          <td>10,000</td>
                          <td>7</td>
                          <td>2022-05-01</td>
                        </tr>
                        <tr>
                          <td>2024-03-22</td>
                          <td>12,000</td>
                          <td>10,000</td>
                          <td>7</td>
                          <td>2022-05-01</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <h5 class="mt-5">기타</h5>
                <div class="form-group">
                  <textarea class="input-dis custom-textarea mt-2" readonly style="resize: none;">{{ auctionDetails.data.memo }}</textarea>
                </div>
                <ul class="machine-inform-title">
                  <li class="tc-light-gray">거래지역</li>
                  <li class="info-num">경기>성남시 중원구</li>
                </ul>
                <ul class="machine-inform-title">
                  <li class="tc-light-gray">기타이력</li>
                  <li class="info-num">-</li>
                </ul>
                <ul class="machine-inform-title">
                  <li class="tc-light-gray">차량명의</li>
                  <li class="info-num">개인</li>
                </ul>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </form>
  </div>
  </div>
</template>

<script setup>

import { ref, onMounted, reactive, watchEffect ,onBeforeUnmount , nextTick } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import BankModal from '@/views/modal/bank/BankModal.vue';
import file from "@/components/file.vue";
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';
import hideIcon from '../../../../../resources/img/Icon-black-down.png';
import showIcon from '../../../../../resources/img/Icon-black-up.png';
import { regions, updateDistricts } from '@/hooks/selectBOX.js';

const showDetails = ref(false); 
const isActive = ref(false);
const isMobileView = ref(window.innerWidth <= 640);
const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };
const auction = reactive({ 
    bank : '',
    account : '',
    car_no: '',
    owner_name: '',
    bank: '',
    account: '',
    memo: '',
    region:'',
    addr_post: '',
    status : '',
    addr1: '',
    addr2: '',
    final_at: '',
    choice_at: '',
    done_at: '',
    success_fee: '',
    diag_fee: '',
    total_fee: '',
    hope_price: '',
    final_price: '',
    file_user_owner : '',
    file_user_owner_name : '',
}); 

/*
const userData = reactive({
  file_user_owner : '',
  file_user_owner_name : '',
});
*/

const route = useRoute();
const { getAuctionById, updateAuctionStatus, isLoading, updateAuction } = useAuctions();
const auctionId = parseInt(route.params.id); 
const auctionDetails = ref(null);
const isVisible = ref(false);
const showBottomSheet = ref(true);
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' });
const isFileAttached = ref(false);
const { openPostcode , closePostcode, amtComma, formatCurrency, wicas } = cmmn();
const successFeeKorean = ref('0 원');
const diagFeeKorean = ref('0 원');
const totalFeeKorean = ref('0 원');
const hopePriceFeeKorean = ref('0 원');
const finalPriceFeeKorean = ref('0 원');
const fileInputOwner = ref(null);

let created_at;
let updated_at;

const store = useStore();

let statusLabel;

// 은행 선택 라벨 클릭 시 처리 함수
const handleBankLabelClick = () => {
  showDetails.value = true;
  nextTick(() => {
    isActive.value = true;
  });
};

// 은행 선택 처리 함수
const selectBank = bankName => {
  auction.bank = bankName;
  showDetails.value = false;
  isActive.value = false;
};

// 은행 선택 모달 닫기 함수
const closeDetailContent = () => {
  showDetails.value = false;
  isActive.value = false;
};

const updateKoreanAmount = (price) => {
  if(price == 'successFee'){
    successFeeKorean.value = formatCurrency(auction.success_fee);
  }
  if(price == 'diagFee'){
    diagFeeKorean.value = formatCurrency(auction.diag_fee);
  }
  if(price == 'totalFee'){
    totalFeeKorean.value = formatCurrency(auction.total_fee);
  }
  if(price == 'hopePrice'){
    hopePriceFeeKorean.value = amtComma(auction.hope_price);
  }
  if(price == 'finalPrice'){
    finalPriceFeeKorean.value = amtComma(auction.final_price);
  }
  
};

function changeStatus(event) {
  auction.status = event.target.value;
}

function changeRegion(event) {
  auction.region = event.target.value;
}

const toggleVisibility = () => {
  isVisible.value = !isVisible.value;
};

function toggleSheet() {
  const bottomSheet = document.querySelector('.bottom-sheet');
  
  if (showBottomSheet.value) {
    bottomSheetStyle.value = { position: 'static', bottom: '-100%' };
  } else {
    bottomSheetStyle.value = { position: 'fixed', bottom: '0px' };
  }
  showBottomSheet.value = !showBottomSheet.value;
}

const fetchAuctionDetails = async () => {
  try {
    const id = route.params.id;
    const data = await getAuctionById(id);
    auctionDetails.value = data;
    console.log('Fetched auction details:', data);
  } catch (error) {
    console.error('Error fetching auction details:', error);
  }
};

const handleFileAttachment = () => {
  isFileAttached.value = true;
  console.log('File attached successfully');
};

const registerAuction = async () => {
  if (!isFileAttached.value) {
    alert('파일을 첨부해주세요.');
    return;
  }
  
  try {
    const id = route.params.id;
    await updateAuctionStatus(id, 'diag');
    router.push({ name: 'auctions.index' }); 
    alert('등록되었습니다.');
  } catch (error) {
    console.error('Error updating auction status:', error);
    alert('등록에 실패했습니다.');
  }
};

function editPostCode(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
      auction.addr_post = zonecode;
      auction.addr1 = address;
    })
}

function handleFileUploadOwner(event) {
    const file = event.target.files[0];
    if (file) {
        auction.file_user_owner = file;
        auction.file_user_owner_name = file.name;
        console.log("Owner file:", file.name);
    }
}

function triggerFileUploadOwner() {
    if (fileInputOwner.value) {
      fileInputOwner.value.click();
    } else {
        console.error("위임장 또는 소유자 인감 증명서 파일을 찾을 수 없습니다.");
    }
}

onMounted(async () => {
  statusLabel = wicas.enum(store).auctions();
  window.addEventListener('resize', checkScreenWidth);
  checkScreenWidth();
  await fetchAuctionDetails();
  const data = auctionDetails.value.data;
  document.getElementById("status").value = data.status;

  watchEffect(() => {
      updated_at = data.updated_at;
      created_at = data.created_at;
      auction.car_no = data.car_no;
      auction.owner_name = data.owner_name;
      auction.status = data.status;
      document.getElementById("status").value = data.status;
      auction.region = data.region;
      document.getElementById("region").value = data.region;
      auction.bank = data.bank;
      auction.account = data.account;
      auction.memo = data.memo;
      auction.addr_post = data.addr_post;
      auction.addr1 = data.addr1;
      auction.addr2 = data.addr2;
      auction.final_at = data.final_at;
      auction.choice_at = data.choice_at;
      auction.done_at = data.done_at;
      auction.success_fee = data.success_fee;
      auction.diag_fee = data.diag_fee;
      auction.total_fee = data.total_fee;
      auction.hope_price = data.hope_price;
      auction.final_price = data.final_price;
      auction.bank = data.bank;
      auction.account = data.account;

      if(data.success_fee){
        successFeeKorean.value = formatCurrency(data.success_fee);
      }
      if(data.diag_fee){
        diagFeeKorean.value = formatCurrency(data.diag_fee);
      }
      if(data.total_fee){
        totalFeeKorean.value = formatCurrency(data.total_fee);
      }
      if(data.hope_price){
        hopePriceFeeKorean.value = amtComma(data.hope_price);
      }
      if(data.final_price){
        finalPriceFeeKorean.value = amtComma(data.final_price);
      }
      
  });

});


onBeforeUnmount(() => {
  window.removeEventListener('resize', checkScreenWidth);
}); 
</script>

<style scoped>
.bottom-sheet {
  height: auto !important;
}
.card-img-top-ty01 {
  width: 100%;
  height: 160px;
  background-image: url('../../../../img/car_example.png');
  background-size: cover;
  background-position: center;
  border-radius: 6px;
}
.slide-enter-active, .slide-leave-active {
  transition: all 0.5s ease;
}
.slide-enter-from, .slide-leave-to {
  max-height: 0;
  opacity: 0;
  overflow: hidden;
}
.slide-enter-to, .slide-leave-from {
  max-height: 1000px;
  opacity: 1;
  overflow: hidden;
}

.card-body {
  margin-bottom: 10px;
}

.card-body:last-child {
  margin-bottom: 0; 
}
.search-btn {
  transform: translateY(-241%) !important; 
}
</style>
