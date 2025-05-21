<template>
  <div class="d-flex flex-column flex-md-row justify-content-between sticky-top">
    <h4><span class="admin-icon admin-icon-menu03"></span>매물 관리</h4>

    <div class="d-flex flex-column flex-md-row justify-content-between pc-bottoms">
      <div style="margin-right: 5px;"> 
        <button class="btn btn-primary btn-sm" @click="updateAuction(auctionId, auction)">저장</button>
      </div>
      <div style="margin-right: 5px;"> 
        <button class="btn btn-success btn-sm" @click="updateAuction(auctionId, {'status': 'diag'})">진단대기</button>
      </div>
      <div class="dropdown">
        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          기타메뉴
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#" @click="updateAuction(auctionId, {'status': 'ing'})">경매진행</a></li>
          <li><a class="dropdown-item" href="#" @click="AuctionIsDeposit('totalDeposit')">차량대금 입금처리</a></li>
          <li><a class="dropdown-item" href="#" @click="checkNameChangeDealerEvent(auction.hashid)">명의이전 알림(딜러)</a></li>
          <li><a class="dropdown-item" href="#" @click="AuctionIsDeposit('totalAfterFee')">수수료 입금처리</a></li>
          <li><a class="dropdown-item" href="#" @click="checkNameChangeStatusEvent(auction.hashid)">명의이전신청 확인</a></li>
          <li><a class="dropdown-item" href="#" @click="DiagnosticAuctionCheck(auction.hashid)">진단상태 강제갱신</a></li>
        </ul>
      </div>
      <div style="margin-left: 5px;">
        <button class="btn btn-primary btn-sm" @click="rollbackAuctionBtn(auctionId)">롤백 (개발용)</button>
      </div>
    </div>
    
  </div>

  <div class="container my-5">
  <div class="container-fluid" v-if="auctionDetails">
    <form @submit.prevent="updateAuction(auctionId, auction)">
      <div>
        <div class="container my-4 mov-wide">
          <div>
            <div class="mb-4">

              <div class="sell-info">
                  <div class="car-image-style">
                    <div class="card-img-top-ty01" :style="{ backgroundImage: `url(${auction.car_thumbnail})` }">
                      <!-- <img :src="auction.car_thumbnail" alt="차량 사진" class="mb-2"> -->
                    </div>
                  </div>
                  <div class="car-info">


                      <div class="item">
                        <span class="label">차량번호</span>
                        <span class="value">{{ auction.car_no }}</span>
                        <input type="hidden" v-model="auction.car_no" id="car_no">
                      </div>
                      <div class="item">
                        <span class="label">소유자명</span>
                        <span class="value">{{ auction.owner_name }}</span>
                        <input type="hidden" v-model="auction.owner_name" id="owner_name">
                      </div>
                      <div class="item">
                          <span class="label">등록일자</span>
                          <span class="value">{{ created_at }}</span>
                          <input type="hidden" v-model="created_at" id="created_at">
                      </div>
                      <div class="item">
                          <span class="label">최종 수정일자</span>
                          <span class="value">{{ updated_at }}</span>
                          <input type="hidden" v-model="updated_at" id="updated_at">
                      </div>           
                      <div class="item">
                        <span class="label">진단상태</span>
                        <span class="value">{{ diagInfo.diag_error_msg ? diagInfo.diag_error_msg : diagInfo.diag_status }}</span>
                        <!-- <input type="hidden" v-model="diagInfo.diag_error_msg" id="diag_error_msg"> -->
                      </div>           
                   </div>
              </div>

              <div class="card my-auction">
                <!-- <div v-if="!isMobileView" class="img-container">
                  <div class="img-wrapper">
                    <img :src="auction.car_thumbnail" alt="차량 사진" class="mb-2">
                  </div>
                </div>
                <div v-if="isMobileView" class="img-container">
                  <div class="img-wrapper">
                    <img :src="auction.car_thumbnail" alt="차량 사진" class="mb-2">
                  </div>
                </div> -->
                <!--
                <div class="card-body">
                  <p class="text-secondary opacity-50">수정일자</p>
                  <input v-model="auction.updated_at" id="updatedAt" class="form-control" type="datetime-local">
                </div>-->


                <!-- <div class="card-body">
                  <p class="text-secondary opacity-50">차량번호</p>
                  <input v-model="auction.car_no" id="car_no" class="form-control"/>
                </div>
                <div class="card-body">
                  <p class="text-secondary opacity-50">등록일자</p>
                  <input v-model="created_at" id="bank" class="input-dis form-control" readonly/>
                </div>
                <div class="card-body">
                  <p class="text-secondary opacity-50">최종 수정일자</p>
                  <input v-model="updated_at" id="bank" class="input-dis form-control" readonly/>
                </div>
                <div class="card-body">
                  <p class="text-secondary opacity-50">소유자명</p>
                  <input v-model="auction.owner_name" id="owner_name" class="form-control"/>
                </div> -->

                <div class="row">
                  <div class="col-lg-6">


                    <div class="card-body">
                      <p class="text-secondary opacity-50">상태</p>
                      <select class="form-select" :v-model="auction.status" @change="changeStatus($event)" id="status">
                        <option v-for="(label, value) in statusLabel" :key="value" :value="value">{{ label }}</option>
                      </select>
                      <p class="opacity-50 text-red">*신청완료 -> 진단대기 -> 경매진행 순서로 진단대기를 거쳐야 경매마감일자가 나옵니다. </p>
                    </div>

                    <div class="card-body">
                      <p class="text-secondary opacity-50">고객사코드</p>
                      <input v-model="auction.hashid" type="text" id="hashid" placeholder="고객사코드" class="form-control"/>
                    </div>

                    <div class="card-body">
                      <p class="text-secondary opacity-50">은행</p>
                      <input v-model="auction.bank" type="text" id="bank" placeholder="은행 선택" @click="handleBankLabelClick" class="input-dis form-control" readonly>
                    </div>
                    <!--<component :is="currentComponent" v-bind:id="dynamicId"></component>-->
                  <!--  <BankModal  :showDetails="showDetails" @update:showDetails="showDetails = $event" @select-bank="selectBank" />-->
                    <div class="card-body">
                      <p class="text-secondary opacity-50">계좌번호</p>
                      <input v-model="auction.account" id="account" class="form-control"/>
                    </div>
                    <div class="card-body">
                      <p class="text-secondary opacity-50">메모</p>
                      <textarea v-model="auction.memo" id="memo" class="form-control" rows="3"></textarea>
                    </div>


                    <div class="card-body">
                      <p class="text-secondary opacity-50">지역</p>
                      <select class="form-select" :v-model="auction.region" @change="changeRegion" id="region">
                        <option value="" selected>시/도 선택</option>
                        <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
                      </select>
                    </div>
                    <div class="card-body">
                      <p class="text-secondary opacity-50">우편주소</p>
                      <input v-model="auction.addr_post" placeholder="우편번호" class="input-dis form-control" readonly>
                      <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')" style="right:32px !important">검색</button>
                      <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                        <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
                      </div>
                      <div>
                        <input v-model="auction.addr1" class="input-dis form-control" readonly>
                      </div>
                    </div>
                    <div class="card-body">
                      <p class="text-secondary opacity-50">상세주소</p>
                      <input v-model="auction.addr2" class="form-control" id="addr2">
                    </div>
                    <!--
                    <div class="card-body">
                      <p class="text-secondary opacity-50">경매마감일</p>
                      <input v-model="auction.final_at" id="finalAt" class="form-control" type="datetime-local">
                    </div>
                    -->


                    <div class="mb-3">
                    <div class="card-body">
                      <label for="user-title" class="form-label">자동차등록증</label>
                      <input type="file" @change="handleFileUploadCarLicense" ref="fileAuctionCarLicense" style="display:none">
                      <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadCarLicense">
                          파일 첨부
                      </button>
                      <div class="text-start mb-5 text-secondary opacity-50" v-if="fileAuctionCarLicenseFileList.length > 0 || fileCarLicenseUrl">
                        자동차등록증 : 
                        <li v-for="(file, index) in fileAuctionCarLicenseFileList" :key="index">
                            <a :href=file.original_url download>{{ file.file_name }}</a><span class="icon-close-img cursor-pointer" @click="triggerFileDelete(file.uuid)"></span>
                        </li>
                        <li v-if="fileCarLicenseUrl">
                          <a :href=fileCarLicenseUrl download>{{ auction.file_auction_car_license_name }}</a><span class="icon-close-img cursor-pointer" @click="triggerCarLicenseFileDelete()"></span>
                        </li>
                      </div>
                    </div>
                    </div>
                    <div class="mb-3">
                      <div class="card-body">
                      <label for="user-title" class="form-label">위임장 or 소유자 인감 증명서</label>
                      <input type="file" @change="handleFileUploadProxy" ref="fileAuctionProxy" style="display:none">
                      <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadProxy">
                          파일 첨부
                      </button>
                      <div class="text-start mb-5 text-secondary opacity-50" v-if="fileAuctionProxyFileList.length > 0 || fileProxyUrl">
                        매매업체 대표증 / 종사원증 : 
                        <li v-for="(file, index) in fileAuctionProxyFileList" :key="index">
                            <a :href=file.original_url download>{{ file.file_name }}</a><span class="icon-close-img cursor-pointer" @click="triggerFileDelete(file.uuid)"></span>
                        </li>
                        <li v-if="fileProxyUrl">
                          <a :href=fileProxyUrl download>{{ auction.file_auction_proxy_name }}</a><span class="icon-close-img cursor-pointer" @click="triggerProxyFileDelete()"></span>
                        </li>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group dealer-check fw-bolder pb-2">
                      <p for="dealer">법인 / 사업자차량</p>
                      <div class="check_box">
                        <input type="checkbox" id="ch2" v-model="auction.isBizChecked" class="form-control">
                        <label for="ch2"></label>
                      </div>
                    </div>


                    <div class="card-body">
                      <p class="text-secondary opacity-50">진단희망일</p>
                      <input v-model="auction.diag_first_at" id="diagFirstAt" class="form-control" type="datetime-local">
                      <input v-model="auction.diag_second_at" id="diagSecondAt" class="form-control" type="datetime-local">
                    </div>
                    
                    <div class="card-body">
                      <p class="text-secondary opacity-50">선택일</p>
                      <input v-model="auction.choice_at" id="choiceAt" class="form-control" type="datetime-local">
                    </div>
                    <div class="card-body">
                      <p class="text-secondary opacity-50">탁송희망일</p>
                      <input v-model="auction.taksong_wish_at" id="choiceAt" class="form-control" type="datetime-local">
                    </div>
                    <div class="card-body">
                      <p class="text-secondary opacity-50">완료일</p>
                      <input v-model="auction.done_at" id="taksongWishAt" class="form-control" type="datetime-local">
                    </div>
                    

                  </div>
                  <div class="col-lg-6">

                    <!-- <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <p class="text-secondary opacity-50">희망가</p>
                        <p class="d-flex justify-content-end text-secondary opacity-50 p-2">{{ hopePriceFeeKorean }}</p>
                      </div>
                      <input v-model="auction.hope_price" id="hopePrice" class="form-control" @input="updateKoreanAmount('hopePrice')">
                    </div> -->

                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <p class="text-secondary opacity-50">낙찰가</p>
                        <p class="d-flex justify-content-end text-secondary opacity-50 p-2">{{ finalPriceFeeKorean }}</p>
                      </div>
                      <input v-model="auction.final_price" id="finalPrice" class="form-control" @input="updateKoreanAmount('finalPrice')">
                    </div>
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <p class="text-secondary opacity-50">성공수수료</p>
                        <p class="d-flex justify-content-end text-secondary opacity-50 p-2">{{ successFeeKorean }}</p>
                      </div>
                      <input v-model="auction.success_fee" id="successFee" class="form-control" @input="updateKoreanAmount('successFee')">
                    </div>
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <p class="text-secondary opacity-50">진단수수료</p>
                        <p class="d-flex justify-content-end text-secondary opacity-50 p-2">{{ diagFeeKorean }}</p>
                      </div>
                      <input v-model="auction.diag_fee" id="diagFee" class="form-control" @input="updateKoreanAmount('diagFee')">
                    </div>
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <p class="text-secondary opacity-50">총 비용</p>
                        <p class="d-flex justify-content-end text-secondary opacity-50 p-2">{{ totalFeeKorean }}</p>
                      </div>
                      <input v-model="auction.total_fee" id="totalFee" class="form-control" @input="updateKoreanAmount('totalFee')">
                    </div>
                    
                    
                    <div class="card-body">
                      <div class="form-group my-3">
                        <label for="dealer">인수차량 도착지 주소</label>
                        <input type="text" v-model="auction.dest_addr_post" class="input-dis form-control" readonly />
                        <button type="button" class="search-btn" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">검색</button>
                        <div id="daumPostcodeDealerReceiveInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                          <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeDealerReceiveInput')">
                        </div>
                        <div class="input-with-button">
                          <input type="text" v-model="auction.dest_addr1" class="input-dis form-control" readonly />
                        </div>
                        <input type="text" v-model="auction.dest_addr2" class="form-control" />
                      </div>
                    </div>

                    <div class="card-body">
                      <p class="text-secondary opacity-50">인수자 연락처</p>
                      <input v-model="auction.customTel1" id="customTel1" class="form-control" />
                    </div>

                    <div class="card-body" v-if="auction.dealer">
                      <p class="text-secondary opacity-50">매입자 정보</p>
                      <div>
                        
                        <div class="car-info">
                          <div class="item">
                            <span class="label">성명</span>
                            <span class="value">{{ auction.dealer_name }}</span>
                          </div>
                          <div class="item">
                            <span class="label">연락처</span>
                            <span class="value">{{ auction.dealer?.phone }}</span>
                          </div>
                          <div class="item">
                            <span class="label">소속</span>
                            <span class="value">{{ auction.dealer?.company }}</span>
                          </div>
                          <div class="item">
                            <span class="label">직책</span>
                            <span class="value">{{ auction.dealer?.company_duty }}</span>
                          </div>
                          <div class="item">
                            <span class="label">주소</span>
                            <span class="value">{{ '(' + auction.dealer?.company_post + ')' + ' ' + auction.dealer?.company_addr1 + ' ' + auction.dealer?.company_addr2 }}</span>
                          </div>
                        </div>
                        
                      </div>
                    </div>


                    <div class="mb-3">
                      <div class="card-body">
                      <label for="user-title" class="form-label">매도관련서류</label>
                      <input type="file" @change="handleFileUploadOwner" ref="fileAuctionOwner" style="display:none">
                      <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadOwner">
                          파일 첨부
                      </button>
                      <div class="text-start mb-5 text-secondary opacity-50" v-if="fileAuctionOwnerFileList.length > 0 || fileOwnerUrl">
                        매매업체 대표증 / 종사원증 : 
                        <li v-for="(file, index) in fileAuctionOwnerFileList" :key="index">
                            <a :href=file.original_url download>{{ file.file_name }}</a><span class="icon-close-img cursor-pointer" @click="triggerFileDelete(file.uuid)"></span>
                        </li>
                        <li v-if="fileOwnerUrl">
                          <a :href=fileOwnerUrl download>{{ auction.file_auction_owner_name }}</a><span class="icon-close-img cursor-pointer" @click="triggerOwnerFileDelete()"></span>
                        </li>
                      </div>
                      </div>
                    </div>
                    

                  </div>
                </div>


              </div>
            </div>
          </div>

          <div class="mobile-bottoms">

            <div class="mt-3" @click.stop="">
                <div class="mt-3">
                  <button class="btn btn-primary tc-wh w-100"> 저장 </button>
                </div>
            </div>


            <div class="mt-3">
              <div class="mt-3">
                <button type="button" class="btn btn-success w-100" @click="diagAuction('diag')"> 진단대기 </button>
              </div>
            </div>


            <!-- 테스트 메뉴 -->
            <hr>
            <div>
              <label class="form-label">테스트 메뉴</label>
            </div>

            <div class="mt-3">
              <div class="mt-3">
                <button type="button" class="btn btn-secondary w-100" @click="diagAuction('ing')"> 경매진행 </button>
              </div>
            </div>

            <div class="mt-3">
              <div class="mt-3">
                <button type="button" class="btn btn-secondary w-100" @click="AuctionIsDeposit('totalDeposit')"> 입금완료 </button>
              </div>
            </div>

            <div class="mt-3">
              <div class="mt-3">
                <button type="button" class="btn btn-secondary w-100" @click="AuctionIsDeposit('totalAfterFee')"> 수수료 입금완료 </button>
              </div>
            </div>

          </div>

          

          <!--
          <div @click="toggleVisibility" class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h5>차량정보</h5>
            <img :src="isVisible ? hideIcon : showIcon" :alt="isVisible ? '숨기기' : '더보기'" class="toggle-icon" width="20px" height="10px" />
          </div>-->
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
              <div></div>
              <ul class="machine-inform-title">
                <li class="text-secondary opacity-50">추가옵션</li>
                <li class="info-num">-</li>
              </ul>
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
                  <textarea class="input-dis custom-textarea mt-2" readonly style="resize: none;">{{ auctionDetails.data.memo }}</textarea>
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
            </div>
          </transition>
        </div>
      </div>
    </form>
  </div>
  </div>
