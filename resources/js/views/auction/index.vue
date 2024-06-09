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
            <div v-if="isUser" class="nav-container mt-3">
                <nav class="navbar navbar-expand navbar-light">
                    <div class="navbar-nav">
                        <a class="nav-item nav-link"@click="setCurrentTab('allInfo')" :class="{ active: currentTab === 'allInfo' }">전체</a>
                        <a class="nav-item nav-link"@click="setCurrentTab('auctionDone')" :class="{ active: currentTab === 'auctionDone' }">판매한 차량<span class="interest mx-1">{{filteredDone.length}}</span></a>
                    </div>
                </nav>
            </div>
            <div v-if="isDealer" class="nav-container mt-3">
                <nav class="navbar navbar-expand navbar-light">
                    <div class="navbar-nav">
                        <a class="nav-item nav-link"@click="setCurrentTab('allInfo')" :class="{ active: currentTab === 'allInfo' }">전체</a>
                        <a class="nav-item nav-link" @click="setCurrentTab('interInfo')" :class="{ active: currentTab === 'interInfo' }">관심 차량<span class="interest mx-1">{{ favoriteAuctions.length }}</span></a><!-- 관심 차량 숫자표기 -->
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
    <div class="container my-3 auction-content">
        <div class="row content-main mt-5">
            <!-- 사이드바 -->
            <div class="sider-content col-md-3 mov-info02">
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
            <div class="col-md-9 auction-main">
                <div class="apply-top text-start">
                    <div class="search-type">
                        <input type="text" placeholder="모델명,차량번호,지역">
                        <button type="button" class="search-btn">검색</button>
                    </div>
                </div>
                <div class="container mb-3" v-if="currentTab !== 'interInfo' && currentTab !== 'auctionDone'">
                    <div class="registration-content">
                        <div class="text-start status-selector">
                        <input type="radio" name="status" value="all" id="all" hidden checked @change="setFilter('all')">
                        <label for="all" class="mx-2">전체</label>

                        <input type="radio" name="status" value="ing" id="ongoing" hidden @change="setFilter('ing')">
                        <label for="ongoing">진행중</label>

                        <input type="radio" name="status" value="done" id="completed" hidden @change="setFilter('done')">
                        <label for="completed" class="mx-2">완료</label>
                        </div>

                        <div class="text-end select-option">
                            <select class="form-select select-rank" aria-label="최근 등록 순">
                                <option selected>최근 등록 순</option>
                                <option value="1">가격 낮은 순</option>
                                <option value="2">가격 높은 순</option>
                                <option value="3">연식 오래된 순</option>
                                <option value="4">연식 최신 순</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div :class="['container my-4', { 'pulling': isPulling && distance > 0, 'is-spinning': isSpinning }]" ref="pullContainer" @touchstart.passive="handleTouchStart" @touchmove.passive="handleTouchMove" @touchend="handleTouchEnd" v-if="currentTab === 'allInfo'">
                    <div v-if="isPulling" class="refresh-indicator" :style="imageStyle">
                        <img src="../../../img/Icon-refresh.png" alt="Refresh" class="fas fa-sync-alt" width="20px" :style="imagestyle"/>
                    </div>
                    <div class="content-wrapper" :style="{ 'transform': `translateY(${Math.min(distance, 20)}px)` }">
                        <div class="row-wrapper">
                            <div class="row" :class="{'pulled': isPulling && distance > 0}">
                                <!-- if. 경매 ing 있을때 -->
                                <div
                                    class="col-6 col-md-4 mb-4 pt-2 shadow-hover"
                                    v-for="auction in filteredAuctions"
                                    :key="auction.id"
                                    @click="navigateToDetail(auction)"
                                    :style="getAuctionStyle(auction)"
                                >
                                    <div class="card my-auction">
                                        <div v-if="isDealer"> 
                                            <input class="toggle-heart" type="checkbox" v-model="auction.isFavorited"  @click.stop/>
                                            <label class="heart-toggle"></label>
                                            <div class="participate-badge" v-if="isDealerParticipating(auction)">참여</div>
                                        </div>
                                        <!-- 경매 상태가 'ask'이거나 'diag'일 경우 -->
                                        <div v-if="auction.status === 'ask' || auction.status === 'diag'">
                                            <div class="card-img-demo">
                                                <img src="../../../img/demo.png" alt="경매대기 데모이미지" class="mb-3">
                                            </div>
                                        </div>
                                        <div v-else :class="{ 'grayscale_img': auction.status === 'done' || auction.status === 'cancel' ||(isDealer && auction.status === 'chosen') }" class="card-img-top-placeholder">
                                            <span v-if="auction.status === 'ing'" class="mx-2 timer">
                                                <img src="../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">
                                                {{ auction.timeLeft.days }}일 {{ auction.timeLeft.hours }}:{{ auction.timeLeft.minutes }}:{{ auction.timeLeft.seconds }}
                                            </span>
                                            <span v-if="auction.status === 'wait'" class="mx-2 timer"><img src="../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">D-3</span>
                                            <div v-if="isDealer"> 
                                                <div class="participate-badge" v-if="isDealerParticipating(auction)">참여</div>
                                            </div>
                                        </div>
                                        <div v-if="auction.status === 'done'" class="time-remaining">경매 완료</div>
                                        <div v-if="isDealer">
                                            <div v-if="auction.status === 'chosen'" class="time-remaining">경매 종료</div>
                                        </div>
                                        <div v-else>
                                            <div v-if="auction.status === 'chosen'" class="wait-selection">선택완료</div>
                                            <div v-if="auction.status === 'cancel'" class="time-remaining">경매 취소</div>
                                            <div v-if="auction.status === 'wait'" class="wait-selection">딜러 선택</div>
                                            <div v-if="auction.status === 'diag'" class="time-remaining">진단 대기</div>
                                            <div v-if="auction.status === 'ask'" class="time-remaining">신청 완료</div>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-title fs-5">더 뉴 그랜저 IG 2.5 가솔린 르블랑</p>
                                            <p class="tc-light-gray mt-0"> 2020 년 / 2.4km / 무사고</p>
                                            <p class="tc-light-gray mt-0">현대 소나타 (DN8)</p>
                                            <div class="d-flex">
                                                <h5 class="card-title"><span class="blue-box">무사고</span></h5>
                                                <h5 v-if="auction.hope_price !== null"><span class="gray-box">재경매</span></h5>
                                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-4" v-if="currentTab === 'interInfo'">
            <div v-if="favoriteAuctions.length > 0">
      <!-- 경매 목록 -->
      <div class="row">
        <div
          class="col-6 col-md-4 mb-4 pt-2 shadow-hover"
          v-for="auction in favoriteAuctions"
          :key="auction.id"
          @click="navigateToDetail(auction)"
        >
          <div class="card my-auction">
            <div class="card-img-top-placeholder"></div>
            <div v-if="auction.status === 'ing'" class="time-remaining">39분 남음</div>
            <div class="card-body">
              <h5 class="card-title"><span class="blue-box">무사고</span>{{ auction.car_no }}</h5>
              <p class="card-text tc-light-gray">현대 쏘나타(DN8)</p>
            </div>
          </div>
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
        </div>
        <div class="container my-4" v-if="currentTab === 'auctionDone'">
            <div class="row">
                <div 
                    class="col-6 col-md-4 mb-4 pt-2 shadow-hover"
                    v-for="auction in filteredDone"
                    :key="auction.id"
                    @click="navigateToDetail(auction)"
                    :style="getAuctionStyle(auction)"
                >
                    <div class="card my-auction">
                        <div class="card-img-top-placeholder grayscale_img"></div>
                         <div v-if="auction.status === 'done'" class="time-remaining">경매 완료</div>
                        <div class="card-body">
                            <h5 class="card-title"><span class="blue-box">무사고</span>{{auction.car_no}}</h5>
                            <p class="card-text tc-light-gray">현대 쏘나타(DN8)</p>
                        </div>
                    </div>
                </div>
                <div v-if ="!filteredDone"class="complete-car">
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
                <nav v-if="currentTab !== 'interInfo' && currentTab !== 'auctionDone'">
                    <ul class="pagination justify-content-center">
                        <li class="page-item" :class="{ disabled: !pagination.prev }">
                        <a class="page-link prev-style" @click="loadPage(pagination.current_page - 1)"></a>
                        </li>
                        <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === pagination.current_page }">
                        <a class="page-link" @click="loadPage(n)">{{ n }}</a>
                        </li>
                        <li class="page-item next-prev" :class="{ disabled: !pagination.next }">
                        <a class="page-link next-style" @click="loadPage(pagination.current_page + 1)"></a>
                        </li>
                    </ul>
                </nav>
                <div class="filter-content">
                <!-- 페이지의 나머지 내용 -->
                <button @click="toggleModal" class="animCircle filter-button" :style="buttonStyle"> 필터</button>
                <FilterModal v-if="showModal" @close="handleClose"/>
                </div>  
            </div>
        </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      isExpanded: false, // 하단 메뉴 확장 상태
      scrollY: 0, // 스크롤 위치 저장
      buttonStyle: { opacity: 1 }, // 버튼 스타일 (불투명도)
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
    handleScroll() { // 스크롤 시 버튼 스타일 변경
      clearTimeout(this.scrollTimeout);
      this.buttonStyle.opacity = 0.5;
      this.scrollTimeout = setTimeout(() => {
        this.buttonStyle.opacity = 1;
      }, 150);
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
import { ref, computed, onMounted, reactive,onUnmounted } from 'vue';
import { useStore } from "vuex";
import useAuctions from "@/composables/auctions"; // 경매 관련 함수 가져오기
import useRoles from '@/composables/roles'; // 역할 관련 함수 가져오기
import FilterModal from '@/views/modal/filter.vue'; // 필터 모달 컴포넌트 가져오기
import { useRouter } from 'vue-router';

const selectedStartYear = ref(new Date().getFullYear() - 1);
const selectedEndYear = ref(new Date().getFullYear());

const router = useRouter();
const currentStatus = ref('all'); // 현재 필터 상태 (기본값: 전체)
const { role, getRole } = useRoles();
const currentTab = ref('allInfo'); // 현재 탭 상태 (기본값: 모든 정보)
const { auctionsData, pagination, getAuctions } = useAuctions();
const currentPage = ref(1); // 현재 페이지 번호
const showModal = ref(false); // 모달 표시 상태
const interestCount = computed(() => auctionsData.value.filter(auction => auction.isInterested).length); // 관심 있는 경매 수
const auctionModal = ref(false);
const isPulling = ref(false);
const distance = ref(0);
const store = useStore();
const user = computed(() => store.getters["auth/user"]); // 사용자 정보
const isDealer = computed(() => user.value?.roles?.includes('dealer')); // 딜러 여부
const isUser = computed(() => user.value?.roles?.includes('user')); // 사용자 여부
const isSpinning = ref(false);

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
  if (auction.status !== 'ing' || !auction.final_at) {
    return {
      days: 0,
      hours: '00',
      minutes: '00',
      seconds: '00'
    };
  }
  const finalAtDate = new Date(auction.final_at.replace(' ', 'T') + 'Z');
  const diff = finalAtDate.getTime() - currentTime.value.getTime();
  return {
    days: Math.floor(diff / (24 * 3600000)),
    hours: padZero(Math.floor((diff % (24 * 3600000)) / 3600000)),
    minutes: padZero(Math.floor((diff % 3600000) / 60000)),
    seconds: padZero(Math.floor((diff % 60000) / 1000)),
  };
};

