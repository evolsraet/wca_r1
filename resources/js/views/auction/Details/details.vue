<template>
    <!--
        TODO: 취소 성공 처리 후 모달 하나더 있음.
    -->
    <div class="container-fluid" v-if="auctionDetail">
        <!--차량 정보 조회 내용 : 제조사,최초등록일,배기량, 추가적으로 용도변경이력 튜닝이력 리콜이력 추가 필요-->
        <div v-if="!showReauctionView">
            <div class="container my-4">
                <div>
                    <div class="mb-4">
                        <div class="tc-light-gray">조회수: {{ auctionDetail.data.hit }}</div>
                        <div class="card my-auction">
                            <input class="toggle-heart" type="checkbox" checked />
                            <label class="heart-toggle"></label>
                            <div :class="{ 'grayscale_img': auctionDetail.data.status === 'done' }" class="card-img-top-ty01"></div>
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

            <!-- bottom sheet Start-->
            <div class="bottom-sheet" :style="bottomSheetStyle" @click="toggleSheet">
                <div class="sheet-content">

        <!--#####################
            사용자 바텀시트
        #########################-->

                    <div v-if="isUser">
                        <!-------[사용자]diag (진단평가)알때------->
                        <div v-if="auctionDetail.data.status === 'diag' " @click.stop="">
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
                        <div v-if="auctionDetail.data.status === 'ing' " @click.stop="">
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
                            <p class="auction-deadline">현재 경매중 입니다.</p>
                        </div>
                        <!-- [사용자]- 딜러 선택 (wait) 중일때  -->
                        <div v-if="!selectedDealer && auctionDetail.data.status === 'wait'" @click.stop="">
                            <div class="steps-container">
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
                                    <ConnectDealerModal
                                        v-if="connectDealerModal"
                                        :bid="selectedBid"
                                        :userData="userInfo"
                                        @close="handleModalClose"
                                        @confirm="handleDealerConfirm"
                                    />
                                    </transition>
                                    </div>
                            </div>
                        </div>

                        <!--[사용자] - 딜러 선택 후 경매 했을떄 -->
                        <div v-if="selectedDealer && auctionDetail.data.status === 'wait'" @click.stop="">
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
                            <router-link :to="{ name: 'user.create-review' }" type="button" class="tc-wh btn btn-danger w-100">후기 남기기</router-link>
                        </div>
                    </transition>
                    </div>

                    <!-- 바텀 시트 show or black-->
                    <button class="animCircle scroll-button" :style="scrollButtonStyle" v-show="scrollButtonVisible"></button>

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
                        <div v-if="!succesbid && auctionDetail && auctionDetail.data.status === 'ing'" @click.stop="">
                            <div class="steps-container">
                                <div class="step completed">
                                    <div class="label completed">
                                        STEP01
                                    </div>
                                </div>
                                <div class="line completed"></div>
                                <div class="step completing">
                                    <div class="label completed">
                                        STEP02
                                    </div>
                                </div>
                                <div class="line "></div>
                                <div class="step ">
                                    <div class="label ">
                                        STEP03
                                    </div>
                                </div>
                            </div>
                            <p class="auction-deadline text-center">경매를 시작합니다.</p>
                            <!--TODO: 경매 마감일? => final_at 인지 경매마감일 - 최종진단 일 ?, like 관심 기능 추가 필요-->
                            <p class="tc-red mt-2">경매 마감까지 {{auctionDetail.data.final_at || "null" }} 분 남음</p>
                            <div class="mt-3 d-flex justify-content-end gap-3">
                                <p class="bid-icon tc-light-gray normal-16-font">입찰 {{ auctionDetail.data.top_bids.length }}</p>
                                <p class="interest-icon tc-light-gray normal-16-font">관심 6</p>
                            </div>
                            <div class="o_table_mobile my-5">
                                <div class="tbl_basic tbl_dealer">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th>입찰 순위</th>
                                                <th>딜러명</th>
                                                <th>입찰금액</th>
                                            </tr>
                                            <tr v-for="(bid, index) in sortedTopBids" :key="bid.id">
                                                <td>{{ index + 1 }}위</td>
                                                <td>{{ bid.user_id }}</td>
                                                <td>{{ bid.price }} 만원</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <h5 class="text-start">나의 입찰 금액을 입력해주세요</h5>
                            <div class="input-container mt-4">
                                <input type="text" class="styled-input" placeholder="0" v-model="amount" @input="updateKoreanAmount">
                            </div>
                            <p class="d-flex justify-content-end tc-light-gray p-2">{{ koreanAmount }}</p>
                            <button type="button" class="tc-wh btn btn-danger w-100" @click="submitAuctionBid">확인</button>
                        </div>
                    </div>
                </div>
                <!------------------- [딜러] - 입찰 완료후 바텀 메뉴 -------------------->
                <div class="p-4" v-if="succesbid && auctionDetail.data.status === 'ing'" @click.stop="">
                    <h5 class="mx-3 text-center">경매 마감까지 03:25:43 남음</h5>
                    <p class="auction-deadline my-4">나의 입찰 금액 <span class="tc-red">1,300만원</span></p>
                    <h5 class="my-4">입찰 n명/ 관심 n 명</h5>
                    <div class="d-flex justify-content-between">
                        <p class="tc-light-gray">현재 최고 입찰가</p>
                        <p>1,200만원</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="tc-light-gray">현재 최저 입찰가</p>
                        <p>1,100만원</p>
                    </div>
                    <button type="button" class="my-3 btn btn-outline-danger w-100">입찰 취소 (1회 남음)</button>
                    <!--  수수료 보증금이 부족할때 나오는 메뉴
        <div class="bottom-message">
            성사수수료 보즘금이 부족해요
        </div>-->
                </div>
            </div>

        </div>

        <!-- 재경매 버튼 눌렀을 때 view -->
        <div class="container my-4" v-if="showReauctionView">
            <div class="p-4">
                <h5 class="mb-2 ">재 경매를 진행합니다</h5>
                <div class="card my-auction">
                    <div :class="{ 'grayscale_img': auctionDetail.data.status === 'done' }" class="card-img-top-ty01"></div>
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
      <button type="button" class="btn btn-danger" @click="reauction">재경매</button>
      <transition name="fade">
      <modal v-if="reauctionModal" :isVisible="reauctionModal" />
      </transition>
    </div>
        </div>
    </div>


