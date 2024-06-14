<template>
  <!--
      TODO: 미구현 
            조회수는 경매완료 되면 동결 , final_at , choice_at, done_at(?) => 시간 초.
  -->
  <div class="container-fluid" v-if="auctionDetail">
      <!--차량 정보 조회 내용 : 제조사,최초등록일,배기량, 추가적으로 용도변경이력 튜닝이력 리콜이력 추가 필요-->
      <div v-if="!showReauctionView && auctionDetail.data.status !== 'wait'">
          <div class="web-content-style">
              <div class="container mov-wide">
                  <div>
                      <div>
                          <div class="mb-4">
                              <!--    <p class="card-text tc-light-gray fs-5">{{ auctionDetail.data.car_no }}</p>-->
                              <div class="card my-auction">
                                  <div>
                                      <div class="mb-3" v-if="auctionDetail.data.status === 'ask' || auctionDetail.data.status === 'diag'">
                                          <div class="diag-img">
                                              <p class="diag-text tc-light-gray mb-4">위카가 꼼꼼하게 진단 중이에요</p>
                                          </div>
                                      </div>
                                      <div v-else>
                                          <span v-if="auctionDetail.data.status === 'ing'" class="mx-2 timer"><img src="../../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock"><span v-if="timeLeft.days != '0' ">{{ timeLeft.days }}일 &nbsp; </span>{{ timeLeft.hours }} : {{ timeLeft.minutes }} : {{ timeLeft.seconds }}</span>
                                          <span v-if="auctionDetail.data.status === 'done'" class="mx-2 auction-done">경매완료</span>   
                                          <input class="toggle-heart" type="checkbox" checked />
                                          <label class="heart-toggle"></label>
                                          <div :class="[{ 'grayscale_img': auctionDetail.data.status === 'done' || auctionDetail.data.status === 'cancel' }]">
                                            <div v-if ="!isMobileView" class="d-flex flex-row">
                                                <div class="w-50">
                                                    <div class="card-img-top-ty02"></div>
                                                </div>
                                                <div class="w-50 d-flex flex-column">
                                                    <div class="card-img-top-ty02 h-50 left-image background-auto"></div>
                                                    <div class="card-img-top-ty02 h-50 right-image background-auto"></div>
                                                </div>
                                            </div>
                                            <div v-if = "isMobileView">
                                              <div class="card-img-top-ty02"></div>
                                            </div>
                                          </div>
                                          <div class="allpage">
                                              <p class="more-page">1/1</p>
                                          </div>
                                        <!--  <div v-if="auctionDetail.data.status === 'cancel'" class="time-remaining">경매 취소</div>-->
                                          <div v-if="isDealer">
                                           <!--     <div v-if="auctionDetail.data.status === 'chosen'" class="time-remaining">경매 종료</div>-->
                                          </div>
                                          <div v-else>
                                         <!--       <div v-if="auctionDetail.data.status === 'chosen'" class="time-remaining">선택 완료</div>-->
                                          </div>
                                          <h4 v-if="auctionDetail.data.status === 'done'" class="wait-selection">낙찰가 {{ amtComma&nbsp(auctionDetail.data.final_price) }}</h4>
                                          <div class="p-3 pb-1 d-flex gap-3 justify-content-between">
                                          <div></div>
                                            <!--TODO: 실차주 딜러 차주 판매 추가 하기-->
                                             <!-- <p class="bid-icon tc-light-gray normal-16-font">딜러 판매</p>
                                              <p class="bid-icon tc-light-gray normal-16-font">실차주 판매</p>-->

                                              <div class="d-flex gap-3 justify-content-end mb-1">
                                                  <div class="tc-light-gray icon-hit">조회수 {{ auctionDetail.data.hit }}</div>
                                                  <div class="tc-light-gray ml-2 icon-heart">관심 0</div>
                                                  <p class="tc-light-gray icon-bid">입찰 {{ auctionDetail.data.bids_count }}</p>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="card-body p-3 pt-0">
                                      <p class="card-title fs-5">더 뉴 그랜저 IG 2.5 가솔린 르블랑</p>
                                      <p>{{ carDetails.year }} 년 / 2.4km / 무사고</p>
                                      <p class="tc-light-gray">현대 소나타 (DN8)</p>
                                      <div class="enter-view">
                                          <AlarmModal ref="alarmModal" />
                                      </div>
                                      <div class="d-flex">
                                  <h5 class="card-title"><span class="blue-box">무사고</span></h5>
                                  <h5 v-if="auctionDetail.data.hope_price !== null"><span class="gray-box">재경매</span></h5>
                                  </div>
                                      <div v-if="auctionDetail.data.status !== 'diag' || auctionDetail.data.status !== 'ask'">
                                          <p class="ac-evaluation mt-4 btn-fileupload-red" @click.prevent="openAlarmModal">위카 진단평가 확인하기</p>
                                          <MessageModal v-if="showMessage" :show="showMessage" message="진단평가가 완료되면 자동으로 경매가 진행돼요" :duration="3000" />
                                      </div>

                                  </div>
                            <!--      <div v-if="isUser && auctionDetail.data.status === 'ing'" class="p-3">
                                    <template v-if="auctionDetail.data.hope_price !== null">
                                      <div class="bold-18-font modal-bid d-flex p-3 justify-content-between blinking">
                                        <p>현재 희망가</p>
                                        <p class="icon-coins">{{ amtComma(auctionDetail.data.hope_price) }}</p>
                                      </div>
                                    </template>
                                    <template v-else>
                                      <div class="bold-18-font modal-bid d-flex p-3 justify-content-between blinking">
                                        <p>현재 최고 입찰액</p>
                                        <p class="icon-coins">{{ amtComma(heightPrice)}}</p>
                                      </div>
                                    </template>
                                  </div>-->
                                  <div v-if="isDealer && auctionDetail.data.status === 'ing' " class="p-3">
                                
                                        <div v-if=" auctionDetail.data.hope_price !== null">
                                          <div class="bold-18-font modal-bid d-flex p-3 justify-content-between blinking">
                                              <p>현재 희망가</p>
                                              <p class="icon-coins">{{ amtComma(auctionDetail.data.hope_price) }}</p>
                                            </div>
                                        </div>
                                    
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!--     <div class="bold-18-font">
                      <div v-if="auctionDetail.data.status === 'ing'">
                          <p class="auction-deadline">경매 마감일<span> {{ auctionDetail.data.final_at }}</span></p>
                      </div>
                      <div v-else-if="auctionDetail.data.status === 'done'">
                          <p class="auction-deadline">낙찰가 {{ auctionDetail.data.final_price }} 만원</p>
                      </div>
                      <div v-else-if="auctionDetail.data.status === 'ask'">
                          <p class="auction-deadline">신청 완료</p>
                      </div>
                  </div>-->
                  <!--   <div class="container card-style">
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
                  </div>-->
                  <div class="container p-4">
                      <h5>차량 정보</h5>
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
                          <li class="sub-title">{{ carDetails.grade }}</li>
                      </ul>
                      <ul class="machine-inform">
                          <li class="tc-light-gray">세부등급</li>
                          <li class="sub-title">{{ carDetails.gradeSub }}</li>
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
                          <textarea class="form-control text-box process" readonly style="resize: none;">{{ auctionDetail.data.memo }}</textarea>
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
              <!-- bottom sheet Start-->
          <!--      <bottom-sheet initial="half" :dismissable="true">
                <div class="sheet-content">
                      #####################
                      사용자 바텀시트
                  #########################-->

                    <!--    <div v-if="isUser">
                          -----[사용자]diag (진단평가)알때------->
                    <!--      <div v-if="auctionDetail.data.status === 'diag' || auctionDetail.data.status === 'ask' " @click.stop="">
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

                          -----[사용자]취소 (진단평가)알때------->
                         <!--   <div v-if="auctionDetail.data.status === 'cancel'" @click.stop="">
                              <div class="steps-container">
                                  <div class="step">
                                      <div class="label">
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
                              <p class="auction-deadline">경매가 취소 되었습니다.</p>
                          </div>
                          <div v-if="auctionDetail.data.status === 'ing' " @click.stop="">
                              <div class="steps-container">
                                  <div class="step completing">
                                      <div class="label completed">
                                          STEP01
                                      </div>
                                      <div class="label label-style tc-light-gray">매물 준비</div>
                                  </div>
                                  <div class="line"> </div>
                                  <div class="step">
                                      <div class="label">
                                          STEP02
                                      </div>
                                      <div class="label label-style tc-light-gray completing-text">경매</div>
                                  </div>
                                  <div class="line"></div>
                                  <div class="step">
                                      <div class="label">
                                          STEP03
                                      </div>
                                      <div class="label label-style tc-light-gray">완료</div>
                                  </div>
                              </div>
                              <p class="auction-deadline mt-4">현재 경매중 입니다.</p>
                              <div class="o_table_mobile my-5">
                                  <div class="tbl_basic tbl_dealer">
                                      <div class="overflow-auto select-dealer">
                                      </div>
                                  </div>
                              </div>
                          </div>

                         [사용자]- 딜러 선택 (wait) 중일때  -->
                      <!--      <div v-if="!selectedDealer && auctionDetail.data.status === 'wait'" @click.stop="">
                              <div class="steps-container">
                                  <div class="step completed">
                                      <div class="label completed">
                                          STEP01
                                      </div>
                                      <div class="label label-style tc-light-gray">매물 준비</div>
                                  </div>
                                  <div class="line completed"> </div>
                                  <div class="step completing">
                                      <div class="label completed">
                                          STEP02
                                      </div>
                                      <div class="label label-style tc-light-gray completing-text">경매</div>
                                  </div>
                                  <div class="line"></div>
                                  <div class="step">
                                      <div class="label">
                                          STEP03
                                      </div>
                                      <div class="label label-style02 tc-light-gray">완료</div>
                                  </div>
                              </div>
                              <p class="auction-deadline mt-2">딜러를 선택해주세요.</p>
                              <p class="tc-red text-start mt-2">※ 3일후 까지 선택된 딜러가 없을시, 경매가 취소 됩니다.</p>
                              <div class="btn-group mt-3 mb-2">
                                  <button @click="openModal" type="button" class="btn btn-outline-dark">경매취소</button>
                                  <button type="button" class="btn btn-dark" @click="toggleView">재경매</button>
                              </div>
                            
                                  <auction-modal v-if="isModalVisible" :showModals="isModalVisible" :auctionId="selectedAuctionId" @close="closeModal" @confirm="handleConfirmDelete" />
                             
                               <p class="text-end tc-light-gray">3번 더 재경매 할 수 있어요.</p>-->
                         <!--       <div class="content mt-3 text-start process">
                                  <h5 class="process">경매에 참여한 딜러</h5>
                                  <p> 금액이 가장 높은 <span class="highlight">5명</span>까지만 표시돼요.</p>
                                  <div class="overflow-auto select-dealer mt-3">
                                      <table class="">
                                          <tbody>
                                              <tr v-for="(bid, index) in sortedTopBids" :key="bid.user_id">
                                                  <td class="w-25"><img src="../../../../img/myprofile_ex.png" alt="딜러 사진" class="mb-2 align-text-top"></td>
                                                  <td class="d-flex flex-column align-items-center w-75">
                                                      <div :class="[(index === 0 ? 'red-box' : index < 3 ? 'blue-box' : 'gray-box'), 'rounded-pill', 'me-0']">
                                                          {{ index + 1 }}위
                                                      </div>
                                                      <div class="bold-18-font">{{ bid.dealerInfo ? bid.dealerInfo.name : 'Loading...'}}</div>
                                                  </td>
                                                  <td class="w-30">
                                                      <div class="d-flex flex-column align-items-left">
                                                          <p class="tc-light-gray">{{bid.dealerInfo ? bid.dealerInfo.company : 'Loading...'}}</p>
                                                          <em class="lh-base tc-blue bold-18-font">{{amtComma(bid.price)}}</em>
                                                      </div>
                                                  </td>
                                                  <td class="text-center align-middle w-auto">
                                                      <input type="checkbox" :id="'checkbox-' + bid.user_id" class="custom-checkbox-input" @change="selectDealer(bid, $event, index + 1)">
                                                      <label :for="'checkbox-' + bid.user_id" class="custom-checkbox-label"></label>
                                                  </td>
                                              </tr>
                                          </tbody>
                                      </table>
                                   
                                          <ConnectDealerModal v-if="connectDealerModal" :bid="selectedBid" :userData="userInfo" @close="handleModalClose" @confirm="handleDealerConfirm" />
                                     
                                  </div>
                              </div>
                          </div>

                          [사용자] - 딜러 선택 후 경매 했을떄 -->
                         <!--   <div v-if="selectedDealer && auctionDetail.data.status === 'wait'" @click.stop="">
                              <div class="steps-container">
                                  <div class="step completed">
                                      <div class="label completed">
                                          STEP01
                                      </div>
                                      <div class="label label-style tc-light-gray">매물 준비</div>
                                  </div>
                                  <div class="line completed"></div>
                                  <div class="step completed">
                                      <div class="label completed">
                                          STEP02
                                      </div>
                                      <div class="label label-style tc-light-gray completing-text">경매</div>
                                  </div>
                                  <div class="line completed"></div>
                                  <div class="step completing">
                                      <div class="label completed">
                                          STEP03
                                      </div>
                                      <div class="label label-style02 tc-light-gray">완료</div>
                                  </div>
                              </div>
                              <p class="auction-deadline">낙찰가 <span class="tc-red"> {{ amtComma(selectedDealer.price) }}</span></p>
                              <p class="tc-red text-start mt-2">※ 3일 후 자동으로 경매완료 처리됩니다. </p>
                              <div class="btn-group mt-3 mb-2">
                                  <button type="button" class="btn btn-outline-dark" @click="cancelSelection">선택 취소</button>
                                  <button type="button" class="btn btn-primary" @click="completeAuction">선택 완료</button>
                              </div>
                           
                                <div v-if="completeAuctionModal" class="modal">
                                  <div class="modal-content">
                                    <h5>경매가 성공적으로 완료되었습니다.</h5>
                                    <button @click="closeCompleteAuctionModal">확인</button>
                                  </div>
                                </div>
                             
    
                              <h5 class="mt-5 text-start">내가 선택한 딜러</h5>
                              <div class="select-content my-4">
                                  <img src="../../../../img/myprofile_ex.png" alt="딜러 사진" width="100px">
                                  <div class="text-container">
                                      <h4 class="amount fw-semibold">{{ amtComma(selectedDealer.price) }}</h4>
                                      <p class="info">{{ selectedDealer.userData.dealer.name }} | {{ selectedDealer.userData.dealer.company }}</p>
                                  </div>
                              </div>

                              <div class="p-4 rounded text-body-emphasis bg-body-secondary">
                                  <div class="info-item m-0 process">
                                      <div class="phone"></div>
                                      <p>010-1234-1234</p>
                                  </div>
                                  <div class="info-item m-0">
                                      <div class="location"></div>
                                      <p>
                                          <span>{{ selectedDealer.userData.dealer.company_addr1 }},{{ selectedDealer.userData.dealer.company_addr2 }}</span>
                                      </p>
                                  </div>
                                  <div class="info-item m-0">
                                      <p class="text-start">{{ selectedDealer.userData.dealer.introduce || '소개 정보 없음' }}</p>
                                  </div>
                              </div>
                          </div>

                          [사용자] - 경매 완료 -->
                        <BottomSheet02>
                                <h5 class="text-center p-2" v-if="auctionDetail.data.status === 'done'">거래는 어떠셨나요?</h5>
                                <p class="auction-deadline mt-4" v-else>선택이 완료 되었습니다.</p>
                                <router-link
                                  v-if="auctionDetail.data.status === 'done'"
                                  :to="{ name: 'user.create-review' }"
                                  type="button"
                                  class="tc-wh btn btn-primary w-100"
                                >
                                  후기 남기기
                                </router-link>
                        </BottomSheet02>

                     <!--  바텀 시트 show or black-->
             <!--       <button class="animCircle scroll-button floating" :style="scrollButtonStyle" v-show="scrollButtonVisible"></button>

                      <div v-if="isDealer">
                         ------------------ [딜러] - 경매 완료 -------------------->
                        <!--    <div v-if="auctionDetail.data.status === 'chosen' || auctionDetail.data.status === 'done'" @click.stop="">
                            <div class="steps-container mb-3">
                                  <div class="step completed">
                                      <div class="label completed">
                                          STEP01
                                      </div>
                                      <div class="label label-style tc-light-gray">매물 준비</div>
                                  </div>
                                  <div class="line completed"></div>
                                  <div class="step completed">
                                      <div class="label completed">
                                          STEP02
                                      </div>
                                      <div class="label label-style tc-light-gray completing-text">경매</div>
                                  </div>
                                  <div class="line completed"></div>
                                  <div class="step completed">
                                      <div class="label completed">
                                          STEP03
                                      </div>
                                      <div class="label label-style02 tc-light-gray">완료</div>
                                  </div>
                              </div> 
                            <hr>
                              <h5 class="text-center mt-4"> 불편 사항이 있으신가요?</h5>
                              <router-link :to="{ name: 'index.claim' }" type="button" class="my-3 btn btn-outline-danger w-100">클레임 신청하기</router-link >
                                <a href="#" class="d-flex justify-content-center tc-light-gray" @click.prevent="openClaimModal">클레임 규정 확인</a>
                              
                                <ClaimModal v-if="isClaimModalOpen" :isOpen="isClaimModalOpen" @close="closeClaimModal" />
                           
                              </div>
                            

                              <div v-if="auctionDetail.data.status === 'wait'" @click.stop="">
                            <div class="steps-container mb-3">
                                  <div class="step completed">
                                      <div class="label completed">
                                          STEP01
                                      </div>
                                      <div class="label label-style tc-light-gray">매물 준비</div>
                                  </div>
                                  <div class="line completed"></div>
                                  <div class="step completed">
                                      <div class="label completed">
                                          STEP02
                                      </div>
                                      <div class="label label-style tc-light-gray completing-text">경매</div>
                                  </div>
                                  <div class="line "></div>
                                  <div class="step ">
                                      <div class="label ">
                                          STEP03
                                      </div>
                                      <div class="label label-style02 tc-light-gray">완료</div>
                                  </div>
                              </div> 
                              <p class="auction-deadline text-center mt-2">경매 선택 중 입니다.</p>
                              </div>

                          ----------------- [딜러] - 입찰 바텀 뷰 -------------------->
                     <!--       <div v-if="!succesbidhope && !auctionDetail.data.bids.some(bid => bid.user_id === user.id) && auctionDetail && !userBidCancelled && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price !==null" @click.stop="">
                            <div class="steps-container">
                                  <div class="step completed">
                                      <div class="label completed">STEP01</div>
                                      <div class="label label-style tc-light-gray">매물 준비</div>
                                  </div>
                                  <div class="line completed"></div>
                                  <div class="step completing">
                                      <div class="label completed">STEP02</div>
                                      <div class="label label-style tc-light-gray completing-text">경매</div>
                                  </div>
                                  <div class="line"></div>
                                  <div class="step">
                                      <div class="label">STEP03</div>
                                      <div class="label label-style02 tc-light-gray">완료</div>
                                  </div>
                              </div>
                              <p class="auction-deadline text-center">희망가 재경매를 시작합니다.</p>
                              <p class="tc-red mt-2">경매 마감까지 {{ timeLeft.days }}일 {{ timeLeft.hours }}시간 {{ timeLeft.minutes }}분 {{ timeLeft.seconds }}초 남음</p>
                              <div class="mt-3 d-flex justify-content-end gap-3">
                                  <p class="bid-icon tc-light-gray normal-16-font">입찰 {{ auctionDetail.data.bids.length }}</p>
                                  <p class="interest-icon tc-light-gray normal-16-font">관심 0</p>
                              </div>
                              <div>
                                  <h5 class="text-start mt-3 tc-primary">희망가에 경매하시겠습니까?</h5>
                                  <div class="input-container mt-4">
                                    <input type="text" class="styled-input" placeholder="0" v-model="amount" :readonly="auctionDetail.data.hope_price !== null">
                                  </div>
                                  <p class="d-flex justify-content-end tc-light-gray p-2">{{ amtComma(amount) }}</p>
                                  <p class="text-start tc-red mb-2">※ 희망가에 경매 시 즉시 낙찰이 가능합니다.</p>
                                  <button type="button" class="tc-wh btn btn-primary w-100" @click="submitAuctionBid">확인</button>
                              </div>
                          </div>
                          <div v-if="!succesbid && !auctionDetail.data.bids.some(bid => bid.user_id === user.id) && auctionDetail && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price ==null" @click.stop="">
                              <div class="steps-container">
                                  <div class="step completed">
                                      <div class="label completed">STEP01</div>
                                      <div class="label label-style tc-light-gray">매물 준비</div>
                                  </div>
                                  <div class="line completed"></div>
                                  <div class="step completing">
                                      <div class="label completed">STEP02</div>
                                      <div class="label label-style tc-light-gray completing-text">경매</div>
                                  </div>
                                  <div class="line"></div>
                                  <div class="step">
                                      <div class="label">STEP03</div>
                                      <div class="label label-style02 tc-light-gray">완료</div>
                                  </div>
                              </div>
                              <p class="auction-deadline text-center">경매를 시작합니다.</p>
                              <p class="tc-red mt-2">경매 마감까지 {{ timeLeft.days }}일 {{ timeLeft.hours }}시간 {{ timeLeft.minutes }}분 {{ timeLeft.seconds }}초  남음</p>
                              <div class="mt-3 d-flex justify-content-end gap-3">
                                  <p class="bid-icon tc-light-gray normal-16-font">입찰 {{ auctionDetail.data.bids.length }}</p>
                                  <p class="interest-icon tc-light-gray normal-16-font">관심 0</p>
                              </div>
                              <div>
                                  <h5 class="text-start mt-3">나의 입찰 금액을 입력해주세요</h5>
                                  <div class="input-container mt-4">
                                      <input type="text" class="styled-input" placeholder="0" v-model="amount" @input="updateKoreanAmount">
                                  </div>
                                  <p class="d-flex justify-content-end tc-light-gray p-2">{{ koreanAmount }}</p>
                                  <button type="button" class="tc-wh btn btn-primary w-100" @click="submitAuctionBid">확인</button>
                              </div>
                          </div>
                          <div v-else-if="userBidExists && !userBidCancelled && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price == null">
                                  <h5 class="text-center mt-4">입찰이 완료되었습니다.</h5>
                                  <p class="text-center tc-red">※ 최초 1회 수정이 가능합니다</p>
                                  </div>
                          ----------------- [딜러] - 입찰 완료후 바텀 메뉴 -------------------->
                           <!-- <div class="p-4" v-if="auctionDetail.data.status === 'ing' && (succesbid || auctionDetail.data.bids.some(bid => bid.user_id === user.id))&& auctionDetail.data.hope_price == null" @click.stop="">
                              <h5 class="mx-3 text-center">{{ minutesLeft }}</h5>
                              <p class="auction-deadline my-4">나의 입찰 금액 <span class="tc-red">{{ amtComma(myBidPrice) }}</span></p>
                              <h5 class="my-4">입찰 {{ auctionDetail.data.bids.length }}명/ 관심 0 명</h5>
                              <button
                                type="button"
                                class="my-3 w-100 btn"
                                :class="{'btn-outline-primary': auctionDetail.data.hope_price === null, 'primary-disable': auctionDetail.data.hope_price !== null}"
                                @click="handleCancelBid">
                                입찰 취소
                              </button>

                                수수료 보증금이 부족할때 나오는 메뉴
                                  <div class="bottom-message">
                                      성사수수료 보즘금이 부족해요
                                  </div>-->
                       <!--     </div>
                          <div v-else-if="userBidExists && !userBidCancelled && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price == null">
                                  <h5 class="text-center mt-4">입찰이 완료되었습니다.</h5>
                                  <p class="text-center tc-red">※ 최초 1회 수정이 가능합니다</p>
                                  </div>
                          
                                  <bid-modal v-if="showBidModal" :amount="amount" :highestBid="highestBid" :lowestBid="lowestBid" @close="closeBidModal" @confirm="confirmBid"></bid-modal>
                         
                            </div>
                            ----------------- [딜러] - 입찰 완료후 바텀 메뉴 -------------------->
                          <!--    <div v-if="isDealer && auctionDetail.data.status === 'ing' && (succesbid || succesbidhope || auctionDetail.data.bids.some(bid => bid.user_id === user.id))&& auctionDetail.data.hope_price !== null" @click.stop="">
                                <h5 class="mx-3 text-center">{{ minutesLeft }}</h5>
                                <p class="auction-deadline my-4">나의 입찰 금액 <span class="tc-red">{{ amtComma(myBidPrice) }}</span></p>
                                <h5 class="my-4">입찰 {{ auctionDetail.data.bids.length }}명/ 관심 0 명</h5>
                                  수수료 보증금이 부족할때 나오는 메뉴
                                    <div class="bottom-message">
                                        성사수수료 보즘금이 부족해요
                                    </div>-->
                                    <!--  <h5 class="text-center mt-5">희망가에 입찰 완료 되었습니다.</h5>
                                    <p class="text-center tc-red mb-2">※ 희망가에 입찰이 완료되었습니다. 수정이 불가능합니다.</p>
                                    <button
                                type="button"
                                class="my-3 w-100 btn"
                                :class="{'btn-outline-primary': auctionDetail.data.hope_price === null, 'primary-disable': auctionDetail.data.hope_price !== null}"
                                @click.prevent="openAlarmGuidModal">
                                입찰 취소
                              </button>
                            </div>
                         
                              <AlarmGuidModal ref="alarmGuidModal" />
                       
                      </div>
                  </bottom-sheet> -->
        </div>
          </div>
    
      <div class="container" v-if="isUser && auctionDetail.data.status === 'wait'">
          <div class="wd-100 bid-content p-4">
              <div class="d-flex justify-content-between">
                  <p class="bold-20-font">현재 6명이 입찰했어요.</p>
                  <p class="mt-1"><span class="cancelbox">경매취소</span></p>
              </div>
          </div>
          <div class="container p-3 mt-3">
              <h5>딜러 선택하기</h5>
              <p class="tc-light-gray">입찰 금액이 가장 높은 순으로 5명까지만 표시돼요.</p>
              <p class="tc-red text-start mt-2">※ 3일후 까지 선택된 딜러가 없을시, 경매가 취소 됩니다.</p>
          </div>
          <div class="bid-bc p-2">
              <ul  v-for="(bid, index) in sortedTopBids" :key="bid.user_id" class="px-0 inspector_list max_width_900">
                <li @click="handleClick(bid, $event, index+1)">
                      <div class="d-flex gap-4 align-items-center justify-content-between">
                          <div class="img_box">
                              <img src="../../../../img/myprofile_ex.png" alt="딜러 사진" class="mb-2 align-text-top">
                          </div>
                          <div class="txt_box me-auto">
                              <h5 class="name mb-1">{{ bid.dealerInfo ? bid.dealerInfo.name : 'Loading...'}}</h5>
                              <p class="txt">{{bid.price}} 만원</p>
                          </div>
                          <p class="restar mb-4 normal-16-font me-auto">4.5점</p>
                          <p class="btn-apply-ty03"></p>
                      </div>
                  </li>
              </ul>
          </div>
          <ConnectDealerModal v-if="connectDealerModal" :bid="selectedBid" :userData="userInfo" @close="handleModalClose" @confirm="handleDealerConfirm" />
          <BottomSheet02 v-if="!showReauctionView" initial="half" :dismissable="true">
            <button type="button" class="btn btn-dark" @click="toggleView">재경매</button>
        </BottomSheet02> 
      </div>
      <!-- 재경매 버튼 눌렀을 때 view -->
      <BottomSheet03 initial="half" :dismissable="true" v-if="showReauctionView" class="p-0 filter-content ">
        <div>
        <button type="button" class="mb-1 btn-close" @click="backView"></button>
              <h5 class="my-4">재경매할 금액을<br>입력해 주세요.</h5>

            <div>
                  <!--<ul class="machine-inform">
                      <li class="tc-light-gray">현재 시세</li>
                      <li class="sub-title">{{ carDetails.priceNow }}</li>
                  </ul>
                  <ul class="machine-inform">
                      <li class="tc-light-gray">입찰가</li>
                      <li class="sub-title">입찰가(데모)</li>
                  </ul>-->
                <div v-if="auctionDetail.data.hope_price != null" class="form-group dealer-check mt-0 mb-0">
                    <label for="sell">희망가 수정
                        <span class="tooltip-toggle normal-14-font" aria-label="희망가 판매시, 해당가격에서 입찰한 딜러에게 자동으로 낙찰됩니다." tabindex="0"></span>
                    </label>
                    <div class="check_box">
                        <input type="checkbox" id="sell" class="form-control" v-model="isSellChecked">
                        <label for="sell">희망가 판매</label>
                    </div>
                </div>

                <div v-else class="form-group dealer-check mt-0 mb-0">
                    <label for="sell">희망가로 판매할까요?
                        <span class="tooltip-toggle normal-14-font" aria-label="희망가 판매시, 해당가격에서 입찰한 딜러에게 자동으로 낙찰됩니다." tabindex="0"></span>
                    </label>
                    <div class="check_box">
                        <input type="checkbox" id="sell" class="form-control" v-model="isSellChecked">
                        <label for="sell">희망가 판매</label>
                    </div>
                </div>
            </div>
              <div class="input-container mt-4">
                  <input type="text" class="styled-input" placeholder="희망가 입력(선택)" v-model="amount" @input="updateKoreanAmount" :readonly="isReadonly">
              </div>
              <p class="d-flex justify-content-end tc-light-gray p-2">{{ koreanAmount }}</p>
              <div class="btn-group mt-3 mb-2">
                  <button type="button" class="btn btn-primary" @click="reauction">재경매</button>
                      <modal v-if="reauctionModal" :isVisible="reauctionModal" />
              </div>
          </div>
        </BottomSheet03> 
      </div>