</template>
<script setup>
import { ref, onMounted, reactive, watchEffect, onBeforeUnmount, nextTick, inject, createApp, defineComponent, h } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router';
import file from "@/components/file.vue";
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';
import hideIcon from '../../../../../resources/img/Icon-black-down.png';
import showIcon from '../../../../../resources/img/Icon-black-up.png';
import { regions, updateDistricts } from '@/hooks/selectBOX.js';

const showDetails = ref(false); 
const isActive = ref(false);
const isMobileView = ref(window.innerWidth <= 640);
const checkScreenWidth = () => {
  if (typeof window !== 'undefined') {
    isMobileView.value = window.innerWidth <= 640;
  }
};
const auction = reactive({ 
  bank: '',
  account: '',
  car_no: '',
  owner_name: '',
  memo: '',
  region: '',
  addr_post: '',
  status: '',
  addr1: '',
  addr2: '',
  choice_at: '',
  taksong_wish_at:'',
  done_at: '',
  success_fee: '',
  diag_fee: '',
  total_fee: '',
  hope_price: '',
  final_price: '',
  file_auction_proxy: '',
  file_auction_proxy_name: '',
  file_auction_owner: '',
  file_auction_owner_name: '',
  file_auction_car_license: '',
  file_auction_car_license_name: '',
  deletFileList:'',
  isBizChecked:false,
  dest_addr_post:'',
  dest_addr1:'',
  dest_addr2:'',
  diag_first_at:'',
  diag_second_at:'',
  car_thumbnail:'',
  hashid:''
});