</template>
<script setup>
import { ref, computed, onMounted, onUnmounted ,watch} from 'vue'; 
import { useRoute, useRouter } from 'vue-router'; // Vue Router 함수들
import { useStore } from 'vuex'; // Vuex 스토어 함수들
import useUsers from '@/composables/users'; // 사용자 관련 작업
import useRoles from '@/composables/roles'; // 역할 관련 작업
import useAuctions from '@/composables/auctions'; // 경매 관련 작업
import useBids from '@/composables/bids'; // 입찰 관련 작업
import modal from '@/views/modal/modal.vue'; // 모달 컴포넌트
import auctionModal from '@/views/modal/auction/auctionModal.vue'; // 경매 모달 컴포넌트
import ConnectDealerModal from '@/views/modal/auction/connectDealer.vue';

import { convertToKorean } from '@/hooks/convertToKorean'; // 숫자를 한국어로 변환하는 함수

const isSellChecked = ref(false); // 판매 체크박스 상태를 위한 Ref
const { getUser } = useUsers();
const store = useStore(); // Vuex 스토어 인스턴스
const user = computed(() => store.getters['auth/user']); // 현재 사용자 가져오는 계산된 속성
const isDealer = computed(() => user.value?.roles?.includes('dealer')); // 사용자가 딜러인지 확인하는 계산된 속성
const isUser = computed(() => user.value?.roles?.includes('user')); // 사용자가 일반 사용자인지 확인하는 계산된 속성
const selectedBid = ref(null); // 선택된 입찰을 관리하기 위한 ref
const route = useRoute(); // 현재 라우트 인스턴스
const router = useRouter(); // 라우터 인스턴스
const userInfo = ref(null);
const succesbid = ref(false); // 입찰 성공 여부를 추적하는 Ref
const amount = ref(''); // 입찰 금액을 저장하는 Ref
const koreanAmount = ref('원'); // 한국어로 된 금액을 저장하는 Ref
const updateKoreanAmount = () => { // 한국어 금액을 업데이트하는 함수
  koreanAmount.value = convertToKorean(amount.value) + ' 원';
};