</template>
<script setup>
import { ref, computed, onMounted, onUnmounted, watch, watchEffect, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { gsap } from 'gsap';
import useUsers from '@/composables/users';
import useRoles from '@/composables/roles';
import useAuctions from '@/composables/auctions';
import useBids from '@/composables/bids';
import modal from '@/views/modal/modal.vue';
import auctionModal from '@/views/modal/auction/auctionModal.vue';
import ConnectDealerModal from '@/views/modal/auction/connectDealer.vue';
import AlarmModal from '@/views/modal/AlarmModal.vue';
import AlarmGuidModal from '@/views/modal/AlarmGuidModal.vue';



import ClaimModal from '@/views/modal/ClaimModal.vue';
import bidModal from '@/views/modal/bid/bidModal.vue';
import { cmmn } from '@/hooks/cmmn';
import { initReviewSystem } from '@/composables/review';
import BottomSheet from '@/views/bottomsheet/BottomSheet.vue';
import BottomSheet02 from '@/views/bottomsheet/Bottomsheet-type02.vue';
import BottomSheet03 from '@/views/bottomsheet/Bottomsheet-type03.vue';

const { getUserReview , deleteReviewApi , reviewsData , formattedAmount } = initReviewSystem(); 


const isMobileView = ref(window.innerWidth <= 640);
const isClaimModalOpen = ref(false);
const lastBidId = ref(null);
const usersInfo = ref({});
const alarmModal = ref(null);
const alarmGuidModal = ref(null);
const isSellChecked = ref(false);
const { getUser } = useUsers();
const store = useStore();
const user = computed(() => store.getters['auth/user']);
const isDealer = computed(() => user.value?.roles?.includes('dealer'));
const isUser = computed(() => user.value?.roles?.includes('user'));
const selectedBid = ref(null);
const route = useRoute();
const router = useRouter();
const userInfo = ref(null);
const succesbid = ref(false);
const succesbidhope = ref(false);
const amount = ref('');
const koreanAmount = ref('원');
const { numberToKoreanUnit , amtComma } = cmmn();
const myBidPrice = computed(() => {
  const myBid = auctionDetail.value?.data?.bids?.find(bid => bid.user_id === user.value.id);
  return myBid ? myBid.price : '0';
});
let pollingInterval = null;
const updateKoreanAmount = () => {
  console.log("Updating Korean amount"); // Check if this logs in the console
  koreanAmount.value = amtComma(amount.value);
};
const openClaimModal = () => {
  isClaimModalOpen.value = true;
};
const closeClaimModal = () => {
  isClaimModalOpen.value = false;
};

const checkScreenWidth = () => {
    if (typeof window !== 'undefined') {
      isMobileView.value = window.innerWidth <= 640;
    }
  };

const dynamicClass = computed(() => {
  if (auctionDetail.value?.data?.status === 'ask' || auctionDetail.value?.data?.status === 'diag') {
    return 'diag-img';
  } else {
    return 'card-img-top-ty02';
  }
});

// 사용자 입찰이 취소된 적이 있는지 확인
const userBid = computed(() => auctionDetail.value?.data?.bids?.find(bid => bid.user_id === user.value.id));
const userBidExists = computed(() => userBid.value && !userBid.value.deleted_at);
const userBidCancelled = computed(() => auctionDetail.value?.data?.bids?.some(bid => bid.user_id === user.value.id && bid.deleted_at));

const auctionId = computed(() => auctionDetail.value?.data?.id);
const cancelAttempted = computed({
  get() {
    return store.getters['cancelAttempted/getCancelAttempted'](auctionId.value);
  },
  set(value) {
    store.dispatch('cancelAttempted/setCancelAttempted', { auctionId: auctionId.value, value });
  }
});

const showBidModal = ref(false);
const auctionModalVisible = ref(false);
const reauctionModal = ref(false);
const connectDealerModal = ref(false);

const scrollButtonVisible = ref(false);
const selectedDealer = ref(null);
const showBottomSheet = ref(true);
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' });
const scrollButtonStyle = ref({ display: 'none' });

const showReauctionView = ref(false);

const auctionDetail = ref(null);
const { AuctionCarInfo, getAuctions, auctionsData, AuctionReauction, chosenDealer, getAuctionById, updateAuctionStatus } = useAuctions();
const { submitBid, cancelBid,getBidById } = useBids();
const carDetails = ref({});
const highestBid = ref(0);
const lowestBid = ref(0);

const sortedTopBids = computed(() => {
  if (!auctionDetail.value?.data?.top_bids) {
    return [];
  }

  const bidsByUser = auctionDetail.value.data.top_bids.reduce((acc, bid) => {
    if (!acc[bid.user_id] || acc[bid.user_id].price < bid.price) {
      acc[bid.user_id] = bid;
    }
    return acc;
  }, {});

  const topBids = Object.values(bidsByUser)
    .sort((a, b) => b.price - a.price)
    .slice(0, 5);

  return topBids;
});

const heightPrice = ref(0);
// 숫자 애니메이션 함수
const animateHeightPrice = (newPrice) => {
  const heightPriceElement = document.querySelector('.icon-coins');
  const startValue = parseInt(heightPriceElement.innerText.replace(/[^0-9]/g, ''), 10);
  const endValue = newPrice;

  gsap.fromTo(
    { value: startValue },
    { value: endValue },
    {
      duration: 1.5,
      ease: 'Power1.easeOut',
      snap: { value: 1 },
      onUpdate: function () {
        heightPriceElement.innerText = amtComma(Math.round(this.targets()[0].value));
      }
    }
  );
};


// auctionDetail의 변경사항을 감지하여 amount 값을 업데이트합니다.
watch(
  () => auctionDetail.value?.data?.hope_price,
  (newVal, oldVal) => {
    console.log('auctionDetail.data.hope_price changed from', oldVal, 'to', newVal);
    if (newVal !== null) {
      amount.value = newVal;
    }
  },
  { immediate: true }
);

watch(
  () => auctionDetail.value?.data?.bids,
  (bids) => {
    if (bids && bids.length > 0) {
      heightPrice.value = Math.max(...bids.map(bid => bid.price), 0);
      console.log("Height price updated:", heightPrice.value);
    }
  },
  { immediate: true } // 이 옵션을 통해 컴포넌트가 마운트될 때 즉시 실행됩니다.
);

const openAlarmModal = () => {
  console.log("openAlarmModal called");
  if (alarmModal.value) {
    alarmModal.value.openModal();
  }
};

const openAlarmGuidModal = () => {
  console.log("openAlarmGuidModal called");
  if (alarmGuidModal.value) {
    alarmGuidModal.value.openModal();
  }
};


/*[사용자] 재경매 - 현재 날짜에서 D-3 일 후 라고 가정함.*/ 
function getThreeDaysFromNow() {
  const currentDate = new Date();
  currentDate.setDate(currentDate.getDate() + 3);
  return currentDate.toISOString().slice(0, 19).replace('T', ' ');
}


const isModalVisible = ref(false);
const selectedAuctionId = ref(null);


/*[사용자] 재경매 - 버튼 눌렀을떄 처리되는 곳*/ 
const reauction = async () => {
  const id = route.params.id;
  let data = {
  status: 'ing',
  final_at: getThreeDaysFromNow(),
};

  if (isSellChecked.value) {
    data.hope_price = amount.value;
  }

  try {
    await AuctionReauction(id, data);
    reauctionModal.value = true;
  } catch (error) {
    console.error('Error re-auctioning:', error);
    alert('재경매에 실패했습니다.');
  }
};


const openModal = () => {
  isModalVisible.value = true;
  selectedAuctionId.value = auctionDetail.value?.data.id;
};

const closeModal = () => {
  isModalVisible.value = false;
};

const handleConfirmDelete = async () => {
  closeModal();
  try {
    await updateAuctionStatus(selectedAuctionId.value, 'cancel');
  } catch (error) {
    console.error(error);
  }
};

const toggleView = () => {
  showReauctionView.value = true;
  console.log(showReauctionView.value);
};
const backView = () => {
  showReauctionView.value = false;
  console.log(showReauctionView.value);
};


// 사용자 정보를 가져오는 함수
const getDealer = async (user_Id) => {
  if (!user_Id) {
    console.error('user_id is undefined:', user_Id);
    return { name: 'Unknown' };
  }

  try {
    const userData = await getUser(user_Id);
    console.log(`User Data for user_id ${user_Id}:`, userData);
    return userData;
  } catch (error) {
    console.error(`Error fetching data for user_id ${user_Id}:`, error);
    return { name: 'Unknown' };
  }
};
const submitHopePrice = () => {
  console.log("입력된 희망가:", hopePrice.value);
};

// auctionDetail이 변경될 때마다 각 bid에 userData를 추가하는 함수
watchEffect(async () => {
  if (auctionDetail.value && auctionDetail.value.data.hope_price !== null) {
    amount.value = auctionDetail.value.data.hope_price;
  }
  if (isUser.value) {
    if (auctionDetail.value && auctionDetail.value.data && auctionDetail.value.data.top_bids) {
      const bids = auctionDetail.value.data.top_bids;
      for (const bid of bids) {
        if (bid.user_id) {
          const userData = await getDealer(bid.user_id);
          bid.dealerInfo = userData.dealer;
        }
      }
      sortedTopBids.value = bids;
    }
  }
});

const computedAmount = computed(() => {
  if (userBidExists.value && !userBidCancelled.value && auctionDetail.value.data.status === 'ing' && auctionDetail.value.data.hope_price !== null) {
    return auctionDetail.value.data.hope_price;
  } else {
    return amount.value; 
  }
});

watch(amount, (newValue) => {
  koreanAmount.value = amtComma(newValue);
});

watch(computedAmount, (newValue) => {
  amount.value = newValue;
}, { immediate: true });

const selectDealer = async (bid, index) => {
  selectedBid.value = { ...bid, index };
  connectDealerModal.value = true;

  try {
    const userData = await getUser(bid.user_id);
    userInfo.value = userData;
  } catch (error) {
    console.error('Error dealer data:', error);
  }
};
const handleClick = async (bid, event, index) => {
  // Ensure we are only acting on clicks to the li itself and not the children
  if (event.currentTarget === event.target || event.currentTarget.contains(event.target)) {
    await selectDealer(bid, index);
  }
};

const handleModalClose = () => {
  connectDealerModal.value = false;
  if (selectedBid.value) {
    const checkbox = document.getElementById('checkbox-' + selectedBid.value.user_id);
    if (checkbox) checkbox.checked = false;
    selectedBid.value = null;
  }
};

const handleDealerConfirm = ({ bid, userData }) => {
  selectedDealer.value = { ...bid, userData };
  connectDealerModal.value = false;
  completeAuction();
};

const cancelSelection = () => {
  selectedDealer.value = null;
};
const formatDateToString = (date) => {
  const pad = (num) => num.toString().padStart(2, '0');

  const year = date.getFullYear();
  const month = pad(date.getMonth() + 1); // months are zero-indexed
  const day = pad(date.getDate());
  const hours = pad(date.getHours());
  const minutes = pad(date.getMinutes());
  const seconds = pad(date.getSeconds());

  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

const completeAuctionModal = ref(false); // 추가된 모달 상태
const completeAuction = async () => {
  auctionDetail.value.data.status = 'chosen';
  const id = route.params.id;
  const currentDate = new Date();
  const formattedDate = formatDateToString(currentDate);

  const data = {
    status: 'chosen',
   // choice_at: formattedDate,
    final_price: selectedDealer.value.price,
    bid_id: selectedDealer.value.user_id,
  };

  try {
    await chosenDealer(id, data);
    auctionDetail.value.data.status = 'chosen';
    completeAuctionModal.value = true; // 경매 완료 모달 표시
  } catch (error) {
    console.error('Error completing auction:', error);
    alert('경매에 실패했습니다.');
  }
};

const closeCompleteAuctionModal = () => {
  completeAuctionModal.value = false; // 모달 닫기
};

const checkScroll = () => {
  const scrollY = window.scrollY;
  const windowHeight = document.documentElement.clientHeight;
  const totalHeight = document.documentElement.scrollHeight;

  if (scrollY + windowHeight >= totalHeight) {
    scrollButtonVisible.value = false;
  } else {
    scrollButtonVisible.value = true;
  }
};

async function fetchUserNames() {
  for (const bid of auctionDetail.value?.data?.bids || []) {
    if (!usersInfo.value[bid.user_id]) {
      const userData = await getUser(bid.user_id);
      usersInfo.value[bid.user_id] = userData.name;
    }
  }
}

const openBidModal = () => {
  showBidModal.value = true;
};

const closeBidModal = () => {
  showBidModal.value = false;
};

const submitAuctionBid = async () => {
const userBidExists = auctionDetail.value?.data?.bids?.some(bid => bid.user_id === user.value.id && !bid.deleted_at);
if (!amount.value || isNaN(parseFloat(amount.value))) {
  alert('유효한 금액을 입력해주세요.');
} else {
    openBidModal();
};
}




const handleImmediateAuctionEnd = async (userId, price) => {
  const id = route.params.id;
  const data = {
    status: 'chosen',
    final_price: price,
    bid_id: userId,
    //chosen_at: new Date().toISOString(),
  };

  try {
    const response = await axios.post(`api/auctions/${id}`, data); // 사용자 API로 요청
    auctionDetail.value.data.status = 'chosen';
    auctionDetail.value.data.final_price = price;
    auctionDetail.value.data.bid_id = userId;
   /* auctionDetail.value.data.chosen_at = data.chosen_at;*/
    completeAuctionModal.value = true; // 경매 완료 모달 표시
  } catch (error) {
    console.error('Error completing auction:', error);
    alert('경매에 실패했습니다.');
  }
};


const confirmBid = async () => {
  try {
    const bidResult = await submitBid(auctionDetail.value.data.id, amount.value, user.value.id);
    if (bidResult.success) {
      lastBidId.value = bidResult.bidId;
      await fetchAuctionDetail();
      closeBidModal();
      succesbid.value = true;
     /* if (auctionDetail.value.data.hope_price !== null && amount.value == auctionDetail.value.data.hope_price) {
        await handleImmediateAuctionEnd(user.value.id, amount.value);
      }*/
    } else {
      alert(bidResult.message);
    }
  } catch (error) {
    console.error('Error confirming bid:', error);
  }
};

const errorMessage = ref('');

const fetchAuctionDetail = async () => {
  const auctionId = parseInt(route.params.id);
  try {
    auctionDetail.value = await getAuctionById(auctionId);
    const { car_no, owner_name } = auctionDetail.value.data;
    const carInfoForm = {
      owner: owner_name,
      no: car_no,
      forceRefresh: ""
    };
    const carInfoResponse = await AuctionCarInfo(carInfoForm);
    const carData = carInfoResponse.data;
    carDetails.value.no = carData.no;
    carDetails.value.model = carData.model;
    carDetails.value.modelSub = carData.modelSub;
    carDetails.value.grade = carData.grade;
    carDetails.value.gradeSub = carData.gradeSub;
    carDetails.value.year = carData.year;
    carDetails.value.fuel = carData.fuel;
    carDetails.value.mission = carData.mission;
    if (isUser.value && auctionDetail.value.data.status !== 'done') { 
      // top_bids를 통해 각 bid의 정보를 가져옵니다.
      if (auctionDetail.value.data.top_bids && auctionDetail.value.data.top_bids.length > 0) {
        await fetchBidsInfo(auctionDetail.value.data.top_bids);
      }
    }
  } catch (error) {
    console.error('Error fetching auction detail:', error);
  }
};

const fetchBidsInfo = async (topBids) => {
  try {
    const bidsInfo = await Promise.all(topBids.map(bid => getBidById(bid.id)));
    sortedTopBids.value = bidsInfo;

    if (auctionDetail.value.data.status !== 'chosen') {
    
      const newHeightPrice = Math.max(...bidsInfo.map(bid => bid.price));
      if (newHeightPrice !== heightPrice.value) {
         heightPrice.value = newHeightPrice;
         console.log("?",auctionDetail.value.data.status);
         console.log(auctionDetail.value.data.hope_price);
         if (auctionDetail.value.data.status === 'ing' && auctionDetail.value.data.hope_price === null) {
          animateHeightPrice(newHeightPrice);
        }else if(auctionDetail.value.data.hope_price !== 'null'){
          animateHeightPrice(auctionDetail.value.data.hope_price);
        }
      }
      console.log("Height price updated:", heightPrice.value);
    }
  } catch (error) {
    console.error('Error fetching bid info:', error);
  }
};


// 일정 간격으로 데이터를 갱신하는 함수
const startPolling = () => {
  pollingInterval = setInterval(fetchAuctionDetail, 60000);
};
let timer;
const currentTime = ref(new Date());
onMounted(async () => {
  timer = setInterval(() => {
    currentTime.value = new Date();
  }, 1000);

  const screenWidth = window.innerWidth;
  bottomSheetStyle.value = {
    position: screenWidth >= 1200 ? 'static' : 'fixed',
    bottom: '0px'
  };
  console.log("Component mounted, fetching auctions");
  await getAuctions();
  await fetchAuctionDetail(); // Ensure this is awaited to get the latest auction detail before checking the condition

  if(auctionDetail.value?.data?.status === 'ing' && isUser.value){
    startPolling();
  }

  window.addEventListener('scroll', checkScroll);

  try {
    console.log('Sorted Top Bids:', sortedTopBids.value);
  } catch (error) {
    console.error('Error fetching auction detail:', error);
  }
  window.addEventListener('resize', checkScreenWidth);
    checkScreenWidth();
});

onUnmounted(() => {
  clearInterval(timer);
  window.removeEventListener('scroll', checkScroll);
  if(isUser.value){
    clearInterval(pollingInterval);
  }
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkScreenWidth);
}); 
const populateHopePrice = () => {
  if (auctionDetail.value && auctionDetail.value.data) {
    amount.value = '';
  }
};

const sortedBids = computed(() => {
  const bids = auctionDetail.value?.data?.bids?.slice().sort((a, b) => b.price - a.price) || [];
  if (bids.length > 0) {
    highestBid.value = bids[0].price;
    lowestBid.value = bids[bids.length - 1].price;
  }
  return bids;
});


const finalAt = () => {
  if (auctionDetail.value?.data?.status === 'ing') {
    const finalAtValue = auctionDetail.value.data.final_at;

    //const finalAtDate = new Date(finalAtValue.replace(' ', 'T') + 'Z'); 
    const finalAtDate = new Date(finalAtValue);
    //console.log('DB에서 받은 finalAtValue:', finalAtValue);
    //console.log('Parsed finalAtDate:', finalAtDate.toString());
    return finalAtDate;
  }
}; // 마감 시간을 Date 객체로 변환
const padZero = (num) => {
  return num < 10 ? '0' + num : num; // 숫자가 10보다 작을 경우 앞에 '0' 추가
};
const timeLeft = computed(() => {
  if (auctionDetail.value?.data?.status === 'ing') {
    const diff = finalAt().getTime() - currentTime.value.getTime(); // 마감 시간과 현재 시간의 차이 계산
    const days = Math.floor(diff / (24 * 3600000)); // 밀리초를 일로 변환
    const hours = padZero(Math.floor((diff % (24 * 3600000)) / 3600000)); // 남은 밀리초를 시간으로 변환하고 두 자리로 포맷팅
    const minutes = padZero(Math.floor((diff % 3600000) / 60000)); // 남은 밀리초를 분으로 변환하고 두 자리로 포맷팅
    const seconds = padZero(Math.floor((diff % 60000) / 1000)); // 남은 밀리초를 초로 변환하고 두 자리로 포맷팅

    return { days, hours, minutes, seconds }; // 객체 형태로 일, 시, 분, 초 반환
  } else {

    return { days: '00', hours: '00', minutes: '00', seconds: '00' }; // 기본값 반환
  }
});

const handleCancelBid = async () => {
  try {
    const myBid = auctionDetail.value?.data?.bids?.find(bid => bid.user_id === user.value.id && !bid.deleted_at);
    if (myBid) {
      const result = await cancelBid(myBid.id);
      if (result.success) {
        myBid.deleted_at = new Date().toISOString();
        await fetchAuctionDetail();
        amount.value = '';
        succesbid.value = false;
        koreanAmount.value = '원';
      } else {
        alert(result.message);
      }
    } else {
      alert('입찰 내역이 없습니다.');
    }
  } catch (error) {
    console.error('Error canceling bid:', error);
    alert('입찰 취소에 실패했습니다.');
  }
};

</script>



<style scoped>

@media (min-width: 992px) {
  .mov-wide {
    width: 80vh;
  }
}
.w-30{
  width: 30% !important; 
}
.animCircle::after {
  border-radius: 50%;
}
  .dealer-check {
      margin-top: 50px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #F5F5F6;
      border-radius: 30px;
      padding: 10px;
  }

  .no-resize {
      resize: none;
  }

  .dealer-check input[type=checkbox] {
      margin-right: 10px;
  }

  .dealer-check label {
      display: flex;
      align-items: center;
      font-size: 14px;
      color: #333;
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

input[type="checkbox"] {
  align-self: center; 
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
.blinking {
animation: blink 1.5s linear infinite;
}

@keyframes blink {
50% {
  opacity: 0.5;
}
}

.styled-input {
  border: none;
  outline: none;
  flex-grow: 1;
  font-size: 16px;
  padding-left: 30px; 
  color: #333;
  background-color: transparent;
  width: 100%;
  direction: rtl; 
  background-image: url('../../../../img/icon-won.png'); 
  background-repeat: no-repeat;
  background-size: 20px 20px;
  background-position: left 0px center; 
}

.styled-input::placeholder {
  color: #CCC;
  direction: ltr; 
}
  .more-page {
      color: white;
      font-size: 16px;
      
  }
  .more-img{
      color: white;
      border: none;
      margin-left: 10px;
      text-decoration: none;
      border-radius: 3px;
      cursor: pointer;
  }
@media screen and (min-width:1200px) {
  .bottom-sheet {
      width:50%;
  }
}
@media screen and (min-width:575px) {

}
.label-style{
  top: 22px !important;
  width: 100px;
}
.label-style02{
  top: 20px !important;
  width: 100px;
}
.fade-enter-active, .fade-leave-active {
transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to{
opacity: 0;
}
</style>