const diagInfo = ref('');

const route = useRoute();
const router = useRouter();
const { 
  getAuctionById, 
  updateAuctionStatus, 
  isLoading, 
  updateAuction, 
  updateAuctionIsDeposit, 
  diagnostic, 
  checkNameChangeStatus, 
  checkNameChangeDealer,
  rollbackAuction
} = useAuctions();
const auctionId = route.params.id; 
const auctionDetails = ref(null);
const isVisible = ref(false);
const showBottomSheet = ref(true);
const bottomSheetStyle = ref({ position: 'fixed', bottom: '0px' });
const isFileAttached = ref(false);
const { openPostcode, closePostcode, amtComma, formatCurrency, wicas, wica } = cmmn();
const successFeeKorean = ref('0 원');
const diagFeeKorean = ref('0 원');
const totalFeeKorean = ref('0 원');
const hopePriceFeeKorean = ref('0 원');
const finalPriceFeeKorean = ref('0 원');
const fileAuctionProxy = ref(null);
const fileAuctionOwner = ref(null);
const swal = inject('$swal');
const fileOwnerUrl = ref('');
const fileProxyUrl =ref('');
const fileAuctionProxyList = ref([]);
const fileAuctionProxyFileList = ref([]);
const fileAuctionOwnerList = ref([]);
const fileAuctionOwnerFileList = ref([]);
const fileAuctionCarLicense = ref(null);
const fileAuctionCarLicenseList = ref([]);
const fileAuctionCarLicenseFileList = ref([]);
const fileCarLicenseUrl = ref('');
let created_at;
let updated_at;

