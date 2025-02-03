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
                        <a class="nav-item nav-link pe-0" @click="setCurrentTab('auctionDone')" :class="{ active: currentTab === 'auctionDone' }">판매한 매물<span class="interest mx-2">{{filteredDone.length}}</span></a>
                    </div>
                </nav>
            </div>
            <div v-if="isDealer" class="px-3 container mt-3 overflow-x-auto">
                <nav class="mx-width navbar navbar-expand navbar-light">
                    <div class="navbar-nav gap-2">
                        <a class="nav-item nav-link" @click="setCurrentTab('allInfo')" :class="{ active: currentTab === 'allInfo' }">진행 중인 매물</a>
                        <a class="nav-item nav-link pe-0" @click="setCurrentTab('interInfo')" :class="{ active: currentTab === 'interInfo' }">관심 매물<span class="interest mx-2">{{ favoriteAuctionsPagination.total }}</span></a><!-- 관심 차량 숫자표기 -->
                        <a class="nav-item nav-link pe-0" @click="setCurrentTab('myBidInfo')" :class="{ active: currentTab === 'myBidInfo' }">내 입찰 매물<span class="interest mx-2">{{ mybidPagination.total }}</span></a>
                        <a class="nav-item nav-link pe-0" @click="setCurrentTab('scsbidInfo')" :class="{ active: currentTab === 'scsbidInfo' }">낙찰 매물<span class="interest mx-2">{{ scsbidPagination.total }}</span></a>
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
    <div class="content-main mt-5" :class="{ row: isDealer }">
        <Filter/>
            <!-- 메인 컨텐츠 -->
            <div
                class="auction-main"
                :class="{'col-md-9': isDealer, 'auction-wide': isUser}">
                <div class="d-flex gap-2">
                    <div class="apply-top text-start">
                        <div class="search-type">
                            <input type="text" class="border-6" v-model="search_title" placeholder="모델명,차량번호,지역" @keyup.enter="searchBtn">
                            <button type="button" class="search-btn start-0" @click="searchBtn">검색</button>
                        </div>
                    </div>
                    <div v-if="isDealer" class="filter-content">
                        <!-- 페이지의 나머지 내용 -->
                        <button @click="toggleModal" class="animCircle filter-button text-secondary opacity-20 mx-2"> 필터</button>
                        <transition name="fade" mode="out-in">
                        <FilterModal v-if="showModal" @close="handleClose" />
                        </transition>
                    </div>
                </div>
                <div>
                    <div class="container mb-3" v-if="currentTab == 'interInfo'">
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
                    <div v-if="isLoading">
                        <div :class="['container my-4', { 'pulling': isPulling && distance > 0, 'is-spinning': isSpinning }]" ref="pullContainer" @touchstart.passive="handleTouchStart" @touchmove.passive="handleTouchMove" @touchend="handleTouchEnd" v-if="currentTab === 'allInfo'">
                            <div v-if="isPulling" class="refresh-indicator" :style="imageStyle">
                                <img src="../../../img/Icon-refresh.png" alt="Refresh" class="fas fa-sync-alt" width="20px" :style="imagestyle" />
                            </div>
                            <div v-if="auctionsData.length > 0">
                                <div class="content-wrapper" :style="{ 'transform': `translateY(${Math.min(distance, 20)}px)` }">
                                    <div class="row-wrapper">
                                        <div class="row" :class="{'pulled': isPulling && distance > 0}">
                                            <!-- if. 경매 ing 있을때 -->
                                            <div class="col-6 col-md-4 mb-4 pt-2 hover-anymate" v-for="auction in auctionsData" :key="auction.id" @click="navigateToDetail(auction)" :style="getAuctionStyle(auction)">
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
                                                        <div v-if="auction.car_thumbnail">
                                                            <img :src="auction.car_thumbnail" alt="Car Image">
                                                        </div>
                                                        <div v-else>
                                                            <img src="../../../img/car_example.png">
                                                        </div>
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
                                                        <p class="card-title fw-bolder">{{ auction.car_model ? auction.car_model +' '+ auction.car_model_sub +' '+ auction.car_fuel + '('+ auction.car_no +')' : '더 뉴 그랜저 IG 2.5 가솔린 르블랑' }}</p>
                                                        <p class="tc-gray mt-0"> {{ auction.car_year ? auction.car_year : '2020' }} 년 |<span class="mx-1">{{ auction.car_km ? auction.car_km : '2.4' }}km</span>| 무사고</p>
                                                        <p class="tc-gray mt-0">{{ auction.car_maker ? auction.car_maker + auction.car_model : '현대 소나타' }} ({{ auction.car_grade ? auction.car_grade : 'DN8' }})</p>
                                                        <div class="d-flex">
                                                            <h5 class="card-title"><span class="blue-box fw-bold border-6">무사고</span></h5>
                                                            <h5 v-if="auction.is_reauction !== 0"><span class="gray-box border-6">재경매</span></h5>
                                                            <h5 v-if="auction.is_biz !== 0"><span class="red-box-type03 border-6">법인 / 사업자</span></h5>
                                                            <!--TODO: 이건 추후에 지우기 !! 일단 생성해놓음-->
                                                            <!--<p class="tc-gray">{{ auction.car_no }}</p>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="complete-car my-3">
                                    <div class="card my-auction mt-3">
                                        <div class="none-complete">
                                            <span class="tc-gray">매물 정보가 없습니다.</span>
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
                                    <div class="col-6 col-md-4 mb-4 pt-2 hover-anymate" v-for="auction in favoriteAuctionsData" :key="auction.id" @click="navigateToDetail(auction)">
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
                                                <div v-if="auction.car_thumbnail">
                                                    <img :src="auction.car_thumbnail" alt="Car Image">
                                                </div>
                                                <div v-else>
                                                    <img src="../../../img/car_example.png">
                                                </div>
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
                                                <p class="card-title fw-bolder">{{ auction.car_model ? auction.car_model +' '+ auction.car_model_sub +' '+ auction.car_fuel + '('+ auction.car_no +')' : '더 뉴 그랜저 IG 2.5 가솔린 르블랑' }}</p>
                                                <p class="tc-gray mt-0"> {{ auction.car_year ? auction.car_year : '2020' }} 년 |<span class="mx-1">{{ auction.car_km ? auction.car_km : '2.4' }}km</span>| 무사고</p>
                                                <p class="tc-gray mt-0">{{ auction.car_maker ? auction.car_maker + auction.car_model : '현대 소나타' }} ({{ auction.car_grade ? auction.car_grade : 'DN8' }})</p>
                                                <div class="d-flex">
                                                    <h5 class="card-title"><span class="blue-box border-6">무사고</span></h5>
                                                    <h5 v-if="auction.hope_price !== null"><span class="gray-box border-6">재경매</span></h5>
                                                    <!--TODO: 이건 추후에 지우기 !! 일단 생성해놓음-->
                                                   <!-- <p class="tc-gray">{{ auction.car_no }}</p>-->
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
                                            <p class="card-text tc-gray">현대 쏘나타(DN8)</p>
                                            </div>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <!-- 하트 토글된 경매가 없을 경우의 메시지 -->
                                <div class="complete-car my-3">
                                    <div class="card my-auction mt-3">
                                        <div class="none-complete">
                                            <span class="tc-gray">관심 매물이 없습니다.</span>
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
                            <div v-if="filteredDone.length > 0">
                                <div class="row">
                                
                                    <div class="col-6 col-md-4 mb-4 pt-2 hover-anymate" v-for="auction in filteredDone" :key="auction.id" @click="navigateToDetail(auction)" :style="getAuctionStyle(auction)">
                                        <div class="card my-auction">
                                            <div class="card-img-top-placeholder grayscale_img"><img :src="auction.car_thumbnail"></div>
                                            <span v-if="auction.status === 'done'" class="mx-2 auction-done">경매완료</span>
                                            <div class="card-body">
                                                <p class="card-title fw-bolder">{{ auction.car_model ? auction.car_model +' '+ auction.car_model_sub +' '+ auction.car_fuel + '('+ auction.car_no +')' : '더 뉴 그랜저 IG 2.5 가솔린 르블랑' }}</p>
                                                <p class="tc-gray mt-0"> {{ auction.car_year ? auction.car_year : '2020' }} 년 |<span class="mx-1">{{ auction.car_km ? auction.car_km : '2.4' }}km</span>| 무사고</p>
                                                <p class="tc-gray mt-0">{{ auction.car_maker ? auction.car_maker + auction.car_model : '현대 소나타' }} ({{ auction.car_grade ? auction.car_grade : 'DN8' }})</p>
                                                <div class="d-flex">
                                                    <h5 class="card-title"><span class="blue-box border-6">무사고</span></h5>
                                                    <h5 v-if="auction.is_biz !== 0"><span class="red-box-type03 border-6">법인 / 사업자</span></h5>
                                                    <h5 v-if="auction.hope_price !== null"><span class="gray-box border-6">재경매</span></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="complete-car my-3">
                                    <div class="card my-auction mt-3">
                                        <div class="none-complete">
                                            <span class="tc-gray">판매 매물이 없습니다.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                            </div>
                        </div>
                        <div class="container my-4" v-if="currentTab === 'myBidInfo'">
                            <div class="registration-content overflow-hidden">
                                <div class="text-start status-selector registration-content">
                                    <div v-for="(label, value) in myBidsStatusLabel" :key="value" class="mx-2">
                                        <input type="radio" name="status" :value="value" :id="value" :checked="value === currentMyBidsStatus " @change="event => setMyBidsFilter(event.target.value)" />
                                        <label :for="value">{{ label }}</label>
                                    </div>
                                </div>
                            </div>
                            <div v-if="mybidsData.length > 0">
                                <!-- 경매 목록 -->
                                <div class="row">
                                    <div class="col-6 col-md-4 mb-4 pt-2 hover-anymate" v-for="bid in mybidsData" :key="bid.id" @click="navigateToDetail(bid)">
                                        <div class="card my-auction">
                                            <div v-if="isDealer">
                                                <input class="toggle-heart" type="checkbox" :id="'favorite-' + bid.id" :checked="bid.isFavorited" @click.stop="toggleFavorite(bid)" />
                                                <label class="heart-toggle" :for="'favorite-' + bid.id" @click.stop></label>
                                            </div>
                                            <!-- 경매 상태가 'ask'이거나 'diag'일 경우 -->
                                            <div v-if="bid.status === 'ask' || bid.status === 'diag'">
                                                <div class="card-img-demo">
                                                    <img src="../../../img/demo.png" alt="경매대기 데모이미지" class="mb-3">
                                                </div>
                                            </div>
                                            <div v-else="bid.status !== 'ask' || bid.status !== 'diag'" :class="{ 'grayscale_img': bid.status === 'done' || bid.status === 'cancel' ||(isDealer && bid.status === 'chosen') }" class="card-img-top-placeholder">
                                                <div v-if="bid.car_thumbnail">
                                                    <img :src="bid.car_thumbnail" alt="Car Image">
                                                </div>
                                                <div v-else>
                                                    <img src="../../../img/car_example.png">
                                                </div>
                                            </div>
                                            <span v-if="bid.status === 'dlvr'" class="mx-2 auction-done bg-info">{{ wicas.enum(store).toLabel(bid.status).auctions() }}</span>
                                            <div>
                                                <span v-if="['done', 'cancel', 'chosen', 'diag', 'ask'].includes(bid.status)" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(bid.status).auctions() }}</span>
                                            </div>
                                            <div class="d-flex">
                                                <span v-if="(bid.status === 'ing' || bid.status === 'wait') && bid.timeLeft" class="mx-2 timer">
                                                    <img src="../../../img/Icon-clock-wh.png" alt="Clock Icon" class="icon-clock">
                                                    <span v-if="bid.timeLeft.days != '0' ">{{ bid.timeLeft.days }}일 &nbsp; </span>{{ bid.timeLeft.hours }}:{{ bid.timeLeft.minutes }}:{{ bid.timeLeft.seconds }}
                                                </span>
                                                <div v-if="isDealer">
                                                    <div class="participate-badge" v-if="bid.isDealerParticipating">
                                                        <span class="hand-icon">
                                                            <img src="../../../img/Icon-hand.png" alt="Hand Icon">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-title fw-bolder">{{ bid.car_model ? bid.car_model +' '+ bid.car_model_sub +' '+ bid.car_fuel + '('+ bid.car_no +')' : '더 뉴 그랜저 IG 2.5 가솔린 르블랑' }}</p>
                                                <p class="tc-gray mt-0"> {{ bid.car_year ? bid.car_year : '2020' }} 년 |<span class="mx-1">{{ bid.car_km ? bid.car_km : '2.4' }}km</span>| 무사고</p>
                                                <p class="tc-gray mt-0">{{ bid.car_maker ? bid.car_maker + bid.car_model : '현대 소나타' }} ({{ bid.car_grade ? bid.car_grade : 'DN8' }})</p>
                                                <div class="d-flex">
                                                    <h5 class="card-title"><span class="blue-box border-6">무사고</span></h5>
                                                    <h5 v-if="bid.hope_price !== null"><span class="gray-box border-6">재경매</span></h5>
                                                    <!--TODO: 이건 추후에 지우기 !! 일단 생성해놓음-->
                                                   <!--<p class="tc-gray">{{ bid.auction.car_no }}</p>--> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="complete-car my-3">
                                    <div class="card my-auction mt-3">
                                        <div class="none-complete">
                                            <span class="tc-gray">입찰한 매물이 없습니다.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <li class="page-item" :class="{ disabled: !mybidPagination.prev }">
                                        
                                        <a class="page-link prev-style" @click="loadPage( mybidPagination.current_page - 1, 'bid', mybidPagination)"></a>
                                    </li>
                                    <li v-for="n in mybidPagination.last_page" :key="n" class="page-item" :class="{ active: n === mybidPagination.current_page }">
                                        <a class="page-link" @click="loadPage( n, 'bid', mybidPagination)">{{ n }}</a>
                                    </li>
                                    <li class="page-item next-prev" :class="{ disabled: !mybidPagination.next }">
                                        <a class="page-link next-style" @click="loadPage( mybidPagination.current_page + 1, 'bid', mybidPagination)"></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="container my-4" v-if="currentTab === 'scsbidInfo'">
                            <div class="registration-content overflow-hidden">
                                <div class="text-start status-selector registration-content">
                                    <div v-for="(label, value) in scsBidsstatusLabel" :key="value" class="mx-2">
                                        <input type="radio" name="status" :value="value" :id="value" :checked="value === 'all' " @change="event => setScsBidsFilter(event.target.value)" />
                                        <label :for="value">{{ label }}</label>
                                    </div>
                                </div>
                            </div>
                                <div v-if="scsbidsData.length > 0">
                                    <!-- 경매 목록 -->
                                    <div class="row">
                                        <div class="col-6 col-md-4 mb-4 pt-2 hover-anymate" v-for="scsBid in scsbidsData" :key="scsBid.id" @click="navigateToDetail(scsBid)">
                                            <div class="card my-auction">
                                                <!-- 경매 상태가 'ask'이거나 'diag'일 경우 -->
                                                <div v-if="scsBid.status === 'ask' || scsBid.status === 'diag'">
                                                    <div class="card-img-demo">
                                                        <img src="../../../img/demo.png" alt="경매대기 데모이미지" class="mb-3">
                                                    </div>
                                                </div>
                                                <div v-else="scsBid.status !== 'ask' || scsBid.status !== 'diag'" :class="{ 'grayscale_img': scsBid.status === 'done' || scsBid.status === 'cancel' ||(isDealer && scsBid.status === 'chosen') }" class="card-img-top-placeholder">
                                                    <div v-if="scsBid.car_thumbnail">
                                                        <img :src="scsBid.car_thumbnail" alt="Car Image">
                                                    </div>
                                                    <div v-else>
                                                        <img src="../../../img/car_example.png">
                                                    </div>
                                                </div>
                                                <span v-if="scsBid.status === 'dlvr'" class="mx-2 auction-done bg-info">{{ wicas.enum(store).toLabel(scsBid.status).auctions() }}</span>
                                                <div>
                                                    <span v-if="['done', 'cancel', 'chosen', 'diag', 'ask'].includes(scsBid.status)" class="mx-2 auction-done">{{ wicas.enum(store).toLabel(scsBid.status).auctions() }}</span>
                                                </div>
                                                <div class="d-flex">
                                                    <div v-if="isDealer">
                                                        <div class="participate-badge" v-if="scsBid.isDealerParticipating">
                                                            <span class="hand-icon">
                                                                <img src="../../../img/Icon-hand.png" alt="Hand Icon">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-title fw-bolder">{{ scsBid.car_model ? scsBid.car_model +' '+ scsBid.car_model_sub +' '+ scsBid.car_fuel + '('+ scsBid.car_no +')' : '더 뉴 그랜저 IG 2.5 가솔린 르블랑' }}</p>
                                                    <p class="tc-gray mt-0"> {{ scsBid.car_year ? scsBid.car_year : '2020' }} 년 |<span class="mx-1">{{ scsBid.car_km ? scsBid.car_km : '2.4' }}km</span>| 무사고</p>
                                                    <p class="tc-gray mt-0">{{ scsBid.car_maker ? scsBid.car_maker + scsBid.car_model : '현대 소나타' }} ({{ scsBid.car_grade ? scsBid.car_grade : 'DN8' }})</p>
                                                    <div class="d-flex">
                                                        <h5 class="card-title"><span class="blue-box border-6">무사고</span></h5>
                                                        <h5 v-if="scsBid.hope_price !== null"><span class="gray-box border-6">재경매</span></h5>
                                                        <!--TODO: 이건 추후에 지우기 !! 일단 생성해놓음-->
                                                      <!--<p class="tc-gray">{{ scsBid.auction.car_no }}</p>-->  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div v-else>
                                <div class="complete-car my-3">
                                    <div class="card my-auction mt-3">
                                        <div class="none-complete">
                                            <span class="tc-gray">낙찰된 매물이 없습니다.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <li class="page-item" :class="{ disabled: !scsbidPagination.prev }">
                                        
                                        <a class="page-link prev-style" @click="loadPage( scsbidPagination.current_page - 1, 'scsbid', scsbidPagination)"></a>
                                    </li>
                                    <li v-for="n in scsbidPagination.last_page" :key="n" class="page-item" :class="{ active: n === scsbidPagination.current_page }">
                                        <a class="page-link" @click="loadPage( n, 'scsbid', scsbidPagination)">{{ n }}</a>
                                    </li>
                                    <li class="page-item next-prev" :class="{ disabled: !scsbidPagination.next }">
                                        <a class="page-link next-style" @click="loadPage( scsbidPagination.current_page + 1, 'scsbid', scsbidPagination)"></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    
                </div>
    
                <div class="container my-4" v-if="currentTab === 'auctionDone'">
    
                    <div>
                    </div>
                </div>
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
      years: [], // 연도 목록 저장
      minRange: 0, // 최소값 초기화
      maxRange: 200000, // 최대값 초기화
      expandedDropdown: null,
      dropdownStates: {
        domestic: true,
        imported: true,
        cargoSpecial:true,
      },
        domesticCars: [
        { id: 'hyundai', label: '현대', value: 'hyundai', count: '1,356' },
        { id: 'kia', label: '기아', value: 'kia', count: '1,356' },
        { id: 'genesis', label: '제네시스', value: 'genesis', count: '1,356' },
        { id: 'chevrolet', label: '쉐보레(GM대우)', value: 'chevrolet', count: '1,356' },
        { id: 'renault', label: '르노코리아(삼성)', value: 'renault', count: '1,356' },
        { id: 'kg_mobility', label: 'KG모빌리티(쌍용)', value: 'kg_mobility', count: '1,356' },
      ],
      importedCars: [
        { id: 'mercedes', label: '벤츠', value: 'mercedes', count: '1,356' },
        { id: 'bmw', label: 'BMW', value: 'bmw', count: '1,356' },
        { id: 'audi', label: '아우디', value: 'audi', count: '1,356' },
        { id: 'porsche', label: '포르쉐', value: 'porsche', count: '1,356' },
        { id: 'mini', label: '미니', value: 'mini', count: '1,356' },
        { id: 'landrover', label: '랜드로버', value: 'landrover', count: '1,356' },
        { id: 'volkswagen', label: '폭스바겐', value: 'volkswagen', count: '1,356' },
      ],
      cargoSpecialCars: [
        { id: 'box_truck', label: '탑차', value: 'box_truck', count: '1,356' },
        { id: 'refrigerated_truck', label: '냉동/냉장차', value: 'refrigerated_truck', count: '1,356' },
        { id: 'wing_body', label: '윙바디', value: 'wing_body', count: '1,356' },
        { id: 'tank_lorry', label: '탱크로리', value: 'tank_lorry', count: '1,356' },
        { id: 'cargo_crane', label: '카고크레인', value: 'cargo_crane', count: '1,356' },
        { id: 'trailer', label: '트레일러', value: 'trailer', count: '1,356' },
        { id: 'flatbed_truck', label: '평판 트럭', value: 'flatbed_truck', count: '1,356' },
        { id: 'container_truck', label: '컨테이너 트럭', value: 'container_truck', count: '1,356' },
        { id: 'livestock_truck', label: '축산 차량', value: 'livestock_truck', count: '1,356' },
        { id: 'garbage_truck', label: '쓰레기 수거차', value: 'garbage_truck', count: '1,356' },
        { id: 'logging_truck', label: '목재 운반차', value: 'logging_truck', count: '1,356' }
        ],
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
    toggleDropdown(type) {
      // 상태 토글
      this.dropdownStates[type] = !this.dropdownStates[type];
    },

    

    toggleMenuHeight() { // 하단 메뉴 높이 토글
      this.isExpanded = !this.isExpanded;
    },
    formatNumber(value) {
      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    },
    submitReview() { // 리뷰 제출 시 메뉴 높이 토글
      this.toggleMenuHeight();
    },
  
    generateYearRange(start, end) {
      const years = [];
      for (let year = start; year <= end; year++) {
        years.push(year);
      }
      return years;
    },

    handleScroll() {
      clearTimeout(this.scrollTimeout);
      this.scrollTimeout = setTimeout(() => {
        this.scrollY = window.scrollY;
      }, 200);
    },
  },

  beforeDestroy() {
    window.removeEventListener('scroll', this.handleScroll);
    clearTimeout(this.scrollTimeout);
  },
    updateProgressBar() {
      const rangeSlider = document.querySelector('.range-slider');
      const minPercent = (this.minRange / 200000) * 100;
      const maxPercent = (this.maxRange / 200000) * 100;

      // 슬라이더 배경 업데이트
      rangeSlider.style.background = `linear-gradient(to right, #d3d3d3 0%, #d3d3d3 ${minPercent}%, #f24200 ${minPercent}%, #f24200 ${maxPercent}%, #d3d3d3 ${maxPercent}%, #d3d3d3 100%)`;
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
import Filter from "@/views/import/filter.vue";

const swal = inject('$swal');
const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();
const selectedStartYear = ref(new Date().getFullYear() - 1);
const selectedEndYear = ref(new Date().getFullYear());
const {getBids, bidsData , getscsBids, getMyBidsAll } = usebid();
const { getLikes, likesData, isAuctionFavorited , like , setLikes , deleteLike } = useLikes();
const router = useRouter();
const route = useRoute();
const currentStatus = ref('all');
const currentScsBidsStatus = ref('all'); 
const currentMyBidsStatus = ref('all');

const { role, getRole } = useRoles();
// const currentTab = ref('allInfo'); 
const currentTab = localStorage.getItem('currentTab') ? ref(localStorage.getItem('currentTab')) : ref('allInfo');
const { auctionsData, pagination, getAuctions, getAuctionsByDealer, getAuctionsByDealerLike, getAuctionsWithBids } = useAuctions();

const currentPage = ref(1); 
const currentFavoritePage = ref(1); 
const currentMyBidPage = ref(1); 
const currentScsBidsPage = ref(1);
const isLoading = ref(false);
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
let scsBidsstatusLabel;
let myBidsStatusLabel;
//let likeMessage;

const favoriteAuctionsData = ref({}); //관심 차량 데이터
const favoriteAuctionsPagination = ref({}); //관심 차량 페이징
const scsbidsData = ref([]); //낙찰 차량 데이터
const scsbidPagination = ref({}); //낙찰 차량 페이징
const search_title = ref('');

const mybidsData = ref([]);
const mybidPagination = ref({});
const bidsIdString = ref('');

const currentPageValue = localStorage.getItem('currentPage') ? ref(localStorage.getItem('currentPage')) : ref(1);
const currentFavoritePageValue = localStorage.getItem('currentFavoritePage') ? ref(localStorage.getItem('currentFavoritePage')) : ref(1);
const currentMyBidPageValue = localStorage.getItem('currentMyBidPage') ? ref(localStorage.getItem('currentMyBidPage')) : ref(1);
const currentScsBidsPageValue = localStorage.getItem('currentScsBidsPage') ? ref(localStorage.getItem('currentScsBidsPage')) : ref(1);

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
        wica.ntcn(swal).icon('S').title('관심 매물이 추가되었습니다.').fire();
        getAuctionsData();
    }
};

