<!--
TODO:
- 관심 차량 숫자 표기
- [사용자] - 딜러 선택이 가능해요, 후기남기기 (라우터 어디로..?)
- 옵션:최신등록순, 가격낮은순 처리
-->

<template>
    <!-- 서브 네비게이션 바 -->
    <div class="sub-nav row ">
        <div class="col-12 p-0">
            <div v-if="isUser" class="px-4 container mt-3">
                <nav class="navbar navbar-expand navbar-light">
                    <div class="navbar-nav gap-2">
                        <a class="nav-item nav-link" @click="setCurrentTab('allInfo')" :class="{ active: currentTab === 'allInfo' }">전체</a>
                        <a class="nav-item nav-link pe-0" @click="setCurrentTab('auctionDone')" :class="{ active: currentTab === 'auctionDone' }">판매한 차량<span class="interest mx-2">{{filteredDone.length}}</span></a>
                    </div>
                </nav>
            </div>
            <div v-if="isDealer" class="px-4 container mt-3">
                <nav class="navbar navbar-expand navbar-light">
                    <div class="navbar-nav gap-2">
                        <a class="nav-item nav-link" @click="setCurrentTab('allInfo')" :class="{ active: currentTab === 'allInfo' }">전체</a>
                        <a class="nav-item nav-link pe-0" @click="setCurrentTab('interInfo')" :class="{ active: currentTab === 'interInfo' }">관심 차량<span class="interest mx-2">{{ favoriteAuctionsData.length }}</span></a><!-- 관심 차량 숫자표기 -->
                        <a class="nav-item nav-link pe-0" @click="setCurrentTab('myBidInfo')" :class="{ active: currentTab === 'myBidInfo' }">내 입찰 차량<span class="interest mx-2">{{ bidsData.length }}</span></a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- 슬라이드업 메뉴 트리거 버튼 및 컨테이너 -->
    <!-- <div v-if="isUser"  class="review-button-container" :style="{ height: menuHeight }" @click="toggleMenuHeight">
        <div class="icon-container" v-show="isExpanded" ></div>
        <router-link :to="{ name: 'home' }" tag="button" class="black-btn tc-wh" v-show="!isExpanded" :disabled="isExpanded">딜러 선택이 가능해요!<span class="btn-apply-ty02">바로가기</span></router-link>
       
       <router-link :to="{ name: 'index.allreview' }" tag="button" class="review-btn tc-red" v-show="!isExpanded && hasCompletedAuctions" :disabled="isExpanded">후기 남기기</router-link>      
     
       <div class="review-none" v-show="!isExpanded && !hasCompletedAuctions" :disabled="isExpanded" @click.stop="">후기 남기기</div>
    </div>-->
    <div class="container my-3 auction-content ">
        <div class="content-main mt-5" :class="{'row': isDealer}">
            <!-- 사이드바 -->
            <div v-if="isDealer" class="sider-content col-md-3 mov-info02 ">
                <div class="side-content">
                    <h5>제조사 모델</h5>
                    <div class="mt-4">
                        <div class="demo"></div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                            <label class="form-check-label" for="hyundai">현대 <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">기아 <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">제네시스 <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">쉐보레(GM대우) <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check ">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">르노코리아(삼성) <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check ">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">KG모빌리티(쌍용) <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check ">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">BMW <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check ">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">화물 특징 기타 <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check ">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">벤츠 <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check ">
                            <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                            <label class="form-check-label" for="kia">아우디 <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                    </div>
                </div>
                <div class="sub-side mt-2">
                    <h5>분류</h5>
                    <div class="mt-4 pb-3 bd-gray">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="manufacturer" checked>
                            <label class="form-check-label" for="hyundai">국산차 <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="manufacturer">
                            <label class="form-check-label" for="kia">수입차 <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="manufacturer">
                            <label class="form-check-label" for="kia">화물 특장 기타 <span class="text-secondary mx-2">(1,356)</span></label>
                        </div>
                    </div>
                    <!-- 지역 섹션 -->
                    <div class="region mt-5">
                        <h5>지역</h5>
                        <div class="row mt-4 pb-3 bd-gray">
                            <!-- 서울 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="seoul" value="seoul" checked>
                                    <label class="form-check-label" for="seoul">서울</label>
                                </div>
                            </div>
                            <!-- 경기(북) 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="gyeonggi-north" value="gyeonggi-north">
                                    <label class="form-check-label" for="gyeonggi-north">경기(북)</label>
                                </div>
                            </div>
                            <!-- 강원 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="gangwon" value="gangwon">
                                    <label class="form-check-label" for="gangwon">강원</label>
                                </div>
                            </div>
                            <!-- 인천 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="incheon" value="incheon">
                                    <label class="form-check-label" for="incheon">인천</label>
                                </div>
                            </div>
                            <!-- 경기(남) 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="gyeonggi-south" value="gyeonggi-south">
                                    <label class="form-check-label" for="gyeonggi-south">경기(남)</label>
                                </div>
                            </div>
                            <!-- 충남 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="chungnam" value="chungnam">
                                    <label class="form-check-label" for="chungnam">충남</label>
                                </div>
                            </div>
                            <!-- 충북 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="chungbuk" value="chungbuk">
                                    <label class="form-check-label" for="chungbuk">충북</label>
                                </div>
                            </div>
                            <!-- 세종 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="sejong" value="sejong">
                                    <label class="form-check-label" for="sejong">세종</label>
                                </div>
                            </div>
                            <!-- 대전 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="daejeon" value="daejeon">
                                    <label class="form-check-label" for="daejeon">대전</label>
                                </div>
                            </div>
                            <!-- 경북 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="gyeongbuk" value="gyeongbuk">
                                    <label class="form-check-label" for="gyeongbuk">경북</label>
                                </div>
                            </div>
                            <!-- 전북 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="jeonbuk" value="jeonbuk">
                                    <label class="form-check-label" for="jeonbuk">전북</label>
                                </div>
                            </div>
                            <!-- 대구 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="daegu" value="daegu">
                                    <label class="form-check-label" for="daegu">대구</label>
                                </div>
                            </div>
                            <!-- 광주 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="gwangju" value="gwangju">
                                    <label class="form-check-label" for="gwangju">광주</label>
                                </div>
                            </div>
                            <!-- 경남 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="gyeongnam" value="gyeongnam">
                                    <label class="form-check-label" for="gyeongnam">경남</label>
                                </div>
                            </div>
                            <!-- 울산 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="ulsan" value="ulsan">
                                    <label class="form-check-label" for="ulsan">울산</label>
                                </div>
                            </div>
                            <!-- 전남 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="jeonnam" value="jeonnam">
                                    <label class="form-check-label" for="jeonnam">전남</label>
                                </div>
                            </div>
                            <!-- 제주 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="jeju" value="jeju">
                                    <label class="form-check-label" for="jeju">제주</label>
                                </div>
                            </div>
                            <!-- 부산 라디오 버튼 -->
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="region" id="busan" value="busan">
                                    <label class="form-check-label" for="busan">부산</label>
                                </div>
                            </div>
                        </div>
                        <div class="year-select mt-5 bd-gray">
                            <h5>연식</h5>
                            <div class="model-year mt-4 pb-4 d-flex align-items-center">
                                <select v-model="selectedStartYear" class="form-control custom-select">
                                    <option v-for="year in years" :key="'start-'+year" :value="year">
                                        {{ year }}년
                                    </option>
                                </select>
                                ~
                                <select v-model="selectedEndYear" class="form-control custom-select">
                                    <option v-for="year in years" :key="'end-'+year" :value="year">
                                        {{ year }}년
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class=" mt-4">
                            <h5>사고</h5>
                            <div class="mt-4 pb-3 bd-gray">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                                    <label class="form-check-label" for="hyundai">완전 무사고</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                    <label class="form-check-label" for="kia">단순교환 무사고</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                    <label class="form-check-label" for="kia">유사고</label>
                                </div>
                            </div>
                        </div>
                        <div class=" mt-4">
                            <h5>미션</h5>
                            <div class="mt-4 pb-3 bd-gray">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                                    <label class="form-check-label" for="hyundai">오토 (A/T)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                    <label class="form-check-label" for="kia">수동 (M/T)</label>
                                </div>
                            </div>
                        </div>
                        <div class=" mt-4">
                            <h5>주행거리</h5>
                            <div class="range-slider">
                                <input type="range" min="0" max="200000" value="0" id="min-range" class="range-min">
                                <input type="range" min="0" max="200000" value="200000" id="max-range" class="range-max">
                                <div class="slider-value">
                                    <span id="range-min-value">0km</span> ~ <span id="range-max-value">200,000km 이상</span>
                                </div>
                            </div>
                        </div>
                        <div class=" mt-4">
                            <h5>연료</h5>
                            <div class="row mt-4 pb-3 bd-gray">
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                                        <label class="form-check-label" for="hyundai">휘발류</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">디젤</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">LPG</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">하이브리드</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">바이퓨얼</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">전기</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">수소</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">PHEV</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mt-4">
                            <h5>경매 이력</h5>
                            <div class="mt-4 pb-3 bd-gray">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                                    <label class="form-check-label" for="hyundai">재경매차</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                    <label class="form-check-label" for="kia">내 입찰 차</label>
                                </div>
                            </div>
                        </div>
                        <div class=" mt-4">
                            <h5>리스렌트</h5>
                            <div class="row mt-4 pb-3 bd-gray">
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                                        <label class="form-check-label" for="hyundai">휘발류</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">현금 할부</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">금융라스</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">운용리스</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">렌트</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mt-4">
                            <h5>구동방식</h5>
                            <div class="row mt-4 pb-3">
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="hyundai" value="hyundai" checked>
                                        <label class="form-check-label" for="hyundai">2WD</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="manufacturer" id="kia" value="kia">
                                        <label class="form-check-label" for="kia">4WD</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 메인 컨텐츠 -->
            <div class="col-md-9 auction-main" :class="{'auction-wide': isUser}">
                <div class="d-flex gap-2">
                    <div class="apply-top text-start">
                        <div class="search-type">
                            <input type="text" class="border-6" placeholder="모델명,차량번호,지역">
                            <button type="button" class="search-btn start-0">검색</button>
                        </div>
                    </div>
                    <div v-if="isDealer" class="filter-content">
                        <!-- 페이지의 나머지 내용 -->
                        <button @click="toggleModal" class="animCircle filter-button tc-light-gray mx-2"> 필터</button>
                        <FilterModal v-if="showModal" @close="handleClose" />
                    </div>
                </div>
                <div>
                    <div class="container mb-3" v-if="currentTab == 'allInfo' && currentTab !== 'auctionDone'">
                        <div class="registration-content overflow-hidden">
                            <div class="text-start status-selector registration-content">
                                <div v-for="(label, value) in statusLabel" :key="value" class="mx-2">
                                    <input type="radio" name="status" :value="value" :id="value" :checked="value === 'all' " @change="event => setFilter(event.target.value)" />
                                    <label :for="value">{{ label }}</label>
                                </div>
                                <!--     <input type="radio" name="status" value="all" id="all" checked @change="event => setFilter(event.target.value)">
                                <label for="all" class="mx-2">전체</label>

                                <input type="radio" name="status" value="dlvr" id="dlvr" @change="event => setFilter(event.target.value)">
                                <label for="dlvr" class="mx-2">탁송진행</label>

                                <input type="radio" name="status" value="done" id="done"  @change="event => setFilter(event.target.value)">
                                <label for="done" class="mx-2">경매완료</label>

                                <input type="radio" name="status" value="chosen" id="chosen"  @change="event => setFilter(event.target.value)">
                                <label for="chosen" class="mx-2">선택완료</label>

                                <input type="radio" name="status" value="wait" id="wait" @change="event => setFilter(event.target.value)">
                                <label for="wait" class="mx-2">선택대기</label>

                                <input type="radio" name="status" value="ing" id="ing" @change="event => setFilter(event.target.value)">
                                <label for="ing" class="mx-2">경매진행</label>

                                <input type="radio" name="status" value="diag" id="diag" @change="event => setFilter(event.target.value)">
                                <label for="diag" class="mx-2">진단대기</label>

                                <input type="radio" name="status" value="ask" id="ask" @change="event => setFilter(event.target.value)">
                                <label for="ask" class="mx-2">신청완료</label>-->
                            </div>

                            <!--  <div class="text-end select-option">
                            <select class="form-select select-rank" aria-label="최근 등록 순">
                                <option selected>최근 등록 순</option>
                                <option value="1">가격 낮은 순</option>
                                <option value="2">가격 높은 순</option>
                                <option value="3">연식 오래된 순</option>
                                <option value="4">연식 최신 순</option>
                            </select>
                        </div>-->
                        </div>
                    </div>
                    <div :class="['container my-4', { 'pulling': isPulling && distance > 0, 'is-spinning': isSpinning }]" ref="pullContainer" @touchstart.passive="handleTouchStart" @touchmove.passive="handleTouchMove" @touchend="handleTouchEnd" v-if="currentTab === 'allInfo'">
                        <div v-if="isPulling" class="refresh-indicator" :style="imageStyle">
                            <img src="../../../img/Icon-refresh.png" alt="Refresh" class="fas fa-sync-alt" width="20px" :style="imagestyle" />
                        </div>
                        <div v-if="auctionsData.length > 0">
                            <div class="content-wrapper" :style="{ 'transform': `translateY(${Math.min(distance, 20)}px)` }">
                                <div class="row-wrapper">
                                    <div class="row" :class="{'pulled': isPulling && distance > 0}">
                                        <!-- if. 경매 ing 있을때 -->
                                        <div class="col-6 col-md-4 mb-4 pt-2 shadow-hover" v-for="auction in auctionsData" :key="auction.id" @click="navigateToDetail(auction)" :style="getAuctionStyle(auction)">
                                            <div class="card my-auction">
                                                <div v-if="isDealer">
                                                    <input class="toggle-heart" type="checkbox" :id="'favorite-' + auction.id" :checked="auction.isFavorited" @click.stop="toggleFavorite(auction)" />
                                                    <label class="heart-toggle" :for="'favorite-' + auction.id" @click.stop></label>
                                                </div>
                                                <!-- 경매 상태가 'ask'이거나 'diag'일 경우 -->
                                                <div v-if="auction.status === 'ask' || auction.status === 'diag'">
                                                    <div class="card-img-demo">
                                                        <img src="../../../img/demo.png" alt="경매대기 데모이미지" class="mb-3">
                                                    </div>
                                                </div>
                                                <div v-else="auction.status !== 'ask' || auction.status !== 'diag'" :class="{ 'grayscale_img': auction.status === 'done' || auction.status === 'cancel' ||(isDealer && auction.status === 'chosen') }" class="card-img-top-placeholder">
                                                    <img src="../../../img/car_example.png">
                                                </div>
                                                <span v-if="auction.status === 'dlvr'" class="mx-2 auction-done bg-info">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span>
                                                <div>
                                                    <span v-if="['done', 'cancel', 'chosen', 'diag', 'ask'].includes(auction.status)" class="mx-2 auction-done">
                                                        {{ wicas.enum(store).toLabel(auction.status).auctions() }}
                                                    </span>
                                                </div>
                                                <div class="d-flex">
                                                    <span v-if="(auction.status === 'ing' || auction.status === 'wait') && auction.timeLeft" class="mx-2 timer">
                                                        <img src="../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">
                                                        <span v-if="auction.timeLeft.days != '0' ">{{ auction.timeLeft.days }}일 &nbsp; </span>{{ auction.timeLeft.hours }}:{{ auction.timeLeft.minutes }}:{{ auction.timeLeft.seconds }}
                                                    </span>
                                                    <div v-if="isDealer">
                                                        <div class="participate-badge" v-if="auction.isDealerParticipating">
                                                            <span class="hand-icon">
                                                                <img src="../../../img/Icon-hand.png" alt="Hand Icon">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <!--<span v-if="auction.status === 'wait'" class="mx-2 timer"><img src="../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">D-3</span>-->
                                                </div>
                                                <!--  <div v-if="auction.status === 'done'" class="time-remaining">경매 완료</div>-->
                                                <!--  <div v-if="isDealer">-->
                                                <!-- <div v-if="auction.status === 'chosen'" class="time-remaining">경매 종료</div>
                                            </div>
                                            <div v-else>
                                                <div v-if="auction.status === 'chosen'" class="wait-selection">선택완료</div>
                                                <div v-if="auction.status === 'cancel'" class="time-remaining">경매 취소</div>
                                                <div v-if="auction.status === 'wait'" class="wait-selection">딜러 선택</div>
                                                <div v-if="auction.status === 'diag'" class="time-remaining">진단 대기</div>
                                                <div v-if="auction.status === 'ask'" class="time-remaining">신청 완료</div>
                                            </div>-->
                                                <div class="card-body">
                                                    <p class="card-title fs-5">더 뉴 그랜저 IG 2.5 가솔린 르블랑</p>
                                                    <p class="tc-light-gray mt-0"> 2020 년 / 2.4km / 무사고</p>
                                                    <p class="tc-light-gray mt-0">현대 소나타 (DN8)</p>
                                                    <div class="d-flex">
                                                        <h5 class="card-title"><span class="blue-box">무사고</span></h5>
                                                        <h5 v-if="auction.hope_price !== null"><span class="gray-box">재경매</span></h5>
                                                        <!--TODO: 이건 추후에 지우기 !! 일단 생성해놓음-->
                                                        <p class="tc-light-gray">{{ auction.car_no }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="complete-car">
                                <div class="card my-auction mt-3">
                                    <div class="none-complete">
                                        <span class="tc-light-gray"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item" :class="{ disabled: !pagination.prev }">
                                    
                                    <a class="page-link prev-style" @click="loadPage( pagination.current_page - 1, 'all', pagination)"></a>
                                </li>
                                <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === pagination.current_page }">
                                    <a class="page-link" @click="loadPage( n , 'all', pagination)">{{ n }}</a>
                                </li>
                                <li class="page-item next-prev" :class="{ disabled: !pagination.next }">
                                    <a class="page-link next-style" @click="loadPage( pagination.current_page + 1, 'all', pagination)"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="container my-4" v-if="currentTab === 'interInfo'">
                        <div v-if="favoriteAuctionsData.length > 0">
                            <!-- 경매 목록 -->
                            <div class="row">
                                <div class="col-6 col-md-4 mb-4 pt-2 shadow-hover" v-for="auction in favoriteAuctionsData" :key="auction.id" @click="navigateToDetail(auction)">
                                    <div class="card my-auction">
                                        <div v-if="isDealer">
                                            <input class="toggle-heart" type="checkbox" :id="'favorite-' + auction.id" :checked="auction.isFavorited" @click.stop="toggleFavorite(auction)" />
                                            <label class="heart-toggle" :for="'favorite-' + auction.id" @click.stop></label>
                                        </div>
                                        <!-- 경매 상태가 'ask'이거나 'diag'일 경우 -->
                                        <div v-if="auction.status === 'ask' || auction.status === 'diag'">
                                            <div class="card-img-demo">
                                                <img src="../../../img/demo.png" alt="경매대기 데모이미지" class="mb-3">
                                            </div>
                                        </div>
                                        <div v-else="auction.status !== 'ask' || auction.status !== 'diag'" :class="{ 'grayscale_img': auction.status === 'done' || auction.status === 'cancel' ||(isDealer && auction.status === 'chosen') }" class="card-img-top-placeholder">
                                            <img src="../../../img/car_example.png">
                                        </div>
                                        <span v-if="auction.status === 'dlvr'" class="mx-2 auction-done bg-info">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span>
                                        <div>
                                            <span v-if="['done', 'cancel', 'chosen', 'diag', 'ask'].includes(auction.status)" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span>
                                        </div>
                                        <div class="d-flex">
                                            <span v-if="(auction.status === 'ing' || auction.status === 'wait') && auction.timeLeft" class="mx-2 timer">
                                                <img src="../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">
                                                <span v-if="auction.timeLeft.days != '0' ">{{ auction.timeLeft.days }}일 &nbsp; </span>{{ auction.timeLeft.hours }}:{{ auction.timeLeft.minutes }}:{{ auction.timeLeft.seconds }}
                                            </span>
                                            <div v-if="isDealer">
                                                <div class="participate-badge" v-if="auction.isDealerParticipating">
                                                    <span class="hand-icon">
                                                        <img src="../../../img/Icon-hand.png" alt="Hand Icon">
                                                    </span>
                                                </div>
                                            </div>
                                            <!--<span v-if="auction.status === 'wait'" class="mx-2 timer"><img src="../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">D-3</span>-->
                                        </div>
                                        <!--  <div v-if="auction.status === 'done'" class="time-remaining">경매 완료</div>-->
                                        <!--  <div v-if="isDealer">-->
                                        <!-- <div v-if="auction.status === 'chosen'" class="time-remaining">경매 종료</div>
                                        </div>
                                        <div v-else>
                                            <div v-if="auction.status === 'chosen'" class="wait-selection">선택완료</div>
                                            <div v-if="auction.status === 'cancel'" class="time-remaining">경매 취소</div>
                                            <div v-if="auction.status === 'wait'" class="wait-selection">딜러 선택</div>
                                            <div v-if="auction.status === 'diag'" class="time-remaining">진단 대기</div>
                                            <div v-if="auction.status === 'ask'" class="time-remaining">신청 완료</div>
                                        </div>-->
                                        <div class="card-body">
                                            <p class="card-title fs-5">더 뉴 그랜저 IG 2.5 가솔린 르블랑</p>
                                            <p class="tc-light-gray mt-0"> 2020 년 / 2.4km / 무사고</p>
                                            <p class="tc-light-gray mt-0">현대 소나타 (DN8)</p>
                                            <div class="d-flex">
                                                <h5 class="card-title"><span class="blue-box">무사고</span></h5>
                                                <h5 v-if="auction.hope_price !== null"><span class="gray-box">재경매</span></h5>
                                                <!--TODO: 이건 추후에 지우기 !! 일단 생성해놓음-->
                                                <p class="tc-light-gray">{{ auction.car_no }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="card my-auction">
                                        <div class="card-img-top-placeholder"></div>
                                        <span v-if="auction.status === 'ing' && auction.timeLeft" class="mx-2 timer">
                                            <img src="../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">
                                            <span v-if="auction.timeLeft.days != '0' ">{{ auction.timeLeft.days }}일 &nbsp; </span>{{ auction.timeLeft.hours }}:{{ auction.timeLeft.minutes }}:{{ auction.timeLeft.seconds }}
                                        </span>
                                        <div class="card-body">
                                        <h5 class="card-title"><span class="blue-box">무사고</span>{{ auction.car_no }}</h5>
                                        <p class="card-text tc-light-gray">현대 쏘나타(DN8)</p>
                                        </div>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <!-- 하트 토글된 경매가 없을 경우의 메시지 -->
                            <div class="complete-car">
                                <div class="card my-auction mt-3">
                                    <div class="none-complete">
                                        <span class="tc-light-gray">관심 차량이 없습니다.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item" :class="{ disabled: !favoriteAuctionsPagination.prev }">
                                    
                                    <a class="page-link prev-style" 
                                    @click="loadPage( favoriteAuctionsPagination.current_page - 1, 'favorite', favoriteAuctionsPagination)"></a>
                                </li>
                                <li v-for="n in favoriteAuctionsPagination.last_page" :key="n" class="page-item" :class="{ active: n === favoriteAuctionsPagination.current_page }">
                                    <a class="page-link" @click="loadPage( n, 'favorite', favoriteAuctionsPagination)">{{ n }}</a>
                                </li>
                                <li class="page-item next-prev" :class="{ disabled: !favoriteAuctionsPagination.next }">
                                    <a class="page-link next-style" @click="loadPage( favoriteAuctionsPagination.current_page + 1, 'favorite', favoriteAuctionsPagination)"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="container my-4" v-if="currentTab === 'auctionDone'">
                        <div class="row">
                            <div class="col-6 col-md-4 mb-4 pt-2 shadow-hover" v-for="auction in filteredDone" :key="auction.id" @click="navigateToDetail(auction)" :style="getAuctionStyle(auction)">
                                <div class="card my-auction">
                                    <div class="card-img-top-placeholder grayscale_img"><img src="../../../img/car_example.png"></div>
                                    <span v-if="auction.status === 'done'" class="mx-2 auction-done">경매완료</span>
                                    <div class="card-body">
                                        <h5 class="card-title"><span class="blue-box">무사고</span>{{auction.car_no}}</h5>
                                        <p class="card-text tc-light-gray">현대 쏘나타(DN8)</p>
                                    </div>
                                </div>
                            </div>
                            <div v-if="!filteredDone" class="complete-car">
                                <div class="card my-auction mt-3">
                                    <div class="none-complete">
                                        <span class="tc-light-gray">판매 차량이 없습니다.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                    <div class="container my-4" v-if="currentTab === 'myBidInfo'">
                        <div v-if="bidsData.length > 0">
                            <!-- 경매 목록 -->
                            <div class="row">
                                <div class="col-6 col-md-4 mb-4 pt-2 shadow-hover" v-for="bid in bidsData" :key="bid.id" @click="navigateToDetail(bid.auction)">
                                    <div class="card my-auction">
                                        <div v-if="isDealer">
                                            <input class="toggle-heart" type="checkbox" :id="'favorite-' + bid.auction.id" :checked="bid.auction.isFavorited" @click.stop="toggleFavorite(bid.auction)" />
                                            <label class="heart-toggle" :for="'favorite-' + bid.auction.id" @click.stop></label>
                                        </div>
                                        <!-- 경매 상태가 'ask'이거나 'diag'일 경우 -->
                                        <div v-if="bid.auction.status === 'ask' || bid.auction.status === 'diag'">
                                            <div class="card-img-demo">
                                                <img src="../../../img/demo.png" alt="경매대기 데모이미지" class="mb-3">
                                            </div>
                                        </div>
                                        <div v-else="bid.auction.status !== 'ask' || bid.auction.status !== 'diag'" :class="{ 'grayscale_img': bid.auction.status === 'done' || bid.auction.status === 'cancel' ||(isDealer && bid.auction.status === 'chosen') }" class="card-img-top-placeholder">
                                            <img src="../../../img/car_example.png">
                                        </div>
                                        <span v-if="bid.auction.status === 'dlvr'" class="mx-2 auction-done bg-info">{{ wicas.enum(store).toLabel(bid.auction.status).auctions() }}</span>
                                        <div>
                                            <span v-if="['done', 'cancel', 'chosen', 'diag', 'ask'].includes(bid.auction.status)" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(bid.auction.status).auctions() }}</span>
                                        </div>
                                        <div class="d-flex">
                                            <span class="mx-2 timer">
                                                <img src="../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">
                                                <span v-if="bid.auction.timeLeft.days != '0' ">{{ bid.auction.timeLeft.days }}일 &nbsp; </span>{{ bid.auction.timeLeft.hours }}:{{ bid.auction.timeLeft.minutes }}:{{ bid.auction.timeLeft.seconds }}
                                            </span>
                                            <div v-if="isDealer">
                                                <div class="participate-badge" v-if="bid.auction.isDealerParticipating">
                                                    <span class="hand-icon">
                                                        <img src="../../../img/Icon-hand.png" alt="Hand Icon">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-title fs-5">더 뉴 그랜저 IG 2.5 가솔린 르블랑</p>
                                            <p class="tc-light-gray mt-0"> 2020 년 / 2.4km / 무사고</p>
                                            <p class="tc-light-gray mt-0">현대 소나타 (DN8)</p>
                                            <div class="d-flex">
                                                <h5 class="card-title"><span class="blue-box">무사고</span></h5>
                                                <h5 v-if="bid.auction.hope_price !== null"><span class="gray-box">재경매</span></h5>
                                                <!--TODO: 이건 추후에 지우기 !! 일단 생성해놓음-->
                                                <p class="tc-light-gray">{{ bid.auction.car_no }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="complete-car">
                                <div class="card my-auction mt-3">
                                    <div class="none-complete">
                                        <span class="tc-light-gray">입찰한 차량이 없습니다.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item" :class="{ disabled: !bidPagination.prev }">
                                    
                                    <a class="page-link prev-style" @click="loadPage( bidPagination.current_page - 1, 'bid', bidPagination)"></a>
                                </li>
                                <li v-for="n in bidPagination.last_page" :key="n" class="page-item" :class="{ active: n === bidPagination.current_page }">
                                    <a class="page-link" @click="loadPage( n, 'bid', bidPagination)">{{ n }}</a>
                                </li>
                                <li class="page-item next-prev" :class="{ disabled: !bidPagination.next }">
                                    <a class="page-link next-style" @click="loadPage( bidPagination.current_page + 1, 'bid', bidPagination)"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <div class="container my-4" v-if="currentTab === 'auctionDone'">

                    <div>
                    </div>
                </div>
                <!--
                <div class="container mt-5 mov-info02">
                    <table class="table custom-border">
                        <tbody class="auction-table">
                            <tr>
                                <td class="auction_no tc-light-gray">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="gray-box">미참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain tc-light-gray"></td>
                                <td><span class="auc-blue">선택대기</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no tc-light-gray">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain tc-light-gray"></td>
                                <td><span class="auc-gray">진행중</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no tc-light-gray">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain tc-light-gray"></td>
                                <td><span class="auc-black">경매완료</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no tc-light-gray">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain tc-light-gray"></td>
                                <td><span class="auc-blue">선택대기</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no tc-light-gray">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain tc-light-gray"><span class="tc-red">46</span>분남음</td>
                                <td><span class="auc-gray">진행중</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no tc-light-gray">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain tc-light-gray"><span class="tc-red">46</span>분남음</td>
                                <td><span class="auc-blue">선택대기</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no tc-light-gray">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain tc-light-gray"><span class="tc-red">46</span>분남음</td>
                                <td><span class="auc-blue">선택대기</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no tc-light-gray">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain tc-light-gray"><span class="tc-red">46</span>분남음</td>
                                <td class="auc-state"><span class="auc-gray">진행중</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                -->
                <!-- Pagination -->
            </div>
        </div>
    </div>
    <Footer />
</template>
<script>
export default {
  data() {
    return {
      isExpanded: false, // 하단 메뉴 확장 상태
      scrollY: 0, // 스크롤 위치 저장
      scrollTimeout: null, // 스크롤 타임아웃 저장
      selectedYear: new Date().getFullYear(), // 선택된 연도 (현재 연도)
      years: [] // 연도 목록 저장
    };
  },

  computed: {
    menuHeight() { // 하단 메뉴 높이 계산
      return this.isExpanded ? '0px' : '115px';
    }
  },

  created() {
    window.addEventListener('scroll', this.handleScroll); // 스크롤 이벤트 리스너 추가
    this.years = this.generateYearRange(1990, new Date().getFullYear()); // 연도 목록 생성
  },

  methods: {
    toggleMenuHeight() { // 하단 메뉴 높이 토글
      this.isExpanded = !this.isExpanded;
    },
    submitReview() { // 리뷰 제출 시 메뉴 높이 토글
      this.toggleMenuHeight();
    },
    generateYearRange(start, end) { // 연도 범위 생성
      const years = [];
      for (let year = start; year <= end; year++) {
        years.push(year);
      }
      return years;
    }
  },

  beforeDestroy() { // 컴포넌트 제거 시 스크롤 이벤트 리스너 제거
    window.removeEventListener('scroll', this.handleScroll);
    clearTimeout(this.scrollTimeout);
  }
};

</script>
<script setup>
import { ref, computed, onMounted, reactive, onUnmounted , inject } from 'vue';
import { useStore } from "vuex";
import useAuctions from "@/composables/auctions"; 
import useRoles from '@/composables/roles'; 
import FilterModal from '@/views/modal/filter.vue'; 
import { useRoute, useRouter } from 'vue-router';
import Footer from "@/views/layout/footer.vue";
import useLikes from '@/composables/useLikes';
import usebid from '@/composables/bids.js'; 
import { cmmn } from '@/hooks/cmmn';
import { isError } from 'lodash';

const swal = inject('$swal');
const { wicas , wica } = cmmn();
const selectedStartYear = ref(new Date().getFullYear() - 1);
const selectedEndYear = ref(new Date().getFullYear());
const {getBids, bidsData , bidPagination } = usebid();
const { getLikes, likesData, isAuctionFavorited , like , setLikes , deleteLike , getAllLikes} = useLikes();
const router = useRouter();
const route = useRoute();
const currentStatus = ref('all'); 
const { role, getRole } = useRoles();
const currentTab = ref('allInfo'); 
const { auctionsData, pagination, getAuctions, getAuctionsByDealer, getAuctionsByDealerLike } = useAuctions();

const currentPage = ref(1); 
const currentFavoritePage = ref(1); 
const currentMyBidPage = ref(1); 

const showModal = ref(false); 
//const interestCount = computed(() => auctionsData.value.filter(auction => auction.isInterested).length); 
//const auctionModal = ref(false);
const isPulling = ref(false);
const distance = ref(0);
const store = useStore();
const user = computed(() => store.getters["auth/user"]); 
const isDealer = computed(() => user.value?.roles?.includes('dealer')); 
const isUser = computed(() => user.value?.roles?.includes('user')); 
const isSpinning = ref(false);
let statusLabel;
//let likeMessage;

const favoriteAuctionsData = ref({}); //관심 차량 데이터
const favoriteAuctionsPagination = ref({}); //관심 차량 페이징

/**
const initializeFavorites = () => {


    const userId = user.value.id; // 현재 사용자 ID
    const userLikes = likesData.value.filter(like => like.user_id === userId); // 현재 사용자의 좋아요 데이터 필터링

    console.log('Filtered Likes Data:', userLikes); // 필터링된 좋아요 데이터 콘솔 출력

    auctionsData.value.forEach(auction => {
        auction.isFavorited = userLikes.some(like => like.likeable_id === auction.id);
    });

    // 좋아요 매물 ID 출력
    const likedAuctionIds = userLikes.map(like => like.likeable_id);
    console.log('Liked Auction IDs:', likedAuctionIds); 
};*/

const toggleFavorite = (auction) => {
    console.log(auction);
    auction.isFavorited = !auction.isFavorited;
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
    if(response.isSuccess){
        wica.ntcn(swal).icon('S').title('관심 차량이 추가되었습니다.').fire();
        fetchFilteredViewLikes();
    }
};

const removeLike = async (auction) => {
    console.log(auction);
    const response = await deleteLike(auction.like.id);
    console.log(response);
    if(response.isSuccess){
        wica.ntcn(swal).icon('S').title('관심 차량이 취소되었습니다.').fire();
        fetchFilteredViewLikes();
    }
    
};

/**
function showLikeMessage(cl) {
    likeMessageVisible.value = true;
    if(cl == "add"){
        likeMessage = '관심 차량이 추가되었습니다.'
    } else if(cl == "remove"){
        likeMessage = '관심 차량이 삭제되었습니다.'
    }
    
    setTimeout(() => {
        likeMessageVisible.value = false;
        fetchFilteredViewLikes();
        //location.reload();
        //currentTab.value = 'allInfo';
    }, 1000);
    
} */

const pullContainer = ref(null);
const state = reactive({
  startY: 0,
  isPulling: false,
  distance: 0
});
const currentTime = ref(new Date());
const padZero = (num) => {
  return num < 10 ? '0' + num : num;
};

const calculateTimeLeft = (auction) => {
    const finalAtDate = new Date(auction.final_at);
    const diff = finalAtDate.getTime() - currentTime.value.getTime();
    if (auction.status !== 'ing' || !auction.final_at || diff < 0) {
        return {
            days: 0,
            hours: '00',
            minutes: '00',
            seconds: '00'
        };
    }
    return {
        days: Math.floor(diff / (24 * 3600000)),
        hours: padZero(Math.floor((diff % (24 * 3600000)) / 3600000)),
        minutes: padZero(Math.floor((diff % 3600000) / 60000)),
        seconds: padZero(Math.floor((diff % 60000) / 1000)),
    };
};

const updateAuctionTimes = (auction) => {
    auction.forEach((auction) => {
        auction.timeLeft = calculateTimeLeft(auction);
    });
};

const handleTouchStart = (e) => {
    state.startY = e.touches[0].pageY;
    state.isPulling = true;
};

const handleTouchMove = (e) => {
    if (window.scrollY !== 0) return; 
    const currentY = e.touches[0].pageY;
    const diff = currentY - state.startY;
    if (diff > 0) {
        isPulling.value = true;
        distance.value = Math.min(diff, 100); 
    }
};

const handleTouchEnd = async () => {
    if (distance.value === 100) { 
        isSpinning.value = true; 
        setTimeout(async () => {
            fetchFilteredViewLikes();
            isSpinning.value = false; 
            isPulling.value = false;
            distance.value = 0;
        }, 3000); 
    } else {
        isPulling.value = false;
        distance.value = 0;
    }
};

/**
const favoriteAuctions = computed(() => {
    return auctionsData.value.filter(auction => auction.isFavorited);
}); 

const hasCompletedAuctions = computed(() => { 
    return auctionsData.value.some(auction => auction.status === 'done');
});

*/

function setCurrentTab(tab) {
    currentTab.value = tab;
}

function toggleModal() { 
    showModal.value = !showModal.value; 
}

function setFilter(status) { 
    currentStatus.value = status;
    fetchFilteredViewLikes();
}

function handleClose() { 
    showModal.value = false;
}



const filteredDone = computed(() => { 
    return auctionsData.value.filter(auction => ['done'].includes(auction.status));
});

function loadPage( page, type, pagination) {
    if (page < 1 || page > pagination.last_page) return;
    
    window.scrollTo(0, 0);
    
    switch (type) {
        case 'all':
            currentPage.value = page;
            fetchFilteredViewLikes();
            break;
        case 'favorite':
            currentFavoritePage.value = page;
            favoriteAuctionsGetData();
            break;
        case 'bid':
            currentMyBidPage.value = page;
            fetchFilteredBids();
            break;
        default:
            console.error('Unknown page type');
    }
}

function navigateToDetail(auction) { 
    //console.log("디테일 :", auction.id);
    router.push({ name: 'AuctionDetail', params: { id: auction.id } });
}

function getAuctionStyle(auction) { 
    const validStatuses = ['done', 'wait', 'ing', 'diag'];
    return validStatuses.includes(auction.status) ? { cursor: 'pointer' } : {};
}

function isDealerParticipating(auctionId) { 
    console.log(bidsData.value);
    return bidsData.value.some(bid => bid.auction_id === auctionId);
}

const fetchFilteredViewLikes = async () => {
    if(isUser.value){
        await getAuctions(currentPage.value , false , currentStatus.value );
    } else if(isDealer.value){
        await getAuctionsByDealer(currentPage.value , currentStatus.value );
    }
    
    filterLikeData(auctionsData.value);

}

const favoriteAuctionsGetData = async () => {
    await getBids(currentMyBidPage.value, false, true, user.value.id);
    const response = await getAuctionsByDealerLike(currentFavoritePage.value , user.value.id);

    favoriteAuctionsData.value = response.data;
    favoriteAuctionsPagination.value = response.rawData.data.meta;
    //console.log("favoriteAuctionsData : ",favoriteAuctionsData.value);
    
    filterLikeData(favoriteAuctionsData.value);
}

const fetchFilteredBids = async () => { 
    await getAllLikes('Auction', user.value.id);
    bidsData.value.forEach(bid => {
        bid.auction = auctionsData.value.find(auction => parseInt(auction.id) === bid.auction_id);
        const matchedLike = likesData.value.find(like => parseInt(like.likeable_id) === bid.auction_id);

        if (matchedLike) {
            bid.auction.like = matchedLike;
            bid.auction.isFavorited = true;
        } else {
            bid.auction.isFavorited = false;
        }
        bid.auction.isDealerParticipating = isDealerParticipating(bid.auction.id);
    });
};

const filterLikeData = (auctions) => {
    auctions.forEach(auction => {
        const userLike = auction.likes.find(like => like.user_id === user.value.id);
        if (userLike) {
            auction.like = userLike;
            auction.isFavorited = true;
        } else {
            auction.isFavorited = false;
        }
        auction.isDealerParticipating = isDealerParticipating(auction.id);
    });
}


let timer;

onMounted(async () => {

    if(history.state.currentTab){
        currentTab.value = history.state.currentTab;
    } 
    
    fetchFilteredViewLikes();
    favoriteAuctionsGetData();
    fetchFilteredBids();

    //console.log("bids",bidsData.value);
	statusLabel = wicas.enum(store).addFirst('all','전체').excl('cancel','취소').ascVal().auctions();

    if (role.value.name === 'user') {
        isUser.value = true;
    }
    
    timer = setInterval(() => {
        currentTime.value = new Date();
        updateAuctionTimes(auctionsData.value);
        updateAuctionTimes(favoriteAuctionsData.value);
        updateAuctionTimes(bidsData.value);
    }, 1000);
    
    
});

onUnmounted(() => {
    clearInterval(timer);
});


</script>


<style scoped>
.card-img-top-placeholder {
    border-bottom-right-radius: 6px;
    border-bottom-left-radius: 6px;
}

.refresh-indicator {
    text-align: center;
    overflow: hidden; 
    height: auto; 
    width: 100%;
}
.fa-sync-alt {
  animation: spin 3s linear infinite; 
  animation-play-state: paused; 
}

.pulling .fa-sync-alt {
  animation-play-state: paused; 
}

.is-spinning .fa-sync-alt {
  animation-play-state: running; 
}

@keyframes spin {
  100% { transform: rotate(1080deg); } 
}
@media (max-width: 449px){
.col-md-4 {
    flex: 0 0 auto;
    width: 100% !important;
}
}

@media (min-width: 768px){
.auction-wide {
    flex: 0 0 auto;
    width: 100%;
}
}
.usernav{
    padding-right: 55% !important;
}
@media (max-width: 768px){
    .usernav{
    padding-right: 35% !important;
    padding-left: 5%;
}
}
@media (max-width: 991px){
.status-selector {
    overflow-x: scroll !important;
}
}
</style>
 