const store = useStore();

let statusLabel;

const handleBankLabelClick = async () => {
  const module = await import('@/views/modal/bank/BankModal.vue');
  const BankModalComponent = module.default;

  swal.fire({
    html: '<div id="modal-content"></div>',
    customClass: 'bank-modal',
    showCloseButton: true,
    showConfirmButton: false,
    width: '600px',
    padding: '20px',
    didOpen: () => {
      nextTick(() => {
        const modalContent = document.getElementById('modal-content');
        if (modalContent) {
          const app = createApp(BankModalComponent, {
            onSelectBank: (bankName) => {
              auction.bank = bankName;
              swal.close();
            }
          });
          app.mount(modalContent);
        }
      });
    }
  });
};

const updateKoreanAmount = (price) => {
  if (price == 'successFee') {
    successFeeKorean.value = formatCurrency(auction.success_fee);
  }
  if (price == 'diagFee') {
    diagFeeKorean.value = formatCurrency(auction.diag_fee);
  }
  if (price == 'totalFee') {
    totalFeeKorean.value = formatCurrency(auction.total_fee);
  }
  if (price == 'hopePrice') {
    hopePriceFeeKorean.value = amtComma(auction.hope_price);
  }
  if (price == 'finalPrice') {
    finalPriceFeeKorean.value = amtComma(auction.final_price);
  }
};

