<template>
    <!--
        * mov-text : 모바일 일때 보이는 뷰
        * web-text: : 웹 화면에서 보이는뷰
    --> 
    <div class="container mt-2">
        <form>
            <div class="web-content-style02 d-flex justify-content-between" v-for="review in reviewsData" :key="review">
                    <div >
                        <div class="container-img">
                            <div class="left-img">
                                <div v-if ="!isMobileView" class="d-flex flex-row gap-1">
                                    <div class="w-50">
                                        <div class="card-img-top-ty02"></div>
                                    </div>
                                    <div class="w-50 d-flex flex-column gap-1">
                                    <div class="card-img-top-ty02 h-50 left-image background-auto"></div>
                                    <div class="card-img-top-ty02 h-50 right-image background-auto"></div>
                                    </div>
                                </div>
                                <div v-if = "isMobileView">
                                    <div class="card-img-top-ty02"></div>
                                </div>
                            </div>
                            <div class="web-text">
                                <div class="detail detail1"></div>
                                <div class="detail detail2"></div>
                            </div>
                        </div>
                        <div class="card-body p-3 pt-0">
                            <p class="mt-4 card-title fs-5 fw-bolder">더 뉴 그랜저 IG 2.5 가솔린 르블랑</p>
                            <p> 년 | 2.4km | 무사고</p>
                            <p class="text-secondary opacity-50">현대 소나타 (DN8)</p>
                            <div class="enter-view">
                            <AlarmModal ref="alarmModal" />
                            </div>
                            <div class="d-flex">
                            <h5 class="card-title"><span class="blue-box border-6">무사고</span></h5>
                          <!--<h5 v-if="auctionDetail.data.is_reauction !== 0"><span class="gray-box">재경매</span></h5>-->  
                        </div>
                    </div>
                    <div class="container p-3">
            <h5>차량 정보</h5>
            <ul class="machine-inform-title">
              <li class="text-secondary opacity-50">차량번호</li>
              <li class="info-num"></li>
              <li class="car-icon"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">제조사</li>
              <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">모델</li>
              <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">세부모델</li>
              <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">등급</li>
              <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">세부등급</li>
              <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-secondary opacity-50">최초등록일</li>
              <li class="info-num"></li>
              <li class="car-aside-icon"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">년식</li>
              <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">차량유형</li>
              <li class="sub-title">종합 승용차</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-secondary opacity-50">배기량</li>
              <li class="info-num">2000cc</li>
              <li class="gasoline-icon"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">연료</li>
              <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">미션</li>
              <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-secondary opacity-50">용도변경이력</li>
              <li class="info-num">-</li>
              <li class="clean-icon"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">튜닝이력</li>
              <li class="sub-title">1회</li>
            </ul>
            <ul class="machine-inform">
              <li class="text-secondary opacity-50">리콜이력</li>
              <li class="sub-title">-</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-secondary opacity-50">옵션정보</li>
            </ul>
            <div class="option-icons">
              <div class="option-row">
                <div class="option-icon">
                  <div class="icon smart-key-ac"></div>
                  <p>스마트키</p>
                </div>
                <div class="option-icon">
                  <div class="icon navigation-ac"></div>
                  <p>네비게이션</p>
                </div>
                <div class="option-icon">
                  <div class="icon rear-camera-ac"></div>
                  <p>후방카메라</p>
                </div>
              </div>
              <div class="option-row">
                <div class="option-icon">
                  <div class="icon sunroof"></div>
                  <p>선루프</p>
                </div>
                <div class="option-icon">
                  <div class="icon headlamp-ac"></div>
                  <p>헤드램프</p>
                </div>
                <div class="option-icon">
                  <div class="icon auto-aircon-ac"></div>
                  <p>자동에어컨</p>
                </div>
              </div>
              <div class="option-row">
                <div class="option-icon">
                  <div class="icon electric-seat-ac"></div>
                  <p>전동</p>
                </div>
                <div class="option-icon">
                  <div class="icon family"></div>
                  <p>가죽</p>
                </div>
                <div class="option-icon">
                  <div class="icon heated-seat-ac"></div>
                  <p>열선</p>
                </div>
                <div class="option-icon">
                  <div class="icon ventilated-seat"></div>
                  <p>통풍</p>
                </div>
              </div>
              <div class="option-row">
                <div class="option-icon">
                  <div class="icon parking-sensor"></div>
                  <p>주차 감지 센서</p>
                </div>
                <div class="option-icon">
                  <div class="icon electric-side-mirror-ac"></div>
                  <p>전동 사이드미러</p>
                </div>
              </div>
            </div>
            <ul class="machine-inform-title">
              <li class="text-secondary opacity-50">추가옵션</li>
              <li class="info-num">-</li>
            </ul>
          </div>
          <div class="contour-style"></div>
          <div class="container px-4 py-5">
            <h5>이력</h5>
            <div class="p-4 rounded text-body-emphasis bg-body-secondary">
              <ul class="mt-0 machine-inform-title">
                <li class="text-secondary opacity-50">용도 변경이력</li>
                <li class="info-num">-</li>
              </ul>
              <ul class="mt-0 machine-inform-title">
                <li class="text-secondary opacity-50">소유자 변경</li>
                <li class="info-num">1</li>
              </ul>
              <ul class="mt-0 machine-inform-title">
                <li class="text-secondary opacity-50">압류/저당</li>
                <li class="info-num">-</li>
              </ul>
              <ul class="mt-0 mb-0 machine-inform-title">
                <li class="text-secondary opacity-50">특수사고 이력</li>
                <li class="info-num">전손 0 침수 0 도난 0</li>
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

            <h5 class="mt-5">판매자 메모</h5>
            <div class="form-group">
              <textarea class="form-control text-box process" readonly style="resize: none;"></textarea>
            </div>
            <h5 class="mt-5">평가자 의견</h5>
            <div class="form-group">
              <textarea class="form-control text-box process" readonly style="resize: none;"></textarea>
            </div>
            <ul class="machine-inform-title">
              <li class="text-secondary opacity-50">거래지역</li>
              <li class="info-num">경기>성남시 중원구</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-secondary opacity-50">기타이력</li>
              <li class="info-num">-</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-secondary opacity-50">차량명의</li>
              <li class="info-num">개인</li>
            </ul>
          </div>
      
          
          <!--<p class="mt-4 auction-deadline justify-content-sm-center text-secondary opacity-50">판매가<span>{{ amtComma(review.auction.win_bid.price) }}</span></p>-->
        </div>
        <div class="sheet-content">
            <BottomSheet>
            <div class="p-0 text-start">
                <h4>클레임 작성하기</h4>
                <p class="text-secondary opacity-50">※ 해당 차량에서 겪고 계신 불편사항이나 문제를 작성해 주시기 바랍니다.</p>
             <!--   <h5>분류</h5>
                <select>
                    <option value="">선택해 주세요</option>
                    <option value="엔진 문제">엔진 문제</option>
                    <option value="변속기 문제">변속기 문제</option>
                    <option value="전기 시스템 문제">전기 시스템 문제</option>
                    <option value="차체 문제">차체 문제</option>
                    <option value="기타">기타</option>
                </select>-->
                <h5 class="my-2">작성 내용</h5>
                <textarea class="custom-textarea mt-2" rows="6" placeholder="불편사항이나 문제 작성" id="content"></textarea>
                <div class="btn-group mt-3 mb-4">
                    <button class="btn btn-primary mt-2"> 작성 완료 </button>
                </div>
            </div>
        </BottomSheet>
    </div>
    </div>
