<template>
    <!--
        TODO: 미구현 
              조회수는 경매완료 되면 동결 , final_at , choice_at, done_at(?) => 시간 초.
    -->
    <div class="container-fluid" v-if="auctionDetail">
        <!--차량 정보 조회 내용 : 제조사,최초등록일,배기량, 추가적으로 용도변경이력 튜닝이력 리콜이력 추가 필요-->
        <div v-if="!showReauctionView">
            <div class="web-content-style">
                <div>
                    <div class="container my-4">
                        <div>
                            <div class="mb-4">
                                <div class="d-flex gap-2 justify-content-end mb-1">
                                    <div class="tc-light-gray">조회수: {{ auctionDetail.data.hit }}</div>
                                    <div class="tc-light-gray ml-2">관심 {{ auctionDetail.data.hit }}</div>
                                </div>
                                <div class="card my-auction">
                                    <input class="toggle-heart" type="checkbox" checked />
                                    <label class="heart-toggle"></label>
                                    <div :class="{ 'grayscale_img': auctionDetail.data.status === 'done' }" class="card-img-top-ty01"></div>
                                    <div class="allpage">
                                        <p class="more-page">1/31 |</p>
                                        <button class="more-img">전체보기</button>
                                    </div>
                                    <div v-if="auctionDetail.data.status === 'done'" class="time-remaining">경매 완료</div>
                                    <div class="card-body">
                                        <div class="enter-view align-items-baseline ">
                                            <p class="card-title fs-5"><span class="blue-box">무사고</span>현대 쏘나타(DN8)</p>
                                        </div>
                                        <div class="enter-view">
                                            <p class="card-text tc-light-gray fs-5">{{ auctionDetail.data.car_no }}</p>
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
                        <div v-if="auctionDetail.data.status === 'ing'">
                            <p class="auction-deadline">경매 마감일<span> {{ auctionDetail.data.final_at }}</span></p>
                        </div>
                        <div v-else-if="auctionDetail.data.status === 'done'">
                            <p class="auction-deadline">낙찰가 {{ auctionDetail.data.final_price }} 만원</p>
                        </div>
                        <div v-else-if="auctionDetail.data.status === 'ask'">
                            <p class="auction-deadline">신청 완료</p>
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
                <div class="bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet">
                    <div class="sheet-content">

                        <!--#####################
                        사용자 바텀시트
                    #########################-->

                        <div v-if="isUser">
                            <!-------[사용자]diag (진단평가)알때------->
                            <div v-if="auctionDetail.data.status === 'diag' || auctionDetail.data.status === 'ask' " @click.stop="">
                                <div class="steps-container mt-3">
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

                            <!-------[사용자]취소 (진단평가)알때------->
                            <div v-if="auctionDetail.data.status === 'cancel'" @click.stop="">
                                <div class="steps-container mt-3">
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
                                <div class="steps-container mt-3">
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
                                <p class="auction-deadline">현재 경매중 입니다.</p>
                            </div>

                            <!-- [사용자]- 딜러 선택 (wait) 중일때  -->
                            <div v-if="!selectedDealer && auctionDetail.data.status === 'wait'" @click.stop="">
                                <div class="steps-container mt-3">
                                    <div class="step completed">
                                        <div class="label completed">
                                            STEP01
                                        </div>
                                    </div>
                                    <div class="line completed"> </div>
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
                                    <button @click="openModal" type="button" class="btn btn-outline-dark">경매취소</button>
                                    <transition name="fade" mode="out-in">
                                        <auction-modal v-if="isModalVisible" :showModals="isModalVisible" :auctionId="selectedAuctionId" @close="closeModal" @confirm="handleConfirmDelete" />
                                    </transition>
                                    <button type="button" class="btn btn-dark" @click="toggleView">재경매</button>
                                </div>
                                <p class="text-end tc-light-gray">3번 더 재경매 할 수 있어요.</p>
                                <div class="content mt-3 text-start">
                                    <h5>경매에 참여한 딜러</h5>
                                    <p> 금액이 가장 높은 <span class="highlight">5명</span>까지만 표시돼요.</p>
                                    <div class="overflow-auto select-dealer">
                                        <table>
                                            <tbody>
                                                <tr v-for="(bid, index) in sortedTopBids" :key="bid.user_id">
                                                    <td class="w-25"><img src="../../../../img/myprofile_ex.png" alt="딜러 사진" class="align-text-top"></td>
                                                    <td class="d-flex flex-column align-items-center w-75">
                                                        <div :class="[(index === 0 ? 'red-box' : index < 3 ? 'blue-box' : 'gray-box'), 'rounded-pill', 'me-0']">
                                                            {{ index + 1 }}위
                                                        </div>
                                                        <div class="bold-18-font">{{bid.userData}}</div>
                                                    </td>
                                                    <td class="tc-light-gray align-bottom">{{ bid.price }}원</td>
                                                    <td class="align-middle w-25">
                                                        <input type="checkbox" :id="'checkbox-' + bid.user_id" class="custom-checkbox-input" @change="selectDealer(bid, $event, index + 1)">
                                                        <label :for="'checkbox-' + bid.user_id" class="custom-checkbox-label"></label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <transition name="fade">
                                            <ConnectDealerModal v-if="connectDealerModal" :bid="selectedBid" :userData="userInfo" @close="handleModalClose" @confirm="handleDealerConfirm" />
                                        </transition>
                                    </div>
                                </div>
                            </div>

                            <!--[사용자] - 딜러 선택 후 경매 했을떄 -->
                            <div v-if="selectedDealer && auctionDetail.data.status === 'wait'" @click.stop="">
                                <div class="steps-container mt-3">
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
                                    <button type="button" class="btn btn-primary" @click="completeAuction">경매 완료</button>
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

                            <!--[사용자] - 경매 완료 -->
                            <transition name="fade">
                                <div v-if="auctionDetail.data.status === 'done'" @click.stop="">
                                    <h5 class="text-center p-4"> 거래는 어떠셨나요?</h5>
                                    <router-link :to="{ name: 'user.create-review' }" type="button" class="tc-wh btn btn-primary w-100">후기 남기기</router-link>
                                </div>
                            </transition>
                        </div>

                        <!-- 바텀 시트 show or black-->
                        <button class="animCircle scroll-button floating" :style="scrollButtonStyle" v-show="scrollButtonVisible"></button>

                        <!--#####################
                        딜러에 관힌 바텀시트
                    #########################-->

                        <div v-if="isDealer">
                            <!------------------- [딜러] - 경매 완료 -------------------->
                            <div class="mt-4" v-if="auctionDetail.data.status === 'done'" @click.stop="">
                                <h5 class="text-center"> 불편 사항이 있으신가요?</h5>
                                <button type="button" class="my-3 btn btn-outline-danger w-100">클레임 신청하기</button>
                                <a href="#" class="d-flex justify-content-center tc-light-gray">클레임 규정</a>
                            </div>

                            <!------------------- [딜러] - 입찰 바텀 뷰 -------------------->
                            <div v-if="!succesbid && !auctionDetail.data.bids.some(bid => bid.user_id === user.id) && auctionDetail && auctionDetail.data.status === 'ing'" @click.stop="">
                                <div class="steps-container mt-3">
                                    <div class="step completed">
                                        <div class="label completed">STEP01</div>
                                    </div>
                                    <div class="line completed"></div>
                                    <div class="step completing">
                                        <div class="label completed">STEP02</div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step">
                                        <div class="label">STEP03</div>
                                    </div>
                                </div>
                                <p class="auction-deadline text-center">경매를 시작합니다.</p>
                                <p class="tc-red mt-2">경매 마감까지 {{ auctionDetail.data.final_at || "null" }} 분 남음</p>
                                <div class="mt-3 d-flex justify-content-end gap-3">
                                    <p class="bid-icon tc-light-gray normal-16-font">입찰 {{ auctionDetail.data.bids.length }}</p>
                                    <p class="interest-icon tc-light-gray normal-16-font">관심 6</p>
                                </div>
                                <div class="o_table_mobile my-5">
                                    <div class="tbl_basic tbl_dealer">
                                        <div class="overflow-auto select-dealer">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <th>입찰 순위</th>
                                                        <th>딜러명</th>
                                                        <th>입찰금액</th>
                                                    </tr>
                                                    <tr v-for="(bid, index) in sortedBids" :key="bid.user_id">
                                                        <td>{{ index + 1 }}위</td>
                                                        <td>{{ bid.user_id }}</td>
                                                        <td>{{ bid.price }} 만원</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-start">나의 입찰 금액을 입력해주세요</h5>
                                    <div class="input-container mt-4">
                                        <input type="text" class="styled-input" placeholder="0" v-model="amount" @input="updateKoreanAmount">
                                    </div>
                                    <p class="d-flex justify-content-end tc-light-gray p-2">{{ koreanAmount }}</p>
                                    <button type="button" class="tc-wh btn btn-primary w-100" @click="submitAuctionBid">확인</button>
                                </div>
                                <!--  <div v-else-if="userBidExists && !userBidCancelled">
                                                    <h5 class="text-center">입찰이 완료되었습니다.</h5>
                                                    <p class="text-center tc-red">※ 최초 1회 수정이 가능합니다</p>
                                                    <div class="mt-3 text-center d-flex gap-3 justify-content-center">
                                                    <p>수정 가능 횟수 : {{ cancelAttempted === 0 ? '0' : '1' }}회</p>
                                                    <a href="#" class="tc-light-gray btn-apply p-0" @click.prevent="handleLinkClick">수정하기</a>
                                                    </div>
                                                    </div>-->
                                <transition name="fade">
                                    <bid-modal v-if="showBidModal" :amount="amount" :highestBid="highestBid" :lowestBid="lowestBid" @close="closeBidModal" @confirm="confirmBid"></bid-modal>
                                </transition>
                            </div>
                            <!------------------- [딜러] - 입찰 완료후 바텀 메뉴 -------------------->
                            <div class="p-4" v-if="auctionDetail.data.status === 'ing' && (succesbid || auctionDetail.data.bids.some(bid => bid.user_id === user.id))" @click.stop="">
                                <h5 class="mx-3 text-center">경매 마감까지 03:25:43 남음</h5>
                                <p class="auction-deadline my-4">나의 입찰 금액 <span class="tc-red">{{ myBidPrice }}</span></p>
                                <h5 class="my-4">입찰 {{ auctionDetail.data.bids.length }}명/ 관심 n 명</h5>
                                <div class="d-flex justify-content-between">
                                    <p class="tc-light-gray">현재 최고 입찰가</p>
                                    <p>{{ heightPrice }} 만원</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="tc-light-gray">현재 최저 입찰가</p>
                                    <p>{{ lowPrice }} 만원</p>
                                </div>
                                <button type="button" class="my-3 w-100 btn btn-outline-primary" @click="handleCancelBid">
                                    입찰 취소
                                </button>
                                <!--  수수료 보증금이 부족할때 나오는 메뉴
                                    <div class="bottom-message">
                                        성사수수료 보즘금이 부족해요
                                    </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 재경매 버튼 눌렀을 때 view -->
                <div class="container my-4" v-if="showReauctionView">
                    <div class="p-4">
                        <h5 class="mb-2">재경매를 진행합니다</h5>
                        <div class="card my-auction">
                            <div :class="{ 'grayscale_img': auctionDetail.data.status === 'done' }" class="card-img-top-ty01"></div>
                            <div v-if="auctionDetail.data.status === 'done'" class="time-remaining">경매 완료</div>
                            <div class="card-body">
                                <div class="enter-view align-items-baseline">
                                    <p class="card-title fs-5"><span class="blue-box">무사고</span>현대 쏘나타(DN8)</p>
                                </div>
                                <div class="enter-view">
                                    <p class="card-text tc-light-gray fs-5">{{ auctionDetail.data.car_no }}</p>
                                    <a href="#"><span class="red-box-type02 pass-red">위카 진단평가</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <ul class="machine-inform-title">
                                <li class="tc-light-gray">차량번호</li>
                                <li class="info-num">{{ carDetails.no }}</li>
                            </ul>
                            <ul class="machine-inform">
                                <li class="tc-light-gray">모델</li>
                                <li class="sub-title">{{ carDetails.model }}</li>
                            </ul>
                            <ul class="machine-inform">
                                <li class="tc-light-gray">현재 시세</li>
                                <li class="sub-title">{{ carDetails.priceNow }}</li>
                            </ul>
                            <ul class="machine-inform">
                                <li class="tc-light-gray">입찰가</li>
                                <li class="sub-title">입찰가(데모)</li>
                            </ul>
                        </div>
                        <div class="form-group dealer-check mt-0 mb-0">
                            <label for="dealer">희망가로 판매할까요?
                                <span class="tooltip-toggle nomal-14-font" aria-label="희망가 판매시, 해당가격에서 입찰한 딜러에게 자동으로 낙찰됩니다." tabindex="0"></span>
                            </label>
                            <div class="check_box">
                                <input type="checkbox" id="sell" class="form-control" v-model="isSellChecked">
                                <label for="sell">희망가 판매</label>
                            </div>
                        </div>
                        <div class="input-container mt-4">
                            <input type="text" class="styled-input" placeholder="희망가 입력(선택)" v-model="amount" @input="updateKoreanAmount" :readonly="isReadonly">
                        </div>
                        <p class="d-flex justify-content-end tc-light-gray p-2">{{ koreanAmount }}</p>
                        <div class="btn-group mt-3 mb-2">
                            <button type="button" class="btn btn-primary" @click="reauction">재경매</button>
                            <transition name="fade">
                                <modal v-if="reauctionModal" :isVisible="reauctionModal" />
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import useUsers from '@/composables/users';
import useRoles from '@/composables/roles';
import useAuctions from '@/composables/auctions';
import useBids from '@/composables/bids';
import modal from '@/views/modal/modal.vue';
import auctionModal from '@/views/modal/auction/auctionModal.vue';
import ConnectDealerModal from '@/views/modal/auction/connectDealer.vue';
import bidModal from '@/views/modal/bid/bidModal.vue';
import { cmmn } from '@/hooks/cmmn';