function changeStatus(event) {
  auction.status = event.target.value;
}

function changeRegion(event) {
  auction.region = event.target.value;
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

function fileExstCheck(info){
    if(info.hasOwnProperty('files')){
      if(info.files.hasOwnProperty('file_auction_proxy')){
        fileAuctionProxyList.value = info.files.file_auction_proxy;
          if(fileAuctionProxyList.value.length>0){
            for(let i=0; fileAuctionProxyList.value.length>i; i++){
              fileAuctionProxyFileList.value.push({
                original_url: fileAuctionProxyList.value[i].original_url,
                file_name: fileAuctionProxyList.value[i].file_name,
                uuid: fileAuctionProxyList.value[i].uuid
              })
            }
          }
          /*
          if(info.files.file_auction_proxy[0].hasOwnProperty('original_url')){
            fileProxyUrl.value = info.files.file_auction_proxy[0].original_url;
            auction.file_auction_proxy_name = info.files.file_auction_proxy[0].file_name;
          }
          */
      }

      if(info.files.hasOwnProperty('file_auction_owner')){
        fileAuctionOwnerList.value = info.files.file_auction_owner;
          if(fileAuctionOwnerList.value.length>0){
            for(let i=0; fileAuctionOwnerList.value.length>i; i++){
              fileAuctionOwnerFileList.value.push({
                original_url: fileAuctionOwnerList.value[i].original_url,
                file_name: fileAuctionOwnerList.value[i].file_name,
                uuid: fileAuctionOwnerList.value[i].uuid
              })
            }
          }
          /*
          if(info.files.hasOwnProperty('file_auction_owner')){
            if(info.files.file_auction_owner[0].hasOwnProperty('original_url')){
              fileOwnerUrl.value = info.files.file_auction_owner[0].original_url;
              auction.file_auction_owner_name = info.files.file_auction_owner[0].file_name;
            }
          }
          */
      }

      if(info.files.hasOwnProperty('file_auction_car_license')){
        fileAuctionCarLicenseList.value = info.files.file_auction_car_license;
        if(fileAuctionCarLicenseList.value.length>0){
          for(let i=0; fileAuctionCarLicenseList.value.length>i; i++){
            fileAuctionCarLicenseFileList.value.push({
              original_url: fileAuctionCarLicenseList.value[i].original_url,  
              file_name: fileAuctionCarLicenseList.value[i].file_name,
              uuid: fileAuctionCarLicenseList.value[i].uuid
            })
          }
        }
      }
      

      
    }
}

function triggerFileDelete(fileUuid){
  auction.deletFileList +=fileUuid+',';
  fileAuctionProxyFileList.value = fileAuctionProxyFileList.value.filter(file => file.uuid !== fileUuid);
  fileAuctionOwnerFileList.value = fileAuctionOwnerFileList.value.filter(file => file.uuid !== fileUuid);
  fileAuctionCarLicenseFileList.value = fileAuctionCarLicenseFileList.value.filter(file => file.uuid !== fileUuid);
}

function triggerOwnerFileDelete(){
  auction.file_auction_owner = '';
  auction.file_auction_owner_name = '';
  fileOwnerUrl.value = '';
}

function triggerProxyFileDelete(){
  auction.file_auction_proxy = '';
  auction.file_auction_proxy_name = '';
  fileProxyUrl.value = '';
}

function triggerCarLicenseFileDelete(){
  auction.file_auction_car_license = '';
  auction.file_auction_car_license_name = '';
  fileCarLicenseUrl.value = '';
}

const fetchAuctionDetails = async () => {
  try {
    const id = route.params.id;
    const data = await getAuctionById(id);
    auctionDetails.value = data;
    fileExstCheck(data.data);
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
    await updateAuctionStatus(id, 'diag');
    router.push({ name: 'auctions.index' }); 
    alert('등록되었습니다.');
  } catch (error) {
    console.error('Error updating auction status:', error);
    alert('등록에 실패했습니다.');
  }
};

const diagAuction = async (status) => {

  const confirm = await swal.fire({
    title: '경매진행 상태 변경',
    text: '경매진행 상태로 변경하시겠습니까?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: '예',
    cancelButtonText: '아니오'
  });

  if (confirm.isConfirmed) {
    try {
      const id = route.params.id;
      await updateAuctionStatus(id, status);

      // TODO: 코드 수정 필요 / 상태값 정확히 확인 해서 알림 처리.
      // router.push({ name: 'auctions.index' }); 
      alert('상태가 변경되었습니다.');
      // status == 'ing' ? alert('경매진행으로 상태가 변경되었습니다.') : alert('진단대기로 상태가 변경되었습니다.');
      
    } catch (error) {
      console.error('Error updating auction status:', error);
      alert('등록에 실패했습니다.');
    }
  }else{
    return;
  }

}

const AuctionIsDeposit = async (IsDeposit) => {
  
  const confirm = await swal.fire({
    title: '상태 변경',
    text: '상태를 변경하시겠습니까?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: '예',
    cancelButtonText: '아니오'
  });

  if (confirm.isConfirmed) {
  
    try {
      const id = route.params.id;
      await updateAuctionIsDeposit(id, IsDeposit);
      router.push({ name: 'auctions.index' }); 
      alert('등록되었습니다.');
    } catch (error) {
      console.error('Error updating auction status:', error);
      alert('등록에 실패했습니다.');
    }

  }else{
    return;
  }
  
  
  
}

function editPostCode(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
      auction.addr_post = zonecode;
      auction.addr1 = address;
    })
}