</form>
        <AlarmModal ref="alarmModal" />
    </div>
    <Footer />
</template>
<script>
    export default {
        name : 'params',
    }
</script>
<script setup>
import { ref, onMounted, nextTick,onBeforeUnmount } from 'vue';
import { routerViewLocationKey, useRoute } from 'vue-router'; 
import { initReviewSystem } from '@/composables/review'; // 별점 js
import BottomSheet from '@/views/bottomsheet/Bottomsheet-type02.vue';
import AlarmModal from '@/views/modal/AlarmModal.vue';
import Footer from "@/views/layout/footer.vue"
import { cmmn } from '@/hooks/cmmn';

const { amtComma } = cmmn();
const isMobileView = ref(window.innerWidth <= 640);
const route = useRoute();
const reviewId = parseInt(route.params.id); 
const { getUserReviewInfo , getCarInfo , setInitialStarRating} = initReviewSystem(); 
let reviewsData = ref();
const carInfo = ref();
const alarmModal = ref(null);

const openAlarmModal = () => {
console.log("openAlarmModal called");
if (alarmModal.value) {
  alarmModal.value.openModal();
}
};
//바텀 시트 토글시 스타일변경
function toggleSheet() {
    const bottomSheet = document.querySelector('.bottom-sheet');
    
    if (showBottomSheet.value) {
        bottomSheetStyle.value = { position: 'static', bottom: '-100%' };
    } else {
        bottomSheetStyle.value = { position: 'fixed', bottom: '0px' };
    }
    showBottomSheet.value = !showBottomSheet.value;
}
const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };

onMounted(async () => {
    window.addEventListener('resize', checkScreenWidth);
    checkScreenWidth();
    const response = await getUserReviewInfo(reviewId,true);
    console.log(response);
    reviewsData.value = [response]; 
    //carInfo.value = await getCarInfo(response.auction.owner_name, response.auction.car_no);
    await nextTick();
    setInitialStarRating(response.star);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkScreenWidth);
}); 
</script>
<style scoped>
  @media (min-width: 992px){
    .handle{
      display: none;
    }
    .sheet-content{
      width: 80% !important;
      padding: 0px !important;
    }
    .sheet{
    position: relative !important;
    border-radius: 10px !important;
}
    .web-content-style02{
        display: flex;
        gap: 20px;
        padding: 15px;
        justify-content: center;
    }
  }
@media (min-width: 992px) {
  .mov-wide {
    width: 80vw;
    margin: auto;
  }
  .hv-25{
    height: auto !important;
  }
}
@media (max-width: 991px) {
  .container {
     --bs-gutter-x: 1rem !important;
       max-width:none !important;
    }
    .sheet-content{
        padding: 0px;
}
}
  @media (min-width: 992px){
    .handle{
      display: none;
    }
    .sheet-content{
      width: 80% !important;
      padding: 0px !important;
    }
}

</style>