const removeLike = async (auction) => {
    const response = await deleteLike(auction.like.id);
    if(response.isSuccess){
        wica.ntcn(swal).icon('S').title('관심 매물이 취소되었습니다.').fire();
        getAuctionsData();
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
        getAuctionsData();
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
            getAuctionsData();
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

    switch (tab) {
        case 'allInfo':
            getAuctionsData();
            localStorage.setItem('currentTab', 'allInfo');
            break;
        case 'interInfo':
            currentStatus.value = 'all';
            favoriteAuctionsGetData();
            localStorage.setItem('currentTab', 'interInfo');
            break;
        case 'myBidInfo':
            currentMyBidsStatus.value='all';
            getMyBidsGetData();
            localStorage.setItem('currentTab', 'myBidInfo');
            break;
        case 'scsbidInfo':
            currentScsBidsStatus.value = 'all';
            getScsBidsInfo();
            localStorage.setItem('currentTab', 'scsbidInfo');
            break;
        case 'auctionDone':
            localStorage.setItem('currentTab', 'auctionDone');
            break;
    }

}

function toggleModal() { 
    showModal.value = !showModal.value; 
}

function setFilter(status) { 
    currentStatus.value = status;
    favoriteAuctionsGetData();
}

function setScsBidsFilter(status){
    currentScsBidsStatus.value = status;
    getScsBidsInfo();
}

function setMyBidsFilter(status){
    currentMyBidsStatus.value = status;
    getMyBidsGetData();
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
            // currentPage.value = page;
            currentPageValue.value = page;
            localStorage.setItem('currentPage', page);
            getAuctionsData();
            break;
        case 'favorite':
            // currentFavoritePage.value = page;
            currentFavoritePageValue.value = page;
            localStorage.setItem('currentFavoritePage', page);
            favoriteAuctionsGetData();
            break;
        case 'bid':
            // currentMyBidPage.value = page;
            currentMyBidPageValue.value = page;
            localStorage.setItem('currentMyBidPage', page);
            getMyBidsGetData();
            break;
        case 'scsbid':
            // currentScsBidsPage.value = page;
            currentScsBidsPageValue.value = page;
            localStorage.setItem('currentScsBidsPage', page);
            getScsBidsInfo();
            break;
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
    return bidsData.value.some(bid => bid.auction_id === auctionId);
}

const getAuctionsData = async () => {
    if(isUser.value){
        await getAuctions(currentPageValue.value , false , currentStatus.value, search_title.value);
    } else if(isDealer.value){
        await getAuctionsByDealer(currentPageValue.value , currentStatus.value, search_title.value);
        await filterLikeData(auctionsData.value);
        await favoriteAuctionsGetData();
        await getMyBidsGetData();
    }
}
const response = {};
const favoriteAuctionsGetData = async (search_content='') => {
    await getBids(currentMyBidPage.value, false, true, currentStatus.value,search_content);
    response.value = await getAuctionsByDealerLike(currentFavoritePage.value , user.value.id , currentStatus.value, search_content);
    favoriteAuctionsPagination.value = response.value.rawData.data.meta;
    
    favoriteAuctionsData.value = response.value.data;
    filterLikeData(favoriteAuctionsData.value);
}

const filterLikeData = (auctions, likes="none") => {
    auctions.forEach(auction => {
        likes = auction.likes;
        const userLike = likes.find(like => like.likeable_id == auction.id && like.user_id == user.value.id);
        if (userLike) {
            auction.like = userLike;
            auction.isFavorited = true;
        } else {
            auction.isFavorited = false;
        }
        auction.isDealerParticipating = isDealerParticipating(auction.id);
    });
}

const getScsBidsInfo = async (serach_content='') =>{
    //bids낙찰 차량 가져오기
    const scsBidsInfo = await getscsBids(currentScsBidsPage.value,currentScsBidsStatus.value,serach_content,bidsIdString.value);
   
    scsbidsData.value = scsBidsInfo.data;
    scsbidPagination.value = scsBidsInfo.rawData.data.meta;
    
}

const getMyBidsGetData = async(serach_content='') =>{
    const myAuctionBidsInfo = await getAuctionsWithBids(currentScsBidsPage.value,currentMyBidsStatus.value,user.value.id,serach_content,bidsIdString.value);
   
    mybidsData.value = myAuctionBidsInfo.data;
    mybidPagination.value = myAuctionBidsInfo.rawData.data.meta;
    filterLikeData(mybidsData.value);
}

const searchBtn = async() =>{
    
    if(isUser.value){
        getAuctionsData(search_title.value);
    } else if(isDealer.value){
        switch (currentTab.value) {
        case 'allInfo':
            getAuctionsData(search_title.value);
            break;
        case 'interInfo':
            favoriteAuctionsGetData(search_title.value);
            break;
        case 'myBidInfo':
            getMyBidsGetData(search_title.value);
            break;
        case 'scsbidInfo':
            getScsBidsInfo(search_title.value);
            break;
        }
    }
    
}

let timer;

onMounted(async () => {
    if (history.state.currentTab) {
        currentTab.value = history.state.currentTab;
    }
    
    if (history.state.status) {
        currentMyBidsStatus.value = history.state.status;
    }

    //bids id List가져오기
    if(isDealer.value){
        const myBidsList = await getMyBidsAll();
        const bidsIdList = [];
        for (let i = 0; i < myBidsList.data.length; i++) {
            bidsIdList.push(myBidsList.data[i].id);
        }

        bidsIdString.value = bidsIdList.join(',');
    }
    
    await getAuctionsData();

    statusLabel = wicas.enum(store).addFirst('all', '전체').excl('cancel', '취소').ascVal().auctions();
    scsBidsstatusLabel = wicas.enum(store).addFirst('all','전체').perm('dlvr','chosen').auctions();
    myBidsStatusLabel = wicas.enum(store).addFirst('all', '전체')
                             .addFirst('bid', '입찰').add('cnsgnmUnregist', '탁송지 미등록')
                             .excl('ask','diag','ing','wait').ascVal().auctions();
    if (role.value.name === 'user') {
        isUser.value = true;
    }
    if(isDealer.value){
        //낙찰차량정보
        await getScsBidsInfo();

        //초기 관심매물 개수
        response.value = await getAuctionsByDealerLike(currentFavoritePage.value , user.value.id , 'all');
        favoriteAuctionsPagination.value = response.value.rawData.data.meta;
    }

    timer = setInterval(() => {
        if(isUser.value){
            updateAuctionTimes(auctionsData.value);
        } else if(isDealer.value){
            updateAuctionTimes(auctionsData.value);
            updateAuctionTimes(favoriteAuctionsData.value);
            updateAuctionTimes(mybidsData.value);
        }
        isLoading.value = true;
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
@media (max-width: 458px){
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
 