function handleFileUploadProxy(event) {
  const file = event.target.files[0];
  if (file) {
    auction.file_auction_proxy = file;
    auction.file_auction_proxy_name = file.name;
    fileProxyUrl.value = URL.createObjectURL(file);
  }
}

function handleFileUploadOwner(event) {
  const file = event.target.files[0];
  if (file) {
    auction.file_auction_owner = file;
    auction.file_auction_owner_name = file.name;
    fileOwnerUrl.value = URL.createObjectURL(file);
  }
}

function triggerFileUploadProxy() {
  if (fileAuctionProxy.value) {
    fileAuctionProxy.value.click();
  } else {
    console.error("위임장 또는 소유자 인감 증명서 파일을 찾을 수 없습니다.");
  }
}

function triggerFileUploadOwner() {
  if (fileAuctionOwner.value) {
    fileAuctionOwner.value.click();
  } else {
    console.error("위임장 또는 소유자 인감 증명서 파일을 찾을 수 없습니다.");
  }
}

function triggerFileUploadCarLicense() {
  if (fileAuctionCarLicense.value) {
    fileAuctionCarLicense.value.click();
  } else {
    console.error("자동차등록증 파일을 찾을 수 없습니다.");
  }
}

onMounted(async () => {
  statusLabel = wicas.enum(store).auctions();
  window.addEventListener('resize', checkScreenWidth);
  checkScreenWidth();
  await fetchAuctionDetails();
  const data = auctionDetails.value.data;
  document.getElementById("status").value = data.status;
  watchEffect(() => {
    updated_at = data.updated_at;
    created_at = data.created_at;
    auction.car_no = data.car_no;
    auction.owner_name = data.owner_name;
    auction.status = data.status;
    document.getElementById("status").value = data.status;
    auction.region = data.region;
    document.getElementById("region").value = data.region;
    auction.bank = data.bank;
    auction.account = data.account;
    auction.memo = data.memo;
    auction.addr_post = data.addr_post;
    auction.addr1 = data.addr1;
    auction.addr2 = data.addr2;
    auction.customTel1 = data.customTel1;
    auction.hashid = data.hashid;
    // auction.diag_first_at = data.diag_first_at;
    if(data.diag_first_at){
      const formattedValue = data.diag_first_at.replace(" ", "T").substring(0, 16);  // 형식 변환
      auction.diag_first_at = formattedValue;
    }
    // auction.diag_second_at = data.diag_second_at;
    if(data.diag_second_at){
      const formattedValue = data.diag_second_at.replace(" ", "T").substring(0, 16);  // 형식 변환
      auction.diag_second_at = formattedValue;
    }
    auction.car_thumbnail = data.car_thumbnail;
    //auction.final_at = data.final_at;

    if(data.choice_at){
      const formattedValue = data.choice_at.replace(" ", "T").substring(0, 16);  // 형식 변환
      auction.choice_at = formattedValue;
    }
    if(data.done_at){
      const formattedValue = data.done_at.replace(" ", "T").substring(0, 16);  // 형식 변환
      auction.done_at = formattedValue;
    }
    if(data.taksong_wish_at){
      const formattedValue = data.taksong_wish_at.replace(" ", "T").substring(0, 16);  // 형식 변환
      auction.taksong_wish_at = formattedValue;
    }

    console.log('data', data);

    auction.success_fee = data.success_fee;
    auction.diag_fee = data.diag_fee;
    auction.total_fee = data.total_fee;
    auction.hope_price = data.hope_price;
    auction.final_price = data.final_price;
    auction.bank = data.bank;
    auction.account = data.account;
    auction.dest_addr_post = data.dest_addr_post;
    auction.dest_addr1 = data.dest_addr1;
    auction.dest_addr2 = data.dest_addr2;
    auction.dealer_name = data.dealer_name;
    auction.dealer = data.dealer;
    // auction.dealer_tel = data.phone;
    if(data.is_biz == 1){
      auction.isBizChecked = true;
    }
  
    if (data.success_fee) {
      successFeeKorean.value = formatCurrency(data.success_fee);
    }
    if (data.diag_fee) {
      diagFeeKorean.value = formatCurrency(data.diag_fee);
    }
    if (data.total_fee) {
      totalFeeKorean.value = formatCurrency(data.total_fee);
    }
    if (data.hope_price) {
      hopePriceFeeKorean.value = amtComma(data.hope_price);
    }
    if (data.final_price) {
      finalPriceFeeKorean.value = amtComma(data.final_price);
    }
  });

  DiagnosticAuctionCheck(auction.hashid);

});