const heightPrice = ref(0);
const lowPrice = ref(0);
const lastBidId = ref(null);
const usersInfo = ref({});
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
const amount = ref('');
const koreanAmount = ref('원');
const { numberToKoreanUnit } = cmmn();
const myBidPrice = computed(() => {
  const myBid = auctionDetail.value.data.bids.find(bid => bid.user_id === user.value.id);
  return myBid ? myBid.price : '0';
});
const updateKoreanAmount = () => {
  koreanAmount.value = numberToKoreanUnit(amount.value) + ' 원';
};

// 사용자 입찰이 취소된 적이 있는지 확인
const userBid = computed(() => auctionDetail.value.data.bids.find(bid => bid.user_id === user.value.id));
const userBidExists = computed(() => userBid.value && !userBid.value.deleted_at);
const userBidCancelled = computed(() => auctionDetail.value.data.bids.some(bid => bid.user_id === user.value.id && bid.deleted_at));

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
const { AuctionCarInfo, getAuctions, auctionsData, AuctionReauction, chosenDealer, getAuctionById, deleteAuction } = useAuctions();
const { submitBid, cancelBid } = useBids();
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

const isModalVisible = ref(false);
const selectedAuctionId = ref(null);

const reauction = async () => {
  const id = route.params.id;
  const data = {
    status: 'ing',
    hope_price: amount.value
  };

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
    await deleteAuction(selectedAuctionId.value);
    router.push({ name: 'auction.index' });
  } catch (error) {
    console.error(error);
  }
};

