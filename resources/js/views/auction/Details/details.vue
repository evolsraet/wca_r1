<template>
    <!--
        TODO: 사용자: 매물상세2 딜러 체크후 모달창 추가 , ui확인때문에 바꿔놓은 반복문 원복 
    -->
    <div class="container-fluid"  v-if="auctionDetail">
        <!--차량 정보 조회 내용 : 제조사,최초등록일,배기량, 추가적으로 용도변경이력 튜닝이력 리콜이력 추가 필요-->
            <div class="container my-4">
                <div>
                <div class="mb-4">
                    <div class="tc-light-gray">조회수: {{ auctionDetail.hit }}</div>
                    <div class="card my-auction">
                        <input class="toggle-heart" type="checkbox" checked />
                        <label class="heart-toggle"></label>
                        <div :class="{ 'grayscale_img': auctionDetail.status === 'done' }" class="card-img-top-ty01"></div>
                        <div v-if="auctionDetail.status === 'done'" class="time-remaining">경매 완료</div>
                        <div class="card-body">
                            <div class="enter-view align-items-baseline ">
                                <p class="card-title fs-5"><span class="blue-box">무사고</span>현대 쏘나타(DN8)</p>
                            </div>
                            <div class="enter-view">
                                <p class="card-text tc-light-gray fs-5">{{ auctionDetail.car_no }}</p>
                                <a href="#"><span class="red-box-type02 pass-red">위카 진단평가</span></a>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    <div class="bold-18-font">
        <div v-if="auctionDetail.status === 'wait'">
            <p class="auction-deadline">경매 마감일<span> {{ auctionDetail.final_at }}</span></p>
        </div>
        <div v-else-if="auctionDetail.status === 'done'">
            <p class="auction-deadline">낙찰가 {{ selectedDealer.price }} 만원</p>
        </div>
        <div v-else>
            <p class="auction-deadline">경매 마감되었어요</p>
        </div>
    </div>
    <div class="container card-style">
    <div class="card card-custom">
        <div class="row">
        <div class="col-6">
            <div class="item-label">년식</div>
            <div class="item-value">{{ carDetails.year }}</div>
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
        <div class="container p-4">
            <ul class="machine-inform-title">
                <li class="tc-light-gray">차량번호</li>
                <li class="info-num">{{ carDetails.no }}</li>
                <li class="car-icon"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">제조사</li>
                <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">모델</li>
                <li class="sub-title">{{ carDetails.model }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">세부모델</li>
                <li class="sub-title">{{ carDetails.modelSub }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">등급</li>
                <li class="sub-title">{{ carDetails.gradeSub }}</li>
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
                <li class="sub-title">{{ carDetails.year }}</li>
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
                <li class="sub-title">{{ carDetails.fuel }}</li>
            </ul>
            <ul class="machine-inform">
                <li class="tc-light-gray">미션</li>
                <li class="sub-title">{{ carDetails.mission }}</li>
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
        </div>
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
        <textarea class="form-control text-box process" readonly style="resize: none;">{{ auctionDetail.memo }}</textarea>
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
    <!-- bottom sheet Start-->
        <div class="bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet" >
        <div class="sheet-content">
             <!-- ing(경매 진행중) or diag (진단평가)알때-->
            <div v-if="auctionDetail.status === 'wait' || auctionDetail.status === 'diag' " @click.stop="">
            <div class="steps-container">
            <div class="step completing">
                <div class="label completed">
                    STEP01
                </div>
            </div>
            <div class="line"> </div>
            <div class="step">
                <div class="label">
                    STEP02
                </div>
            </div>
            <div class="line"></div>
            <div class="step">
                <div class="label">
                    STEP03
                </div>
            </div>
        </div>
        <p class="auction-deadline">현재 등록신청 후 진단평가 진행 중 입니다.</p>
    </div>
    <!-- 딜러 선택 (chosen) 중일때  -->
    <div v-if="!selectedDealer && auctionDetail.status === 'ing'" @click.stop="">
        <div class="steps-container">
            <div class="step completed">
                <div class="label completed">
                    STEP01
                </div>
            </div>
            <div class="line completed">  </div>
            <div class="step completing">
                <div class="label completed">
                    STEP02
                </div>
            </div>
            <div class="line"></div>
            <div class="step">
                <div class="label">
                    STEP03
                </div>
            </div>
        </div>
        <p class="auction-deadline">딜러를 선택해주세요.</p>
        <p class="tc-red text-start mt-2">※ 3일후 까지 선택된 딜러가 없을시, 경매가 취소 됩니다.</p>
        <div class="btn-group mt-3 mb-2">
            <button type="button" class="btn btn-outline-dark">경매취소</button>
            <button type="button" class="btn btn-dark">재경매</button>
        </div>
        <p class="text-end tc-light-gray">3번 더 재경매 할 수 있어요.</p>
        <div class="content mt-3 text-start">
            <h5>경매에 참여한 딜러</h5>
            <p> 금액이 가장 높은 5명까지만 표시돼요.</p>
            <div class="overflow-auto select-dealer">
        <table>
            <tbody>
                <!-- 시안: 딜러 사진 , (순위)+딜러명 , 딜러소속  임시 데이터:순위, 딜러아이디, 가격 ,날짜-->
                <tr v-for="(bid, index) in sortedTopBids" :key="bid.user_id">
                    <td class="w-25"><img src="../../../../img/myprofile_ex.png" alt="딜러 사진" class="align-text-top"></td>
                <td class="d-flex flex-column align-items-center w-75">
                    <div :class="[(index === 0 ? 'red-box' : index < 3 ? 'blue-box' : 'gray-box'), 'rounded-pill', 'me-0']">
                        {{ index + 1 }}위
                    </div>
                    <div class="bold-18-font">홍길동</div> 
                </td>
              <!--  <td class="tc-light-gray align-bottom">파트너 대전 본점</td>-->
                <td class="tc-light-gray align-bottom">{{ bid.price }}원</td>
                <td class="align-middle w-25">
                    <input type="checkbox" :id="'checkbox-' + bid.user_id" class="custom-checkbox-input"  @change="selectDealer(bid, $event)">
                    <label :for="'checkbox-' + bid.user_id" class="custom-checkbox-label"></label>
                </td>
                </tr>
            </tbody>
        </table>
    </div>
        </div>
    </div>
    <!--딜러 선택 후 경매 했을떄 -->
    <div v-if="selectedDealer && auctionDetail.status === 'ing'" @click.stop="">
    <div class="steps-container">
        <div class="step completed">
            <div class="label completed">
                STEP01
            </div>
        </div>
        <div class="line completed"></div>
        <div class="step completed">
            <div class="label completed">
                STEP02
            </div>
        </div>
        <div class="line completed"></div>
        <div class="step completing">
            <div class="label completed">
                STEP03
            </div>
        </div>
    </div>
    <p class="auction-deadline">낙찰가 <span class="tc-red"> {{ selectedDealer.price }} 만원</span></p>
    <p class="tc-red text-start mt-2">※ 3일 후 자동으로 경매완료 처리됩니다. </p>
        <div class="btn-group mt-3 mb-2">
            <button type="button" class="btn btn-outline-dark" @click="cancelSelection">경매취소</button>
            <button type="button" class="btn btn-danger" @click="completeAuction">경매 완료</button>
        </div>
        
        <h5 class="mt-5 text-start">내가 선택한 딜러</h5>
        <div class="select-content my-4">
            <img src="../../../../img/myprofile_ex.png" alt="딜러 사진" width="100px">
            <div class="text-container">
                <h4 class="amount fw-semibold">{{ selectedDealer.price }} 만원</h4>
                <p class="info">{{ selectedDealer.userData.dealer.name }} | {{ selectedDealer.userData.dealer.company }}</p>
            </div>
        </div>

        <div class="p-4 rounded text-body-emphasis bg-body-secondary">
            <div class="info-item m-0">
                <div  class="phone"></div>
                <p>010-1234-1234</p>
            </div>
            <div class="info-item m-0">
            <div class="location"></div >
            <p>
                <span>{{ selectedDealer.userData.dealer.company_addr1 }},{{ selectedDealer.userData.dealer.company_addr2 }}</span>
            </p>
            </div>
            <div class="info-item m-0">
            <p class="text-start">{{ selectedDealer.userData.dealer.introduce || '소개 정보 없음' }}</p>
            </div>
        </div>
    </div>
        <!-- 경매 완료 -->
        <div v-if="auctionDetail.status === 'done'" @click.stop="">
        <h5 class="text-center p-4"> 거래는 어떠셨나요?</h5>
        <router-link :to="{ name: 'user.review' }" type="button" class="tc-wh btn btn-danger w-100">후기 남기기</router-link>
    </div>
    </div>

     <!-- 바텀 시트 shwo-->
    <button  class="animCircle scroll-button" :style="scrollButtonStyle" v-show="scrollButtonVisible"></button>

 </div>
    <!-- bottom sheet END-->

</div>
</template>
<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import useUsers from "@/composables/users";
import { useRoute } from 'vue-router';
import useAuctions from "@/composables/auctions";

const scrollButtonVisible = ref(false);
const selectedDealer = ref(null);
const showBottomSheet = ref(true); //바텀 시트
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' });
const scrollButtonStyle = ref({ display:'none'});

const auctionDetail = ref(null); //상세 경매
const route = useRoute();
const { getAuctions, auctionsData, submitCarInfo } = useAuctions();
const { getUser } = useUsers();
const carDetails = ref({}); //상세 경매(차 정보)

// 상위 5개의 입찰을 추출하고 정렬
const sortedTopBids = computed(() => {
    return auctionDetail.value?.top_bids?.sort((a, b) => b.price - a.price).slice(0, 5) || []; //.slice(0, 5) : 총 5 개만 뷰에 보여짐 , 가격 비교하여 가장 높은가격순
});



function toggleSheet() { //바텀 시트 토글시 스타일 변경
    if (showBottomSheet.value) {
        bottomSheetStyle.value = { position: 'static', bottom: '-100%' };
        scrollButtonStyle.value = {display:'block'};
    } else {
        bottomSheetStyle.value = { position: 'fixed', bottom: '0px' };
        scrollButtonStyle.value = {display:'none'};
    }
    showBottomSheet.value = !showBottomSheet.value;
}
// 모든 경매 데이터를 불러오는 함수 호출
onMounted(async () => {
    await getAuctions();
    findAuctionDetail();
    window.addEventListener('scroll', checkScroll);
    const storedData = localStorage.getItem('carDetails');
  if (storedData) {
    carDetails.value = JSON.parse(storedData);
  } else {
    console.log('No car details');
  }
});

// URL 파라미터로 받은 ID를 이용하여 해당 경매 데이터 찾기
const findAuctionDetail = () => {
    const auctionId = parseInt(route.params.id);
    const detail = auctionsData.value.find(auction => auction.id === auctionId);
    if (detail) {
        auctionDetail.value = detail; // 경매 상세 정보 저장
    } else {
        console.error("no data"); // 데이터 없음 처리
    }
};

// 딜러 선택 시
function selectDealer(bid, event) {
    if (event.target.checked) {
        event.target.checked = true;
        const confirmed = confirm("선택한 딜러를 확정하시겠습니까?"); //임시 => 시안은 팝업창
        
        if (confirmed) {
            getUser(bid.user_id).then(userData => {
                selectedDealer.value = Object.assign({}, bid, { userData: userData });
                console.log(userData);
            }).catch(error => {
                console.error("Error fetching user data:", error);
            });
        } else {
            event.target.checked = false; // 사용자가 취소를 누르면 체크박스 해제
        }
    } else {
        selectedDealer.value = null;  // 체크박스 해제 시 selectedDealer 초기화
    }
}

//딜러 선택 후 경매 취소
function cancelSelection() {
    selectedDealer.value = null;  
}
//딜러 선택 후 경매 완료
function completeAuction() {
    auctionDetail.value.status = 'done';  
}

function checkScroll() {
  const scrollY = window.scrollY;
  const windowHeight = document.documentElement.clientHeight;
  const totalHeight = document.documentElement.scrollHeight;

  if (scrollY + windowHeight >= totalHeight) {
    scrollButtonVisible.value = false;
  } else {
    scrollButtonVisible.value = true;
  }
}

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll);
});