const updateAuctionTimes = () => {
  auctionsData.value.forEach((auction) => {
    auction.timeLeft = calculateTimeLeft(auction);
  });
};



const handleTouchStart = (e) => {
  state.startY = e.touches[0].pageY;
  state.isPulling = true;
};

const handleTouchMove = (e) => {
  if (window.scrollY !== 0) return; // 스크롤이 최상단이 아닐 때는 작동하지 않음
  const currentY = e.touches[0].pageY;
  const diff = currentY - state.startY;
  if (diff > 0) {
    isPulling.value = true;
    distance.value = Math.min(diff, 100); // 최대 100px까지 당길 수 있도록 제한
  }
};

const handleTouchEnd = async () => {
  if (distance.value === 100) { // 최대 100px 당겨졌을 때만 새로고침 수행
    isSpinning.value = true; // 회전 시작
    setTimeout(async () => {
      await getAuctions(); // 데이터 로딩
      isSpinning.value = false; // 회전 중지
      isPulling.value = false;
      distance.value = 0;
    }, 3000); // 아이콘이 3번 회전하는데 걸리는 시간
  } else {
    isPulling.value = false;
    distance.value = 0;
  }
};

// 이미지 스타일 동적 계산
const imageStyle = computed(() => {
  const opacity = Math.min(distance.value / 100, 1); // 최대 100px 당겨질 때 1의 불투명도
  const translateY = Math.max(100 - distance.value, 0); // 당겨질수록 translateY 감소
  return {
    opacity: opacity,
    transform: `translateY(${translateY}px)`, // 스르륵 효과
    transition: 'opacity 0.3s, transform 0.3s' // 부드러운 전환
  };
});

