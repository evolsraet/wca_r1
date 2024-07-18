<template>
<div id="UsersNav">
    <div class="overlay" style="display: none;"></div>
    <nav :class="['navbar', 'navbar-expand-md', 'navbar-light', 'shadow-sm', navbarClass, textClass, 'p-2']">
      <div v-if="isAuctionDetailPage"></div>
      <div class="container nav-font">
        <button v-if="isDetailPage && isUser" @click="goBack" class="btn btn-back back-btn-icon"></button>
        <button v-else-if="isDetailPage && isDealer" @click="goBack" class="btn btn-back wh-btn-icon"></button>
       <!-- <p v-if="isDetailPage && isMobile">123</p>-->
        <router-link v-else-if="isDealer" to="/dealer" class="navbar-brand-dealer"></router-link>
        <router-link v-else-if="isUser" to="/user" class="navbar-brand logo-container"></router-link>
        <router-link v-else to="/" class="navbar-brand"></router-link>
        <div class="collapse navbar-collapse navbar-collshow" id="navbarSupportedContent">
          <div v-if="isDealer" class="navbar-nav nav-style">
            <div class="nav-header d-flex justify-content-between align-items-center">
              <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close" @click="toggleNavbar"></button>
              <div class="d-flex align-items-center position-relative">
                <span class="p-2 process">{{ user.name }} 님</span>
                <i class="fas fa-cog settings-icon mb-1" @click="toggleSettingsMenu" :class="{ 'rotate': showSettings }"></i>
                <div v-if="showSettings" :class="['settings-menu', { show: showSettings }]">
                  <a href="/profile" class="menu-item mt-0">내 정보</a>
                  <!--<a href="/edit-profile" class="menu-item mt-0">내 정보 수정</a>-->
                  <a class="menu-item mt-0" href="/login" @click="logout">로그아웃</a>
                </div>
              </div>
            </div>
            <div class="toggle-nav-content" :class="{ 'has-gradient': showScrollGradient }">
              <div class="top-content">
                <div class="menu-illustration p-3">
                  <div class="sub-board-style">
                    <div v-if="fetchFilteredViewBids.value" class="text-start">
                      <p>경매가 종료됐어요!</p>
                      <div class="d-flex align-items-center">
                        <router-link :to="{ name: 'auth.login' }" class="tc-primary bold-18-font" @click="toggleNavbar">선택완료차량 확인</router-link>
                        <div class="icon right-icon"></div>
                      </div>
                    </div>
                    <div v-else class="text-start tc-primary">
                      <p>아직 경매가 진행 중이에요</p>
                      <div class="d-flex align-items-center">
                        <router-link :to="{ name: 'auction.index'}" class="tc-primary bold-18-font" @click="toggleNavbar">진행 중 경매 확인</router-link>
                        <div class="icon right-icon"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="footer-content">
                <div class="p-2">
                  <router-link :to="{ name: 'auction.index'}" class="menu-item mt-0" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-tag"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text process">입찰하기</span>
                      <span class="text-secondary opacity-50 font-1">새 매물 둘러보기</span>
                    </div>
                  </router-link>
                  <router-link :to="{ name: 'dealer.address'}" class="menu-item mt-0" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-location-memu"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text process">주소 관리</span>
                      <span class="text-secondary opacity-50 font-1">탁송 주소 관리</span>
                    </div>
                  </router-link>
                  <router-link :to="{ name: 'dealer.bids'}" class="menu-item mt-0" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-awsome"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text process">낙찰 차량관리</span>
                      <span class="text-secondary opacity-50 font-1">경매 낙찰차량 확인</span>
                    </div>
                  </router-link>
                  <router-link :to="{ name: 'dealer.bidList'}" class="menu-item mt-0" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-nav-car"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text process">과거 낙찰 이력</span>
                      <span class="text-secondary opacity-50 font-1">경매 완료 매물</span>                       
                    </div>
                  </router-link>
                  <router-link :to="{ name: 'index.claim' }" class="menu-item process mt-0" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-document"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text">클레임</span>
                      <span class="text-secondary opacity-50 font-1">낙찰 차량에 문제가 있으신가요?</span>
                    </div>
                  </router-link>
                  <router-link :to="{ name: 'index.notices' }" class="menu-item mt-0 process mb-4" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-dash"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text">공지사항</span>
                      <span class="text-secondary opacity-50 font-1">새소식</span>
                    </div>
                  </router-link>
                </div>
              </div>
              <div class="logo-content">
                <img src="../../img/newicon/logo_2.png" alt="자동차 이미지" width="64" height="21">
              </div>
            </div>
          </div>
          <div v-else-if="isUser" class="navbar-nav">
            <div class="nav-header d-flex justify-content-between align-items-center">
              <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close" @click="toggleNavbar"></button>
              <div class="d-flex align-items-center position-relative">
                <span class="p-2 process">{{ user.name }} 님</span>
                <i class="fas fa-cog settings-icon mb-1" @click="toggleSettingsMenu" :class="{ 'rotate': showSettings }"></i>
                <div v-if="showSettings" :class="['settings-menu', { show: showSettings }]">
                  <a href="/edit-profile" class="menu-item mt-0">내 정보 수정</a>
                  <a class="menu-item mt-0" href="/login" @click="logout">로그아웃</a>
                </div>
              </div>
            </div>
            <div class="toggle-nav-content" :class="{ 'has-gradient': showScrollGradient }">
              <div class="top-content">
                <div class="menu-illustration02 p-3">
                  <div class="sub-board-style02">
                    <div class="text-start">
                      <p class="text-secondary opacity-50">경매를 시작해볼까요?</p>
                      <div class="d-flex align-items-center">
                        <router-link :to="{ name: 'home' }" class="process bold-18-font" @click="toggleNavbar">내 차 팔기</router-link>
                        <div class="icon right-icon-nav"></div>
                      </div>
                    </div>
                    <img src="../../img/car-key.png" alt="Description of Image" class="sub-board-image">
                  </div>
                </div>
              </div>
              <div class="footer-content">
                <div class="under-line"></div>
                <div class="p-2">
                  <router-link :to="{ name: 'home' }" class="menu-item mt-0" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-nav-car"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text">내 차 팔기</span>
                      <span class="text-secondary opacity-50 font-1">{{ wicaLabel.title() }}이 척척</span>
                    </div>
                  </router-link>
                  <router-link :to="{ name: 'auction.index'}" class="menu-item mt-1" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-nav-bulb"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text">내 매물관리</span>
                      <span class="text-secondary opacity-50 font-1">경매 진행중인 매물</span>
                    </div>
                  </router-link>
                  <router-link :to="{ name: 'user.review'}" class="menu-item mt-1 mb-4" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-ratings"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text">이용후기</span>
                      <span class="text-secondary opacity-50 font-1"> 다양한 판매 후기</span>
                    </div>
                  </router-link>
                </div>
              </div>
              <div class="logo-content">
                <img src="../../img/newicon/logo_2.png" alt="자동차 이미지" width="64" height="21">
              </div>
            </div>
          </div>
          <div v-else class="navbar-nav">
            <div class="nav-header">
              <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close"></button>
            </div>
            <div class="toggle-nav-content" :class="{ 'has-gradient': showScrollGradient }">
              <div class="top-content">
                <div class="menu-illustration p-3">
                  <div class="sub-board-style">
                    <div class="text-start">
                      <p class="tc-primary">내 차 팔까?</p>
                      <div class="d-flex align-items-center">
                        <router-link :to="{ name: 'auth.login' }" class="tc-primary bold-18-font" @click="toggleNavbar">로그인하기</router-link>
                        <div class="icon right-icon"></div>
                      </div>
                    </div>
                    <img src="../../img/login-object.png" alt="Description of Image" class="sub-board-image">
                  </div>
                </div>
              </div>
              <div class="footer-content">
                <div class="under-line"></div>
                <div class="p-2">
                  <router-link :to="{ name: 'home' }" class="menu-item mt-0" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-nav-car"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text">차량 조회</span>
                      <span class="text-secondary opacity-50 font-1">내 차량 조회</span>
                    </div>
                  </router-link>
                  <router-link :to="{ name: 'index.allreview' }" class="menu-item mt-1" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-ratings"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text">이용후기</span>
                      <span class="text-secondary opacity-50 font-1"> 다양한 판매 후기</span>
                    </div>
                  </router-link>
                  <router-link :to="{ name: 'index.introduce' }" class="menu-item mt-1 mb-3" @click="toggleNavbar">
                    <div class="sd-menu">
                      <div class="icon icon-nav-bulb"></div>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="menu-text">서비스 소개</span>
                      <span class="text-secondary opacity-50 font-1">위카 란?</span>
                    </div>
                  </router-link>
                </div>
              </div>
              <div class="logo-content">
                <img src="../../img/newicon/logo_2.png" alt="자동차 이미지" width="64" height="21">
              </div>
            </div>
          </div>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="mx-3 mov-navbar navbar-nav mt-2 mt-lg-0 w-100 d-flex align-items-center">
            <template v-if="isUser">
              <div class="d-flex">
                <li class="nav-item">
                  <router-link to="/" class="nav-link mx-3 nav-inq" aria-current="page" exact-active-class="active-link">내차조회</router-link>
                </li>
                <li class="nav-item">
                  <router-link :to="{ name: 'auction.index'}" class="nav-link mx-3 nav-auction" aria-current="page" exact-active-class="active-link">내 매물관리</router-link>
                </li>
                <li class="nav-item">
                  <router-link :to="{ name: 'user.review'}" class="nav-link me-0 mx-3 nav-review" exact-active-class="active-link">이용후기</router-link>
                </li>
              </div>
              <li class="nav-item my-member ms-auto dropdown">
                <a class="tc-wh p-1 pb-0 mx-2 dropdown-toggle mb-1" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ user.name }} 님
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><router-link to="/edit-profile" class="dropdown-item">내 정보 수정</router-link></li>
                  <li><a class="dropdown-item" href="/login" @click="logout">로그아웃</a></li>
                </ul>
              </li>
            </template>
            <template v-else-if="!user?.name">
              <li class="nav-item">
                <router-link :to="{ name: 'home'}" class="nav-link mx-3 nav-inq" exact-active-class="active-link">내차조회</router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'index.allreview'}" class="nav-link me-0 mx-3-review nav-review" exact-active-class="active-link">이용후기</router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'index.introduce'}" class="nav-link mx-3" to="/register" exact-active-class="active-link">서비스소개</router-link>
              </li>
              <li class="nav-item my-member-guest ms-auto">
                <img src="../../img/Icon-person.png" class="nav-profile-login" alt="LoginImg" height="25px" width="25px"><router-link class="nav-link me-0 text-secondary opacity-50 pb-0" to="/login" exact-active-class="active-link">로그인</router-link>
              </li>
            </template>
            <template v-else-if="isDealer">
              <li class="nav-item">
                <router-link :to="{ name: 'auction.index'}" class="nav-link tc-wh mx-3 nav-auction" to="/register" exact-active-class="active-link">입찰하기</router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'dealer.bids'}" class="nav-link tc-wh mx-3" exact-active-class="active-link">선택 완료 차량</router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'dealer.bidList'}" class="nav-link tc-wh mx-3" exact-active-class="active-link">과거 낙찰 이력</router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'index.claim'}" class="nav-link tc-wh mx-3" exact-active-class="active-link">클레임</router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'index.notices' }" class="nav-link tc-wh mx-3" exact-active-class="active-link">공지사항</router-link>
              </li>
              <li class="nav-item my-member-dealer ms-auto dropdown dropdown-arrow">
                <a class="tc-wh p-1 pb-0 me-3 dropdown-toggle" href="#" id="dealerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img :src="photoUrl" alt="Profile Photo" class="nav-profile" />{{ user.name }}
                </a>
                <ul class="dropdown-menu p-2" aria-labelledby="dealerDropdown">
                  <li class="my-2"><router-link to="/profile" class="dropdown-item">내 정보</router-link></li>
                <!--<li class="my-2"><router-link to="/edit-profile" class="dropdown-item">내 정보 수정</router-link></li>-->
                  <li class="my-2"><a class="dropdown-item" href="/login" @click="logout">로그아웃</a></li>
                </ul>
              </li>
            </template>
          </ul>
        </div>
        <a class="navbar-toggler p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span :class="{'toggle-nav-wh': isDealer, 'toggle-nav-black': !isDealer}"></span>
        </a>
      </div>
    </nav>