const auctionModalVisible = ref(false); // 경매 모달의 제어
const reauctionModal = ref(false); // 재경매 모달의 제어
const connectDealerModal =ref(false);

const scrollButtonVisible = ref(false); // 스크롤 버튼
const selectedDealer = ref(null); // 선택된 딜러를 저장
const showBottomSheet = ref(true); // 바텀 시트의 가시성을 제어
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' }); // 바텀 시트 스타일을 저장하는 Ref
const scrollButtonStyle = ref({ display: 'none' }); // 스크롤 버튼 스타일을 저장하는 Ref

const showReauctionView = ref(false); // 재경매 뷰의 가시성을 제어하는 Ref

const auctionDetail = ref(null); // 경매 세부 정보를 저장하는 Ref
const { getAuctions, auctionsData, AuctionReauction ,chosenDealer, getAuctionById ,deleteAuction} = useAuctions(); // 경매 composable에서 함수
const { submitBid } = useBids(); // 입찰 composable에서 함수 
const carDetails = ref({}); // 자동차 세부 정보를 저장하는 Ref

/*const sortedTopBids = computed(() => { // 가격에 따라 상위 5개 입찰을 정렬하는 계산된 속성
  return auctionDetail.value?.data?.top_bids?.sort((a, b) => b.price - a.price).slice(0, 5) || [];
});*/

const sortedTopBids = computed(() => {
  // 필터 전의 모든 입찰을 로그로 출력
  console.log('All Bids:', auctionDetail.value?.data?.top_bids);

  if (!auctionDetail.value?.data?.top_bids) {
    return [];
  }

  // 각 user_id에 대해 최고 가격의 입찰만 남기기
  const bidsByUser = auctionDetail.value.data.top_bids.reduce((acc, bid) => {
    if (!acc[bid.user_id] || acc[bid.user_id].price < bid.price) {
      acc[bid.user_id] = bid;
    }
    return acc;
  }, {});

  // 필터 후의 입찰을 로그로 출력
  console.log('Filtered Bids by User:', bidsByUser);

  // 남은 입찰들을 가격 순으로 정렬하여 상위 5개를 가져오기
  const topBids = Object.values(bidsByUser)
    .sort((a, b) => b.price - a.price)
    .slice(0, 5);

  // 상위 5개의 입찰을 로그로 출력
  console.log('Top 5 Bids:', topBids);

  return topBids;
});

const isModalVisible = ref(false); // 모달
const selectedAuctionId = ref(null); // 선택된 경매 ID