</script>


<style type="text/css">
  .tbl_basic table {width: 100%;border-collapse: collapse; font-size:14px;}
  .tbl_basic table tr th, .tbl_basic table tr td { text-align:center; }
  .tbl_basic table tr th {padding:8px 5px; border-radius: 6px; background: #f5f5f6; color: gray; border-style:solid none;}
  .tbl_basic table tr td {padding:7px 5px; }
  @media screen and (max-width: 481px) 
  {
    .o_table_mobile {width: 100%;overflow: hidden;}
    .o_table_mobile .tbl_basic {overflow-x: scroll;}
    .o_table_mobile .tbl_basic table {width: 100%;min-width:480px;}
  }
</style>    

<style scoped>
.select-content {
    display: flex;
    align-items: stretch;
}
.scroll-button {
    width: 30px;
    height: 43px;
    border-radius: 50%;
    box-shadow: 0 3px 6px 0 rgba(27, 50, 142, 0.2);
    background-color: #fff;
    border: none;
    color: #000;
    font-size: 14px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    padding: 4px 10px 5px 34px;
    position: fixed;
    bottom: 0;
    transform: translate(-50%, -50%);
    right: 0px;
    background-repeat: no-repeat;
    background-size: 20px 20px;
    background-position: 10px center;
    z-index: 40;
}


.text-container {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px;
    flex: 1;
    align-items: flex-start;
}

.select-content.amount, .select-content.info {
    flex: 1; 
    display: flex;
    align-items: center;
}






.custom-checkbox-input {
    display: none;
}

.custom-checkbox-label {
    position: relative;
    cursor: pointer;
    display: inline-block;
    width: 20px; 
    height: 20px; 
}

.custom-checkbox-label:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #fff; 
    border: 2px solid #ccc; 
    box-sizing: border-box;
    transition: background-color 0.2s, border-color 0.2s;
}

