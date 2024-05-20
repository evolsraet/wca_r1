<template>
  <div class="container-fluid" v-if="auctionDetails">
    <div>
      <div class="container my-4">
        <div>
          <div class="mb-4">
            <div class="card my-auction">
              <div class="card-img-top-ty01"></div>
              <div class="card-body">
                <p class="tc-light-gray">매물번호</p>
                <span>{{ auctionDetails.data.car_no }}</span>
              </div>
              <div></div>
              <p class="tc-light-gray ms-2">진단평가 자료 정보</p>
              <file v-if="auctionDetails.data.status === 'diag'" @file-attached="handleFileAttachment" />
            </div>
          </div>
        </div>
        <div @click="toggleVisibility" class="d-flex justify-content-between align-items-center p-3 border-bottom">
          <h5>차량정보</h5>
          <img :src="isVisible ? hideIcon : showIcon" :alt="isVisible ? '숨기기' : '더보기'" class="toggle-icon" width="20px" height="10px" />
        </div>
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
            <!-- 나머지 콘텐츠 -->
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
        </transition>
      </div>
      <div class="style-view bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet">
        <div class="sheet-content">
          <div class="mt-3" @click.stop="">
            <div class="btn-group mt-3" v-if="auctionDetails.data.status === 'diag'">
              <button class="btn btn-danger tc-wh" @click="registerAuction" :disabled="!isFileAttached"> 등록 </button>
            </div>
            <div class="btn-group mt-3" v-else>
              <div class="btn primary-disable tc-wh"> 등록</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import file from "@/components/file.vue";
import useAuctions from '@/composables/auctions';

import hideIcon from '../../../../../resources/img/Icon-black-down.png';
import showIcon from '../../../../../resources/img/Icon-black-up.png';

const route = useRoute();
const { getAuctionById, updateAuctionStatus, isLoading } = useAuctions();

const auctionDetails = ref(null);
const isVisible = ref(false);
const showBottomSheet = ref(true);
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' });
const isFileAttached = ref(false);

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
    await updateAuctionStatus(id, 'ask');
    alert('등록되었습니다.');
  } catch (error) {
    console.error('Error updating auction status:', error);
    alert('등록에 실패했습니다.');
  }
};

onMounted(() => {
  fetchAuctionDetails();
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
</style>
