<!--
    TODO: 상태 Ui만 진행
    -페이지네이션
    -전체정보 가져오기
    -선택후 입찰화면
-->
<template>
    <div class="proceeding"></div>
    <!-- 서브 네비게이션 바 -->
    <div class="sub-nav row">
        <div class="col-12">
            <div class="nav-container mt-3">
                <nav class="navbar navbar-expand navbar-light">
                    <div class="navbar-nav">
                        <a class="nav-item nav-link active" href="#">전체</a>
                        <a class="nav-item nav-link" href="#">관심 차량<span class="interest mx-1">0</span></a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <div class="container my-3 auction-content">
        <div class="row mt-5">
            <!-- 사이드바 -->
            <div class="sider-content col-md-3 mov-info02">
                <div class="side-content">
                    <h5>제조사 모델</h5>
                    <div class="mt-4">
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
                        <input type="text" >
                        <button type="button" class="search-btn">검색</button>
                    </div>
                </div>
                <div class="container mb-3">
                    <div class="registration-content">
                        <div class="text-start status-selector">
                            <input type="radio" name="status" value="all" id="all" hidden checked>
                            <label for="all" class="mx-2">전체</label>

                            <input type="radio" name="status" value="ongoing" id="ongoing" hidden>
                            <label for="ongoing">진행중</label>

                            <input type="radio" name="status" value="completed" id="completed" hidden>
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
                <!-- 내 경매 관리 / 경매 전체 -->

                <div class="container my-4">
                    <div class="row">
                        <!-- if. 경매 시간이 남아 있을때 -->
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" checked />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-placeholder"></div>
                                <div class="participate-badge">참여</div>
                                <div class="time-remaining">39분 남음</div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"></div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                    <p class="card-text text-secondary opacity-50">현대 쏘나타(DN8) </p>
                                </div>
                            </div>
                        </div>
                        <!-- if. 경매 완료 되었을때 -->
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" checked />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-placeholder"></div>
                                <div class="time-remaining">39분 남음</div>
                                <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">보험3건</span>12 삼 4567</h5>
                                    <p class="card-text text-secondary opacity-50">현대 쏘나타(DN8) </p>
                                </div>
                            </div>
                        </div>
                        <!-- if. 선택 대기 일때-->
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-placeholder"></div>
                                <div class="participate-badge">참여</div>
                                <div class="wait-selection">선택 대기</div>
                                <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                    <p class="card-text text-secondary opacity-50">현대 쏘나타(DN8) </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" checked />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-placeholder"></div>
                                <div class="time-remaining">39분 남음</div>
                                <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                    <p class="card-text text-secondary opacity-50">현대 쏘나타(DN8) </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" checked />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-placeholder"></div>
                                <div class="time-remaining">39분 남음</div>
                                <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                    <p class="card-text text-secondary opacity-50">현대 쏘나타(DN8) </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-placeholder"></div>
                                <div class="time-remaining">39분 남음</div>
                                <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                    <p class="card-text text-secondary opacity-50">현대 쏘나타(DN8) </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-placeholder"></div>
                                <div class="time-remaining">39분 남음</div>
                                <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">보험3건</span>12 삼 4567</h5>
                                    <p class="card-text text-secondary opacity-50">현대 쏘나타(DN8) </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-placeholder"></div>
                                <div class="time-remaining">39분 남음</div>
                                <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                    <p class="card-text text-secondary opacity-50">현대 쏘나타(DN8) </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card my-auction">
                                <input class="toggle-heart" type="checkbox" />
                                <label class="heart-toggle"></label>
                                <div class="card-img-top-placeholder"></div>
                                <div class="time-remaining">39분 남음</div>
                                <div class="card-body">
                                    <h5 class="card-title"><span class="blue-box">무사고</span>12 삼 4567</h5>
                                    <p class="card-text text-secondary opacity-50">현대 쏘나타(DN8) </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mt-5 mov-info02">
                    <table class="table custom-border">
                        <tbody class="auction-table">
                            <tr>
                                <td class="auction_no text-secondary opacity-50">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="gray-box">미참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain text-secondary opacity-50"></td>
                                <td><span class="auc-blue">선택대기</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no text-secondary opacity-50">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain text-secondary opacity-50"></td>
                                <td><span class="auc-gray">진행중</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no text-secondary opacity-50">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain text-secondary opacity-50"></td>
                                <td><span class="auc-black">경매완료</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no text-secondary opacity-50">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain text-secondary opacity-50"></td>
                                <td><span class="auc-blue">선택대기</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no text-secondary opacity-50">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain text-secondary opacity-50"><span class="tc-red">46</span>분남음</td>
                                <td><span class="auc-gray">진행중</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no text-secondary opacity-50">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain text-secondary opacity-50"><span class="tc-red">46</span>분남음</td>
                                <td><span class="auc-blue">선택대기</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no text-secondary opacity-50">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain text-secondary opacity-50"><span class="tc-red">46</span>분남음</td>
                                <td><span class="auc-blue">선택대기</span></td>
                            </tr>
                            <tr>
                                <td class="auction_no text-secondary opacity-50">경매번호<span>202307200006</span></td>
                                <td class="part_whether"><span class="blue-box">참여</span></td>
                                <td class="car_name">신형 카니발<span class="car_date ms-1">2020-12-30<span class="car_mileage ms-1">7,000</span></span>
                                </td>
                                <td class="time-remain text-secondary opacity-50"><span class="tc-red">46</span>분남음</td>
                                <td class="auc-state"><span class="auc-gray">진행중</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link prev-style"></a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item next-prev">
                            <a class="page-link next-style"></a>
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
                selectedYear: new Date().getFullYear(),
                years: [],
            };
        },
        created() {
            this.years = this.generateYearRange(1990, new Date().getFullYear());
        },
        methods: {
            generateYearRange(start, end) {
                const years = [];
                for (let year = start; year <= end; year++) {
                    years.push(year);
                }
                return years;
            },
        },
    };
    
</script>


<script setup>
import { ref, computed, onMounted } from 'vue';
import useAuctions from "@/composables/auctions";
import useRoles from '@/composables/roles';
import FilterModal from '@/views/modal/filter.vue'; 

const currentStatus = ref('all'); 
const { role, getRole } = useRoles();
const currentTab = ref('allInfo');
const { auctionsData, pagination, getAuctions } = useAuctions();
const currentPage = ref(1);
const showModal = ref(false);

const isUser = ref(false);

function toggleModal() {
  showModal.value = !showModal.value; 
}

function setFilter(status) {
  currentStatus.value = status;
}

function handleClose() {
  showModal.value = false;
}

onMounted(async () => {
  if (role.value.name === 'user') {
    isUser.value = true;
  }
  await getAuctions(currentPage.value);
});
const rangeMin = document.getElementById('min-range');
const rangeMax = document.getElementById('max-range');
const rangeProgress = document.createElement('div');
rangeProgress.classList.add('range-progress');
document.querySelector('.range-slider').appendChild(rangeProgress);

function updateProgressBar() {
    const minValue = parseInt(rangeMin.value);
    const maxValue = parseInt(rangeMax.value);

    const minPercent = (minValue / 200000) * 100;
    const maxPercent = (maxValue / 200000) * 100;

    rangeProgress.style.left = `${minPercent}%`;
    rangeProgress.style.width = `${maxPercent - minPercent}%`;
}

rangeMax.addEventListener('input', updateProgressBar);

// 초기화 시 프로그레스 바 업데이트
updateProgressBar();

</script>