// 진단상태 확인 
const DiagnosticAuctionCheck = (hashid) => {

  const id = hashid;

  diagnostic(id).then((res) => {
    if(res){
      console.log('res', res);

      const data = 
        {
          'diag_car_no': res.data.data.diag_car_no,
          'diag_status': res.data.data.diag_status == 'done' ? '진단완료' : '-',
          'diag_done_at': res.data.data.diag_done_at,
          'diag_outer_id': res.data.data.diag_outer_id,
          'diag_check_at': res.data.data.diag_check_at,
          // 본사검수확인 
          'diag_is_confirmed': res.data.diag_is_confirmed,
          'diag_error_msg': res.data.status === 'error' ? res.data.message : ''
        }
      ;

      diagInfo.value = data;


      console.log('data!!', diagInfo.value);

    }else{
      console.log('조회실패');
    }
  });
}

// 명의이전신청 확인 
const checkNameChangeStatusEvent = async (auctionId) => {
  const result = await checkNameChangeStatus(auctionId);
  console.log('result', result);

  if(result.success === true){
    alert('명의이전신청 확인 완료');
    console.log('명의이전신청 확인 완료');
  }else{
    alert('명의이전신청 확인 실패 / 딜러가 명의이전 신청 했는지 확인이 필요 합니다.');
    console.log('명의이전신청 확인 실패');
  }
}