const toggleView = () => {
  showReauctionView.value = true;
  console.log(showReauctionView.value)
};

const toggleSheet = () => {
  const bottomSheet = document.querySelector('.bottom-sheet');
  const screenWidth = window.innerWidth;

  if (showBottomSheet.value) {
    bottomSheetStyle.value = { position: 'static', bottom: '-100%' };

    scrollButtonStyle.value = { display: 'block' };
  } else {
    bottomSheetStyle.value = {
      position: screenWidth >= 1200 ? 'static' : 'fixed',
      bottom: '0px'
    };
    scrollButtonStyle.value = { display: 'none' };
  }
  showBottomSheet.value = !showBottomSheet.value;
};

const selectDealer = async (bid, event, index) => {
  if (event.target.checked) {
    selectedBid.value = { ...bid, index };
    connectDealerModal.value = true;

    try {
      const userData = await getUser(bid.user_id);
      userInfo.value = userData;
    } catch (error) {
      console.error('Error dealer data:', error);
    }
  } else {
    selectedBid.value = null;
    connectDealerModal.value = false;
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
};

const cancelSelection = () => {
  selectedDealer.value = null;
};

const completeAuction = async () => {
  auctionDetail.value.status = 'done';
  const id = route.params.id;
  const data = {
    status: 'done',
    final_price: selectedDealer.value.price,
    bid_id: selectedDealer.value.user_id,
  };

  try {
    await chosenDealer(id, data);
    auctionDetail.value.data.status = 'done';
  } catch (error) {
    console.error('Error completing auction:', error);
    alert('경매에 실패했습니다.');
  }
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
  for (const bid of auctionDetail.value.data.bids) {
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
  const userBidExists = auctionDetail.value.data.bids.some(bid => bid.user_id === user.value.id && !bid.deleted_at);
  if (!amount.value || isNaN(parseFloat(amount.value))) {
    alert('유효한 금액을 입력해주세요.');
  } else {
    if (userBidExists) {
      alert('이미 입찰하셨습니다.');
    } else {
      openBidModal();
    }
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
        } else {
            alert(bidResult.message);
        }
    } catch (error) {
        console.error('Error confirming bid:', error);
    }
};


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

    if (auctionDetail.value.data.bids.length > 0) {
      heightPrice.value = Math.max(...auctionDetail.value.data.bids.map(bid => bid.price));
      lowPrice.value = Math.min(...auctionDetail.value.data.bids.map(bid => bid.price));
    }
    
    console.log("차량 상세 정보:", carInfoResponse);
  } catch (error) {
    console.error('Error fetching auction detail:', error);
    errorMessage.value = 'Error fetching auction details';
  }
};

