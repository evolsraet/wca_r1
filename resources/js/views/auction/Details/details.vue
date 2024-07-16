<template>
  <div class="container-fluid" v-if="auctionDetail">
    <div v-if="!auctionChosn && !showReauctionView &&(auctionDetail.data.status === 'ing' && auctionDetail.data.bids_count !== 0 && isUser)"></div>
    <div v-else-if="isDealer || !auctionChosn && !showReauctionView && (auctionDetail.data.status !== 'wait' && isUser) " class="container">
      <div class="web-content-style02">
        <div class="container p-1">
          <div>
            <div>
              <div class="mb-4">
                <div class="card my-auction">
                  <div>
                    <div class="mb-3 px-0" v-if="auctionDetail.data.status === 'ask' || auctionDetail.data.status === 'diag'">
                      <div class="diag-img">
                        <p class="diag-text tc-light-gray mb-4">{{ wicaLabel.title() }}이 꼼꼼하게 진단 중이에요</p>
                        <span v-if="auctionDetail.data.status === 'diag'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                        <span v-if="auctionDetail.data.status === 'ask'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                      </div>
                    </div>
                    <div v-else>
                      <span v-if="auctionDetail.data.status === 'ing'" class="mx-2 timer">
                        <img src="../../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">
                        <span v-if="timeLeft.days != '0'">{{ timeLeft.days }}일 &nbsp;</span>{{ timeLeft.hours }} : {{ timeLeft.minutes }} : {{ timeLeft.seconds }}
                      </span>
                      <span v-if="auctionDetail.data.status === 'dlvr'" class="mx-2 auction-done bg-info">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                      <span v-if="auctionDetail.data.status === 'done'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                      <span v-if="auctionDetail.data.status === 'cancel'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                      <span v-if="auctionDetail.data.status === 'chosen'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                      <div v-if="auctionDetail.data.status !== 'cancel' & !isUser">
                        <input class="toggle-heart" type="checkbox" :id="'favorite-' + auctionDetail.data.id"
                        :checked="auctionDetail.data.isFavorited" @click.stop="toggleFavorite(auctionDetail.data)"/>
                        <label class="heart-toggle" :for="'favorite-' + auctionDetail.data.id" @click.stop></label>
                      </div>
                      <div class="gap-1" :class="[{ 'grayscale_img': auctionDetail.data.status === 'done' || auctionDetail.data.status === 'cancel' }]">
                      <div v-if="!isMobileView" class="d-flex flex-row gap-1">
                        <div class="w-50">
                          <div class="card-img-top-ty02" :style="borderStyle"></div>
                        </div>
                        <div class="w-50 d-flex flex-column gap-1">
                          <div class="card-img-top-ty02 h-50 left-image background-auto" :style="borderStyle"></div>
                          <div class="card-img-top-ty02 h-50 right-image background-auto" :style="borderStyle"></div>
                        </div>
                      </div>
                      <div v-if="isMobileView">
                        <div class="card-img-top-ty02" :style="borderStyle"></div>
                      </div>
                    </div>

                      <h4 v-if="auctionDetail.data.status === 'done' || auctionDetail.data.status === 'chosen'" class="wait-selection">낙찰가 {{ amtComma(auctionDetail.data.final_price) }}</h4>
                      <div class="mt-2 pb-1 d-flex gap-3 justify-content-between me-1">
                        <div></div>
                        <div class="d-flex gap-3 justify-content-end align-items-center mb-1">
                          <div class="tc-light-gray icon-hit">조회수 {{ auctionDetail.data.hit }}</div>
                          <div class="tc-light-gray ml-2 icon-heart">관심 {{ auctionDetail.data.likes ? auctionDetail.data.likes.length : 0 }}</div>
                          <p class="tc-light-gray icon-bid">입찰 {{ auctionDetail.data.bids_count }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-3 pt-0 ">
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
                   <div v-if="auctionDetail.data.status ==='chosen' || auctionDetail.data.status ==='dlvr'">
                     <!-- <hr>
                      <h4>탁송 신청 정보</h4>
                      <div class="fw-medium ">
                      <p class="mt-4 tc-light-gray ">낙찰 딜러 :<span class="tc-red">&nbsp; 홍길동 딜러</span></p>
                      <p class="tc-light-gray">낙&nbsp;&nbsp;  찰&nbsp;&nbsp;  액 : <span class="tc-red">&nbsp;3500만원</span></p>
                      <p class="tc-light-gray">탁&nbsp;&nbsp; 송&nbsp;&nbsp; 일 : <span class="tc-red">&nbsp;2024년 6월 26일 오후 6:12</span></p>
                      </div>-->
                    </div>
                    <div v-if="showNotification" class="container">
                      <div class="notification-container show container px-3">
                        <router-link :to="{ name: 'index.claim' }" class="btn wait-selection shadow-sm d-flex align-items-center justify-content-between gap-5">
                          클레임 신청<p class="icon-right-wh"></p>
                        </router-link>
                      </div>
                    </div>
                    <div v-if="auctionDetail.data.status !== 'diag' || auctionDetail.data.status !== 'ask'">
                      <p class="ac-evaluation mt-4 btn-fileupload-red" @click.prevent="openAlarmModal">위카 진단평가 확인하기</p>
                      <div class="mt-5" v-if="showPdf">
                      <h5>진단 평가</h5>
                      <div id="diagnostic-evaluation-modal" style="padding-top: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                          <iframe
                              src="https://diag.wecarmobility.co.kr/uploads/result/WI-23-000001_92.pdf"
                              width="100%"
                              height="600px"
                              
                          ></iframe>
                      </div>
                  </div>
              </div>
            </div>
                  <!--   <template v-if="auctionDetail.data.hope_price !== null">
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
                    </template>-->
                    
                    <!--TODO: bid가 1명이라도 있을때 알림뜨기-->

                <!-- <div v-if="isDealer && auctionDetail.data.status === 'ing'" class="p-3">
                    <div v-if="auctionDetail.data.hope_price !== null">
                      <div class="bold-18-font modal-bid d-flex p-3 justify-content-between blinking">
                        <p>현재 희망가</p>
                        <p class="icon-coins">{{ amtComma(auctionDetail.data.hope_price) }}</p>
                      </div>
                    </div>
                  </div>-->
                </div>
              </div>
            </div>
          </div>
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
              <textarea class="form-control text-box process" readonly style="resize: none;">{{ auctionDetail.data.memo }}</textarea>
            </div>
            <h5 class="mt-5">평가자 의견</h5>
            <div class="form-group">
              <textarea class="form-control text-box process" readonly style="resize: none;">{{ auctionDetail.data.memo_digician }}</textarea>
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
        <!--
          사용자 바텀시트
        -->
        <div v-if="(isUser && auctionDetail.data.status === 'ask') || (isUser && auctionDetail.data.status === 'diag')" class="sheet-content">
            <BottomSheet02 class="text-center">
              <div v-if="auctionDetail.data.status === 'ask'">
              <p class="auction-deadline align-items-center my-4 p-4 ">
                <span class="text-center fw-semibold">매물 신청 완료</span>
              </p>
              <p class="tc-light-gray fw-semibold">해당 매물 신청이 완료 되었습니다. <br><span class="fw-light fs-6">※ 경매진행까지 약간의 검토 시간이 소요됩니다. </span></p>
              </div>
              <div v-if="auctionDetail.data.status === 'diag'">
              <p class="auction-deadline align-items-center my-4 p-4 ">
                <span class="text-center fw-semibold">진단 대기 중</span>
              </p>
              <p class="tc-light-gray fw-semibold">※ 진단이 완료되는 즉시 경매진행이 시작됩니다 ※ <br><span>잠시만 기다려주세요.</span></p>
              </div>
            </BottomSheet02>
          </div>
        <div v-if="isUser && auctionDetail.data.status === 'done'" class="sheet-content">
            <BottomSheet02>
              <h5 class="text-center p-2">거래는 어떠셨나요?</h5>
              <div v-if="reviewIsOk">
                <router-link :to="{ name: 'user.create-review' }" type="button" class="tc-wh btn btn-primary w-100">
                  후기 남기기
              </router-link>
              </div>
              <div v-else>
                <button type="button" class="tc-wh btn btn-primary w-100 disabled">
                  이 거래는 이미 후기를 작성하셨습니다.
              </button>
              </div>
            </BottomSheet02>
          </div>
          <div v-if="isUser && auctionDetail.data.status === 'cancel'" class="sheet-content">
            <BottomSheet02>
              <p class="auction-deadline align-items-center my-4 p-4 ">
                <span class="text-center tc-light-gray fw-semibold">경매 취소</span>
              </p>
              <p class="tc-light-gray fw-semibold">해당 매물의 경매가 취소 되었습니다.</p>
            </BottomSheet02>
          </div>
          <div v-if="isUser && auctionDetail.data.status === 'ing'" class="sheet-content">
            <BottomSheet02 v-if="auctionDetail.data.bids_count === 0">
              <h4 class="text-start my-2">경매 진행중</h4>
              <P class="text-start tc-light-gray">※ 입찰한 딜러가 있으면 즉시 선택이 가능합니다.</P>
              <button  class="bg-sub-color01 bold-18-font modal-bid d-flex mt-3 p-3 justify-content-center blinking">
                  <p class="text-center">경매 진행중 입니다.</p>
              </button>
            </BottomSheet02>
          <!-- <BottomSheet02 v-else>
              <h4 class="text-start my-2">경매 진행중</h4>
              <P class="text-start tc-light-gray">※ 입찰한 딜러가 있으면 즉시 선택이 가능합니다.</P>
              <button class="bg-sub-color bold-18-font modal-bid d-flex p-3 mt-3 justify-content-between blinking" @click="auctionIngChosen">
                  <p>딜러 선택이 가능해요!</p>
                  <p class="d-flex align-items-center gap-2">바로가기<p class="icon-right-wh"></p></p>
              </button>
          </BottomSheet02> -->
        </div>

          <!--
            딜러 : 바텀 시트 
          -->
          <div v-if="auctionDetail.data.status !== 'done' && auctionDetail.data.status !== 'dlvr' && auctionDetail.data.status !== 'chosen'  &&  isDealer" class="sheet-content">
            <BottomSheet02 initial="half" :dismissable="true" v-if="!succesbid && !auctionDetail.data.bids.some(bid => bid.user_id === user.id) && auctionDetail && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price == null && !bidSession">
                <div  @click.stop="">
                  <p class="text-center tc-red my-2">현재  {{ auctionDetail.data.bids_count }}명이 입찰했어요.</p>
                  <button type="button" class="btn btn-primary w-100 align-items-center d-flex justify-content-center gap-3" @click="showbidView">입찰하기<p class="icon-up-wh"></p></button>
                </div>
              </BottomSheet02>

              <BottomSheet02  v-if="auctionDetail.data.status == 'wait' && isDealer">
                <div class="steps-container mb-3">
                  <div class="step completed">
                    <div class="label completed">STEP01</div>
                    <div class="label label-style tc-light-gray">입찰 중</div>
                  </div>
                  <div class="line completed"></div>
                  <div class="step completing">
                    <div class="label completing">STEP02</div>
                    <div class="label label-style tc-light-gray completing-text">딜러 선택</div>
                  </div>
                  <div class="line"></div>
                  <div class="step">
                    <div class="label">STEP03</div>
                    <div class="label label-style02 tc-light-gray">완료</div>
                  </div>
                </div>
                <p class="auction-deadline text-center mt-2">경매 완료 후 딜러선택 중 입니다.</p>
              </BottomSheet02>
              <BottomSheet02 v-if="auctionDetail.data.status == 'cancel'" >
                <h5 class="text-start">입찰이 취소되었습니다</h5>
                <p class="auction-deadline align-items-center my-4 p-4 justify-content-between">
                  <span class="tc-light-gray">나의 입찰 금액</span>
                  <span class="bold-20-font">{{ amtComma(myBidPrice) }}</span>
                </p>
              </BottomSheet02>
            <BottomSheet02 initial="half" class="p-2 pt-0" v-if="userBidExists && !userBidCancelled && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price == null">
              <div class="d-flex justify-content-between align-items-baseline">
                <h5>나의 입찰 금액</h5>
                <div class="mt-3 d-flex align-items-center justify-content-end gap-3">
                  <p class="tc-light-gray icon-bid">입찰  {{ auctionDetail.data.bids_count }}</p>
                  <div class="tc-light-gray ml-2 icon-heart">관심 {{ auctionDetail.data.likes ? auctionDetail.data.likes.length : 0 }}</div>
                </div>
              </div>
              <div v-if="auctionDetail.data.status === 'ing' && (succesbid || auctionDetail.data.bids.some(bid => bid.user_id === user.id)) && auctionDetail.data.hope_price == null" @click.stop="">
                <p class="auction-deadline align-items-center my-4 p-4 justify-content-between">
                  <span class="bold-20-font">{{ amtComma(myBidPrice) }}</span>
                </p>
                <p class="tc-light-gray text-center">앞으로 3회 더 취소할 수 있어요</p>
                <button type="button" class="my-3 w-100 btn shadow-sm border" @click="handleCancelBid">
                  입찰 취소하기
                </button>
                <div class="bottom-message">성사수수료 보증금이 부족해요</div>
              </div>
              
              <div v-else>
                <h5 class="text-center mt-4">입찰이 완료되었습니다.</h5>
                <p class="text-center tc-red">※ 최초 1회 수정이 가능합니다</p>
              </div>
            </BottomSheet02>
          </div>
          <BottomSheet02 v-if="auctionDetail.data.status == 'dlvr' || auctionDetail.data.status == 'chosen'">
             <div class="d-flex justify-content-between align-items-baseline">
              <h4>탁송 신청 정보</h4>
            </div>
            <div class="text-start mt-2">
              <p class="tc-light-gray">낙찰 딜러 :<span class="tc-red">&nbsp; 홍길동 딜러</span></p>
              <p class="tc-light-gray">낙&nbsp;&nbsp;  찰&nbsp;&nbsp;  액 : <span class="tc-red ms-1">3500 만원</span></p>
              <p class="tc-light-gray">입금&nbsp;&nbsp;은행 :<span class="tc-red ms-1">(농협은행) 0000-0088-0024</span></p>
              <p class="tc-light-gray">탁&nbsp;&nbsp; 송&nbsp;&nbsp; 일 : <span class="tc-red ms-1">2024년 6월 26일 오후 6:12</span></p>
            </div>
            <div>
              <button class="border-6 btn-fileupload my-4 shadow02">매도용 인감증명서 다운로드</button>
            </div>
            <div v-if ="auctionDetail.data.status ==='chosen' && isUser">
            <hr>
            <h4>탁송 확인</h4>
            <p class="text-start tc-light-gray">※ 탁송 서비스 안내는 ' 탁송 확인 '에서 확인 가능합니다. </p>
            <button
              class="my-4 btn-primary bold-18-font modal-bid d-flex p-3 justify-content-between blinking"
              @click="competionsuccess"
            >
              <p>탁송 확인</p>
              <p class="d-flex align-items-center gap-2">
                바로가기
                <p class="icon-right-wh"></p>
              </p>
            </button>
            </div>
              <div v-if ="auctionDetail.data.status ==='chosen' && isDealer">
                <hr>
              <h4 class="mt-2">탁송 주소지</h4>
              <p class="text-start tc-light-gray">※ 현 주소지로 탁송이 진행 됩니다. </p>
              <div class="d-flex justify-content-end">
                <button class=" btn-outline-primary btn sm-height" @click="dealerAddrConnect">주소지 변경</button>
              </div>
              <div class="fw-semibold">
                <p>우편번호 :<span class="tc-red ms-1">{{ selectedAuction ? selectedAuction.zipCode : user.dealer.company_post }}</span></p>
                <p>주<span class="ms-4">소</span> :<span class="tc-red ms-2">{{ selectedAuction ? selectedAuction.address : user.dealer.company_addr1 }}</span></p>
              </div>
              <button
                class="my-4 btn-primary btn w-100"
                @click="dealerAddrCompetion"
              >
                <p>현 주소지 탁송하기</p>
              </button>
            </div>
            <div v-if="showModal" class="modal-overlay p-3">
            <div class="modal-container">
              <div class="card-body">
                <div class="text-start">
                  <h4>탁송지 변경</h4>
                  <p>원하시는 탁송지를 선택해주세요.</p>
                  <a href="/addr" class="fs-6 tc-light-gray link-hov">다른 주소지로 변경, 추가를 원하시나요?</a>
                </div>
                <div class="scrollable-content mt-4" ref="scrollableContent"></div>
              </div>
              <button @click="confirmSelection" class="btn btn-primary w-100">확인</button>
            </div>
            </div>
          </BottomSheet02>
          
      </div>
    </div>

    

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
                          </div>-->

          
                     <!--  바텀 시트 show or black-->
                     <button class="animCircle scroll-button floating" :style="scrollButtonStyle" v-show="scrollButtonVisible"></button>
                        <div v-if="isDealer">
                            <div v-if="auctionDetail.data.status === 'wait'" @click.stop="">
                            
                            </div>

                  <!--       <div v-if="!succesbidhope && !auctionDetail.data.bids.some(bid => bid.user_id === user.id) && auctionDetail && !userBidCancelled && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price !== null" @click.stop="">
                            <BottomSheet02>
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
                            </BottomSheet02>
                          </div>-->

                      
                          <BottomSheet03 initial="half" :dismissable="true"  v-if="!succesbid && !auctionDetail.data.bids.some(bid => bid.user_id === user.id) && auctionDetail && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price == null && bidSession">
                            <div @click.stop="">
                              <div class="d-flex justify-content-between">
                                  <button type="button" class="mb-1 btn-close" @click="DealerbackView"></button>
                                  <div class="mt-3 d-flex align-items-center justify-content-end gap-3">
                                    <p class="tc-light-gray icon-bid">입찰 {{ auctionDetail.data.bids.length }}</p>
                                    <div class="tc-light-gray ml-2 icon-heart">관심 {{ auctionDetail.data.likes ? auctionDetail.data.likes.length : 0 }}</div>
                                  </div>
                                </div>
                              <div>
                                <h5 class="text-start process my-4">입찰 금액을 <br> 입력해주세요</h5>
                                <div class="input-container mt-5">
                                  <input type="text" class="styled-input" placeholder="0" v-model="amount" @input="updateKoreanAmount">
                                </div>
                                <p class="d-flex justify-content-end tc-light-gray p-2">{{ koreanAmount }}</p>
                                <button type="button" class="tc-wh btn btn-primary w-100 my-4" @click="submitAuctionBid">입찰 완료</button>
                              </div>
                            </div>
                          </BottomSheet03>

                            
                            <bid-modal v-if="showBidModal" :amount="amount" :highestBid="highestBid" :lowestBid="lowestBid" @close="closeBidModal" @confirm="confirmBid"></bid-modal>
                            
                        <!--    <div v-if="isDealer && auctionDetail.data.status === 'ing' && (succesbid || succesbidhope || auctionDetail.data.bids.some(bid => bid.user_id === user.id)) && auctionDetail.data.hope_price !== null" @click.stop="">
                              <h5 class="mx-3 text-center">{{ minutesLeft }}</h5>
                              <p class="auction-deadline my-4">나의 입찰 금액 <span class="tc-red">{{ amtComma(myBidPrice) }}</span></p>
                              <h5 class="my-4">입찰 {{ auctionDetail.data.bids.length }}명/ 관심 0 명</h5>
                              <div class="bottom-message">성사수수료 보즘금이 부족해요</div>
                              <h5 class="text-center mt-5">희망가에 입찰 완료 되었습니다.</h5>
                              <p class="text-center tc-red mb-2">※ 희망가에 입찰이 완료되었습니다. 수정이 불가능합니다.</p>
                              <button type="button" class="my-3 w-100 btn" :class="{'btn-outline-primary': auctionDetail.data.hope_price === null, 'primary-disable': auctionDetail.data.hope_price !== null}" @click.prevent="openAlarmGuidModal">
                                입찰 취소하기
                              </button>
                            </div>
                            <AlarmGuidModal ref="alarmGuidModal" />-->
                            
                        </div>
                        
                        <div class="container" v-if="isUser && auctionDetail.data.status === 'wait' && !connectDealerModal || auctionChosn && !connectDealerModal || !connectDealerModal && (auctionDetail.data.status === 'ing' && auctionDetail.data.bids_count !== 0 && isUser) ">
                          <div class="wd-100 bid-content p-4">
                            <div class="d-flex justify-content-between">
                              <p class="bold-20-font">현재 {{auctionDetail.data.bids_count}}명이 입찰했어요.</p>
                              <button class="mt-1" @click="openModal"><span class="cancelbox">경매취소</span></button>
                            </div>
                          </div>
                          <div class="container p-3 mt-3">
                          <h5>딜러 선택하기</h5>
                          <p class="tc-light-gray">입찰 금액이 가장 높은 순으로 5명까지만 표시돼요.</p>
                          <p class="tc-red text-start mt-2">※ 3일후 까지 선택된 딜러가 없을시, 경매가 취소 됩니다.</p>
                        </div>
                        <div class="bid-bc p-2">
                          <ul v-for="(bid, index) in sortedTopBids" :key="bid.user_id" class="px-0 inspector_list max_width_900">
                            <li @click="handleClick(bid, $event, index)">
                              <div class="d-flex gap-4 align-items-center justify-content-between">
                                <div class="img_box">
                                  <img :src="getPhotoUrl(bid)" alt="Profile Photo" class="profile-photo" />
                                </div>
                                <div class="txt_box me-auto">
                                  <h5 class="name mb-1">{{ bid.dealerInfo ? bid.dealerInfo.name : 'Loading...'}}</h5>
                                  <p class="txt">{{bid.price}} 만원</p>
                                </div>
                                <p class="restar normal-16-font me-auto">4.5점</p>
                                <p class="btn-apply-ty03"></p>
                              </div>
                            </li>
                          </ul>
                          <ul v-if="!sortedTopBids || !sortedTopBids.length" class="px-0 inspector_list max_width_900 mt-3">
                            <li>
                              <p class="tc-light-gray text-center border-none">선택 가능한 딜러가 없습니다.</p>
                            </li>
                          </ul>
                          <!-- 취소 모달 -->
                          <auction-modal v-if="isModalVisible" :showModals="isModalVisible" :auctionId="selectedAuctionId" @close="closeModal" @confirm="handleConfirmDelete" />
                        </div>
                         <!-- 딜러 선택시 모달 -->
                    
                        <BottomSheet02 class="container" v-if="!showReauctionView" initial="half" :dismissable="true" style="position: fixed !important;">
                          <button type="button" class="btn btn-dark d-flex align-items-center justify-content-center gap-1" @click="toggleView">재경매 하기<p class="icon-up-wh"></p></button>
                        </BottomSheet02>
                       <!-- <BottomSheet03 initial="half" :dismissable="true" v-if="showReauctionView &&isUser" class="p-0 filter-content">
                        <div>
                          <button type="button" class="mb-1 btn-close" @click="backView"></button>
                          <h5 class="my-4 mb-5">재경매할 금액을<br>입력해 주세요.</h5>
                          <div>
                            <div v-if="auctionDetail.data.hope_price != null" class="form-group dealer-check mt-0 mb-0">
                              <label for="sell">희망가 수정
                                <span class="tooltip-toggle normal-14-font" aria-label="희망가 판매시, 해당가격에서 입찰한 딜러에게 자동으로 낙찰됩니다." tabindex="0"></span>
                              </label>
                              <div class="check_box">
                                <input type="checkbox" id="sell" class="form-control" v-model="isSellChecked">
                                <label for="sell">희망가 판매</label>
                              </div>
                            </div>
                            <div v-else class="form-group dealer-check mt-0 mb-5">
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
                          </div>
                        </div>
                      </BottomSheet03>-->
                      <modal v-if="reauctionModal" :isVisible="reauctionModal" />
                      </div>
                    </div>
                  <consignment v-if="connectDealerModal" :bid="selectedBid" :userData="userInfo" @close="handleModalClose" @confirm="handleDealerConfirm" />
</template>
<script setup>
import { ref, computed, onMounted, onUnmounted, watch, watchEffect, onBeforeUnmount , inject,reactive,nextTick} from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { gsap } from 'gsap';
import useUsers from '@/composables/users';
import useRoles from '@/composables/roles';
import useAuctions from '@/composables/auctions';
import useBids from '@/composables/bids';
import modal from '@/views/modal/modal.vue';
import auctionModal from '@/views/modal/auction/auctionModal.vue';
import consignment from '@/views/consignment/consignment.vue';
import AlarmModal from '@/views/modal/AlarmModal.vue';
import profileDom from '/resources/img/profile_dom.png';

import drift from '../../../../../resources/img/drift.png';
import carObjects from '../../../../../resources/img/modal/car-objects-blur.png';
import carInfo from '../../../../../resources/img/electric-car.png';
import { cmmn } from '@/hooks/cmmn';
import bidModal from '@/views/modal/bid/bidModal.vue';
import { initReviewSystem } from '@/composables/review';
import BottomSheet02 from '@/views/bottomsheet/Bottomsheet-type02.vue';
import BottomSheet03 from '@/views/bottomsheet/Bottomsheet-type03.vue';
import useLikes from '@/composables/useLikes';
import { isEqual } from 'date-fns';

const { getUserReview , deleteReviewApi , reviewsData , formattedAmount } = initReviewSystem(); 
const auctionChosn = ref(false);
const showNotification = ref(false);
const isMobileView = ref(window.innerWidth <= 640);
const isClaimModalOpen = ref(false);
const lastBidId = ref(null);
const usersInfo = ref({});
const alarmModal = ref(null);
const bidSession =ref(false);
const alarmGuidModal = ref(null);
const isSellChecked = ref(false);
const { getUser } = useUsers();
const { like , setLikes , deleteLike } = useLikes();
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
const { numberToKoreanUnit , amtComma , wica , wicaLabel, wicas } = cmmn();

const reviewIsOk = ref(true);
let likeMessage;

const swal = inject('$swal');
const myBidPrice = computed(() => {
  const myBid = auctionDetail.value?.data?.bids?.find(bid => bid.user_id === user.value.id);
  return myBid ? myBid.price : '0';
});
let pollingInterval = null;
const updateKoreanAmount = () => {
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

const toggleFavorite = (auction) => {
  console.log(auction)
  auction.isFavorited = !auction.isFavorited;
  //console.log(auction.isFavorited);
  if (auction.isFavorited) {
      addLike(auction.id);
  } else {
      removeLike(auction);
  }
};

const addLike = async (auctionId) => { 
    like.user_id = user.value.id;
    like.likeable_id = auctionId;
    //console.log('Like added for auction:', auctionId);
    const response = await setLikes(like);
    console.log(response);
    if(response.isSuccess){
      wica.ntcn(swal).icon('S').title('관심 차량이 추가되었습니다.').fire();
      fetchAuctionDetail();
    }
};

const removeLike = async (auction) => {
    //console.log(auction.likes[0].id);
    const response = await deleteLike(auction.like.id);
    //console.log('Like removed for auction:', auction.id);
    if(response.isSuccess){
      wica.ntcn(swal).icon('S').title('관심 차량이 취소되었습니다.').fire();
      fetchAuctionDetail();

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
const showPdf = ref(false);

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
/*const animateHeightPrice = (newPrice) => {
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
};*/

const auctionIngChosen = () => {
  auctionChosn.value=true;
}
const competionsuccess = () => {
  const id = route.params.id;
  router.push({ name: 'completionsuccess', params: { id } });
};

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
  { immediate: true } 
);
const DOMauctionsData = ref([
  { id: 1, name: '주소명칭1', address: '주소1', zipCode: '우편번호1' },
  { id: 2, name: '주소명칭2', address: '주소2', zipCode: '우편번호2' },
]);

const selectedAuction = ref(null); 
const temporarySelectedAuction = ref(null); 
const showModal = ref(false); 
const scrollableContent = ref(null); 

const selectAuction = (id) => {
  temporarySelectedAuction.value = DOMauctionsData.value.find(auction => auction.id === id);
  console.log('선택된 항목:', temporarySelectedAuction.value); 
};

const dealerAddrConnect = () => {
  console.log('dealerAddrConnect called'); 
  showModal.value = true; 


  nextTick(() => {
    if (scrollableContent.value) {
      renderAuctionItems();
    } else {
      console.error('Scrollable content element is not available.');
    }
  });
};

const renderAuctionItems = () => {
  const scrollableContentElement = scrollableContent.value;
  if (!scrollableContentElement) {
    console.error('Scrollable content element is null.');
    return;
  }

  scrollableContentElement.innerHTML = ''; 

  DOMauctionsData.value.forEach(auction => {
    const auctionItem = document.createElement('div');
    auctionItem.classList.add('auction-item');
    auctionItem.setAttribute('data-id', auction.id);
    auctionItem.innerHTML = `
      <div class="complete-car">
        <div class="my-auction">
          <div class="bid-bc p-2" style="max-height: 480px;">
            <ul class="px-0 inspector_list max_width_900">
              <li>
                <div class="text-start fw-semibold">
                  <p>명칭: ${auction.name}</p>
                  <p>주소: ${auction.address}</p>
                  <p>우편번호: ${auction.zipCode}</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    `;

  /* ul 선택 스타일 부분*/ 
    const ulElement = auctionItem.querySelector('ul');
    ulElement.addEventListener('click', (event) => {
      event.stopPropagation();

      selectAuction(auction.id);

      document.querySelectorAll('.auction-item ul').forEach(item => {
        item.style.border = '';
        item.style.color = '';
      });

      ulElement.style.border = '2px solid red';
      ulElement.style.color = 'red';
      ulElement.style.borderRadius = "6px";
      console.log('Custom styles added:', ulElement);
    });
    scrollableContentElement.appendChild(auctionItem);
});
};

const confirmSelection = () => {
  if (temporarySelectedAuction.value) {
    selectedAuction.value = temporarySelectedAuction.value;
    const textOk = `<div class="enroll_box" style="position: relative;">
                    <img src="${carObjects}" alt="자동차 이미지" width="160" height="160">
                    <p class="overlay_text04">탁송 주소지가 변경되었습니다.</p>
                  </div>`;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그인 경우 활성화
    .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
    .addOption({ padding: 20 }) // swal 기타 옵션 추가
    .callback(async function (result) { 
    })
    .confirm(textOk);
  } else {
    alert("선택을 해줘야합니다.");
  }
  showModal.value = false; 
};



const openAlarmModal = async () => {
  showPdf.value = !showPdf.value;
};


/*[사용자] 재경매 - 현재 날짜에서 D-3 일 후 라고 가정함.*/ 
function getThreeDaysFromNow() {
  const currentDate = new Date();
  currentDate.setDate(currentDate.getDate() + 3);
  return currentDate.toISOString().slice(0, 19).replace('T', ' ');
}


const isModalVisible = ref(false);


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
    //reauctionModal.value = true;
      const textOk=`<div class="enroll_box" style="position: relative;">
              <img src="${carObjects}" alt="자동차 이미지" width="160" height="160">
              <p class="overlay_text02">경매 신청이 완료되었습니다.</p>
              <p class="overlay_text03">진단평가 완료까지 조금만 기다려주세요!</p>
              </div>`;
  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 인 경우 활성화
    .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
    .addOption({ padding: 20}) // swal 기타 옵션 추가
    .callback(function (result) {
              window.location.href = '/auction';
      })
    .confirm(textOk);
  } catch (error) {
    console.error('Error re-auctioning:', error);
    alert('재경매에 실패했습니다.');
  }
};

/* 사용자 경매 취소 눌렀을시의 모달 */ 
const openModal = () => {
//isModalVisible.value = true;
  selectedAuctionId.value = auctionDetail.value?.data.id;
  const text= `<div class="enroll_box" style="position: relative;">
                  <img src="${drift}" alt="자동차 이미지" width="160" height="160">
                  <p class="overlay_text02">경매를 취소하시겠습니까?</p>
                  <p class="overlay_text03">경매자가 마음에 들지 않으시다면<br>재경매를 진행 할 수 있어요.</p>
                </div>`;
  wica.ntcn(swal)
  .useHtmlText() // HTML 태그 인 경우 활성화
  .labelOk('재경매') // 확인 버튼 라벨 변경
  .labelCancel('경매 취소') // 취소 버튼 라벨 변경
  .btnBatch('R') // 확인 버튼 위치 지정, 기본은 L
  .addClassNm('cancel-modal') // 클래스명 변경, 기본 클래스명: wica-salert
  .addOption({ padding: 20 }) // swal 기타 옵션 추가
  .callback(function (result) {
    if (result.isOk) {
      toggleView();
    } else if (!result.isOk) {
      handleConfirmDelete();
    } else {
      console.error('Unexpected result:', result);
    }
  })
  .confirm(text);

};
const closeModal = () => {
  isModalVisible.value = false;
};

/* 경매 취소 행동부분 */ 
const handleConfirmDelete = async () => {
  try {
    const Auctioncancel = await updateAuctionStatus(selectedAuctionId.value, 'cancel');
    console.log(Auctioncancel);
    if (Auctioncancel.isError) {
      // 에러 처리 로직
      console.error("Auction cancellation error");
    } else {
      const text = `<div class="enroll_box" style="position: relative;">
                   <img src="${drift}" alt="자동차 이미지" width="160" height="160">
                   <p class="overlay_text04">경매가 취소되었습니다.</p>
                   </div>`;
      if (Auctioncancel.isSuccess) {
        await new Promise((resolve, reject) => {
          wica.ntcn(swal)
            .useHtmlText() // HTML 태그 인 경우 활성화
            .labelCancel()
            .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
            .addOption({ padding: 20, height: 265 }) // swal 기타 옵션 추가
            .callback(function (result) {
              window.location.href = '/auction';

              })
            .confirm(text);
        });
      } 
    }
  } catch (error) {
    console.error(error);
  }
};

const toggleView = () => {
  showReauctionView.value = true;
  const textOk = `<div class="enroll_box" style="position: relative;">
                    <img src="${carObjects}" alt="자동차 이미지" width="160" height="160">
                    <p class="overlay_text04">재경매를 진행하시겠습니까?</p>
                  </div>`;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그인 경우 활성화
    .btnBatch('R') // 확인 버튼 위치 지정, 기본은 L
    .addClassNm('review-custom') // 클래스명 변경, 기본 클래스명: wica-salert
    .addOption({ padding: 20 }) // swal 기타 옵션 추가
    .callback(async function (result) { // callback 함수를 async로 변경
      if (result.isOk) {
        await reauction(); // reauction 함수 호출
      } else {
        showReauctionView.value = false;
      }
    })
    .confirm(textOk);
};
const showbidView = () =>{
  bidSession.value=true;
}
const backView = () => {
  showReauctionView.value = false;
  console.log(showReauctionView.value);
};
const DealerbackView = () =>{
  bidSession.value = false;
}

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
          bid.dealerfile = userData;
          bid.dealerInfo = userData.dealer;
          console.log("???????,",bid.dealerInfo);
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
  if (event.currentTarget === event.target || event.currentTarget.contains(event.target)) {
    await selectDealer(bid, index);
  }
};
const getPhotoUrl = (bid) => {
  return bid.dealerfile && bid.dealerfile.files && bid.dealerfile.files.file_user_photo
    ? bid.dealerfile.files.file_user_photo[0].original_url
    : profileDom;
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
    bid_id: selectedDealer.value.id,
  };

  try {
    await chosenDealer(id, data);
    auctionDetail.value.data.status = 'chosen';
    const textOk= `<div class="enroll_box" style="position: relative;">
                   <img src="${drift}" alt="자동차 이미지" width="160" height="160">
                   <p class="overlay_text04">탁송이 신청되었습니다.</p>
                   </div>`;
    wica.ntcn(swal)
      .useHtmlText() // HTML 태그 인 경우 활성화
      .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
      .addOption({ padding: 20}) // swal 기타 옵션 추가
      .callback(function (result) {
                window.location.href = '/auction';
        })
      .confirm(textOk);
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

  const response = wicac.conn()
  .url(`/api/auctions/${id}`) //호출 URL
  .param(data)
  .callback(function(result) {
    if(result.isSuccess){
      auctionDetail.value.data.status = 'chosen';
      auctionDetail.value.data.final_price = price;
      auctionDetail.value.data.bid_id = userId;
    /* auctionDetail.value.data.chosen_at = data.chosen_at;*/
      completeAuctionModal.value = true; // 경매 완료 모달 표시
    }else{
      wica.ntcn(swal)
      .title('')
      .icon('E') //E:error , W:warning , I:info , Q:question
      .alert('경매에 실패했습니다.');
    }
    
  })
  .post();
};