.custom-checkbox-label:after {
    content: '✔'; 
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff; 
    font-size: 14px; 
    opacity: 0; 
    transition: opacity 0.2s; 
}

.custom-checkbox-input:checked + .custom-checkbox-label:before {
    background-color: red; 
    border-color: red; 
}
.custom-checkbox-input:checked + .custom-checkbox-label:after {
    opacity: 1; 
}




.select-dealer{
    max-height: 220px;

}
.select-dealer tr{
    border-bottom: 1px solid #d4d4d4;
    line-height: 40px;
}
input[type="checkbox"] {
    align-self: center; 
}

.select-dealer td img {
    width: 50px;
    height: auto;
    max-height: 50px;
}


    .bottom-sheet {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: white; 
        box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
        transition: bottom 0.5s ease-in-out;
        z-index: 1000; 
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
    }
    .bottom-sheet::before {
    content: "";
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: #dbdbdb;
}
    .sheet-content {
        padding: 20px;
        text-align: center;
    }
    .card-img-top-ty01 {
        width: 100%;
        height: 160px;
        background-image: url('../../../../img/car_example.png');
        background-size: cover;
        background-position: center;
        border-radius: 6px;
    }
    .auction-deadline {
    width: 100%;
    height: 38px;
    background-color: #f5f5f6;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: space-around;
    gap: 5px;
}
.card-style{
    padding-top: 1.5rem;
    padding-right: 1.5rem;
    padding-left: 1.5rem;
}
.card-style .card:hover{
    box-shadow: 0 0 6px 0 rgba(27, 50, 142, 0.2);
}

.card-style .card-custom {
      margin: 1rem;
      padding: 1rem;
      border-radius: 5px;
      text-align: center;
    }
    .card-style .card-custom :hover{
        box-shadow: none !important;
    }
    .card-style .item-label {
      font-size: 0.85rem;
      color: #6c757d;
      margin-bottom: 0.5rem;
    }
    .card-style .item-value {
      font-size: 1.25rem;
      color: #212529;
      margin-bottom: 1rem; 
    }
    .card-style .row:not(:last-child) {
      padding-bottom: 1rem;
    }
    .card-style .row:not(:first-child) {
      padding-top: 1rem; 
    }
    .card-style .col-6 {
      position: relative;
    }
    .card-style .col-6:not(:last-child)::after {
        content: "";
        position: absolute;
        right: 0;
        top: 50%; 
        bottom: 50%; 
        height: 50%;
        width: 1px;
        background-color: #dee2e6;
        transform: translateY(-50%);
}

.contour-style {
    height: 6px;
    background-color: #d9d9d9;
}
.styled-div {
    width: 100%;
    height: 200px;

    border-radius: 6px;
    background-color: #f5f5f6;
}
</style>
