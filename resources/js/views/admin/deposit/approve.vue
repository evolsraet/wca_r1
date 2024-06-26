<template>
  <div class="container-fluid" v-if="auctionDetails">
    <form @submit.prevent="updateAuction(auctionId, auction)">
      <div>
        <div class="container mov-wide my-4">
          <div>
            <div class="mb-4">
              <div class="card my-auction">
                <div class="d-flex align-items-baseline justify-content-between">
                  <h4>No.3801<span class="tc-light-gray ms-3 fw-lighter">차량번호 385가1231</span></h4>
                  <a class="btn-apply">자세히 보기</a>
                  </div>
                  <div class="sell-info mb-5">
                          <div class="car-image-style">
                            <div class="card-img-top-ty01"></div>
                          </div>
                    <div class="car-info">
                        <div class="item">
                            <span class="label">성공수수료</span>
                            <span class="value">300,000 원</span>
                        </div>
                        <div class="item">
                            <span class="label">진단 비</span>
                            <span class="value">120,000 원</span>
                        </div>
                        <div class="total">
                            <span>합 계</span>
                            <span>420,000 원</span>
                        </div>
                    </div>
                </div>
                  <div class="card-body">
                      <p class="tc-light-gray">입금 상태</p>
                      <select class="form-select" :v-model="auction.status" @change="changeStatus($event)" id="status">
                      <option value="done">입금 완료</option>
                      <option value="chosen">입금 대기</option>
                      </select>
                  </div>
                  <div class="card-body">
                    <p class="tc-light-gray">낙찰 금액</p>
                    <input value="40,000,000 원" class="input-dis form-control bg-secondary bg-opacity-10" readonly/>
                  </div>
                  <div class="card-body">
                    <p class="tc-light-gray">입금 총액</p>
                    <input value="35,000,000 원" class="input-dis form-control bg-secondary bg-opacity-10" readonly/>
                  </div>
                  <div class="card-body">
                    <p class="tc-light-gray">미수금</p>
                    <input value="5,000,000 원"  class="input-dis tc-red form-control bg-secondary bg-opacity-10" readonly/>
                  </div>
                  
              <!--       <div class="container-receipt mt-5">
                    <img src="../../../../img/admin/admin-back-sell.png" alt="Image" style="width:100%; height: auto;">
                    <div class="text-block">
                      <h4>Your Text Here</h4>
                      <p>Additional Text Here</p>
                    </div>
                  </div>
          -->
                  <!--<div class="card-body">
                      <p class="tc-light-gray">차량번호</p>
                      <input v-model="auction.car_no" id="car_no" class="form-control"/>
                  </div>
                  <div class="card-body">
                      <p class="tc-light-gray">소유자명</p>
                      <input v-model="auction.owner_name" id="owner_name" class="form-control"/>
                  </div>
                  <div class="card-body">
                    <p class="tc-light-gray">은행</p>
                    <input v-model="auction.bank" id="bank" class="form-control"/>
                  </div>
                  <div class="card-body">
                    <p class="tc-light-gray">계좌번호</p>
                    <input v-model="auction.account" id="account" class="form-control"/>
                  </div>
                <div class="card-body">
                  <p class="tc-light-gray">메모</p>
                  <input v-model="auction.memo" id="memo" class="form-control"/>
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">우편주소</p>
                  <input v-model="auction.addr_post" class="form-control" type="hidden" id="addr_post">
                  <input v-model="auction.addr1" class="form-control" id="addr1" @click="openPostcodePopup">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">상세주소</p>
                  <input v-model="auction.addr2" class="form-control" id="addr2">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">경매마감일</p>
                  <input v-model="auction.final_at" id="finalAt" class="form-control">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">선택일</p>
                  <input v-model="auction.choice_at" id="choiceAt" class="form-control">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">완료일</p>
                  <input v-model="auction.done_at" id="doneAt" class="form-control">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">성공수수료</p>
                  <input v-model="auction.success_fee" id="successFee" class="form-control">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">진단수수료</p>
                  <input v-model="auction.diag_fee" id="diagFee" class="form-control">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">총 비용</p>
                  <input v-model="auction.total_fee" id="totalFee" class="form-control">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">희망가</p>
                  <input v-model="auction.hope_price" id="hopePrice" class="form-control">
                </div>
                <div class="card-body">
                  <p class="tc-light-gray">낙찰가</p>
                  <input v-model="auction.final_price" id="finalPrice" class="form-control">
                </div>
                <div></div>
                <p class="tc-light-gray ms-2">진단평가 자료 정보</p>
                <file v-if="auctionDetails.data.status === 'ask'" @file-attached="handleFileAttachment" />
              </div>
            </div>
          </div>-->
          <div v-if="auctionDetails.data.status === 'chosen'" class="btn-group mt-4">
                  <button class="btn btn-primary tc-wh" @click="registerAuction"> 등록 </button>
                </div>
                <div class="btn-group" v-else>
                  <button class="btn primary-disable tc-wh" @click="auctionDone"> 등록</button>
                </div>
                </div>
              </div>
            </div>
                
           <!-- <div @click="toggleVisibility" class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h5>차량정보</h5>
            <img :src="isVisible ? hideIcon : showIcon" :alt="isVisible ? '숨기기' : '더보기'" class="toggle-icon" width="20px" height="10px" />
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
                  <textarea class="form-control text-box process" readonly style="resize: none;">{{ auctionDetails.data.memo }}</textarea>
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
          </transition>-->
        </div>
      </div>
    </form>
    
  </div>