function setCurrentTab(tab) {
  currentTab.value = tab;
}
function toggleModal() { // 모달 토글
  showModal.value = !showModal.value; 
}

function setFilter(status) { // 필터 설정
  currentStatus.value = status;
}

function handleClose() { // 모달 닫기
  showModal.value = false;
}

const hasCompletedAuctions = computed(() => { // 완료된 경매 여부
  return auctionsData.value.some(auction => auction.status === 'done');
});

const filteredAuctions = computed(() => { // 필터된 경매 목록
  if (currentStatus.value === 'all') {
    return auctionsData.value.filter(auction => ['ing', 'done', 'wait', 'chosen', 'diag', 'ask', 'cancel'].includes(auction.status));
  }
  return auctionsData.value.filter(auction => auction.status === currentStatus.value);
});

const filteredDone = computed(() => { // 필터된 경매 목록
    return auctionsData.value.filter(auction => ['done'].includes(auction.status));
});

function loadPage(page) { // 페이지 로드
  if (page < 1 || page > pagination.value.last_page) return;
  currentPage.value = page;
  getAuctions(page);
}

function navigateToDetail(auction) { // 경매 상세 페이지로 이동
  console.log("디테일 :", auction.id);
  router.push({ name: 'AuctionDetail', params: { id: auction.id } });
}

function getAuctionStyle(auction) { // 경매 스타일 설정
  const validStatuses = ['done', 'wait', 'ing', 'diag'];
  return validStatuses.includes(auction.status) ? { cursor: 'pointer' } : {};
}

function isDealerParticipating(auction) { // 딜러 참여 여부 확인
  const currentUserId = user.value.id;
  return auction.bids.some(bid => bid.user_id === currentUserId);
}
let timer;
onMounted(async () => { // 컴포넌트 마운트 시 초기화 작업
  if (role.value.name === 'user') {
    isUser.value = true;
  }
  await getAuctions(currentPage.value);
  updateAuctionTimes();

setInterval(() => {
  currentTime.value = new Date();
  updateAuctionTimes();
}, 1000);
});
onUnmounted(() => {
  clearInterval(timer);
});
// 데이터 예시
const favoriteAuctions = computed(() => {
  return auctionsData.value.filter(auction => auction.isFavorited);
});
</script>


<style scoped>
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
</style>
