<template>
    <div class="container-fluid" v-if="auctionDetail" :style="{'padding-top': isDealer && isMobileView ? '70px' : '10px'}">
      <div v-if="isDealer || !chosendlvr && !auctionChosn && !showReauctionView && ((auctionDetail.data.status !== 'wait') && isUser)" class="container">
        <div class="web-content-style02">
          <div class="container p-1">
            <div>
              <div>
                  
                <div class="mb-2">
  
                  <div class="sheet-contents">
                    <div class="steps-container step-box mb-3">
                      <template v-for="(step, index) in steps" :key="index">
                        <!-- Step 요소 -->
                        <div 
                          class="step"
                          :class="{
                            'completed': getStatusIndex(step.status) < getStatusIndex(auctionDetail.data.is_taksong === 'done' ? (auctionDetail.data.status === 'done' ? 'done' : 'dlvrDone'): auctionDetail.data.status),
                            'completing': getStatusIndex(step.status) === getStatusIndex(auctionDetail.data.is_taksong === 'done' ? (auctionDetail.data.status === 'done' ? 'done' : 'dlvrDone'): auctionDetail.data.status)
                          }"
                        >
                          <div 
                            class="label"
                            :class="{
                              'completed': getStatusIndex(step.status) < getStatusIndex(auctionDetail.data.is_taksong === 'done' ? (auctionDetail.data.status === 'done' ? 'done' : 'dlvrDone'): auctionDetail.data.status),
                              'completing': getStatusIndex(step.status) === getStatusIndex(auctionDetail.data.is_taksong === 'done' ? (auctionDetail.data.status === 'done' ? 'done' : 'dlvrDone'): auctionDetail.data.status)
                            }"
                          >
                            {{ step.label }}
                          </div>
                          <div 
                            class="label label-style02"
                            :class="{
                              'completing-text': getStatusIndex(step.status) <= getStatusIndex(auctionDetail.data.is_taksong === 'done' ? (auctionDetail.data.status === 'done' ? 'done' : 'dlvrDone'): auctionDetail.data.status),
                              'text-secondary opacity-50': getStatusIndex(step.status) > getStatusIndex(auctionDetail.data.is_taksong === 'done' ? (auctionDetail.data.status === 'done' ? 'done' : 'dlvrDone'): auctionDetail.data.status)
                            }"
                          >
                            {{ step.text }}
                          </div>
                        </div>
  
                        <!-- Line 요소 -->
                        <div 
                          v-if="index < steps.length - 1" 
                          class="line" 
                          :class="{ 'completed': getStatusIndex(steps[index + 1].status) <= getStatusIndex(auctionDetail.data.is_taksong === 'done' ? (auctionDetail.data.status === 'done' ? 'done' : 'dlvrDone'): auctionDetail.data.status) }"
                        ></div>
                      </template>
                    </div>
                  </div>
  
  
                  <div class="card my-auction">
                    <div>
                      <div class="mb-3 px-0" v-if="auctionDetail.data.status === 'ask'">
                        <div class="diag-img">
                          <p class="diag-text tc-gray mb-4">{{ wicaLabel.title() }}이 진단대기 상태에요</p>
                          <span v-if="auctionDetail.data.status === 'diag'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                          <span v-if="auctionDetail.data.status === 'ask'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                        </div>
                      </div>
                      <div v-else-if="auctionDetail.data.status === 'diag'">
                        <div class="diag-img">
                          <p class="diag-text tc-gray mb-4">{{ wicaLabel.title() }}이 꼼꼼하게 진단 중이에요</p>
                          <span v-if="auctionDetail.data.status === 'diag'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                          <span v-if="auctionDetail.data.status === 'ask'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                        </div>
                      </div>
                      <div v-else>
                        <span v-if="auctionDetail.data.status === 'ing'" class="mx-2 timer">
                          <img src="../../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">
                          <span v-if="timeLeft.days != '0'">{{ timeLeft.days }}일 &nbsp;</span>{{ timeLeft.hours }} : {{ timeLeft.minutes }} : {{ timeLeft.seconds }}
                        </span>
                        <span v-if="auctionDetail.data.status === 'dlvr'" class="mx-2 auction-done bg-info">{{ auctionDetail.data.is_taksong === 'done' ? '탁송완료' : wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                        <span v-if="auctionDetail.data.status === 'done'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                        <span v-if="auctionDetail.data.status === 'cancel'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                        <span v-if="auctionDetail.data.status === 'chosen'" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auctionDetail.data.status).auctions() }}</span>
                        <div v-if="auctionDetail.data.status !== 'cancel' & !isUser">
                          <input class="toggle-heart" type="checkbox" :id="'favorite-' + auctionDetail.data.id"
                          :checked="auctionDetail.data.isFavorited" @click.stop="toggleFavorite(auctionDetail.data)"/>
                          <label class="heart-toggle" :for="'favorite-' + auctionDetail.data.id" @click.stop></label>
                        </div>
                        <div class="gap-1" :class="[{ 'grayscale_img': auctionDetail.data.status === 'done' || auctionDetail.data.status === 'cancel' }]">
                        <div v-if="!isMobileView" class="d-flex flex-row gap-1 img-container" :style="auctionDetail.data.car_thumbnails ? '' : 'height:320px'">
  
  
                          <div v-if="auctionDetail.data.car_thumbnails" class="img-wrapper">
                              <!-- <img :src="thumbnail" alt="Car Image" v-for="thumbnail in auctionDetail.data.car_thumbnails" :key="thumbnail"> -->
  
  
                              <div id="carThumbnail" class="carousel slide">
                                <div class="carousel-inner">
                                  <div class="carousel-item" v-for="(thumbnail, index) in auctionDetail.data.car_thumbnails" :key="index" :class="{'active': index === 0}" @click.prevent="openGallery(index)">
                                    <img :src="thumbnail" alt="Car Image">
                                  </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carThumbnail" data-bs-slide="prev">
                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carThumbnail" data-bs-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Next</span>
                                </button>
                              </div>
  
                          </div>
                          <div v-else id="no-image" class="d-flex">
                            <img :src="auctionDetail.data.car_thumbnail" alt="Car Image" style="width: 100%; height: 100%; object-fit: cover;">
                          </div>
                        </div>
                        <div v-if="isMobileView">
                          <div v-if="auctionDetail.data.car_thumbnails" class="d-flex flex-row gap-1 img-container">
                            <!-- <div v-if="auctionDetail.data.car_thumbnails" class="img-wrapper">
                              <img :src="auctionDetail.data.car_thumbnails[0]" alt="Car Image">
                            </div> -->
  
  
                            <div id="carThumbnail" class="carousel slide">
                              <div class="carousel-inner">
                                <div class="carousel-item" v-for="(thumbnail, index) in auctionDetail.data.car_thumbnails" :key="index" :class="{'active': index === 0}">
                                  <img :src="thumbnail" alt="Car Image">
                                </div>
                              </div>
                              <button class="carousel-control-prev" type="button" data-bs-target="#carThumbnail" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                              </button>
                              <button class="carousel-control-next" type="button" data-bs-target="#carThumbnail" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                              </button>
                            </div>
  
                          </div>
                          <div id="no-image" class="d-flex" v-else>
                            <div class="card-img-top-ty02">
                              <img :src="auctionDetail.data.car_thumbnail" alt="Car Image" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                          </div>
                        </div>
                      </div>
  
                        <h4 v-if="auctionDetail.data.status === 'done' || auctionDetail.data.status === 'chosen' || auctionDetail.data.status === 'dlvr' || auctionDetail.data.status === 'dlvrDone'" class="wait-selection">낙찰가 {{ amtComma(auctionDetail.data.final_price) }}</h4>
                        <div class="mt-2 pb-1 d-flex gap-3 justify-content-between me-1">
                          <div></div>
                          <div class="d-flex gap-3 justify-content-end align-items-center mb-1">
                            <div class="tc-gray icon-hit">조회수 {{ auctionDetail.data.hit }}</div>
                            <div class="tc-gray ml-2 icon-heart">관심 {{ auctionDetail.data.likes ? auctionDetail.data.likes.length : 0 }}</div>
                            <p class="tc-gray icon-bid" style="color:red !important;">입찰 {{ auctionDetail.data.bids_count }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-3 pt-0 ">
                      <!--<p>{{ auctionDetail.data.car_year ? auctionDetail.data.car_year : '2020' }} 년 | {{ auctionDetail.data.car_km ? auctionDetail.data.car_km : '2.4' }}km</p>-->
                      <!--<p class="text-secondary opacity-50">{{ auctionDetail.data.car_maker ? auctionDetail.data.car_maker +' '+ auctionDetail.data.car_model : '현대 소나타' }} ({{ auctionDetail.data.car_grade ? auctionDetail.data.car_grade : 'DN8' }})</p>-->
                      <div class="enter-view">
                        <AlarmModal ref="alarmModal" />
                      </div>
                      <div class="d-flex justify-content-between align-items-baseline">
                        <p class="card-title fs-4 fw-bolder">{{ auctionDetail.data.car_model ? auctionDetail.data.car_model +' '+ auctionDetail.data.car_model_sub +' '+ auctionDetail.data.car_fuel + ' ('+ auctionDetail.data.car_no +')' : '더 뉴 그랜저 IG 2.5 가솔린 르블랑' }}</p>
                        <h5 class="card-title"><span class="blue-box border-6">{{ isAccident(auctionDetail.data.is_accident) }}</span></h5>
                        <h5 v-if="auctionDetail.data.is_reauction !== 0"><span class="gray-box border-6">재경매</span></h5>
                        <h5 v-if="auctionDetail.data.is_biz !== 0"><span class="red-box-type03 border-6">법인 / 사업자</span></h5>
                      </div>
                     <div v-if="auctionDetail.data.status ==='chosen' || auctionDetail.data.status ==='dlvr' || auctionDetail.data.status ==='dlvrDone' && scsbid">
                       <!-- <hr>
                        <h4>탁송 신청 정보</h4>
                        <div class="fw-medium ">
                        <p class="mt-4 tc-gray">낙찰 딜러 :<span class="tc-red">&nbsp; 홍길동 딜러</span></p>
                        <p class="text-secondary opacity-50">낙&nbsp;&nbsp;  찰&nbsp;&nbsp;  액 : <span class="tc-red">&nbsp;3500만원</span></p>
                        <p class="text-secondary opacity-50">탁&nbsp;&nbsp; 송&nbsp;&nbsp; 일 : <span class="tc-red">&nbsp;2024년 6월 26일 오후 6:12</span></p>
                        </div>-->
                      </div>
  
                      <hr style="border-top: 1px dashed;"/>
  
                      
                      <div v-if="auctionDetail.data.status === 'chosen' && isDealer">
                        <p class="ac-evaluation btn-fileupload-red btn-shadow" @click.prevent="openCarLicenseModal">자동차등록증</p>
                      </div>
                      <div v-if="(auctionDetail.data.status === 'dlvr' || auctionDetail.data.status === 'dlvrDone') && isUser" class="mt-4">
                        <div>
                          <p class="ac-evaluation btn-fileupload-red btn-shadow" @click.prevent="openDealerLicenseModal">매수자 사업자등록증</p>
                        </div>
                      </div>
                      <div v-if="auctionDetail.data.status === 'dlvr' || auctionDetail.data.status === 'dlvrDone' && isUser" class="mt-4">
                        <p class="ac-evaluation btn-fileupload-red btn-shadow" @click.prevent="openNameChangeModal">명의이전 등록증</p>
                      </div>
                 </div>
  
  
                 
                  </div>
                </div>
              </div>
            </div>
            <div class="container">
              <h4 class="mb-4">차량 정보</h4>
              <div class="car-info-grid">
  
                <CarInfoItem label="차대번호" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_car_id ? diagnosticResult.diag_car_id : '-'  : '-'" />
                <CarInfoItem label="차량번호" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_car_no ? diagnosticResult.diag_car_no : '-' : '-' " />
                <CarInfoItem label="최초등록일" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_registred_date ? diagnosticResult.diag_registred_date :'-' : '-' " />
                <CarInfoItem label="주행거리" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_distance ? diagnosticResult.diag_distance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' km' : '-' : '-' " />
  
                <CarInfoItem label="엔진형식" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_car_id ? carDetails.engineType : '-' : '-' " />
                <CarInfoItem label="미션" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_mission ? diagnosticResult.diag_mission : '-' : '-' " />
  
                <CarInfoItem label="제조사" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_company ? diagnosticResult.diag_company : '-' : '-' " />
                <CarInfoItem label="년식" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_year ? diagnosticResult.diag_year : '-' : '-' " />
                <CarInfoItem label="용도변경" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_history_use ? diagnosticResult.diag_history_use : '-' : '-' " />
  
                <CarInfoItem label="모델" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_model ? diagnosticResult.diag_model : '-' : '-' " />
                <CarInfoItem label="배기량" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_displacement ? diagnosticResult.diag_displacement +' cc' : '-' : '-' " />
                <!-- <CarInfoItem label="튜닝이력" :value="diagnosticResult.diag_history_tuning ? diagnosticResult.diag_history_tuning  : '-'" /> -->
  
                <!-- <CarInfoItem label="등급" :value="diagnosticResult.diag_grade ? diagnosticResult.diag_grade : '-'" /> -->
                <CarInfoItem label="연료" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_fuel ? diagnosticResult.diag_fuel : '-' : '-' " />
                
  
                <div class="detail-row" v-if="carDetails.modelSub || carDetails.gradeSub">
                  <CarInfoItem label="세부모델" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_submodel ? diagnosticResult.diag_submodel : '-' : '-' " v-if="carDetails.modelSub" />
                  <CarInfoItem label="세부등급" :value="auctionDetail.data.status !== 'diag' ? diagnosticResult.diag_subgrade ? diagnosticResult.diag_subgrade : '-' : '-' " v-if="carDetails.gradeSub" />
                </div>
  
              </div>
  
              <div v-if="auctionDetail.data.status !== 'diag'">
                <ul class="machine-inform-title">
                  <li class="text-secondary opacity-50">옵션정보</li>
                </ul>
    
                <div v-html="diagnosticOptionViewObject"></div>
              
              </div>
              
            </div>
  
  
            <div v-if="auctionDetail.data.status !== 'ask' && auctionDetail.data.status !== 'diag'">
              <div class="mt-4 mb-4">
                <p class="ac-evaluation btn-fileupload-red btn-shadow" @click.prevent="openDiagResultModal">위카 진단평가 확인하기</p>
              </div>
            </div>
            
  
            <div class="dropdown border-bottom" v-if="auctionDetail.data.status !== 'ask' && auctionDetail.data.status !== 'diag'">
              <button
                class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
                @click="toggleDropdown('general_1')"
              >
                추가옵션
                <img
                  :src="openSection.includes('general_1') ? iconUp : iconDown"
                  alt="Dropdown Icon"
                  class="dropdown-icon"
                  width="14"
                />
              </button>
              <transition name="slide">
              <div v-show="openSection.includes('general_1')" class="dropdown-content mt-0 p-4">
                <div v-html="diagnosticResult.diag_base_option ? diagnosticResult.diag_base_option : '내용이 없습니다.'" id="diag_base_option" class="html-table-wrapper">
                </div>
              </div>
              </transition>
            </div>
  
  
            <div class="dropdown border-bottom" v-if="auctionDetail.data.status !== 'ask' && auctionDetail.data.status !== 'diag'">
              <button
                class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
                @click="toggleDropdown('general_2')"
              >
                기타옵션
                <img
                  :src="openSection.includes('general_2') ? iconUp : iconDown"
                  alt="Dropdown Icon"
                  class="dropdown-icon"
                  width="14"
                />
              </button>
              <transition name="slide">
                <div v-show="openSection.includes('general_2')" class="dropdown-content mt-0 p-4">
                  {{ diagnosticResult.diag_add_option? diagnosticResult.diag_add_option : '내용이 없습니다.' }}
                </div>
              </transition>
            </div>
  
  
            <div class="mb-3" v-if="auctionDetail.data.status !== 'diag' && auctionDetail.data.status !== 'ask' && auctionDetail.data.status !== 'cancel'">
              <!-- <p v-if="!showPdf" class="ac-evaluation mt-4 btn-fileupload-red btn-shadow" @click.prevent="openAlarmModal">위카 진단평가 숨기기</p> -->
              <!-- <p v-if="showPdf" class="ac-evaluation mt-4 btn-fileupload-red btn-shadow" @click.prevent="openAlarmModal">위카 진단평가 확인하기</p> -->
              <!-- <p class="ac-evaluation btn-fileupload-red btn-shadow" @click.prevent="openAlarmModal">위카 진단평가 확인하기</p> -->
              <!-- <div class="mt-5" v-if="!showPdf">
              <h5>진단 평가</h5>
              <div id="diagnostic-evaluation-modal" style="padding-top: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                  <iframe
                      src="https://diag.wecarmobility.co.kr/uploads/result/WI-23-000001_92.pdf"
                      width="100%"
                      height="600px"
                      
                  ></iframe>
              </div>
              </div> -->
  
              <!-- <div class="dropdown border-bottom">
                <button
                  class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
                  @click="toggleDropdown('general')"
                >
                  위카 진단평가 확인하기
                  <img
                    :src="openSection === 'general' ? iconUp : iconDown"
                    alt="Dropdown Icon"
                    class="dropdown-icon"
                    width="14"
                  />
                </button>
                <transition name="slide">
                <div v-show="openSection === 'general'" class="dropdown-content mt-0 p-4">
                  <div id="diagnostic-evaluation-modal" style="padding-top: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <iframe
                        :src="diagnosticExtra.url_pdf"
                        width="100%"
                        height="600px"
                        
                    ></iframe>
                  </div>
                </div>
                </transition>
              </div> -->
  
  
            </div>
            
            <!-- <div class="contour-style"></div> -->
  
  
            <div class="dropdown border-bottom" v-if="auctionDetail.data.status === 'ing' || auctionDetail.data.status === 'dlvr' || auctionDetail.data.status === 'chosen' || auctionDetail.data.status === 'done'">
                <button
                  class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
                  @click="toggleDropdown('carinfo')"
                >
                  차량 세부정보
                  <img
                    :src="openSection.includes('carinfo') ? iconUp : iconDown"
                    alt="Dropdown Icon"
                    class="dropdown-icon"
                    width="14"
                  />
                </button>
                <transition name="slide">
                <div v-show="openSection.includes('carinfo')" class="mt-0">
                  
                  <div id="car-history-detail">
              <!-- 차량 세부 정보 내용 -->
  
                  <div class="container px-3 py-5">
                    <h5>이력</h5>
                    <div class="p-4 rounded text-body-emphasis bg-body-secondary">
                      <ul class="mt-0 machine-inform-title">
                        <li class="text-secondary opacity-50">용도 변경이력</li>
                        <li class="info-num">
                          {{ niceDnrHistory.resUseHistYn === 'Y' ? '용도이력 / ' : '용도이력 없음 / ' }}
                          {{ niceDnrHistory.resUseHistBiz === 'Y' ? '사업자 / ' : '사업자 없음 / ' }}
                          {{ niceDnrHistory.resUseHistRent === 'Y' ? '렌트 / ' : '렌트 없음 / ' }}
                          {{ niceDnrHistory.resUseHistGov === 'Y' ? '공공기관' : '공공기관 없음' }}
                        </li>
                      </ul>
                      <ul class="mt-0 machine-inform-title">
                        <li class="text-secondary opacity-50">소유자 변경</li>
                        <li class="info-num" v-if="niceDnrHistory.userChangeCount > 0">
                          {{ niceDnrHistory.userChangeCount }} 회
                        </li>
                        <li class="info-num" v-else>
                          -
                        </li>
                      </ul>
                      <ul class="mt-0 machine-inform-title">
                        <li class="text-secondary opacity-50">압류/저당</li>
                        <li class="info-num">
                          {{ niceDnrHistory.seizCt > 1 ? '압류 '+niceDnrHistory.seizCt? niceDnrHistory.seizCt : 0 + '건 / ' : '압류 0건 / ' }}
                          {{ niceDnrHistory.mortCt > 1 ? '저당 '+niceDnrHistory.mortCt? niceDnrHistory.mortCt : 0 + '건' : '저당 0건' }}
                        </li>
                      </ul>
                      <ul class="mt-0 mb-0 machine-inform-title">
                        <li class="text-secondary opacity-50">특수사고 이력</li>
                        <li class="info-num">
                          전손 {{carHistoryCrash?.special_crash?.basic_length? carHistoryCrash?.special_crash?.basic_length + '건' : '0건'}}  / 
                          침수 {{carHistoryCrash?.special_crash?.partial_length? carHistoryCrash?.special_crash?.partial_length + '건' : '0건'}}  / 
                          도난 {{carHistoryCrash?.special_crash?.theft_length? carHistoryCrash?.special_crash?.theft_length + '건' : '0건'}}
                        </li>
                      </ul>
                    </div>
                    <div class="flex-container" style="">
                      <div class="column" style="overflow-x: auto; width:100%;">
                        <h5 class="mt-5">내차피해 (<span class="tc-red">{{ carHistoryCrash?.self_length? carHistoryCrash?.self_length : 0 }}</span>건)</h5>
                        <div class="o_table_mobile" style="overflow-x: auto; white-space: nowrap;">
                          <div class="tbl_basic">
                            <table style="min-width: 600px;">
                              <thead>
                                <tr>
                                  <th>일시</th>
                                  <th>부품</th>
                                  <th>공임</th>
                                  <th>도장</th>
                                  <th>비용</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="item in carHistoryCrash?.self" :key="item.id">
                                  <td>{{ item.crashDate? item.crashDate : '-' }}</td>
                                  <td>{{ item.part? item.part + '원' : '-' }}</td>
                                  <td>{{ item.labor? item.labor + '원' : '-' }}</td>
                                  <td>{{ item.paint? item.paint + '원' : '-' }}</td>
                                  <td>{{ item.cost? item.cost + '원' : '-' }}</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      
                      <div class="column" style="overflow-x: auto; width:100%;">
                        <h5 class="mt-5">타차피해 (<span class="tc-red">{{ carHistoryCrash?.other_length? carHistoryCrash?.other_length : 0 }}</span>건)</h5>
                        <div class="o_table_mobile" style="overflow-x: auto; white-space: nowrap;">
                          <div class="tbl_basic">
                            <table style="min-width: 600px;">
                              <thead>
                                <tr>
                                  <th>일시</th>
                                  <th>부품</th>
                                  <th>공임</th>
                                  <th>도장</th>
                                  <th>비용</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="item in carHistoryCrash?.other" :key="item.id">
                                  <td>{{ item.crashDate? item.crashDate : '-' }}</td>
                                  <td>{{ item.part? item.part + '원' : '-' }}</td>
                                  <td>{{ item.labor? item.labor + '원' : '-' }}</td>
                                  <td>{{ item.paint? item.paint + '원' : '-' }}</td>
                                  <td>{{ item.cost? item.cost + '원' : '-' }}</td>
                                </tr>
                               </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <h5 class="mt-5">판매자 메모</h5>
                    <div class="form-group">
                      <textarea class="form-control text-box process" readonly style="resize: none;">{{ auctionDetail.data.memo || "판매자 메모사항이 없습니다."}}</textarea>
                    </div>
                    <h5 class="mt-5">평가자 의견</h5>
                    <div class="form-group">
                      <textarea class="form-control text-box process" readonly style="resize: none;">{{ diagnosticResult.diag_opinion || "평가자의 의견이 아직 없습니다." }}</textarea>
                    </div>
                    <ul class="machine-inform-title">
                      <li class="text-secondary opacity-50">거래지역</li>
                      <li class="info-num">{{ auctionLocation }}</li>
                    </ul>
                    <ul class="machine-inform-title">
                      <li class="text-secondary opacity-50">기타이력</li>
                      <li class="info-num">-</li>
                    </ul>
                    <ul class="machine-inform-title">
                      <li class="text-secondary opacity-50">차량명의</li>
                      <li class="info-num">{{ carHistoryCrash?.car_use }}</li>
                    </ul>
                  </div>
  
                </div>
                
                </div>
                </transition>
              </div>
  
          </div>
          <!--
            사용자 바텀시트
          -->
          <div v-if="(isUser && auctionDetail.data.status === 'ask') || (isUser && auctionDetail.data.status === 'diag')" class="sheet-content sticky-top">
              <BottomSheet02 class="text-center">
                <div v-if="auctionDetail.data.status === 'ask'">
                <p class="auction-deadline align-items-center my-4 p-4 ">
                  <span class="text-center fw-semibold">매물 신청 완료</span>
                </p>
                <p class="tc-gray fw-semibold">해당 매물 신청이 완료 되었습니다. <br><span class="fw-light fs-6">※ 경매진행까지 약간의 검토 시간이 소요됩니다. </span></p>
                </div>
                <div v-if="auctionDetail.data.status === 'diag'">
                <p class="auction-deadline align-items-center my-4 p-4 ">
                  <span class="text-center fw-semibold">진단 대기 중</span>
                </p>
                <p class="tc-gray fw-semibold">※ 진단이 완료되는 즉시 경매진행이 시작됩니다 ※ <br><span>잠시만 기다려주세요.</span></p>
                </div>
              </BottomSheet02>
            </div>
          <div v-if="isUser && auctionDetail.data.status === 'done'" class="sheet-content sticky-top">
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
  
                <div class="mt-3">
                  <!-- <div class="d-flex justify-content-between align-items-baseline mt-4">
                    <h4 class="custom-highlight">명의이전 서류 확인</h4>
                  </div> -->
                  <button class="btn btn-outline-primary w-100 mt-2" @click="openNameChangeModal">명의이전 서류 확인</button>
                  <!-- <div class="text-start text-secondary opacity-50 mt-2" v-if="fileAuctionCompanyLicenseName">명의이전 서류: <a :href="fileAuctionCompanyLicenseUrl" target="_blank">{{ fileAuctionCompanyLicenseName }}</a></div> -->
                </div>
                
              </BottomSheet02>
  
              
  
            </div>
            <div v-if="isUser && auctionDetail.data.status === 'cancel'" class="sheet-content sticky-top">
              <BottomSheet02>
                <p class="auction-deadline align-items-center my-4 p-4 ">
                  <span class="text-center tc-gray fw-semibold">경매 취소</span>
                </p>
                <p class="tc-gray fw-semibold">해당 매물의 경매가 취소 되었습니다.</p>
              </BottomSheet02>
            </div>
            <div v-if="isUser && auctionDetail.data.status === 'ing'" class="sheet-content">
              <div v-if="auctionDetail.data.bids_count === 0" class="sheet-content-wrap sticky-top">
              <BottomSheet02 >
                <div class="d-flex justify-content-between align-items-baseline">
                <h4 class="text-start my-2 custom-highlight">경매 진행중</h4>
                </div>
                <p class="text-start text-secondary opacity-50">※ 입찰한 딜러가 있으면 즉시 선택이 가능합니다.</p>
                <button  class="btn-primary bold-18-font modal-bid d-flex mt-3 p-3 justify-content-center blinking">
                    <p class="text-center">경매 진행중 입니다.</p>
                </button>
              </BottomSheet02>
            </div>
            <div v-else class="sheet-content-wrap">
            <BottomSheet02>
                <div class="wd-100 bid-content p-4">
                    <div class="d-flex justify-content-between">
                      <p class="bold-20-font">현재 {{auctionDetail.data.bids_count}}명이 입찰했어요.</p>
                      <button class="mt-1" @click="openModal"><span class="cancelbox">경매취소</span></button>
                    </div>
                  </div>
                  <div class="container p-3 mt-3">
                  <div class="d-flex justify-content-between align-items-baseline">
                  <h4 class="custom-highlight">{{auctionDetail.data.status === 'wait' ? '딜러 선택하기' : '입찰한 딜러'}}</h4>
                  </div>
                  <p class="text-start text-secondary opacity-50">※ 입찰 금액이 가장 높은 순으로 5명까지만 표시돼요.</p>
                </div>
                <div class="bid-bc p-2 overflow-y-auto hv-25">
                  <ul v-for="(bid, index) in sortedTopBids" :key="bid.user_id" class="px-0 inspector_list max_width_900">
                    <li @click="handleClick(bid, $event, index)" class="min-width-no mx-width-no">
                      <div class="d-flex gap-4 align-items-center justify-content-between ">
                        <div class="img_box">
                          <img :src="getPhotoUrl(bid)" alt="Profile Photo" class="profile-photo" />
                        </div>
                        <div class="txt_box me-auto d-flex flex-column align-items-start">
                          <h5 class="name mb-1">{{ bid.dealerInfo ? bid.dealerInfo.name : 'Loading...'}}</h5>
                          <h4 class="txt tc-red">{{amtComma(bid.price)}}</h4>
                          <p class="restar normal-16-font me-auto average-score">{{ bid.points }}점</p>
                        </div>
                        <p class="restar normal-16-font me-auto average-score-web">{{ bid.points }}점</p>
                        <p class="btn-apply-ty03"></p>
                      </div>
                    </li>
                  </ul>
                  </div>
            </BottomSheet02> 
          </div>
          </div>
  
            <!--
              딜러 : 바텀 시트 
            -->
            <div v-if="auctionDetail.data.status !== 'done' && auctionDetail.data.status !== 'dlvr' && auctionDetail.data.status !== 'chosen'  &&  isDealer" class="sheet-content sticky-top">
              <BottomSheet02 initial="half" :dismissable="true" v-if="!succesbid && !auctionDetail.data.bids.some(bid => bid.user_id === user.id) && auctionDetail && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price == null && !bidSession">
                  <div  @click.stop="">
                    <div>
                      <!-- <div class="d-flex">
                        <p class="tc-gray bold">
                          도매가 <span class="tc-primary size_26 mx-2">{{ auctionDetail.data.car_price_now_whole / 10000 }}</span><span class="tc-primary">만원 </span>
                          <span class="mx-2">|</span> 
                          소매가<span class="tc-primary size_26 mx-2">{{ auctionDetail.data.car_price_now / 10000 }}</span><span class="tc-primary">만원 </span>
                        </p>
                      </div> -->
                      <div class="d-flex">
                        <div class="tc-gray">
                          <!-- 도매가 : 오토허브셀카 제공 / 소매가 : NICE D&R 제공 -->
                          <div>도매가 <span class="tc-primary size_26 mx-2">{{ auctionDetail.data.car_price_now_whole / 10000 }}</span><span class="tc-primary">만원 </span></div>
                          <!-- <div>※ 오토허브셀카 제공</div> -->
                        </div>
                        <div style="width:10px;"> </div>
                        <div class="tc-gray">
                          <div>소매가<span class="tc-primary size_26 mx-2">{{ auctionDetail.data.car_price_now / 10000 }}</span><span class="tc-primary">만원 </span></div>
                          <!-- <div>※ NICE D&R 제공</div> -->
                        </div>
                      </div>
  
                      <div class="d-flex mt-4">
                        <div style="text-align:left;">
                          ※ 도매가 오토허브셀카 제공 <br/>
                          ※ 소매가 NICE D&R 제공
                        </div>
                      </div>
  
                    </div>
                    <hr/>
                    <p class="text-center tc-red my-2">현재  {{ auctionDetail.data.bids_count }}명이 입찰했어요.</p>
                    <button type="button" class="btn btn-primary w-100 align-items-center d-flex justify-content-center gap-3" v-if="isAccident(auctionDetail.data.is_accident) !== '사고여부 미진단' && auctionDetail.data.status === 'ing'" @click="showbidView">입찰하기<p class="icon-up-wh"></p></button>
                  </div>
                </BottomSheet02>
  
                <BottomSheet02  v-if="auctionDetail.data.status == 'wait' && isDealer">
                  <div class="steps-container mb-3">
                    <div class="step completed">
                      <div class="label completed">STEP01</div>
                      <div class="label label-style text-secondary opacity-50">입찰 중</div>
                    </div>
                    <div class="line completed"></div>
                    <div class="step completing">
                      <div class="label completing">STEP02</div>
                      <div class="label label-style tc-gray completing-text">딜러 선택</div>
                    </div>
                    <div class="line"></div>
                    <div class="step">
                      <div class="label">STEP03</div>
                      <div class="label label-style02 text-secondary opacity-50">완료</div>
                    </div>
                  </div>
                  <p class="auction-deadline text-center mt-2">경매 완료 후 딜러선택 중 입니다.</p>
                </BottomSheet02>
                <BottomSheet02 v-if="auctionDetail.data.status == 'cancel'" >
                  <h5 class="text-start">입찰이 취소되었습니다</h5>
                  <p class="auction-deadline align-items-center my-4 p-4 justify-content-between">
                    <span class="text-secondary opacity-50">나의 입찰 금액</span>
                    <span class="bold-20-font">{{ amtComma(myBidPrice) }}</span>
                  </p>
                </BottomSheet02>
              <BottomSheet02 initial="half" class="p-2 pt-0" v-if="userBidExists && !userBidCancelled && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price == null">
                <div class="d-flex justify-content-between align-items-baseline">
                  <h5>나의 입찰 금액</h5>
                  <div class="mt-3 d-flex align-items-center justify-content-end gap-3">
                    <p class="tc-gray icon-bid">입찰  {{ auctionDetail.data.bids_count }}</p>
                    <div class="tc-gray ml-2 icon-heart">관심 {{ auctionDetail.data.likes ? auctionDetail.data.likes.length : 0 }}</div>
                  </div>
                </div>
                <div v-if="auctionDetail.data.status === 'ing' && (succesbid || auctionDetail.data.bids.some(bid => bid.user_id === user.id)) && auctionDetail.data.hope_price == null" @click.stop="">
                  <p class="auction-deadline align-items-center my-4 p-4 justify-content-between border-6">
                    <p></p>
                    <h4 class="mb-0">{{ amtComma(myBidPrice) }}</h4>
                  </p>
                 <!--<p class="tc-gray text-center">앞으로 3회 더 취소할 수 있어요</p>--> 
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
            <BottomSheet02 v-if="(auctionDetail.data.status == 'dlvr' || auctionDetail.data.status == 'chosen' || auctionDetail.data.status == 'dlvrDone') && scsbid">


              <div v-if="(auctionDetail.data.status == 'chosen') && scsbid" class="mb-4">

                
                <div class="d-flex justify-content-between align-items-baseline">
                  <h4 class="custom-highlight">탁송전 진행상황</h4>
                </div>
                
                <div class="sheet-content" style="width: 100% !important;">
                  <div class="container">
                    <div class="content" style="padding: 0px !important; margin: 0px !important; padding-top: 10px !important;">
                      <div class="steps-container mb-3">
                        <div :class="auctionDetail.data.taksong_wish_at === null ? 'step completed' : 'step completed'">
                          <div :class="auctionDetail.data.taksong_wish_at === null ? 'label completed' : 'label completed'">STEP01</div>
                          <div :class="auctionDetail.data.taksong_wish_at === null ? 'label label-style text-secondary opacity-50' : 'label label-style text-secondary opacity-50'">선택완료</div>
                        </div>
                        <div :class="auctionDetail.data.taksong_wish_at === null ? 'line completed' : 'line completed'"></div>
                        <div :class="auctionDetail.data.taksong_wish_at === null ? 'step completing' : 'step completed'">
                          <div :class="auctionDetail.data.taksong_wish_at === null ? 'label completed' : 'label completed'">STEP02</div>
                          <div :class="auctionDetail.data.taksong_wish_at === null ? 'label label-style tc-gray completing-text' : 'label label-style text-secondary opacity-50'">판매자<br> 탁송정보</div>
                        </div>
                        <div :class="auctionDetail.data.taksong_wish_at === null ? 'line' : 'line completed'"></div>
                        <div :class="auctionDetail.data.taksong_wish_at === null ? 'step' : 'step completed'">
                          <div :class="auctionDetail.data.taksong_wish_at === null ? 'label' : 'label completed'">STEP03</div>
                          <div :class="auctionDetail.data.taksong_wish_at === null ? 'label label-style02 text-secondary opacity-50' : 'label label-style tc-gray completing-text'">구매자<br> 탁송정보</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <p class="auction-deadline text-center mt-2">
                  {{auctionDetail.data.taksong_wish_at === null ? '판매자가 탁송정보를 입력중 입니다.' : auctionDetail.data.taksong_id === null ? '판매자가 탁송정보를 입력했습니다.' : '구매자가 탁송 신청 했습니다. 차량대금 입금이 완료 되면 탁송이 시작 됩니다.'}}
                </p>
              </div>
              
  
              <div class="mb-5" v-if="auctionDetail.data.taksong_wish_at === null && isUser">
                <!-- <button class="border-6 btn-fileupload my-4 shadow02 text-secondary opacity-50" @click="AttachedInform">딜러 첨부파일</button> -->
                <button @click="showModal2" class="btn btn-primary w-100">탁송일 입력하기</button>
                <!--<button class="border-6 btn-fileupload my-4 shadow02"><a :href=fileSignUrl download class="text-secondary opacity-50">매도용 인감증명서 다운로드</a></button>-->
              </div>
  
              
               <div class="d-flex justify-content-between align-items-baseline" v-if="auctionDetail.data.is_taksong !== 'done' && auctionDetail.data.taksong_wish_at !== null">
                <h4 class="custom-highlight">탁송 신청 정보</h4>
              </div>
              <div class="text-start mt-2" v-if="auctionDetail.data.is_taksong !== 'done' && auctionDetail.data.taksong_wish_at !== null">
                <p class="text-secondary ">낙&nbsp;&nbsp;  찰&nbsp;&nbsp;  액 : <span class="tc-red ms-1 fw-bold">{{auctionDetail.data.final_price}} 만원</span></p>
                <p class="text-secondary ">입금&nbsp;&nbsp;은행 :<span class="tc-red ms-1 fw-bold">( {{auctionDetail.data.bank}} ) {{auctionDetail.data.account}}</span></p>
                <p class="text-secondary ">탁&nbsp;&nbsp; 송&nbsp;&nbsp; 일 :<span class="tc-red ms-1 fw-bold">{{ auctionDetail.data.taksong_wish_at?.substring(0, 16) }}</span></p>
                <p class="text-secondary ">장&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 소 :<span class="tc-red ms-1 fw-bold">{{auctionDetail.data.addr1}}</span></p>
              </div>

              <div v-if="isDealer">
                <div class="d-flex justify-content-between align-items-baseline pt-4">
                  <h4 class="custom-highlight">경락 확인서</h4>
                </div>
                <div class="d-flex justify-content-between align-items-baseline">
                    경락확인서를 확인 하세요.
                </div>
                <button class="my-2 btn btn-outline-primary w-100 mt-4" @click="openDoneModal(auctionId)">경락 확인서</button>
              </div>

              <div v-if="auctionDetail.data.is_taksong === 'ask' || auctionDetail.data.is_taksong === 'start' || auctionDetail.data.is_taksong === 'ing'">
  
                <div class="d-flex justify-content-between align-items-baseline pt-4">
                  <h4 class="custom-highlight">탁송 상태 정보</h4>
                </div>
  
                <div class="text-start mt-2">
                <p class="text-secondary ">상태 :<span class="tc-red ms-1 fw-bold">
                  {{auctionDetail.data.is_taksong === 'ask' ? '대기중' : auctionDetail.data.is_taksong === 'start' ? '배차' : auctionDetail.data.is_taksong === 'ing' ? '탁송중' : '탁송완료'}}
                </span></p>
                <p class="text-secondary ">탁송 기사 :<span class="tc-red ms-1">{{auctionDetail.data.taksong_courier_name ? auctionDetail.data.taksong_courier_name+' / ' : '' }}  {{auctionDetail.data.taksong_courier_mobile ? auctionDetail.data.taksong_courier_mobile : '미정'}}</span></p>
                <p class="text-secondary ">출발 주소 :<span class="tc-red ms-1">{{auctionDetail.data.taksong_departure_address}}</span></p>
                <p class="text-secondary ">도착 주소 :<span class="tc-red ms-1">{{auctionDetail.data.taksong_dest_address}}</span></p>
                <p class="text-secondary ">출발 시간 :<span class="tc-red ms-1">{{auctionDetail.data.taksong_departure_at}}</span></p>
                <!-- <p class="text-secondary ">탁송 도착 시간 :<span class="tc-red ms-1">{{auctionDetail.data.taksong_dest_at}}</span></p> -->
                </div>
              </div>
              
              <div v-if="isDealer">
  
                
                <!-- <div class="steps-container mb-3">
                  <div class="step completed">
                    <div class="label completed">STEP01</div>
                    <div class="label label-style text-secondary opacity-50">입찰 중</div>
                  </div>
                  <div class="line completed"></div>
                  <div class="step completing">
                    <div class="label completing">STEP02</div>
                    <div class="label label-style tc-gray completing-text">딜러 선택</div>
                  </div>
                  <div class="line"></div>
                  <div class="step">
                    <div class="label">STEP03</div>
                    <div class="label label-style02 text-secondary opacity-50">완료</div>
                  </div>
                </div> -->
  
                <div v-if="auctionDetail.data.is_taksong == 'done'">
  
                  <div class="d-flex justify-content-between align-items-baseline mt-4">
                    <h4 class="custom-highlight">명의이전 서류첨부</h4>
                  </div>
  
                  <div class="d-flex justify-content-between align-items-baseline">
                    명의이전 서류를 준비 하여 파일을 첨부 해 주세요.
                  </div>
  
  
                  <div class="form-group mt-3">
                    <!-- <label for="memo"><span class="text-danger me-2">*</span>명의이전 서류</label> -->
                    <input type="file" @change="handleFileUploadCompanyLicense" ref="fileInputRefCompanyLicense" style="display:none" >
                    <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUploadCompanyLicense" ref="fileInputRefCompanyLicenseBtn">
                      파일 첨부
                    </button>
                    <div class="text-start text-secondary opacity-50 mt-2" v-if="fileAuctionCompanyLicenseName">명의이전 서류: {{ fileAuctionCompanyLicenseName }}</div>
  
                    <button type="button" class="btn btn-primary w-100 mt-3" @click="requestedFileUpload" :disabled="auctionDetail.data.has_uploaded_name_change_file">첨부하기</button>
  
                  </div>
  
                  <div>
                    
                  </div>
  
                </div>
  
              </div>
  
              <div v-if="isUser">

                <div v-if="auctionDetail.data.is_taksong == 'done' && auctionDetail.data.has_uploaded_name_change_file != '1'">
                  <div class="dropdown-content" style="border-radius: 15px;">
                    <div class="text-start">
                      구매자가 명의 이전 진행중입니다.<br/> 영업일 기준 2일 내 명의이전이 완료될 예정입니다.
                    </div>
                  </div>
                </div>

                <div class="d-flex justify-content-between align-items-baseline mt-4" v-if="auctionDetail.data.is_taksong !== 'done'">
                  <h4 class="custom-highlight">탁송 전, 준비해 주세요</h4>
                </div>
                <div v-if="auctionDetail.data.is_taksong !== 'done'">
                  <!-- <p class="text-secondary ">차에 있는 짐 빼기</p> -->
                  
                  <div class="dropdown border-bottom">
                    <button
                      class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
                      @click="toggleDropdown('general1')"
                    >
                    <span>1. 차에 있는 짐 빼기</span>
                      <img
                        :src="openSection.includes('general1') ? iconUp : iconDown"
                        alt="Dropdown Icon"
                        class="dropdown-icon"
                        width="14"
                      />
                    </button>
                    <transition name="slide">
                    <div v-show="openSection.includes('general1')" class="dropdown-content mt-0 p-4" style="border-radius: 15px;">
  
                      <h4 class="text-secondary mt-3 mb-3 text-center">자주 분실하는 물건이에요!</h4>
  
                        <div class="d-flex justify-content-between align-items-center find-icons mt-3">
                          <div class="text-secondary">
                            <img :src="find_icon_01"/><br/>
                            선글라스
                          </div>
                          <div class="text-secondary">
                            <img :src="find_icon_02"/><br/>
                            하이패스
                          </div>
                          <div class="text-secondary">
                            <img :src="find_icon_03"/><br/>
                            키링
                          </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center find-icons mt-3">
                          <div class="text-secondary">
                            <img :src="find_icon_04"/><br/>
                            캐롯 플러그
                          </div>
                          <div class="text-secondary">
                            <img :src="find_icon_05"/><br/>
                            블랙박스 SD칩
                          </div>
                          <div class="text-secondary">
                            <img :src="find_icon_06"/><br/>
                            USB
                          </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center find-icons mt-3">
                          <div class="text-secondary">
                            <img :src="find_icon_07"/><br/>
                            아파트 출입증
                          </div>
                          <div class="text-secondary">
                            <img :src="find_icon_08"/><br/>
                            주차 연락처
                          </div>
                          <div class="text-secondary">
                            <img :src="find_icon_09"/><br/>
                            수납함 내 물품
                          </div>
                        </div>
  
                    </div>
                    </transition>
                  </div>
  
  
                  <div class="dropdown border-bottom">
                    <button
                      class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
                      @click="toggleDropdown('general2')"
                    >
                    <span>2. 필수서류 준비하기</span>
                      <img
                        :src="openSection.includes('general2') ? iconUp : iconDown"
                        alt="Dropdown Icon"
                        class="dropdown-icon"
                        width="14"
                      />
                    </button>
                    <transition name="slide">
                    <div v-show="openSection.includes('general2')" class="dropdown-content mt-0 p-4" style="border-radius: 15px;">
                        <div class="font-size-14 font-weight-bold">탁송기사님 도착 전까지, <br/>아래 2가지 서류를 준비해주세요.</div>
                        <div class="d-flex justify-content-between mt-3">
                          <div style="width: 45%;">
                            <img :src="car_licence_icon" alt="car_licence_icon" class="me-2">
                            <div class="text-center mt-2">
                              자동차등록증 원본
                            </div>
                          </div>
                          <div style="width: 45%;">
                            <img :src="auth_licence_icon" alt="auth_licence_icon" class="me-2">
                            <div class="text-center mt-2">
                              자동차매도용 <span class="color-red">본인서명사실확인서</span> 또는 인감증명서 (매수자 인적사항 기재)
                            </div>
                          </div>
                        </div>
                    </div>
                    </transition>
                  </div>
  
  
                </div>
  
                <div class="mt-4">
                  <h4 class="">매수자 정보</h4>
                  <p class="text-danger">본인서명 사실 확인서 (자동차매도용) 발금정보</p>
                </div>
  
                <div class="dropdown-content" style="border-radius: 15px;">
                  <div class="text-start">
                    <p class=""><span class="title-sub">성&nbsp;&nbsp;&nbsp;&nbsp; 명 (법인명)</span> : <span class="ms-1 fw-bold">{{auctionDetail.data.dealer.company ? auctionDetail.data.dealer.company : auctionDetail.data.dealer.name}}</span></p>
                    <p class=""><span class="title-sub">주민(법인) 번호</span> :<span class="ms-1 fw-bold">{{auctionDetail.data.dealer.corporation_registration_number}}</span></p>
                    <p class=""><span class="title-sub">주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 소</span> : <span class="ms-1 fw-bold">{{auctionDetail.data.dealer.company_addr1 +' '+ auctionDetail.data.dealer.company_addr2}}</span></p>
                  </div>
                </div>
  
  
                <div class="d-flex justify-content-between align-items-baseline mt-4">
                  <h4 class="custom-highlight">자주묻는 질문</h4>
                </div>
  
                <div class="dropdown border-bottom">
                  <button
                    class="dropdown-btn ps-3 d-flex justify-content-between align-items-center"
                    @click="toggleDropdown('general3')"
                  >
                  <span>본인서명사실확인서(매도용 인감증명서) 전자 발급 가능한가요?</span>
                    <img
                      :src="openSection.includes('general3') ? iconUp : iconDown"
                      alt="Dropdown Icon"
                      class="dropdown-icon"
                      width="14"
                    />
                  </button>
                  <transition name="slide">
                  <div v-show="openSection.includes('general3')" class="dropdown-content mt-0 p-4" style="border-radius: 15px;">
                    아닙니다.<br/>
                    전자 발급 서류는 사용할 수 없습니다.<br/>
                    자동차 매도용은 <span class="tc-red">주민센터 방문</span>하여 발급받은 원본만 가능합니다.<br/>
                    주민센터 직원분에게 매수자 인적사항을 보여주시고, 그대로 기재 후 발급 받아주세요.
                  </div>
                  </transition>
                </div>
  
  
  
              </div>
              
  
              
  
              
              <div v-if="fileOwnerUrl">
                <button class="border-6 btn-fileupload my-2 shadow02"><a :href=fileOwnerUrl download class="text-secondary opacity-50">매도자관련서류 다운로드</a></button>
              </div>
              <div v-if ="auctionDetail.data.status ==='chosen' && isUser">
              <hr>
            <div class="mt-2 d-flex justify-content-between align-items-baseline">
              <h4 class="custom-highlight">탁송 서비스 이용고객안내</h4>
            </div>
              <div>
                <ul class="timeline p-0 mt-2">
                  <li>
                    <div class="d-flex gap-2">
                      <div class="circle">1</div>
                      <span>키와 서류를 탁송기사에게 전달해 주세요.</span>
                    </div>
                  </li>
                  <div class="small-circles mb-2"></div>
                  <li>
                    <div class="d-flex gap-2">
                      <div class="circle">2</div>
                      <span>탁송기사가 서류를 수령하면 서류접수 <br>완료 처리 버튼을 클릭합니다.</span>
                    </div>
                  </li>
                  <div class="small-circles mb-2"></div>
                  <li>
                    <div class="d-flex gap-2">
                      <div class="circle">3</div>
                      <span>차량대금이 나이스에서 고객 계좌로 송부되어집니다.</span>
                    </div>
                  </li>
                  <div class="small-circles mb-2"></div>
                  <li>
                    <div class="d-flex gap-2">
                      <div class="circle">4</div>
                      <span>경매완료 처리 됩니다.</span>
                    </div>
                  </li>
                </ul>
              </div>
              
            <!--   <p class="text-start text-secondary opacity-50">※ 탁송 서비스 안내는 ' 탁송 확인 '에서 확인 가능합니다. </p>
             <button
                class="my-4 btn-primary bold-18-font modal-bid d-flex p-3 justify-content-between blinking"
                @click="competionsuccess"
              >
                <p>탁송 확인</p>
                <p class="d-flex align-items-center gap-2">
                  바로가기
                  <p class="icon-right-wh"></p>
                </p>
              </button>-->
              </div>
                <div v-if ="auctionDetail.data.status ==='chosen' && isDealer && scsbid">
                  <hr>
                <h4 class="mt-2">탁송 주소지</h4>
                <p class="text-start text-secondary opacity-50">※ 현 주소지로 탁송이 진행 됩니다. </p>
                <div class="d-flex justify-content-end">
                  <button v-if="destAddrBtn" class=" btn-outline-primary btn sm-height" @click="dealerAddrConnect">주소지 변경</button>
                </div>
                <div class="fw-semibold">
                  <p>우편번호 :<span class="tc-red ms-1">{{ selectedAuction.addr_post }}</span></p>
                  <p>주<span class="ms-4">소</span> :<span class="tc-red ms-2">{{ selectedAuction.addr1+' , '+selectedAuction.addr2 }}</span></p>
                </div>
                <button v-if="destAddrBtn"
                  class="my-4 btn-primary btn w-100"
                  @click="dealerAddrCompetion" :disabled="auctionDetail.data.taksong_wish_at === null"
                >
                      <p>탁송하기</p>
                    </button>
                  </div>
                      <div v-if="showModal" class="modal-overlay p-3">
                      <div class="modal-container">
                        <div class="card-body">
                          <div class="text-start">
                            <div class="d-flex justify-content-between align-items-center">
                              <h4 class="custom-highlight">탁송지 변경</h4>
                              <button type="button" class="mb-1 btn-close" @click="closeAddr"></button>
                            </div>
                            <p>원하시는 탁송지를 선택해주세요.</p>
                            <a href="/addr" class="fs-6 tc-gray link-hov text-decoration-underline text-danger">다른 주소지로 변경, 추가를 원하시나요?</a>
                          </div>
                          <div class="p-0 scrollable-content mt-4" ref="scrollableContent"></div>
                          <div class="card-footer">
                              <ul class="pagination justify-content-center">
                                  <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                  <a class="page-link prev-style" @click="loadPage(currentPage - 1)" :disabled="currentPage === 1"></a>
                                  </li>
                                  <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === currentPage }">
                                  <a class="page-link" @click="loadPage(n)">{{ n }}</a>
                                  </li>
                                  <li class="page-item next-prev" :class="{ disabled: currentPage === pagination.last_page }">
                                  <a class="page-link next-style" @click="loadPage(currentPage + 1)" :disabled="currentPage === pagination.last_page"></a>
                                  </li>
                              </ul>
                          </div>
                        </div>
                        <button @click="confirmSelection" class="btn btn-primary w-100">확인</button>
                      </div>
                      </div>
                    </BottomSheet02> 
                    <BottomSheet02 v-if="auctionDetail.data.status === 'done' && isDealer && scsbid">
                      <div>
                        <h4>낙찰 완료</h4>
                        <p class="tc-gray mb-3">※ 차량에 문제가 있으신가요?</p>
                        <div>
  
                          <button class="my-2 btn btn-outline-primary w-100" @click="openDoneModal(auctionId)">경락 확인서</button>
  
                          <button v-if="!isClaimed" class="my-2 btn btn-primary w-100" @click="openClaimInfoModal(auctionId)">클레임 신청</button>
                          <!-- <router-link v-if="!isClaimed"
                            :to="{ name: 'posts.create.withAuctionId', params: { boardId: 'claim', auctionId: auctionId } }" 
                            class="my-2 btn btn-primary w-100"
                            :disabled="disableClaimButton" 
                            @click.prevent="!isClaimed && navigateToClaim"
                          >
                            클레임 신청
                          </router-link> -->
                          <p v-else-if="isClaimed" class="btn primary-disable">클레임 신청 완료</p>
                        </div>
                      </div>
                    </BottomSheet02>
                  </div>
                </div>
  
  
                <!-- <div v-else class="container">
                  <div style="padding: 120px; border-radius: 10px;">
                    <h4>경매가 선택된 매물 입니다. </h4>
                  </div>
                </div> -->
            
                       <!--  바텀 시트 show or black-->
                       <button class="animCircle scroll-button floating" :style="scrollButtonStyle" v-show="scrollButtonVisible"></button>
                          <div v-if="isDealer">
                              <div v-if="auctionDetail.data.status === 'wait'" @click.stop="">
                              
                              </div>
  
                        <!-- 딜러 입찰 -->
                            <BottomSheet03 initial="half" :dismissable="true"  v-if="!succesbid && !auctionDetail.data.bids.some(bid => bid.user_id === user.id) && auctionDetail && auctionDetail.data.status === 'ing' && auctionDetail.data.hope_price == null && bidSession">
                              <div @click.stop="">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="mb-1 btn-close" @click="DealerbackView"></button>
                                    <div class="mt-3 d-flex align-items-center justify-content-end gap-3">
                                      <p class="tc-gray icon-bid">입찰 {{ auctionDetail.data.bids_count }}</p>
                                      <div class="tc-gray ml-2 icon-heart">관심 {{ auctionDetail.data.likes ? auctionDetail.data.likes.length : 0 }}</div>
                                    </div>
                                  </div>
                                <div>
                                  <h4 class="text-start process mb-5 mt-4">입찰 금액을 입력해주세요</h4>
                                  <div class="input-container mt-5">
                                    <input type="number" class="styled-input" :placeholder="`${avgAmount}`" v-model="amount" @input="updateKoreanAmount">
                                  </div>
                                  <p class="d-flex justify-content-end fw-bolder fs-4 text-primary p-2">{{ koreanAmount }}</p>
                                  <button type="button" class="tc-wh btn btn-primary w-100 my-4" @click="submitAuctionBid">입찰 완료</button>
                                </div>
                              </div>
                            </BottomSheet03>
  
                            <transition name="fade" mode="out-in">
                              <bid-modal v-if="showBidModal" :amount="amount" :highestBid="highestBid" :lowestBid="lowestBid" @close="closeBidModal" @confirm="confirmBid"></bid-modal>
                              </transition>
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
                          
                          <div class="container" v-if="isUser && auctionDetail.data.status === 'wait' && !connectDealerModal || auctionChosn && !connectDealerModal">
                            <div class="wd-100 bid-content p-4">
                              <div class="d-flex justify-content-between">
                                <p class="bold-20-font">현재 {{auctionDetail.data.bids_count}}명이 입찰했어요.</p>
                                <button class="mt-1" @click="openModal"><span class="cancelbox">경매취소</span></button>
                              </div>
                            </div>
                            <div class="container p-3 mt-3">
                            <div class="d-flex justify-content-between align-items-baseline">
                            <h4 class="custom-highlight">{{auctionDetail.data.status === 'wait' ? '딜러 선택하기' : '입찰한 딜러'}}</h4>
                            </div>
                            <p class="text-secondary opacity-50">입찰 금액이 가장 높은 순으로 5명까지만 표시돼요.</p>
                            <p class="tc-red text-start mt-2">※ 2일후 까지 선택된 딜러가 없을시, 경매가 취소 됩니다.</p>
                          </div>
                          <div class="bid-bc p-2">
                            <ul v-for="(bid, index) in sortedTopBids" :key="bid.user_id" class="px-0 inspector_list max_width_900">
                              <li @click="handleClick(bid, $event, index)" class="min-width-no mx-width-no">
                                <div class="d-flex gap-4 align-items-center justify-content-between ">
                                  <div class="img_box">
                                    <img :src="getPhotoUrl(bid)" alt="Profile Photo" class="profile-photo" />
                                  </div>
                                  <div class="txt_box me-auto">
                                    <h5 class="name mb-1">{{ bid.dealerInfo ? bid.dealerInfo.name : 'Loading...'}}</h5>
                                    <p class="txt fw-bold">{{amtComma(bid.price)}}</p>
                                  </div>
                                  <p class="restar normal-16-font me-auto">{{ bid.points }}점</p>
                                  <p class="btn-apply-ty03"></p>
                                </div>
                              </li>
                            </ul>
                            <ul v-if="!sortedTopBids || !sortedTopBids.length" class="px-0 inspector_list max_width_900 mt-3">
                              <li class="min-width-no mx-width-no">
                                <p class="tc-gray text-center border-none">선택 가능한 딜러가 없습니다.</p>
                              </li>
                            </ul>
                            <!-- 취소 모달 -->
                         <!-- <auction-modal v-if="isModalVisible" :showModals="isModalVisible" :auctionId="selectedAuctionId" @close="closeModal" @confirm="handleConfirmDelete" />--> 
                          </div>
                           <!-- 딜러 선택시 모달 -->
                      
                          <BottomSheet02 class="container dealer-bottom-sheet" v-if="!showReauctionView" initial="half" :dismissable="true" style="position: fixed !important;">
                            <button type="button" class="btn btn-dark d-flex align-items-center justify-content-center gap-1" @click="toggleView">재경매 하기<div class="icon-up-wh">&nbsp;</div></button>
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
                            <p class="d-flex justify-content-end tc-gray p-2">{{ koreanAmount }}</p>
                            <div class="btn-group mt-3 mb-2">
                              <button type="button" class="btn btn-primary" @click="reauction">재경매</button>
                            </div>
                          </div>
                        </BottomSheet03>-->
                        <modal v-if="reauctionModal" :isVisible="reauctionModal" />
                        </div>
  
                        
  
                      </div>
                      <div>
                          <consignment v-if="connectDealerModal" :bid="selectedBid" :userData="userInfo"  :fileSignData = "registerForm" @close="handleModalClose" @confirm="handleDealerConfirm" />
                      </div>
          </template>
  <script>
  
  import { Swiper, SwiperSlide } from "swiper/vue";
  import "swiper/swiper-bundle.css";
  import { Navigation, Pagination, Autoplay } from "swiper/modules";
  
  export default {
    data() {
      return {
        steps: [
          { status: "ask", label: "STEP1", text: "신청완료" },
          { status: "diag", label: "STEP2", text: "진단대기" },
          { status: "ing", label: "STEP3", text: "경매진행" },
          { status: "chosen", label: "STEP4", text: "선택완료" },
          { status: "dlvr", label: "STEP5", text: "탁송중" },
          { status: "dlvrDone", label: "STEP6", text: "탁송완료" },
          { status: "done", label: "STEP7", text: "경매완료" }
        ],
        auctionDetail: {
        }
      };
    },
    components: {
      Swiper,
      SwiperSlide,
    },
    methods: {
      getStatusIndex(status) {
        return this.steps.findIndex((step) => step.status === status);
      },
  
      renderCustomPagination(swiper, current, total) {
        return `
          <span class="pagination-number">${current}</span>
          <div class="progress-bar">
            <div class="progress"></div>
          </div>
          <span class="pagination-number">${total}</span>
        `;
      },
      // 진행 바 리셋
      resetProgressBar() {
        const progress = document.querySelector('.progress');
        if (progress) {
          progress.style.width = '0%';
          setTimeout(() => {
            progress.style.transition = 'width 5s linear';
            progress.style.width = '100%';
          }, 50);
        }
      },
  
    },
    mounted() {
      this.resetProgressBar();
    },
  };
  </script>
  
  <script setup>
  import diagPdf from '../../../../pdf/diag_result.pdf';
  import iconUp from "../../../../img/Icon-black-up.png";
  import iconDown from "../../../../img/Icon-black-down.png";
  import { ref, computed, onMounted, onUnmounted, watch, watchEffect, onBeforeUnmount , inject,reactive,nextTick} from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import { useStore } from 'vuex';
  import { gsap } from 'gsap';
  import useUsers from '@/composables/users';
  import useRoles from '@/composables/roles';
  import useAuctions from '@/composables/auctions';
  import { initAddressBookSystem } from '@/composables/addressbooks';
  import useBids from '@/composables/bids';
  import modal from '@/views/modal/modal.vue';
  import auctionModal from '@/views/modal/auction/auctionModal.vue';
  import consignment from '@/views/consignment/consignment.vue';
  import AlarmModal from '@/views/modal/AlarmModal.vue';
  import profileDom from '/resources/img/profile_dom.png';
  import { initPostSystem } from "@/composables/posts";
  import CarInfoItem from '../../import/CarInfoItem.vue';
  
  import drift from '../../../../../resources/img/drift.png';
  import carObjects from '../../../../../resources/img/modal/car-objects-blur.png';
  import carInfo from '../../../../../resources/img/electric-car.png';
  import { cmmn } from '@/hooks/cmmn';
  import bidModal from '@/views/modal/bid/bidModal.vue';
  import { initReviewSystem } from '@/composables/review';
  import BottomSheet02 from '@/views/bottomsheet/Bottomsheet-type02.vue';
  import BottomSheet03 from '@/views/bottomsheet/Bottomsheet-type03.vue';
  import useLikes from '@/composables/useLikes';
  import { constructNow, isEqual } from 'date-fns';
  
  import find_icon_01 from '../../../../../resources/img/find-icons/find-icon-01.svg';
  import find_icon_02 from '../../../../../resources/img/find-icons/find-icon-02.svg';
  import find_icon_03 from '../../../../../resources/img/find-icons/find-icon-03.svg';
  import find_icon_04 from '../../../../../resources/img/find-icons/find-icon-04.svg';
  import find_icon_05 from '../../../../../resources/img/find-icons/find-icon-05.svg';
  import find_icon_06 from '../../../../../resources/img/find-icons/find-icon-06.svg';
  import find_icon_07 from '../../../../../resources/img/find-icons/find-icon-07.svg';
  import find_icon_08 from '../../../../../resources/img/find-icons/find-icon-08.svg';
  import find_icon_09 from '../../../../../resources/img/find-icons/find-icon-09.svg';

  import option_icon_01_svg from '../../../../../resources/img/options/option-icon-01.svg?raw';
  import option_icon_02_svg from '../../../../../resources/img/options/option-icon-02.svg?raw';
  import option_icon_03_svg from '../../../../../resources/img/options/option-icon-03.svg?raw';
  import option_icon_04_svg from '../../../../../resources/img/options/option-icon-04.svg?raw';
  import option_icon_05_svg from '../../../../../resources/img/options/option-icon-05.svg?raw';
  import option_icon_06_svg from '../../../../../resources/img/options/option-icon-06.svg?raw';
  import option_icon_07_svg from '../../../../../resources/img/options/option-icon-07.svg?raw';
  import option_icon_08_svg from '../../../../../resources/img/options/option-icon-08.svg?raw';
  import option_icon_09_svg from '../../../../../resources/img/options/option-icon-09.svg?raw';
  import option_icon_10_svg from '../../../../../resources/img/options/option-icon-10.svg?raw';
  import option_icon_11_svg from '../../../../../resources/img/options/option-icon-11.svg?raw';
  import option_icon_12_svg from '../../../../../resources/img/options/option-icon-12.svg?raw';
  import option_icon_13_svg from '../../../../../resources/img/options/option-icon-13.svg?raw';
  import option_icon_14_svg from '../../../../../resources/img/options/option-icon-14.svg?raw';
  import option_icon_15_svg from '../../../../../resources/img/options/option-icon-15.svg?raw';
  import option_icon_16_svg from '../../../../../resources/img/options/option-icon-16.svg?raw';
  import option_icon_17_svg from '../../../../../resources/img/options/option-icon-17.svg?raw';
  import option_icon_18_svg from '../../../../../resources/img/options/option-icon-18.svg?raw';
  import option_icon_19_svg from '../../../../../resources/img/options/option-icon-19.svg?raw';
  import option_icon_71_svg from '../../../../../resources/img/options/option-icon-71.svg?raw';
  import option_icon_86_svg from '../../../../../resources/img/options/option-icon-86.svg?raw';
  import option_icon_88_svg from '../../../../../resources/img/options/option-icon-88.svg?raw';
  import option_icon_92_svg from '../../../../../resources/img/options/option-icon-92.svg?raw';
  import option_icon_93_svg from '../../../../../resources/img/options/option-icon-93.svg?raw';
  
  import car_licence_icon from '../../../../../resources/img/find-icons/car_licence_icon.png';
  import auth_licence_icon from '../../../../../resources/img/find-icons/auth_licence_icon.png';
  
  import PhotoSwipeLightbox from 'photoswipe/lightbox'; // 슬라이드 이미지 라이트 PhotoSwipe
  import 'photoswipe/style.css';
  
  
  const { getContacts, contacts, pagination } = initAddressBookSystem();
  const { posts, getPosts, deletePost, isLoading, getBoardCategories } = initPostSystem();
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
  const { getUser,fileUserSignUpload } = useUsers();
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
  const chosendlvr = ref(false);
  const reviewIsOk = ref(true);
  let likeMessage;
  const fileOwnerUrl = ref('');
  const fileSignUrl =ref('');
  
  const destAddrBtn = ref(true);
  
  const fileAuctionCompanyLicenseName = ref(''); // 추가: 파일 이름 저장 변수
  const fileInputRefCompanyLicenseBtn = ref(null);
  const fileInputRefCompanyLicense = ref(null);
  const fileAuctionCompanyLicenseUrl = ref('');

  const biz_check = ref(false);
  
  const photoSwipeImages = ref(null);
  
  const avgAmount = computed(() => {
  
    let resutlPay = 0;
  
    console.log('auctionDetail.value.data',auctionDetail.value.data);
  
    if(auctionDetail.value.data.middle_prices.max){
      // console.log('auctionDetail.value.data.middle_prices.avg',auctionDetail.value.data);
      resutlPay = auctionDetail.value.data.middle_prices.max
      if(auctionDetail.value.data.middle_prices.avg){
        resutlPay = auctionDetail.value.data.middle_prices.avg
      }
    }
  
    return '평균 '+resutlPay+ '원';
  });
  
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
  const Chosenconfirm = ref(false);
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
  const { AuctionCarInfo, getAuctions, auctionsData, AuctionReauction, chosenDealer, getAuctionById, updateAuctionStatus, setdestddress, isAccident, getNiceDnrHistory, getCarHistoryCrash, nameChangeStatus, nameChangeFileUpload, diagnostic } = useAuctions();
  const { submitBid, cancelBid,getBidById } = useBids();
  const carDetails = ref({});
  const highestBid = ref(0);
  const lowestBid = ref(0);
  const fileUserSignData = ref({});
  const currentPage = ref(1);
  const openSection = ref([]);
  const carHistoryCrash = ref({});
  const niceDnrHistory = ref({});
  const auctionLocation = ref({});
  const unique_number = ref('');
  
  const nameChangeStatusData = ref('');
  
  const diagnosticResult = ref({});
  const diagnosticExtra = ref({});
  const diagnosticOptions = ref({});
  const diagnosticOptionView = ref({});
  
  let lightbox;
  
  const sortedTopBids = computed(() => {
    console.log('sortedTopBids??', auctionDetail.value);
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
  const isClaimed = ref(false);
  const fetchPosts = async () => {
    if(auctionDetail.value?.data?.status === 'done'&& isDealer.value){
    await getPosts(
      'claim',
      1,
      '',
      '',
      '',
      '',
      '',
      'created_at',
      'desc'
    );
  
   
    console.log('Fetched posts:', posts.value);
  
    
    isClaimed.value = posts.value.some(post => post.extra1 === route.params.id);
    console.log('Is claimed:', isClaimed.value); 
    }
  };
  
  const navigateToClaim = () => {
    if (!disableClaimButton.value) {
  
      // 팝업 문서 열기 & 확인후 클레임 페이지로 이동
      openClaimInfoModal();
      return;
  
      router.push({ name: 'posts.create.withAuctionId', params: { boardId: 'claim', auctionId: auctionId.value } });
    }
  };
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
  
  //파일체크
  function fileExstCheck(info){
      if(info.hasOwnProperty('files')){
          if(info.files.hasOwnProperty('file_auction_owner')){
              if(info.files.file_auction_owner[0].hasOwnProperty('original_url')){
                fileOwnerUrl.value = userInfo.files.file_auction_owner[0].original_url;
              }
          }
        
          if(info.files.hasOwnProperty('file_user_sign')){
              if(info.files.file_user_sign[0].hasOwnProperty('original_url')){
                fileSignUrl.value = info.files.file_user_sign[0].original_url;
              }
          } 
      }
  }
  
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
  const DOMauctionsData = ref([]);
  //{ id: 1, name: '주소명칭1', address: '주소1', zipCode: '우편번호1' },
  
  const selectedAuction = ref({}); 
  const temporarySelectedAuction = ref(null); 
  const showModal = ref(false); 
  const scrollableContent = ref(null); 
  
  const showModal2 = () => {
      connectDealerModal.value = true;
      chosendlvr.value=true;
  };
  
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
  const selectedAuctionId = ref(null);
  const closeAddr = () => {
    showModal.value = false; 
  }
  const renderAuctionItems = async(page = 1) => {
    await getContacts(page);
    DOMauctionsData.value=contacts.value;
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
        <div class="complete-car my-3">
          <div class="my-auction">
            <div class="bid-bc p-2" style="max-height: 480px;">
              <ul class="px-0 inspector_list max_width_900">
                <li class="min-width-no mx-width-no">
                  <div class="text-start fw-semibold">
                    <p>이름: ${auction.name}</p>
                    <p>주소: ${auction.addr1} , ${auction.addr2}</p>
                    <p>우편번호: ${auction.addr_post}</p>
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
    currentPage.value = 1;
  };
  
  
  
  // const openAlarmModal = async () => {
  //   showPdf.value = !showPdf.value;
  // };
  
  
  const openAlarmModal = () => {
    const text = `
      <h5>진단 평가</h5>
      <div id="diagnostic-evaluation-modal" style="padding-top: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
          <iframe
              src="${diagPdf}"
              width="100%"
              height="680px"
              
          ></iframe>
      </div>
    `;
  
    wica.ntcn(swal)
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intromodal') // 클래스명 설정
      .addOption({ padding: 20, height:840 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
  }
  
  const openClaimInfoModal = (id) => {
  
    const claim_day = auctionDetail.value?.data.claim_day;
    const done_at = new Date(auctionDetail.value?.data.done_at);
  
    const claim_day_date = done_at.getTime() + claim_day * 24 * 60 * 60 * 1000;
  
    if(claim_day_date < Date.now()){
  
      wica.ntcn(swal)
        .title('')
        .icon('E') //E:error , W:warning , I:info , Q:question
        .alert('클레임 기간을 지났습니다.');
  
      return;
    }
    const text = `<div style="height: 592px; overflow-y: auto;">
      <h3 style="text-align: center;">진단오류 보상 안내</h3>
        <div style="padding: 20px; text-align: left;">
          <p>진단결과에 중대한 오류가 있다면, 인수 후 영업일 기준 2일 내 클레임이 가능합니다.</p>
          <br/>
          <h5>중요</h5>
          <p>현장진단의 특성상 클레임 제외사항이 발생합니다. 아래의 사항은 클레임 대상에서 제외됩니다.</p>
          <br/>
          <ul>
              <li>• 문콕, 생활기스, 사용감, 미세누유, 소모품 상태, 썬팅손상 등 경미한 사항</li>
              <li>• 단순 판금/도장, 라디에이터 서포트, 크로스멤버 교환, 틀 틈, FRP 또는 비금속 재질의 교환/파손 등</li>
              <li>• 하체, 조향, 제동 등의 소모성 부품(비내구성 포함) 관련</li>
              <li>• 하부 진단이 반드시 필요한 누유</li>
              <li>• 자동차 제작사에서 보증수리 가능한 기능적 문제</li>
              <li>• 정지상태에서는 판단할 수 없는 고장</li>
              <li>• 차량 10년 이상, 주행거리 200,000km 이상</li>
              <li>• 주관적인 측면에서 문제가 되는 사항(냄새, 이음, 진동 등)</li>
          </ul>
          <br/>
          <h5>감가항목별 보상기준</h5>
          <table border="1" style="width: 100%; text-align: center; border:1px solid #000; border-collapse: collapse;">
            <tr>
                <th style="width: 30%; border: 1px solid #000; padding: 5px; background-color: #f0f0f0;">감가항목</th>
                <th style="width: 30%; border: 1px solid #000; padding: 5px; background-color: #f0f0f0;">감가율</th>
                <th style="width: 40%; border: 1px solid #000; padding: 5px; background-color: #f0f0f0;">비고</th>
            </tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">후드</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">프론트휀더</td><td style="border: 1px solid #000; padding: 5px;">2%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">도어</td><td style="border: 1px solid #000; padding: 5px;">2%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">트렁크리드</td><td style="border: 1px solid #000; padding: 5px;">2%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">귀타패널</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">루프패널</td><td style="border: 1px solid #000; padding: 5px;">6%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">사이드실패널</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">프론트패널</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">인사이드패널</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">사이드멤버</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">휠하우스</td><td style="border: 1px solid #000; padding: 5px;">5%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">필러패널</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">대쉬패널</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">트렁크플로어</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">리어패널</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">라디에이터 서포트</td><td style="border: 1px solid #000; padding: 5px;">1%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 5px;">패키지트레이</td><td style="border: 1px solid #000; padding: 5px;">3%</td><td style="border: 1px solid #000; padding: 5px;"></td></tr>
        </table>
          <br/>
          <ul>
            <li>※ 클레임 보상 : 매입금액 x감가율= 보상금역 (보상한도 100만원]</li>
            <li>※ 교환/단금에 대한 정의는 자동차관리법상 성능상태점검기준에 따라 적용</li>
            <li>※ 단금오류시 보상비율을 교환다비 50% 김가</li>
            <li>※ 사이드멤버. 필하우스, 인사이드 패널은 좌우앞뒤가 상관없이 단일품목으로 감가</li>
          </ul>
        
          <br/>
          <h5>비내구성 부품(소모품)</h5>
          <table border="1" style="width: 100%; text-align: left; border:1px solid #000; border-collapse: collapse;">
            <tr>
                <th style="width: 30%; border: 1px solid #000; padding: 5px; text-align: center; background-color: #f0f0f0;">구분</th>
                <th style="width: 70%; border: 1px solid #000; padding: 5px; text-align: center; background-color: #f0f0f0;">주변장치</th>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px; text-align: center;">엔진</td>
                <td style="border: 1px solid #000; padding: 5px;">
                  - 엔진장치 일체 (체너레이터, 콤프레서, 인젝터, 강흡 모터류, 열형장치, 케이블, 벨트류, 센서류, 플러그, 점화코일, 배전기, 스위치류 등) <br/>
                  - 냉각장치 (라디에이터) <br/>
                  - 터보 <br/>
                  </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px; text-align: center;">변속기 주진축</td>
                <td style="border: 1px solid #000; padding: 5px;">
                  - 클러치 케이블 및 변속조작장치 <br/>
                  - 입/출력 센서 <br/>
                  - 인디케이터스위치 <br/>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px; text-align: center;">앞뒤 차축</td>
                <td style="border: 1px solid #000; padding: 5px;">
                  - 현가 장치 부품 (소비부품, 쇼버(전자, 에어 등) 판스프링 등) <br/>
                  - 제동장치 부품 (라이닝, 도료, 브레이크디스크 등) <br/>
                  - 조향 장치 부품 (경기어, MDPS, 조인트 등) <br/>
                  ※ 월 허브, 너를, 링핀, 볼조인트, 허브베어링, 등 앞뒤 차속 관련부품 포함
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px; text-align: center;">기타</td>
                <td style="border: 1px solid #000; padding: 5px;">
                  - 배터리(AGM 포함), 고무부트, 상시 4WD. 연료핑프, 연료필터, 플랜저, 별트류, 엔진/미션 마운팅, 소유기, 모일물러, 크로스멤버, 타이어, 월 등 주기적인 교환 필요부품의 품 또는 함
                </td>
            </tr>
        </table> 
      </div>
      <div class="sticky-bottom">
        <a href="/board/claim/create/${id}" class="my-2 btn btn-primary w-100" @click="toClaim">클레임 신청하기</a>
      </div>
      </div>
    `;
  
    wica.ntcn(swal)
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intromodal') // 클래스명 설정
      .addOption({ padding: 20, height:840 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
  
  }
  
  const openDoneModal = (id) => {
  
    console.log('data!', auctionDetail.value?.data);
    const detailInfo = auctionDetail.value?.data;
  
    const created_at = detailInfo.created_at;
    const created_at_view = created_at.split(' ')[0];
  
    const car_first_reg_date = detailInfo.car_first_reg_date;
    const car_first_reg_date_view = car_first_reg_date.split('T')[0];
  
    // 금액 부분 3자리 콤마 추가 
    const final_price = detailInfo.final_price;
    const final_price_str = final_price.toLocaleString('ko-KR');
  
    const dealer_phone = detailInfo.dealer.phone;
    const dealer_phone_str = dealer_phone.replace(/(\d{3})(\d{4})(\d{4})/, '$1 - $2 - $3');
  
    const text = `
      <div id="printArea" style="font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; padding: 20px; border: 1px solid #ddd; text-align: left;">
      <h2 style="text-align: center; text-decoration: underline;">경 락 확 인 서</h2>
      
      <p class="text-center mt-3">「자동차등록규칙 제33조 2항 [나] 목에 근거하여 자동차경매거래를 증명합니다.』</p>
  
      <div style="margin-bottom: 20px; padding: 10px; border-radius: 5px;">
          <h5 style="margin-bottom: 10px; color: #333;">◼︎ 경락 내역</h5>
          <table style="width: 100%; border-collapse: collapse;">
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">출풍사</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">위카옥션(주)</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">상품번호</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.hashid ? detailInfo.hashid : '-'}</td>
              </tr>
  
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">경매회차</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.auction_count ? detailInfo.auction_count : '-'}</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">경매일</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${created_at_view ? created_at_view : '-'}</td>
              </tr>
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">차명</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.car_model ? detailInfo.car_model : '-'} ${detailInfo.car_model_sub ? detailInfo.car_model_sub : '-'}</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">차량번호</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.car_no ? detailInfo.car_no : '-'}</td>
              </tr>
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">연식</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.car_year ? detailInfo.car_year : '-'}</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">최초등록일</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${car_first_reg_date_view ? car_first_reg_date_view : '-'}</td>
              </tr>
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">배기량</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${diagnosticResult.value.diag_displacement ? diagnosticResult.value.diag_displacement : '-'}</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">계기판주행</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.car_km ? detailInfo.car_km + ' km' : '-'}</td>
              </tr>
          </table>
  
          <h5 style="margin-bottom: 10px; color: #333; margin-top: 30px;">◼︎ 경락 금액</h5>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; margin-top: 10px;">
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">경락대금</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 100%;" class="d-flex justify-content-between">
                  <span class="print-input" id="done-price">${final_price_str ? final_price_str : '-'} 만원</span>
                  <span class="text-danger" style="font-size: 12px;">(VAT 포함금액)</span>
                </td>
              </tr>
          </table>
          <p>※ 위 경락대금에 대한 입금사실을 확인합니다.</p>
  
          <h5 style="margin-bottom: 10px; color: #333; margin-top: 30px;">◼︎ 매도자 정보</h5>
          <table style="width: 100%; border-collapse: collapse;">
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">매도자</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.owner_name ? detailInfo.owner_name : '-'}</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">주민(법인)번호</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.personal_id_number ? detailInfo.personal_id_number : '-'}</td>
              </tr>
  
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">주소</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 80%;" colspan="3">
                  (${detailInfo.addr_post}) ${detailInfo.addr1} ${detailInfo.addr2}
                </td>
              </tr>
          </table>
  
  
          <h5 style="margin-bottom: 10px; color: #333; margin-top: 30px;">◼︎ 경락자 정보</h5>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; margin-top: 10px;">
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">상호명</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.dealer.company ? detailInfo.dealer.company : '-'}</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">사업자번호</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.dealer.business_registration_number ? detailInfo.dealer.business_registration_number : '-'}</td>
              </tr>
  
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">대표자명</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.dealer.name ? detailInfo.dealer.name : '-'}</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">연락처</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 30%;">${detailInfo.dealer.phone ? detailInfo.dealer.phone : '-'}</td>
              </tr>
  
              <tr>
                <td style="padding: 8px; border: 1px solid #ddd; width: 20%;">주소</td>
                <td style="padding: 8px; border: 1px solid #ddd; width: 80%;" colspan="3">
                  (${detailInfo.dest_addr_post ? detailInfo.dest_addr_post : detailInfo.dealer.company_post}) ${detailInfo.dest_addr1 ? detailInfo.dest_addr1 : detailInfo.dealer.company_addr1} ${detailInfo.dest_addr2 ? detailInfo.dest_addr2 : detailInfo.dealer.company_addr2}
                </td>
              </tr>
          </table>
  
  
          <p>이 문서는 (주)위카옥션의 승인 없이 수정할 수 없습니다.</p>
          <p>「자동차관리법』 제60조 제1항 및 동법시행규칙 제 126조 제3항의 규정에 의하여 개설된 자동차 경매장</p>
          <p>※ 상기차량을 경락 받은 매매업자가 상품용으로 이전등록하는 경우 매도인의 인감증영서 대신 본 경락확인서로 대신할 수 있음.</p>
  
      </div>
  
      <div style="margin-top: 20px; margin-bottom: 20px;">
        <h3 style="margin-bottom: 10px; color: #333; text-align: center;">위카옥션(주)</h3>
      </div>
  
    </div>
  
    <div class="d-flex justify-content-center">
      <button id="print-button" class="btn btn-primary" style="padding: 10px 20px 10px 20px; margin-top: 20px;">프린트</button>
    </div>
    `;
  
    wica.ntcn(swal)
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intromodal') // 클래스명 설정
      .addOption({ padding: 20, height:840 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
  
  
      setTimeout(() => {
        const printButton = document.getElementById('print-button');
        if (printButton) {
          printButton.addEventListener('click', () => {
            const printArea = document.getElementById('printArea');
            if (printArea) {
              printSpecificArea(printArea);
            }
          });
        }
      }, 300); // 모달이 완전히 렌더링될 시간을 조금 더 길게 줌
      
  }
  
  
  // 특정 영역만 프린트하는 함수
  function printSpecificArea(elementToPrint) {
    // 현재 페이지의 스타일을 저장
    const originalStyles = document.querySelectorAll('style, link[rel="stylesheet"]');
    const styles = Array.from(originalStyles).map(style => style.outerHTML).join('');
    
    // 입력된 데이터를 수집
    const inputData = {};
    const inputs = elementToPrint.querySelectorAll('.print-input');
    inputs.forEach(input => {
      inputData[input.id] = input.value;
    });
    
    // 새 창 열기
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    if (!printWindow) {
      alert('팝업이 차단되었습니다. 팝업 차단을 해제해주세요.');
      return;
    }
    
    // 입력 필드에 값을 채운 HTML 생성
    let printHTML = elementToPrint.outerHTML;
    
    // 입력 필드를 정적 텍스트로 변환
    Object.keys(inputData).forEach(id => {
      const value = inputData[id] || '';
      const inputRegex = new RegExp(`<input[^>]*id="${id}"[^>]*>`, 'g');
      printHTML = printHTML.replace(inputRegex, `<div style="padding: 8px; min-height: 20px;">${value}</div>`);
    });
    
    // HTML 구조를 문자열로 작성
    const printContent = `
      <html>
        <head>
          <title>경락확인서</title>
          ${styles}
          <style>
            body { margin: 0; padding: 0; }
            @media print {
              body { margin: 0; padding: 0; }
              button { display: none !important; }
            }
          </style>
        </head>
        <body>
          ${printHTML}
        </body>
      </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // 스크립트를 별도로 추가
    printWindow.onload = function() {
      setTimeout(function() {
        printWindow.print();
        setTimeout(function() { 
          printWindow.close(); 
        }, 100);
      }, 300);
    };
  }
  
  
  const toClaim = () => {
    if (!disableClaimButton.value) {
      router.push({ name: 'posts.create.withAuctionId', params: { boardId: 'claim', auctionId: auctionId.value } });
    }
  }
  
  const openCarLicenseModal = () => {
    const fileId = auctionDetail.value.data.files.file_auction_car_license[0].id;
    const fileName = auctionDetail.value.data.files.file_auction_car_license[0].file_name;
    const carLicenseUrl = '../media/' + fileId +'/' + fileName;
  
    let carLicenseHtml = '';
  
    if(carLicenseUrl){
      carLicenseHtml = `<iframe
              src="${carLicenseUrl}" 
              width="100%"
              height="600px"
              
          ></iframe>`;
    }else{
      carLicenseHtml = '<div style="text-align: center; padding: 20px;">자동차등록증이 없습니다.</div>';
    }
  
  
    const text = `
      <h5>자동차등록증</h5>
      <div id="car-license-modal" style="padding-top: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
          ${carLicenseHtml}
      </div>
    `;
  
    wica.ntcn(swal)
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intromodal') // 클래스명 설정
      .addOption({ padding: 20, height:700 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
  }
  
  const openDealerLicenseModal = () => {
  
    console.log('auctionDetail.value.data.top_bids',auctionDetail.value);
    const winBid = auctionDetail.value.data.win_bid?.id;
  
    let fileId, fileName;
    if (winBid) {
      const winningBid = auctionDetail.value.data.top_bids.find(bid => bid.id === winBid);
      if (winningBid && winningBid.dealerfile && winningBid.dealerfile.files && winningBid.dealerfile.files.file_user_biz) {
        fileId = winningBid.dealerfile.files.file_user_biz[0]?.id;
        fileName = winningBid.dealerfile.files.file_user_biz[0]?.file_name;
      }
    }
  
    let dealerLicenseUrl = '';
    if (fileId && fileName) {
      dealerLicenseUrl = `../media/${fileId}/${fileName}`;
    }
  
    let dealerLicenseHtml = '';
    if (dealerLicenseUrl) {
      dealerLicenseHtml = `<iframe
              src="${dealerLicenseUrl}" 
              width="100%"
              height="600px"
          ></iframe>`;
    } else {
      dealerLicenseHtml = '<div style="text-align: center; padding: 20px;">사업자등록증이 아직 등록되지 않았습니다.</div>';
    }
  
  
    const text = `
      <h5>매수자 사업자등록증</h5>
      <div id="dealer-license-modal" style="padding-top: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
          ${dealerLicenseHtml}
      </div>
    `;
  
    wica.ntcn(swal)
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intromodal') // 클래스명 설정
      .addOption({ padding: 20, height:700 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
  }
  
  
  const openNameChangeModal = () => {
    const nameChangeUrl = fileAuctionCompanyLicenseUrl.value;
  
    let nameChangeHtml = '';
  
    if(nameChangeUrl){
      nameChangeHtml = `<iframe
              src="${nameChangeUrl}" 
              width="100%"
              height="600px"
              
          ></iframe>`;
    }else{
      nameChangeHtml = '<div style="text-align: center; padding: 20px;">명의이전 등록증이 아직 등록되지 않았습니다.</div>';
    }
  
    const text = `
      <h5>명의이전 등록증</h5>
      <div id="name-change-modal" style="padding-top: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
          ${nameChangeHtml}
      </div>
    `;
  
    wica.ntcn(swal) 
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intromodal') // 클래스명 설정
      .addOption({ padding: 20, height:700 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
  
  }
  
  
  const openDiagResultModal = () => {
    const nameChangeUrl = diagnosticExtra.value.url_pdf;
  
    let addPDF = '';
  
    if(nameChangeUrl){
      addPDF = `<iframe
              src="${nameChangeUrl}" 
              width="100%"
              height="600px"
              
          ></iframe>`;
    }else{
      addPDF = '<div style="text-align: center; padding: 20px;">본사검수가 진행 되지 않아서 위카 진단평가 결과가 아직 없습니다.</div>';
    }
  
    const text = `
      <h5>위카 진단평가</h5>
      <div id="name-change-modal" style="padding-top: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
          ${addPDF}
      </div>
    `;
  
    wica.ntcn(swal) 
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intromodal') // 클래스명 설정
      .addOption({ padding: 20, height:700 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
  
  } 
  
  
  /*[사용자] 재경매 - 현재 날짜에서 D-3 일 후 라고 가정함.*/ 
  function getThreeDaysFromNow() {
    const currentDate = new Date();
    currentDate.setDate(currentDate.getDate() + 3);
    return currentDate.toISOString().slice(0, 19).replace('T', ' ');
  }
  
  
  const isModalVisible = ref(false);
  const selectedIndex = ref(null);
  
  /*[사용자] 재경매 - 버튼 눌렀을떄 처리되는 곳*/ 
  const reauction = async () => {
    const id = route.params.id;
    let data = {
      status: 'wait',
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
    console.log("옥션 디테일 :",auctionDetail?.value?.data.id);
    selectedAuctionId.value = auctionDetail?.value?.data.id;
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
  
    wica.ntcn(swal)
    .addOption({
      input: 'text',
      inputPlaceholder: '취소',
      inputValidator: (value) => {
        return value === '취소' ? undefined : '정확히 "취소"라고 입력해야 합니다.';
      }
    })
    .callback( async(rst) => {
      if (rst.isOk) {
        // 여기서 입력값도 활용 가능: rst.rawData?.inputValue
        console.log('취소 처리 진행');
        // 실제 취소 로직 실행
  
        try {
        const Auctioncancel = await updateAuctionStatus(selectedAuctionId.value, 'cancel');
        console.log('ddddd',Auctioncancel);
        if (Auctioncancel === false) {
          // 에러 처리 로직
          console.error("Auction cancellation error");
  
          // wica.ntcn(swal)
          // .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
          // .icon('I') //E:error , W:warning , I:info , Q:question
          // .alert('경매가 취소되었습니다.');
  
          window.location.href = '/auction/'+unique_number.value;
  
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
  
  
      }
    })
    .confirm('취소 하시려면 "취소"를 입력 후 확인을 누르면 취소 됩니다.');
    // alert('취소 하시려면 "취소"를 입력 하세요.');
    return false;
  
    
  };
  
  const AttachedInform = () => {
    const textOk = `<div class="enroll_box" style="position: relative;">
                      <img src="${carObjects}" alt="자동차 이미지" width="160" height="160">
                      <p class="overlay_text04">딜러 정보 첨부파일 추가</p>
                    </div>`;
  
    wica.ntcn(swal)
      .useHtmlText() // HTML 태그인 경우 활성화
      .btnBatch('R') // 확인 버튼 위치 지정, 기본은 L
      .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
      .addOption({ padding: 20 }) // swal 기타 옵션 추가
      .callback(async function (result) { // callback 함수를 async로 변경
      })
      .confirm(textOk);
  }
  
  
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
  const handleClick = (bid, event, index) => {
    
    if(auctionDetail.value.data.status !== 'wait'){
      // TODO: 알림을 toast 으로 나오도록 수정 
      wica.ntcn(swal).icon('S').title('경매가 진행중이므로 현재 딜러 선택을 할 수 없습니다.').fire();
      return;
    }
  
    selectedBid.value = bid;
    selectedIndex.value = index;
    const textOk = `<div>
      <div class="mb-5 text-start d-flex">
        <h5 class="custom-highlight">선택딜러 상세 정보</h5>
        </div>
          <div>
            <div class="profile ps-2 pt-0 pb-2 ms-0">
              <div class="dealer-info">
                <div class="img_box">
                  <img src="${getPhotoUrl(bid)}" alt="Profile Photo" class="profile-photo" />
                </div>
                <div class="text-start d-flex justify-content-center flex-column flex-nowrap align-center">
                  <h4>${bid.dealerInfo.name}</h4>
                  <p>${bid.dealerInfo.company}</p>
                  <p class="mt-2 restar">(${bid.points}점)</p>
                </div>
              </div>
            </div>
            <div>
            </div>
            <div class="pt-2 pb-3">
              <div class="info-item m-0">
                <div class="phone"></div>
                <p>010-1234-1234</p>
              </div>
              <div class="info-item m-0">
                <div class="location"></div>
                <p><span>${bid.dealerInfo.company_addr1}, ${bid.dealerInfo.company_addr2}</span></p>
              </div>
              <div style="max-height: 300px; overflow-y: auto;">
                <p class="text-start process">${bid.dealerInfo.introduce}</p>
              </div>
            </div>
          </div>
          <div class="top-content-style wd-100">
              <p class="text-secondary opacity-50">입찰 금액</p>
              <h4>${amtComma(bid.price)}</h4>
          </div>
          <p class=" mt-3 tc-gray">선택 완료시, 선택한 딜러에게 문자가 발송됩니다.</p>
      </div>`;
  
    wica.ntcn(swal)
      .useHtmlText() // HTML 태그인 경우 활성화
      .btnBatch('R') // 확인 버튼 위치 지정, 기본은 L
      .labelOk('딜러 선택') // 확인 버튼 라벨 변경
      .labelCancel('취소') // 취소 버튼 라벨 변경
      .addClassNm('review-custom') // 클래스명 변경, 기본 클래스명: wica-salert
      .addOption() // swal 기타 옵션 추가
      .callback(async function (result) { // callback 함수를 async로 변경
        if (result.isOk) {
          await completeAuction(bid);
          ChosenConfirmSelection();
        } 
      })
      .confirm(textOk);
  };
  const ChosenConfirmSelection = async () => {
    Chosenconfirm.value = false;
    if (selectedBid.value !== null && selectedIndex.value !== null) {
      await selectDealer(selectedBid.value, selectedIndex.value);
    }
    chosendlvr.value = true;
    selectedBid.value = null;
    selectedIndex.value = null;
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
  
  const handleDealerConfirm = ({ bid, userData, fileSignData }) => {
    selectedDealer.value = { ...bid, userData };
    connectDealerModal.value = false;
    chosendlvr.value=false;
    fileUserSignData.value = fileSignData;
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
  
  const completeAuction = async (bid) => {
    chosendlvr.value='false'
    auctionDetail.value.data.status = 'chosen';
    const id = route.params.id;
    const currentDate = new Date();
    const formattedDate = formatDateToString(currentDate);
  
    const data = {
      status: 'chosen',
      choice_at: formattedDate,
      final_price: bid.price,
      bid_id: bid.id, 
    };
  
    try {
      await chosenDealer(id, data);
      if(fileUserSignData){
        let fileUploadResult = await fileUserSignUpload(user.value.id,fileUserSignData.value);
      }
      auctionDetail.value.data.status = 'chosen';
      const textOk= `<div class="enroll_box" style="position: relative;">
                     <img src="${drift}" alt="자동차 이미지" width="160" height="160">
                     <p class="overlay_text04">선택이 완료 되었습니다.</p>
                     </div>`;
      wica.ntcn(swal)
        .useHtmlText() // HTML 태그 인 경우 활성화
        .addClassNm('primary-check') // 클래스명 변경, 기본 클래스명: wica-salert
        .addOption({ padding: 20}) // swal 기타 옵션 추가
        .callback(function (result) {
             ChosenConfirmSelection(); 
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
    wica.ntcn(swal).icon('S').title('유효한 금액을 입력해주세요.').fire();
    // alert('유효한 금액을 입력해주세요.');
  } else {
      // 최대값의 값을 만원 단위로 숫자를 바꾼다음 amount와 비교 하여 최대값을 넘어가지 않게 한다. 
      const maxAmount = auctionDetail.value?.data?.middle_prices?.max / 10000;
      const minAmount = auctionDetail.value?.data?.middle_prices?.min / 10000;
      const avgAmount = auctionDetail.value?.data?.middle_prices?.avg / 10000;
      const carPriceNowWhole = auctionDetail.value.data.car_price_now_whole / 10000;
  
      const amountValue = amount.value;
  
      console.log('amountValue',amountValue);
      console.log('carPriceNowWhole',carPriceNowWhole);
      console.log('minAmount',minAmount);
      console.log('maxAmount',maxAmount);
  
      if(amountValue < carPriceNowWhole * 0.6){
        wica.ntcn(swal).icon('S').title('도매가보다 60%가 낮은 금액은 입찰이 불가능합니다.').fire();
        return;
      }
  
      if(auctionDetail.value?.data?.bids_count < 3){
        if(amountValue < minAmount){
          wica.ntcn(swal).icon('S').title('경매 최소 금액을 넘어갑니다.').fire();
          return;
        }
      }else{
        if(amountValue > maxAmount){
          wica.ntcn(swal).icon('S').title('경매 최대 금액을 넘어갑니다.').fire();
          return;
        }
        if(amountValue < minAmount){
          wica.ntcn(swal).icon('S').title('경매 최소 금액을 넘어갑니다.').fire();
          return;
        }
      }
  
      openBidModal();
  };
  }
  
  
  const triggerFileUploadCompanyLicense = () => {
    if (fileInputRefCompanyLicense.value) {
      fileInputRefCompanyLicense.value.click();
    } else {
      console.error('파일을 찾을 수 없습니다.');
    }
  };
  
  const handleFileUploadCompanyLicense = (event) => {
    const file = event.target.files[0];


    fileAuctionCompanyLicenseName.value = file.name;

    // if(file){
    //   wica.ntcn(swal)
    //   .addClassNm('cmm-review-custom')
    //   .icon('I')
    //   .title(`파일이 선택되었습니다. 첨부하기를 클릭해 주세요.`)
    //   .alert();

    // }

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
  const scsbid = ref({});
  
  const fetchAuctionDetail = async () => {
    // const auctionId = parseInt(route.params.id);
    const auctionId = route.params.id;
  
    console.log('auctionId',auctionId);
  
    try {
      auctionDetail.value = await getAuctionById(auctionId);
      fetchPosts();
      /*
      const userInfoData = await getUser(auctionDetail.value.data.user_id);
      fileExstCheck(userInfoData);
      */
  
      // 현재 딜러가 낙찰되었는지 판단
      scsbid.value = auctionDetail.value.data.bids.find(bid => bid.id == auctionDetail.value.data.bid_id);
  
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
  
      console.log('carData',carData);
  
      carDetails.value.no = carData.no;
      carDetails.value.model = carData.model;
      carDetails.value.modelSub = carData.modelSub;
      carDetails.value.grade = carData.grade;
      carDetails.value.gradeSub = carData.gradeSub;
      carDetails.value.year = carData.year;
      carDetails.value.fuel = carData.fuel;
      carDetails.value.mission = carData.mission;
      carDetails.value.maker = carData.maker;
      carDetails.value.firstRegDate = carData.firstRegDate;
      carDetails.value.engineSize = carData.engineSize;
      carDetails.value.tuning = carData.tuning;
      carDetails.value.resUseHistYn = carData.resUseHistYn;
      carDetails.value.engineType = carData.engineType;
      const km = carData.km;
      const formattedKm = km.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      carDetails.value.km = formattedKm;
  
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
  
  const requestedFileUpload = async () => {
    const file = fileInputRefCompanyLicense.value.files[0];
    
    console.log('file',file);
  
    // 파일 업로드 하면서 유저에게 알림으로 전달!
    
    if (file) {
      
      const result = await nameChangeFileUpload(auctionDetail.value.data.id, file);
      fileAuctionCompanyLicenseName.value = result.data.media.name;

      if(result.data.media){

        wica.ntcn(swal)
        .icon('I')
        .addClassNm('cmm-review-custom')
        .addOption({ padding: 20})
        .callback(function(result) {
          window.location.reload();
        })
        .alert('명의이전이 확인되면 경매완료로 변경 됩니다.');

        // router.push({ name: 'AuctionDetail', params: { id: auctionDetail.value.data.id } });
      }
      
      //router.push({ name: 'AuctionDetail', params: { id: auctionDetail.value.data.id } });

      // if(result.data){
      //     wica.ntcn(swal)
      //     .icon('I')
      //     .addClassNm('cmm-review-custom')
      //     .addOption({ padding: 20})
      //     .callback(function(result) {
            
      //       router.push({ name: 'auction.details', params: { id: auctionDetail.value.data.id } });
          
      //     })
      //     .alert('명의이전 등록증이 업로드 되었습니다.');
      // }
  
    }else{
      wica.ntcn(swal)
      .icon('W')
      .addClassNm('cmm-review-custom')
      .addOption({ padding: 20})
      .callback(function(result) {
      })
      .alert('파일을 첨부해 주세요.');
  
      return;
    }
    
  }
  
  
  const diagnosticOptionViewObject = computed(() => {
    const items = diagnosticOptionView.value;
    const groupedHtml = [];
  
    const optionIcons = {
      1: { svg: option_icon_01_svg },
      2: { svg: option_icon_02_svg },
      3: { svg: option_icon_03_svg },
      4: { svg: option_icon_04_svg },
      5: { svg: option_icon_05_svg },
      6: { svg: option_icon_06_svg },
      7: { svg: option_icon_07_svg },
      8: { svg: option_icon_08_svg },
      9: { svg: option_icon_09_svg },
      10: { svg: option_icon_10_svg },
      11: { svg: option_icon_11_svg },
      12: { svg: option_icon_12_svg },
      13: { svg: option_icon_13_svg },
      14: { svg: option_icon_14_svg },
      15: { svg: option_icon_15_svg },
      16: { svg: option_icon_16_svg },
      17: { svg: option_icon_17_svg },
      18: { svg: option_icon_18_svg },
      19: { svg: option_icon_19_svg },
      71: { svg: option_icon_71_svg },
      86: { svg: option_icon_86_svg },
      88: { svg: option_icon_88_svg },
      92: { svg: option_icon_92_svg },
      93: { svg: option_icon_93_svg },
    };
  
    for (let i = 0; i < items.length; i += 6) {
      const group = items.slice(i, i + 6);
  
      const rowHtml = group.map(item => {
        const icon = optionIcons[item.id];
        const svg = icon ? icon.svg : null;

        if (svg) {
            const onDisplay = item.is_ok ? '#da3138' : '#bbbbbb';

            const renderedSvg = svg.replace(/#000000/g, onDisplay);

            let imgSize = 'style="width:34px;"';
            if(item.id == 19 || item.id == 17 || item.id == 18){
              imgSize = 'style="width:43px;"';
            }

            return `
            <div class="option-icon">
                <div class="icon" ${imgSize}>
                ${renderedSvg}
                </div>
                <p style="font-size: 10px !important;">${item.name}</p>
            </div>
            `;
        }

        // fallback
        return `
            <div class="option-icon">
            <p style="font-size: 10px !important;">${item.name}</p>
            </div>
        `;


      });
  
      // 부족한 칸만큼 빈 option-icon 채우기
      const emptySlots = 6 - group.length;
      for (let j = 0; j < emptySlots; j++) {
        rowHtml.push(`<div class="option-icon" style="width: 70px;"></div>`);
      }
  
      groupedHtml.push(`
        <div class="option-row">
          ${rowHtml.join('\n')}
        </div>
      `);
    }
  
    return groupedHtml.join('\n');
  });
  
  
  function openGallery(index) {
    lightbox?.loadAndOpen(index)
  }
  
  // 일정 간격으로 데이터를 갱신하는 함수
  const startPolling = () => {
    pollingInterval = setInterval(fetchAuctionDetail, 60000);
  };
  let timer;
  const currentTime = ref(new Date());
  onMounted(async () => {
    await fetchAuctionDetail();
  
    const auctionDetailData = auctionDetail.value.data;

    // auctionDetail.data.top_bids[0]?.dealerInfo?.biz_check

    // biz_check.value = auctionDetailData.top_bids.filter(bid => bid.id === auctionDetailData.win_bid.id)[0]?.dealerInfo?.biz_check;
    // console.log('biz_check',biz_check.value);

  
    console.log('auctionDetail//',auctionDetail.value.data);
    // unique_number.value = auctionDetail.value.data.unique_number;
    unique_number.value = auctionDetail.value.data.hashid;
    const nameChangeStatusValue = await nameChangeStatus(auctionDetail.value.data.id);
    // console.log('nameChangeStatusData',nameChangeStatusValue.data[0].chk_status);
  
    nameChangeStatusData.value = nameChangeStatusValue.data[0]?.status ? nameChangeStatusValue.data[0].status : '';
  
    // 명의이전서류 파일 이름 설정
    if(auctionDetail.value.data.files.file_auction_name_change){
      fileAuctionCompanyLicenseName.value = auctionDetail.value.data.files?.file_auction_name_change[0]?.name ? auctionDetail.value.data.files.file_auction_name_change[0].name : '';
      fileAuctionCompanyLicenseUrl.value = auctionDetail.value.data.files?.file_auction_name_change[0]?.original_url ? auctionDetail.value.data.files.file_auction_name_change[0].original_url : '';
    }
  
    document.title = auctionDetail.value.data.car_model +' '+ auctionDetail.value.data.car_model_sub +' - 위카옥션';
    timer = setInterval(() => {
      currentTime.value = new Date();
    }, 1000);
  
    const screenWidth = window.innerWidth;
    bottomSheetStyle.value = {
      position: screenWidth >= 1200 ? 'static' : 'fixed',
      bottom: '0px'
    };
  
    await getAuctions();
    const carHistoryCrashData = await getCarHistoryCrash(auctionDetail.value.data.car_no);
    carHistoryCrash.value = carHistoryCrashData.data;
    console.log('carHistoryCrash',carHistoryCrash.value);
  
    const getNiceDnrHistoryData = await getNiceDnrHistory(auctionDetail.value.data.owner_name, auctionDetail.value.data.car_no);
    console.log('niceDnrHistory',getNiceDnrHistoryData);
    niceDnrHistory.value = getNiceDnrHistoryData.data;
  
    const auctionLocationData = auctionDetail.value.data.addr1;
    // 주소 정보를 대전 > 중구 > 대흥동 으로 변환
    console.log('auctionLocationData',auctionLocationData);
    auctionLocation.value = auctionLocationData.split(' ').slice(0, 3).join(' > ');
    console.log('auctionLocation',auctionLocation.value);
  
    if(auctionDetailData.status === 'ing' && isUser.value){
      startPolling();
    }

  
    if(auctionDetailData.status == 'chosen' && isDealer.value){
  
      console.log('auctionDetail.??1',auctionDetail.value.data);
      console.log('user.value',user.value);
  
      if(auctionDetail.value.data.dest_addr_post){
        console.log('auctionDetail.value.data.dest_addr_post',auctionDetail.value.data.dest_addr_post);
        destAddrBtn.value = false;
        selectedAuction.value = {
          addr1 : auctionDetail.value.data.dest_addr1,
          addr2 : auctionDetail.value.data.dest_addr2,
          addr_post : auctionDetail.value.data.dest_addr_post,
        }
      }else{
        console.log('user.value.dealer',user.value.dealer);
        selectedAuction.value = {
          addr1 : user.value.dealer.company_addr1,
          addr2 : user.value.dealer.company_addr2,
          addr_post : user.value.dealer.company_post,
        }
      }
    }
  
  
    // const diagnosticData = await diagnostic(auctionDetail.value.data.unique_number);
    const diagnosticData = await diagnostic(auctionDetail.value.data.hashid);
    console.log('!!diagnosticData',diagnosticData);
  
    diagnosticResult.value = diagnosticData.data.data ? diagnosticData.data.data : {};
    diagnosticExtra.value = diagnosticData.data.extra ? diagnosticData.data.extra : {};
    diagnosticOptions.value = diagnosticData.data.options ? diagnosticData.data.options : {};
  
    diagnosticOptionView.value = diagnosticData.data.diag_option_view ? diagnosticData.data.diag_option_view : [];
  
  
    if(auctionDetail.value.data.car_thumbnails){
  
  
      console.log('auctionDetail.value.data.car_thumbnails',auctionDetail.value.data.car_thumbnails);
  
      photoSwipeImages.value = auctionDetail.value.data.car_thumbnails.map(file => ({
        src: file,
        w: 800,
        h: 600
      }));
    }
  
    console.log('photoSwipeImages',photoSwipeImages.value);
  
  
    lightbox = new PhotoSwipeLightbox({
      gallery: '#carThumbnail',
      children: '.carousel-item',
      pswpModule: () => import('photoswipe'),
      bgOpacity: 0.8,
    });
  
    lightbox.on('itemData', (e) => {
      e.itemData = photoSwipeImages.value[e.index]
    })
  
    lightbox.init();
  
  
    // diagnosticOptionViewObject();
  
    window.addEventListener('scroll', checkScroll);
  
    try {
      console.log('Sorted Top Bids:', sortedTopBids.value);
    } catch (error) {
      console.error('Error fetching auction detail:', error);
    }
    window.addEventListener('resize', checkScreenWidth);
      checkScreenWidth();
  
  
    // 로그아웃 되었으면
    if(user.value.id == null){
      router.push({ name: 'auth.login'});
      // window.location.href = '/login';
    }
    
  
    /*if (auctionDetail.value.data.status === 'done' && isDealer.value) {
      showNotification.value = true;
      setTimeout(() => {
        showNotification.value = false;
      }, 7000);
    }*/
  });
  const disableClaimButton = computed(() => isClaimed.value);
  
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
    
    // 입찰 취소시 취소 입력하여 취소 하기 추가 
    wica.ntcn(swal)
    .addOption({
      input: 'text',
      inputPlaceholder: '취소',
      inputValidator: (value) => {
        return value === '취소' ? undefined : '정확히 "취소"라고 입력해야 합니다.';
      }
    })
    .callback( async(rst) => {
      if (rst.isOk) {
  
        try {
          const myBid = auctionDetail.value?.data?.bids?.find(bid => bid.user_id === user.value.id && !bid.deleted_at);
          if (myBid) {
            const result = await cancelBid(myBid.id);
            if (result.success) {
              myBid.deleted_at = new Date().toISOString();
              await fetchAuctionDetail();
              amount.value = '';
              //succesbid.value = false;
              koreanAmount.value = '원';
              swal.fire({
                title: '',
                icon: 'info',
                text: '입찰이 취소되었습니다.',
                showConfirmButton: true
              }).then(() => {
                window.location.reload();
              });
  
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
        
      }
    })
    .confirm('취소 하시려면 "취소"를 입력 후 확인을 누르면 취소 됩니다.');
    // alert('취소 하시려면 "취소"를 입력 하세요.');
    return false;
    
  
  };
  
  const dealerAddrCompetion = async () => {
    setdestddress(auctionDetail.value.data.id,selectedAuction.value);
  }
  
  async function loadPage(page) {
    if (page < 1 || page > pagination.value.last_page) return;
    currentPage.value = page;
    renderAuctionItems(page);
    window.scrollTo(0, 0);
  }
  
  // 차량 세부 정보의 가시성을 제어하는 상태
  const showCarHistory = ref(false);
  
  // 차량 세부 정보의 가시성을 토글하는 함수
  function toggleCarHistory() {
    showCarHistory.value = !showCarHistory.value;
  }
  
  // const toggleDropdown = (section) => {
  //   openSection.value = openSection.value === section ? null : section;
  // };

  const toggleDropdown = (section) => {
    const index = openSection.value.indexOf(section);
    if (index > -1) {
      openSection.value.splice(index, 1); // 이미 열려 있으면 닫기
    } else {
      openSection.value.push(section); // 없으면 추가해서 열기
    }
  };

  </script>
  
  
  
  <style scoped>
  @media (min-width:992px) {.handle{display:none;}.sheet-content{width:80%!important;padding:0px!important;}.sheet{position:relative!important;border-radius:10px!important;}.web-content-style02{display:flex;gap:20px;padding:15px;justify-content:center;}}
  @media (min-width:992px) {.mov-wide{width:80vw;margin:auto;}.hv-25{height:auto!important;}}
  @media (max-width:991px) {.container{--bs-gutter-x:0rem!important;max-width:none!important;}}
  @media (max-width:406px) {.img-container{height:auto!important;max-width:400px;}}
  
  .animCircle::after{border-radius:50%;}
  .dealer-check{margin-top:50px;display:flex;align-items:center;justify-content:space-between;background:#F5F5F6;border-radius:30px;padding:10px;}
  .no-resize{resize:none;}
  .dealer-check input[type=checkbox]{margin-right:10px;}
  .dealer-check label{display:flex;align-items:center;font-size:14px;color:#333;}
  input[type="checkbox"]{align-self:center;}
  .card-style{padding-top:1.5rem;padding-right:1.5rem;padding-left:1.5rem;}
  .blinking{animation:blink 1.5s linear infinite;}
  
  #no-image {
    width:100%;
  }
  
  @keyframes blink{50%{opacity:0.5;}}
  
  .styled-input{border:none;outline:none;flex-grow:1;font-size:16px;padding-left:30px;color:#333;background-color:transparent;width:100%;direction:rtl;background-image:url('../../../../img/icon-won.png');background-repeat:no-repeat;background-size:20px 20px;background-position:left 0px center;}
  .styled-input::placeholder{color:#CCC;direction:ltr;}
  .more-page{color:white;font-size:16px;}
  .more-img{color:white;border:none;margin-left:10px;text-decoration:none;border-radius:3px;cursor:pointer;}
  
  @media screen and (min-width:1200px) {.bottom-sheet{width:50%;}}
  .sheet-content .label-style{top:22px!important;width:100px;}
  .sheet-content .label-style02{top:20px!important;width:100px;}
  .fade-enter-active,.fade-leave-active{transition:opacity 0.5s;}
  .fade-enter,.fade-leave-to{opacity:0;}
  
  @media (max-width:500px) {.container{--bs-gutter-x:0rem;}}
  
  .img-container{width:100%;height:458px;display:flex;justify-content:center;align-items:center;overflow:hidden;}
  .img-wrapper{width:100%;height:100%;position:relative;overflow:hidden;border-radius:10px;}
  .img-wrapper img{width:100%;height:100%;object-fit:cover;}
  .card-img-top-ty02{border-top-left-radius:6px;border-top-right-radius:6px;}
  .flex-column .card-img-top-ty02{border-top-left-radius:0px!important;border-bottom-left-radius:0px!important;border-top-right-radius:6px;border-top-left-radius:6px!important;}
  .img_box img{width:100%;height:100%;object-fit:cover;}
  /* .sheet.half{max-height:none!important;height:calc(fit-content + env(safe-area-inset-bottom))!important;} */
  
  @media screen and (min-width: 768px) {
    .sheet.half{
      max-height:none!important;
      height:auto!important;
    }
  }
  
  .scrollable-content{max-height:300px;overflow-y:auto;}
  .sticky-top{position:sticky;top:71px;z-index:1020;height:100px;}
  .sheet-content-wrap{width:100%!important;}
  
  .flex-container .column{width:48%;}
  
  @media (max-width:991px) {
    .flex-container .column{width:100%;}
  
    #app.mainPage > .user-nav-wrap + div {
      margin-top: 100px !important;
    }
  }
  
  
  .step-box {
    border: 2px solid #ff0000;
    border-radius: 10px;
  }
  
  
  .dropdown {
    margin-bottom: 10px;
  }
  
  .dropdown-btn {
    padding: 10px;
    width: 100%;
    text-align: left;
    font-size: 16px;
    cursor: pointer;
  }
  
  .dropdown-content {
    display: block;
    max-height:none;
    padding: 10px;
    border-top: none;
    background-color: #F5F5F6;
  }
  /* Slide animation */
  .slide-enter-active,
  .slide-leave-active {
    transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
    overflow: hidden;
  }
  .slide-enter-from,
  .slide-leave-to {
    max-height: 0;
    opacity: 0;
  }
  .slide-enter-to,
  .slide-leave-from {
    max-height: 500px; /* 적절한 최대 높이 값으로 조정 */
    opacity: 1;
  }
  
  .find-icons div{
    width: 33%;
    text-align:center;
    font-size: 13px;
  }
  
  .find-icons div img{
    width: 32%;
    height: 32%;
  }
  
  .title-sub {
    width: 200px !important;
  }
  
  .color-red {
    color:red;
  }
  
  
  .dlvr-sheet{
    height: auto !important;
  }
  /* 
  @media screen and (max-width: 768px) {
    .dlvr-sheet{
      height: calc(100vh - env(safe-area-inset-bottom)px - 200px) !important;
    }
  } */
  
  .circle {
    border:none;
  }
  
  #diag_base_option table tr th:first-child{
    width: 50px !important;
  }
  
  .html-table-wrapper table {
    font-size: 16px !important;
    border: 1px solid #ddd;
  }
  
  .inSlideContent {
    width:650px;
  }
  
  @media screen and (max-width:990px) {
    .inSlideContent {
      width:100%;
    }

    .bid-content {
      background-color: transparent;
    }
    
  }
  
  .carThumbnail img {
    height: 100%;
  }
  
  .carousel-inner {
    height: 100% !important;
    max-height: 100% !important;
  }
  
  .pswp__img{
    width: 90% !important;
    /* height: 100% !important; */
  }
  
  .option-icon p{
    font-size: 10px !important;
  }

  @media screen and (max-width:1400px) {
    .img-container img{
      height: 460px !important;
    }
  }


  .dealer-bottom-sheet {
    height: 100px !important;
  }

  
  </style>