</template>
<script setup>
import { ref, onMounted, reactive, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import file from "@/components/file.vue";
import useAuctions from '@/composables/auctions';
import BottomSheet from '@/views/bottomsheet/Bottomsheet-type02.vue';
import hideIcon from '../../../../../resources/img/Icon-black-down.png';
import showIcon from '../../../../../resources/img/Icon-black-up.png';

const route = useRoute();
const { getAuctionById, updateAuctionStatus, isLoading, updateAuction } = useAuctions();
const auctionId = parseInt(route.params.id); 
const auctionDetails = ref(null);
const isVisible = ref(false);
const showBottomSheet = ref(true);
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' });
const isFileAttached = ref(false);

const auction = reactive({ 
    car_no: '',
    owner_name: '',
    bank: '',
    account: '',
    memo: '',
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
}); 

function changeStatus(event) {
  auction.status = event.target.value;
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
  try {
    const id = route.params.id;
    await updateAuctionStatus(id, 'done');
    router.push({ name: 'approve.index' }); 
    alert('등록되었습니다.');
  } catch (error) {
    console.error('Error updating auction status:', error);
    alert('등록에 실패했습니다.');
  }
};

const auctionDone  = async (event) => {
  event.preventDefault(); 
  event.stopPropagation();
  alert(' 경매 완료된 매물 입니다. ');
};

// Daum Postcode API를 활용한 주소 검색 팝업 열기
const openPostcodePopup = () => {
  new daum.Postcode({
    oncomplete: data => {
      addr_post.value = data.zonecode; // 우편번호
      addr1.value = data.address; // 주소
      auction.addr_post = addr_post.value;
      auction.addr1 = addr1.value;
    }
  }).open();
};

onMounted(async () => {
  await fetchAuctionDetails();
  
  auction.car_no = auctionDetails.value.data.car_no;
  auction.owner_name = auctionDetails.value.data.owner_name;
  document.getElementById("status").value = auctionDetails.value.data.status;
  auction.bank = auctionDetails.value.data.bank;
  auction.account = auctionDetails.value.data.account;
  auction.memo = auctionDetails.value.data.memo;
  auction.addr_post = auctionDetails.value.data.addr_post;
  auction.addr1 = auctionDetails.value.data.addr1;
  auction.addr2 = auctionDetails.value.data.addr2;

  // Change body background color
  const style = document.createElement('style');
  style.innerHTML = `
    body {
      background-color: #f7f8fb !important;
    }
  `;
  document.head.appendChild(style);

  // Store the style element to remove it later
  document.body.dataset.styleElement = style;
});

// Reset body background color on component unmount
onUnmounted(() => {
  const style = document.body.dataset.styleElement;
  if (style) {
    document.head.removeChild(style);
    delete document.body.dataset.styleElement;
  }
});
</script>



<style scoped>
body, html {
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #fff;
}

.receipt {
    width: 80%;
    max-width: 600px;
    height: 90%;
    border: 1px solid #000;
    position: relative;
    background: #fff;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.perforated-top {
    width: 100%;
    height: 20px;
    position: absolute;
    top: -10px;
    left: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 5px;
}

.perforated-top span {
    width: 20px;
    height: 20px;
    background: #fff;
    border: 1px solid #000;
    border-radius: 50%;
    box-shadow: 0 0 0 10px #fff;
    margin-left: -10px; /* To create overlapping effect */
}




.my-auction{
  background-color: #f7f8fb !important;
}
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

   .sell-info {
          display: flex;
          align-items: center;
          border: 1px solid #ccc;
          border-radius: 10px;
          width: 100%;
          padding-right: 25px;
      }
      .car-image-style {
          flex: 1;
      }
      .car-info {
          flex: 1;
          padding-left: 25px;
      }
      .car-info .item {
          display: flex;
          justify-content: space-between;
          margin-bottom: 10px;
      }
      .car-info .item .label {
          font-weight: bold;
      }
      .car-info .total {
          display: flex;
          justify-content: space-between;
          font-size: 1.2em;
          font-weight: bold;
          color: blue;
      }
  .container-receipt {
    position: relative;
    text-align: center;
    color: white;
  }
  .text-block {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.5);
    padding: 20px;
  }
 @media (max-width: 400px) {
.car-info {
  padding-left: 10px;
}
.sell-info{
  padding-right: 15px;
}
}
</style>