// 명의이전신청 알림보내기 
const checkNameChangeDealerEvent = async (auctionId) => {
  const result = await checkNameChangeDealer(auctionId);
  console.log('checkNameChangeDealer ? ', auctionId);

  if(result.success === 'alert_sent'){
    alert('명의이전신청 알림보내기 완료');
    console.log('명의이전신청 알림보내기 완료');
  }else{
    alert('명의이전신청 알림보내기 실패');
  }
}

function editPostCodeReceive(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
      auction.dest_addr_post = zonecode;
      auction.dest_addr1 = address;
    });
}

const rollbackAuctionBtn = async (auctionId) => {
  console.log('rollbackAuction', auctionId);

  const confirm = await swal.fire({
    title: '롤백',
    text: '롤백하시겠습니까?',
    icon: 'warning',
  }); 

  if(confirm.isConfirmed){
    console.log('롤백 완료');

    const result = await rollbackAuction(auctionId);
    console.log('result', result.isSuccess);

    if(result.isSuccess === true){
      alert('롤백 완료');
    }else{
      alert('롤백 실패');
    }
  }else{
    // console.log('롤백 취소');
  }
}

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkScreenWidth);
});
</script>


<style scoped>
/* 이미지 스타일 */
.img-wrapper {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
}
.img-container {
  width: 100%; 
  height: 500px;
  display: flex; 
  justify-content: center;
  align-items: center;
  overflow: hidden; 
}
.img-wrapper img {
  width: 100%; 
  height: 100%; 
  object-fit: cover; 
}

.text-red{
  color: red;
}
.bottom-sheet {
  height: auto !important;
}
/* .card-img-top-ty01 {
  width: 100%;
  height: 160px;
  background-image: url('../../../../img/car_example.png');
  background-size: cover;
  background-position: center;
  border-radius: 6px;
} */

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

.card-body {
  margin-bottom: 10px;
}

.card-body:last-child {
  margin-bottom: 0; 
}
.img_box img{
  object-fit: cover;
  height: inherit;
}

.cursor-pointer{
  cursor: pointer;
}
.search-btn {
  transform: translateY(-119%) !important;
  right: 15px !important;
}
@media screen and (max-width: 767px) {
    .left-container{
        padding: 0px;
    }
    .container{
        --bs-gutter-x: 0rem !important;
        max-width: none;
    }
    .img-container{
        height: auto !important;
    }
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

.sticky-top {
  background-color: #fff !important;
  padding: 5px 0px !important;
}

button.btn {
  height: auto;
}

.mobile-bottoms {
  display: none;
}
@media screen and (max-width: 767px) {
  .mobile-bottoms {
    display: block;
  }
  .pc-bottoms {
    display: none !important;
  }
  .sticky-top {
    position: relative !important;
    top: 0 !important;
    z-index: 100 !important;
  }
} 

.col-lg-6 {
  position: relative;
}

</style>
