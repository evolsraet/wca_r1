<template>
    <div class="container p-4" id="detailCar">
            <h5>차량 정보</h5>
            <ul class="machine-inform-title">
              <li class="text-muted">차량번호</li>
              <li class="info-num">{{ content.no }}</li>
              <li class="car-icon"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">제조사</li>
              <li class="sub-title"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">모델</li>
              <li class="sub-title">{{ content.model }}</li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">세부모델</li>
              <li class="sub-title">{{ content.modelSub }}</li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">등급</li>
              <li class="sub-title">{{ content.grade }}</li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">세부등급</li>
              <li class="sub-title">{{ content.gradeSub }}</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-muted">최초등록일</li>
              <li class="info-num"></li>
              <li class="car-aside-icon"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">년식</li>
              <li class="sub-title">{{ content.year }}</li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">차량유형</li>
              <li class="sub-title">종합 승용차</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-muted">배기량</li>
              <li class="info-num">2000cc</li>
              <li class="gasoline-icon"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">연료</li>
              <li class="sub-title">{{ content.fuel }}</li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">미션</li>
              <li class="sub-title">{{ content.mission }}</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-muted">용도변경이력</li>
              <li class="info-num">-</li>
              <li class="clean-icon"></li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">튜닝이력</li>
              <li class="sub-title">1회</li>
            </ul>
            <ul class="machine-inform">
              <li class="text-muted">리콜이력</li>
              <li class="sub-title">-</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-muted">옵션정보</li>
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
              <li class="text-muted">추가옵션</li>
              <li class="info-num">-</li>
            </ul>
          </div>
          <div class="contour-style"></div>
          <div class="container px-4 py-5">
            <h5>이력</h5>
            <div class="p-4 rounded text-body-emphasis bg-body-secondary">
              <ul class="mt-0 machine-inform-title">
                <li class="text-muted">용도 변경이력</li>
                <li class="info-num">-</li>
              </ul>
              <ul class="mt-0 machine-inform-title">
                <li class="text-muted">소유자 변경</li>
                <li class="info-num">1</li>
              </ul>
              <ul class="mt-0 machine-inform-title">
                <li class="text-muted">압류/저당</li>
                <li class="info-num">-</li>
              </ul>
              <ul class="mt-0 mb-0 machine-inform-title">
                <li class="text-muted">특수사고 이력</li>
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
            <h5 class="mt-5">기타</h5>
            <div class="form-group">
              <textarea class="form-control text-box process" readonly style="resize: none;">{{ auctionData.memo }}</textarea>
            </div>
            <ul class="machine-inform-title">
              <li class="text-muted">거래지역</li>
              <li class="info-num">경기>성남시 중원구</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-muted">기타이력</li>
              <li class="info-num">-</li>
            </ul>
            <ul class="machine-inform-title">
              <li class="text-muted">차량명의</li>
              <li class="info-num">개인</li>
            </ul>
    </div>
 </template>

<script setup>

import {defineProps} from 'vue';


const props = defineProps({
  content: Object ,
  auctionData :  Object
});
 
</script>
