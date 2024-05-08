<template>
    <!--
        TODO: 딜러:입찰 등록 -> 권한문제? 해결하기
    -->
    <div class="container-fluid">
        <!--차량 정보 조회 내용 : 제조사,최초등록일,배기량, 추가적으로 용도변경이력 튜닝이력 리콜이력 추가 필요-->
        <div>
            <div class="proceeding"></div>
            <div class="container my-4">
                <div>
                    <div class="mb-4">
                        <div class="card my-auction">
                            <div class="card-img-top-ty01"></div>
                            <div class="card-body">
                                <div class="enter-view">
                                    <p class="card-text tc-light-gray fs-5"></p>
                                </div>
                            </div>
                            <div>
                            </div>
                            <input type="file" ref="fileInputRefBiz" style="display:none">
                            <button type="button" class="btn btn-fileupload">
                                파일 첨부
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div>
    <button @click="toggleVisibility">
      {{ isVisible ? '숨기기' : '더보기' }}
    </button>
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
            <textarea class="form-control text-box process" readonly style="resize: none;"></textarea>
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
    </div>

</template>
<script setup>
import { ref } from 'vue';

const isVisible = ref(false);

const toggleVisibility = () => {
  isVisible.value = !isVisible.value;
};
</script>


<style>
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
  max-height: 1000px; /* 적절한 최대 높이 설정 */
  opacity: 1;
  overflow: hidden;
}
</style>