onMounted(async () => {
  const screenWidth = window.innerWidth;
  bottomSheetStyle.value = {
    position: screenWidth >= 1200 ? 'static' : 'fixed',
    bottom: '0px'
  };
  await getAuctions();
  fetchAuctionDetail();
  window.addEventListener('scroll', checkScroll);
  try {
    console.log('Sorted Top Bids:', sortedTopBids.value);
  } catch (error) {
    console.error('Error fetching auction detail:', error);
  }
});

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll);
});

const populateHopePrice = () => {
  if (auctionDetail.value && auctionDetail.value.data) {
    if (isSellChecked.value) {
      amount.value = auctionDetail.value.data.hope_price;
    } else {
      amount.value = '';
    }
  }
};

const sortedBids = computed(() => {
  const bids = auctionDetail.value?.data?.bids.slice().sort((a, b) => b.price - a.price) || [];
  if (bids.length > 0) {
    highestBid.value = bids[0].price;
    lowestBid.value = bids[bids.length - 1].price;
  }
  return bids;
});

const isReadonly = computed(() => isSellChecked.value && amount.value !== '');

const handleCancelBid = async () => {
    try {
        const myBid = auctionDetail.value.data.bids.find(bid => bid.user_id === user.value.id && !bid.deleted_at);
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


watch([isSellChecked, auctionDetail], () => {
  populateHopePrice();
});
</script>


<style scoped>
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

.card-img-top-ty01 {
    width: 100%;
    height: 215px;
    background-image: url('../../../../img/car_example.png');
    background-size: cover;
    background-position: center;
    border-top-right-radius: 6px;
    border-top-left-radius: 6px;
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
  .allpage{
    position: absolute;
    display: flex;
    top: 2px;
    bottom: 0px;
    right: 0px;
    height: 37px;
    width: 130px;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 5px;
    border-radius: 30px;
    justify-content: center;
    align-items: center;
}
.allpage:hover{
    background-color: rgb(40 40 40 / 60%);
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
</style>