</div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, watch , nextTick } from 'vue';
  import { useRouter, useRoute } from 'vue-router';
  import { useStore } from 'vuex';
  import useAuth from '@/composables/auth';
  import useAuctions from '@/composables/auctions';
  import { cmmn } from '@/hooks/cmmn';
  import profileDom from '/resources/img/profile_dom.png';

  const photoUrl = ref(profileDom);
  const { wica , wicaLabel } = cmmn();
  const isMobile = ref(false);
  const { getAuctions, auctionsData } = useAuctions();
  const { logout } = useAuth();
  const router = useRouter();
  const route = useRoute();
  const showSettings = ref(false);
  const showSettingsmov = ref(false);
  const store = useStore();
  const isNavbarShown = ref(false);
  const showScrollGradient = ref(false);
  let scrollTimeout = null;
  const auctionDetailsLoaded = ref(false);

  function toggleSettingsMenuMov() {
    showSettingsmov.value = !showSettingsmov.value;
  }
  
  function toggleSettingsMenu() {
    showSettings.value = !showSettings.value;
  }
  
  const user = computed(() => store.getters['auth/user']);
  
  const isDealer = computed(() => user.value?.roles?.includes('dealer'));
  const isUser = computed(() => user.value?.roles?.includes('user'));
  const navbarClass = computed(() => (isDealer.value ? 'bg-primary' : 'bg-white'));
  const textClass = computed(() => (isDealer.value ? 'text-white' : 'text-dark'));
  
  const redirectByName = (routeName) => {
    router.push({ name: routeName });
  };

  const homePath = computed(() => {
    if (isDealer.value) {
      return { name: 'dealer.index' };
    } else if (isUser.value) {
      return { name: 'user.index' };
    } else {
      return { name: 'home' };
    }
  });
  
  const fetchFilteredViewBids = async () => {
    console.log('Original Bids:', bidsData.value);
    const bidsWithDetails = await Promise.all(bidsData.value.map(fetchAuctionDetails));
    console.log('Bids with Details:', bidsWithDetails);  // Bids with Details 데이터 출력
    filteredViewBids.value = bidsWithDetails.filter(bid => {
      console.log('Checking Bid:', bid);  // 각 Bid 데이터 출력
      return bid.auctionDetails && bid.auctionDetails.bid_id === user.value.id;
    });
    console.log('Bids with Auction Details:', filteredViewBids.value);
  };
  
  const userHasAuction = computed(() => {
    return auctionsData.value.some(auction => auction.user_id === user.value.id);
  });
  
  const latestAuction = computed(() => {
    if (auctionsData.value.length === 0) return null;
    const sortedAuctions = [...auctionsData.value].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    return sortedAuctions[0];
  });
  
  const isDiagnosing = computed(() => latestAuction.value && latestAuction.value.status === 'diag' || latestAuction.value.status === 'ask');
  const isAuctioning = computed(() => latestAuction.value && latestAuction.value.status === 'ing');
  const isSelectingDealer = computed(() => latestAuction.value && latestAuction.value.status === 'wait');
  const isCompleted = computed(() => latestAuction.value && latestAuction.value.status === 'chosen' || latestAuction.value && latestAuction.value.status === 'done');
  
  const isDiagnosisCompleted = computed(() => ['ing', 'wait', 'chosen'].includes(latestAuction.value?.status));
  const isAuctionCompleted = computed(() => ['wait', 'chosen'].includes(latestAuction.value?.status));
  const isDealerSelectionCompleted = computed(() => latestAuction.value?.status === 'chosen');
  
  const isDetailPage = computed(() => {
  return /^\/auction\/\d+$/.test(route.path) ||
         route.path === '/selldt' ||
         route.path === '/selldt2' ||
         /^\/completionsuccess\/\d+$/.test(route.path);
});

  
  const isAuctionDetailPage = computed(() => {
    return route.name === 'AuctionDetail';
  });
  
  const goBack = () => {
    router.back();
  };
  
  function toggleNavbar() {
    const content = document.querySelector('.toggle-nav-content');
    content.classList.remove('visible');
    clearTimeout(scrollTimeout);
  
    document.querySelector('.btn-close').click();
    showSettings.value = false;
    if (content) {
      content.scrollTop = 0;
    }
    toggleOverlay(false);
  }
  
  function checkScrollGradient() {
    const content = document.querySelector('.toggle-nav-content');
    if (content.scrollHeight > content.clientHeight) {
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        showScrollGradient.value = content.scrollTop + content.clientHeight < content.scrollHeight;
        if (showScrollGradient.value) {
          content.classList.add('visible');
        } else {
          content.classList.remove('visible');
        }
      }, 200);
    } else {
      showScrollGradient.value = false;
      content.classList.remove('visible');
    }
  }

  function fileExstCheck(info){
    if(info.hasOwnProperty('files')){
      if(info.files.hasOwnProperty('file_user_photo')){
          if(info.files.file_user_photo[0].hasOwnProperty('original_url')){
            photoUrl.value = info.files.file_user_photo[0].original_url;
          }
      }
    }
  }

  onMounted(() => {
    fileExstCheck(user.value);
    let navAuction = document.querySelectorAll('.nav-auction');
    let navReview = document.querySelectorAll('.nav-review');
    let navCarInq = document.querySelectorAll('.nav-inq');

    const content = document.querySelector('.toggle-nav-content');
    content.addEventListener('scroll', checkScrollGradient);
    checkScrollGradient();
    if (isUser.value || isDealer.value) {
      getAuctions();
    }
  
    const navbar = document.querySelector('.navbar-collapse');
    navbar.addEventListener('transitionend', () => {
      if (isNavbarShown.value) {
        checkScrollGradient();
      } else {
        const content = document.querySelector('.toggle-nav-content');
        content.classList.remove('visible');
      }
    });
  
    const navbarButton = document.querySelector('.navbar-toggler');
    navbarButton.addEventListener('click', () => {
      isNavbarShown.value = !isNavbarShown.value;
      toggleOverlay(isNavbarShown.value);
    });
  
    const closeButton = document.querySelector('.btn-close');
    closeButton.addEventListener('click', () => {
      isNavbarShown.value = false;
      toggleOverlay(isNavbarShown.value);
      toggleNavbar();
    });
  
    const overlay = document.querySelector('.overlay');
    overlay.addEventListener('click', () => {
      isNavbarShown.value = false;
      toggleOverlay(isNavbarShown.value);
      toggleNavbar();
    });
  
    router.afterEach(() => {
      toggleOverlay(false);
    });
    const updateIsMobile = () => {
    isMobile.value = window.innerWidth <= 991;
  };
  updateIsMobile();
  window.addEventListener('resize', updateIsMobile);

  watch(() => route.name, (to, from) => {
      //console.log('라우터 이름:', to);

      const removeActiveLink = (elements) => {
        elements.forEach(element => {
          element.classList.remove('active-link');
        });
      };

      removeActiveLink(navAuction);
      removeActiveLink(navReview);
      removeActiveLink(navCarInq);

      //내차조회
      if (to === 'sell' || to === 'selldt2') {
        nextTick(() => {
          navCarInq.forEach(element => {
            element.classList.add('active-link');
          })
        });
      } else {
        if(navCarInq){
          navCarInq.forEach(element => {  
            element.classList.remove('active-link');
          })
        }
      }

      //매물
      if (to === 'AuctionDetail' || to === 'completionsuccess') {
        nextTick(() => {
          navAuction.forEach(element => {
            element.classList.add('active-link');
          })
        });
      } else {
        if(navAuction){
          navAuction.forEach(element => {
            
            element.classList.remove('active-link');
          })
        }
      }

      //이용후기
      if (to === 'user.edit-review' || to === 'index.allreview' || to == "user.create-review" || to == "user.review-detail") {
        nextTick(() => {
          navReview.forEach(element => {
            element.classList.add('active-link');
          }) 
        });
      } else {
        if(navReview){
          if(navReview!=null && navReview.classList!=null)
            navReview.forEach(element => {
              element.classList.remove('active-link');
          }) 
        }
      }
    } , { immediate: true });

  });
    
  function toggleOverlay(show) {
    const overlay = document.querySelector('.overlay');
    if (show) {
      overlay.style.display = 'block';
      setTimeout(() => {
        overlay.style.opacity = '1';
      }, 10);
    } else {
      overlay.style.opacity = '0';
      setTimeout(() => {
        overlay.style.display = 'none';
      }, 300);
    }
  }


  </script>
  
  <style scoped>
  .toggle-nav-content {
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    justify-content: space-between;
    height: 84vh !important;
    position: relative;
  }
  
  .has-gradient::after {
    content: '';
    position: fixed;
    bottom: 54px;
    right: 0;
    width: 46vh;
    height: 58px;
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 1));
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
  }
  
  .has-gradient.visible::after {
    opacity: 1;
  }
  
  .middle-content-ty02 {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex-grow: 1;
  }
  
  .footer-content {
    margin-top: auto;
    width: 100%;
    position: relative;
  }
  
  .has-gradient.footer-content::after {
    content: '';
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 40px;
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 1));
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
  }
  
  .has-gradient.footer-content.visible::after {
    opacity: 1;
  }
  
  .menu-illustration,
  .menu-footer {
    width: 100%;
  }
  
  .menu-illustration {
    position: relative;
  }
  
  .menu-illustration img {
    width: auto;
    height: 100%;
  }
  
  .menu-illustration02 {
    position: relative;
  }
  
  .menu-illustration02 img {
    width: 55px;
    height: auto;
  }
  
  .login-prompt {
    display: block;
    width: 100%;
    text-align: right;
    font-size: 22px;
    font-weight: 600;
    margin: 20px 0;
  }
  
  .register-linker {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
  }
  
  .font-1 {
    font-size: 1rem;
  }
  
  a.navbar-toggler.p-2 {
    display: none !important;
  }
  
  @media (max-width: 991px) {
    a.navbar-toggler.p-2 {
      display: block !important;
    }
    .mov-navbar {
      display: none !important;
    }
  }
  
  .btn-back {
    width: 25px;
    height: 25px;
    border: none;
    font-size: 1.5rem;
    padding: 0;
    cursor: pointer;
  }
  
  .logo-content {
    background-color: #f7f8fb;
    width: -webkit-fill-available;
    height: 65px;
    position: fixed;
    bottom: 0;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .sd-menu {
    width: 48px;
    height: 48px;
    background-color: #f7f8fb;
    border-radius: 6px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  @media (min-width: 768px) {
    .navbar-expand-md .navbar-collapse {
    display:grid !important;
      flex-basis: auto;
      align-items: center;
    }
  }
  
  .settings-icon {
    cursor: pointer;
    transition: transform 0.3s ease-in-out;
  }
  
  .settings-icon.rotate {
    transform: rotate(360deg);
  }
  
  .settings-menu {
    position: absolute;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    width: 150px;
    right: 0;
    top: 45px;
    z-index: 1000;
    transform: translateY(-20px);
    opacity: 0;
    transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    border-radius: 6px;
  }
  
  .settings-menuty02 {
    position: absolute;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    width: 150px;
    right: 224px;
    top: 60px;
    z-index: 1000;
    transform: translateY(-20px);
    opacity: 0;
    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    border-radius: 6px;
  }
  
  .settings-menuty02.show {
    transform: translateY(0);
    opacity: 1;
  }
  
  .setting-web {
    right: 170px;
  }
  
  .settings-menu.show {
    transform: translateY(0);
    opacity: 1;
  }
  
  .timeline {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
  }
  
  .timeline li {
    display: flex;
    align-items: center;
    position: relative;
  }
  
  .timeline li .circle {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 14px;
    color: white;
    z-index: 1;
    background-color: #cacaca;
  }
  
  .timeline li.upcoming .circle {
    background-color: #d3d3d3;
  }
  
  .timeline li span {
    display: flex;
    align-items: center;
  }
  
  .timeline li.completed ~ li .small-circle,
  .timeline li.active ~ li .small-circle {
    background-color: #d3d3d3;
  }
  </style>
  