const reauction = async () => { // 재경매를 처리하는 함수
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

const openModal = () => { // 모달을 여는 함수
  isModalVisible.value = true;
  selectedAuctionId.value = auctionDetail.value?.data.id;
};

const closeModal = () => { // 모달을 닫는 함수
  isModalVisible.value = false;
};

const handleConfirmDelete = async () => { // 경매 삭제를 처리하는 함수
   closeModal();
  try {
    await deleteAuction(selectedAuctionId.value);
    router.push({ name: 'auction.index' });
  } catch (error) {
    console.error(error);
  }
};

const toggleView = () => { // 재경매 뷰를 토글하는 함수
  showReauctionView.value = !showReauctionView.value;
};

const toggleSheet = () => { // 바텀 시트의 가시성을 토글하는 함수
  const bottomSheet = document.querySelector('.bottom-sheet');
  if (showBottomSheet.value) {
    bottomSheet.classList.add('modified');
    bottomSheetStyle.value = { position: 'static', bottom: '-100%' };
    scrollButtonStyle.value = { display: 'block' };
  } else {
    bottomSheet.classList.remove('modified');
    bottomSheetStyle.value = { position: 'fixed', bottom: '0px' };
    scrollButtonStyle.value = { display: 'none' };
  }
  showBottomSheet.value = !showBottomSheet.value;
};

const selectDealer = async (bid, event, index) => {
  if (event.target.checked) {
    selectedBid.value = { ...bid, index }; // 순위를 포함시켜 selectedBid에 저장
    connectDealerModal.value = true;
    console.log('Selected bid data:', selectedBid.value); // 콘솔에 bid 정보 로깅

    try {
      const userData = await getUser(bid.user_id); // API 호출로 사용자 정보 가져오기
      userInfo.value = userData; // 사용자 정보 저장
      console.log('dealer data:', userData); 
    } catch (error) {
      console.error('Error dealer data:', error);
    }
  } else {
    selectedBid.value = null;
    connectDealerModal.value = false;
  }
};


const handleModalClose = () => {
  connectDealerModal.value = false; // 모달 숨기기
  if (selectedBid.value) {
    const checkbox = document.getElementById('checkbox-' + selectedBid.value.user_id);
    if (checkbox) checkbox.checked = false; // 연관 체크박스 해제
    selectedBid.value = null; // 선택된 입찰 초기화
  }
};
const handleDealerConfirm = ({ bid, userData }) => {
  selectedDealer.value = { ...bid, userData };
  connectDealerModal.value = false;
};

const cancelSelection = () => { // 딜러 선택을 취소하는 함수
  selectedDealer.value = null;
};

const completeAuction = async () => { //딜러 선택 후 최종 낙찰가 경매 완료.
  auctionDetail.value.status = 'done';
  const id = route.params.id;
  const data = {
    status: 'done',
    final_price: selectedDealer.value.price,
  };

  try {
    await chosenDealer(id, data);
     auctionDetail.value.data.status = 'done';
  } catch (error) {
    console.error('Error completing auction:', error);
    alert('경매에 실패했습니다.');
  }
};

/*const reauction = () => {
  if (!amount.value || isNaN(parseFloat(amount.value))) {
    alert('희망 가격을 입력해주세요.');
  } else {
    const koreanPrice = convertToKorean(amount.value);
    const confirmReauction = confirm(`입력한 희망 가격: ${koreanPrice} 원입니다. 재경매를 진행하시겠습니까?`);
    if (confirmReauction) {
      reauctionModal.value = true;
    }
  }
};
*/

const cancelAuction = () => { // 경매를 취소하는 함수
  auctionModalVisible.value = !auctionModalVisible.value;
};

const SuccesBid = () => { // 입찰 성공을 처리하는 함수
  succesbid.value = true;
};

const checkScroll = () => { // 스크롤 위치를 확인하는 함수
  const scrollY = window.scrollY;
  const windowHeight = document.documentElement.clientHeight;
  const totalHeight = document.documentElement.scrollHeight;

  if (scrollY + windowHeight >= totalHeight) {
    scrollButtonVisible.value = false;
  } else {
    scrollButtonVisible.value = true;
  }
};

onMounted(async () => { // 컴포넌트 마운트 시 라이프사이클 훅
  await getAuctions();
  const auctionId = parseInt(route.params.id);
  window.addEventListener('scroll', checkScroll);
  try {
    auctionDetail.value = await getAuctionById(auctionId);
    console.log('Auction Detail:', auctionDetail.value);
    console.log('Sorted Top Bids:', sortedTopBids.value);
  } catch (error) {
    console.error('Error fetching auction detail:', error);
  }
});

onUnmounted(() => { // 컴포넌트 언마운트 시 라이프사이클 훅
  window.removeEventListener('scroll', checkScroll);
});

const submitAuctionBid = () => { // 경매 입찰을 제출하는 함수
  if (!amount.value || isNaN(parseFloat(amount.value))) {
    alert('유효한 금액을 입력해주세요.');
  } else {
    submitBid(auctionDetail.value.data.id, amount.value, user.value.id);
  }
};

const populateHopePrice = () => { // 희망 가격을 채우는 함수
  if (auctionDetail.value && auctionDetail.value.data) {
    if (isSellChecked.value) {
      amount.value = auctionDetail.value.data.hope_price;
    } else {
      amount.value = '';
    }
  }
};

const isReadonly = computed(() => isSellChecked.value && amount.value !== ''); // 금액이 읽기 전용인지 확인하는 계산된 속성

watch([isSellChecked, auctionDetail], () => { // 변경 사항을 감지하는 Watcher들
  populateHopePrice();
});

</script>

<style scoped>
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





.input-container {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #ccc;
    padding: 5px 10px;
    width: 100%; /* 전체 너비 사용 */
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
</style>