const confirmBid = async () => {
  try {
    const bidResult = await submitBid(auctionDetail.value.data.id, amount.value, user.value.id);
    if (bidResult.success) {
      lastBidId.value = bidResult.bidId;
      await fetchAuctionDetail();
      closeBidModal();
      succesbid.value = true;

      wica.ntcn(swal)
      .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
      .icon('I') //E:error , W:warning , I:info , Q:question
      .alert('입찰이 완료되었습니다.');
     /* if (auctionDetail.value.data.hope_price !== null && amount.value == auctionDetail.value.data.hope_price) {
        await handleImmediateAuctionEnd(user.value.id, amount.value);
      }*/
    } else {
      wica.ntcn(swal)
      .title('')
      .icon('E') //E:error , W:warning , I:info , Q:question
      .alert(bidResult.message);
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
    console.log(auctionDetail.value.data.likes);
    
    const userLike = auctionDetail.value.data.likes.find(like => like.user_id === user.value.id);

    if (userLike) {
        auctionDetail.value.data.like = userLike;
        auctionDetail.value.data.isFavorited = true;
    }

    if(auctionDetail.value.data.reviews.length > 0){
      reviewIsOk.value = false;
    }
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
         /*
         console.log("?",auctionDetail.value.data.status);
         console.log(auctionDetail.value.data.hope_price);
         if (auctionDetail.value.data.status === 'ing' && auctionDetail.value.data.hope_price === null) {
          animateHeightPrice(newHeightPrice);
        }else if(auctionDetail.value.data.hope_price !== 'null'){
          animateHeightPrice(auctionDetail.value.data.hope_price);
        }*/
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

  await getAuctions();
  await fetchAuctionDetail();

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
  
    console.log("!!!!!!!!!!!!!!!!!!!!:",auctionDetail.value);

  /*if (auctionDetail.value.data.status === 'done' && isDealer.value) {
    showNotification.value = true;
    setTimeout(() => {
      showNotification.value = false;
    }, 7000);
  }*/
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
  const diff = finalAt().getTime() - currentTime.value.getTime(); // 마감 시간과 현재 시간의 차이 계산
  if (auctionDetail.value?.data?.status === 'ing' && diff > 0 ) {
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
        wica.ntcn(swal)
        .title('')
        .icon('E') //E:error , W:warning , I:info , Q:question
        .alert('입찰 취소에 실패하였습니다.');
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
}
@media (max-width: 991px) {
  .container {
     --bs-gutter-x: 0rem !important;
       max-width:none !important;
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

@media (max-width: 500px){
.container {
    --bs-gutter-x: 0rem;
}
}
.card-img-top-ty02{
  border-top-left-radius: 6px;
  border-top-right-radius: 6px;
}
.flex-column .card-img-top-ty02{
  border-top-left-radius: 0px !important;
  border-bottom-left-radius: 0px !important;
  border-top-right-radius: 6px;
  border-top-left-radius: 6px !important;
}
.img_box img{
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.sheet.half{
  max-height: none !important;
  height: fit-content !important;
}
.sm-height{
  height: 34px !important;
}
.ul.px-0.inspector_list.max_width_900.selected {
  background-color: lightgray !important;
  border: 2px solid blue !important;
  color: red;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-container {
  background: white;
  border-radius: 8px;
  padding: 20px;
  max-width: 100%;
  max-height: 90%;
  overflow-y: auto;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  width: -webkit-fill-available;
  z-index: 999;
  animation: fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.scrollable-content {
  max-height: 300px;
  overflow-y: auto;
}

@media (min-width: 768px) {
  .modal-container {
    max-width: 600px;
  }
}

@media (min-width: 1024px) {
  .modal-container {
    max-width: 800px;
  }
}
#ac-evaluation {
  cursor: pointer;
}

#ac-evaluation:hover {
  color: red; /* 예시 스타일, 필요에 따라 수정 가능 */
}
</style>