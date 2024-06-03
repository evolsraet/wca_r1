<template>
    <div class=""></div>
    <nav :class="['navbar', 'navbar-expand-md', 'navbar-light', 'shadow-sm', 'p-2', navbarClass, textClass]">
        <div class="container nav-font">
            <button v-if="isDetailPage" @click="goBack" class="p-2 btn btn-back back-btn-icon">
            </button>
            <router-link v-else-if="isDealer" to="/dealer" class="navbar-brand-dealer"></router-link>
            <router-link v-else-if="isUser" to="/" class="navbar-brand"></router-link>
            <router-link v-else to="/" class="navbar-brand"></router-link>
            <!-- movnav bar -->
            <!-- dealer navbar-->
            <div class="collapse navbar-collapse navbar-collshow" id="navbarSupportedContent">
                <div v-if="isDealer" class="navbar-nav nav-style">
                    <div class="nav-header">
                        <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close"></button>
                    </div>
                    <div class="toggle-nav-content">
                        <div class="top-content mt-2">
                            <p class="nav-gray-box">안녕하세요,<span>{{ user.name }}</span></p>
                            <div class="tc-light-gray text-end fs-6 mt-2 fw-medium">
                                <p>{{ user.dealer.name }}</p>
                                <p class="mt-1">{{ user.dealer.company }} 점</p>
                            </div>
                        </div>
                        <div class="middle-content">
                            <p class="tc-light-gray fs-6">계정</p>
                            <router-link :to="{ name: 'dealer.profile' }" class="menu-item" @click="toggleNavbar">
                                <div class="icon settings-icon"></div>
                                <span class="menu-text">정보수정</span>
                                <div class="icon right-icon"></div>
                            </router-link>
                            <a class="menu-item mb-3" href="/login" @click="logout">
                                <div class="icon logout-icon"></div>
                                <span class="menu-text">로그아웃</span>
                                <div class="icon right-icon"></div>
                            </a>
                        </div>
                        <router-link :to="{ name: 'auction.index'}" class="menu-item mb-3" @click="toggleNavbar">
                            <div class="icon icon-tag"></div>
                            <span class="menu-text recent-new">입찰하기</span>
                            <div class="icon right-icon"></div>
                        </router-link>
                        <router-link :to="{ name: 'dealer.bids'}" class="menu-item mb-4" @click="toggleNavbar">
                            <div class="icon icon-awsome"></div>
                            <span class="menu-text">낙찰 완료 차량</span>
                            <div class="icon right-icon"></div>
                        </router-link>
                        <router-link :to="{ name: 'dealer.bidList'}" class="menu-item mb-3" @click="toggleNavbar">
                            <div class="icon icon-side"></div>
                            <span class="menu-text recent-new">과거 낙찰 이력</span>
                            <div class="icon right-icon"></div>
                        </router-link>
                        <router-link :to="{ name: 'index.claim' }" class="menu-item mb-3" @click="toggleNavbar">
                            <div class="icon icon-document"></div>
                            <span class="menu-text recent-new">클레임</span>
                            <div class="icon right-icon"></div>
                        </router-link>
                        <router-link :to="{ name: 'index.notices' }"class="menu-item mb-3" @click="toggleNavbar">
                            <div class="icon icon-dash"></div>
                            <span class="menu-text">공지사항</span>
                            <div class="icon right-icon"></div>
                        </router-link>
                    </div>
                </div>
                <!-- user -->
                <div v-else-if="isUser" class="navbar-nav">
                    <div class="nav-header">
                        <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close"></button>
                    </div>
                    <div class="toggle-nav-content">
                        <div class="top-content mt-2">
                            <p class="nav-gray-box">안녕하세요,<span>{{ user.name }}</span></p>
                            <div class="tc-light-gray text-end fs-6 mt-2 fw-medium">
                                <p>소속 상사가 들어갑니다 파트너점</p>
                                <p class="mt-1"> </p>
                            </div>
                        </div>
                        <div class="middle-content-ty02">
                            <router-link :to="{ name: 'auction.index' }" class="nav-link dealer-check-link mt-5" @click="toggleNavbar">딜러 페이지 확인용 링크</router-link>
                            <router-link :to="{ name: 'home' }" class="menu-item ">
                                <div class="icon icon-side"></div>
                                <span class="menu-text">내 차 조회</span>
                                <div class="icon right-icon"></div>
                            </router-link>
                            <router-link :to="{ name: 'auction.index' }" class="menu-item mb-4" @click="toggleNavbar">
                                <div class="icon icon-awsome"></div>
                                <span class="menu-text">내 매물관리</span>
                                <div class="icon right-icon"></div>
                            </router-link>
                            <router-link :to="{ name: 'index.allreview' }" class="menu-item mt-0 mb-4" @click="toggleNavbar">
                                <div class="icon icon-ratings"></div>
                                <span class="menu-text">이용후기</span>
                                <div class="icon right-icon"></div>
                            </router-link>
                        </div>
                        <div class="footer-content">
                            <p class="tc-light-gray fs-6">계정</p>
                            <router-link :to="{ name: 'dealer.profile' }" class="menu-item" @click="toggleNavbar">
                                <div class="icon settings-icon"></div>
                                <span class="menu-text">정보수정</span>
                                <div class="icon right-icon"></div>
                            </router-link>
                            <a class="menu-item mb-3" href="/login" @click="logout">
                                <div class="icon logout-icon"></div>
                                <span class="menu-text">로그아웃</span>
                                <div class="icon right-icon"></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else class="navbar-nav">
                    <div class="nav-header">
                        <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close"></button>
                    </div>
                    <div class="toggle-nav-content">
                        <div class="top-content ">
                            <div class="menu-illustration p-3">
                                    <img src="../../img/newicon/login-banner.png" alt="자동차 이미지" width="280" height="80">
                                    <div class="text-start">
                                        <p>내 차 팔까?</p>
                                        <div class="d-flex align-items-center">
                                            <router-link :to="{ name: 'auth.login' }" class="tc-primary bold-18-font"@click="toggleNavbar">로그인하기 </router-link>
                                            <div class="icon right-icon"></div>
                                        </div>
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
                                                <span class="tc-light-gray font-1">내 차량 조회</span>
                                            </div>
                                        </router-link>
                                        <router-link :to="{ name: 'index.allreview' }" class="menu-item mt-1" @click="toggleNavbar">
                                            <div class="sd-menu">
                                            <div class="icon icon-ratings"></div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="menu-text">이용후기</span>
                                                <span class="tc-light-gray font-1"> 다양한 판매 후기</span>
                                            </div>
                                        </router-link>
                                        <router-link :to="{ name: 'dealer.profile' }" class="menu-item mt-1 mb-3" @click="toggleNavbar">
                                            <div class="sd-menu">
                                            <div class="icon icon-nav-bulb"></div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="menu-text">서비스 소개</span>
                                                <span class="tc-light-gray font-1">위카 란?</span>
                                            </div>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="logo-content">
                                <img src="../../img/newicon/logo_2.png" alt="자동차 이미지" width="64" height="21">
                            </div>
                    </div>
                </div>
            <!-- web nav bar -->
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mt-2 mt-lg-0 gap-1 ms-auto">
                 <!-- 사용자 일때 -->
                 <template v-if="isUser">
                        <li class="nav-item">
                            <router-link to="/" class="nav-link" aria-current="page">내차조회</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'auction.index'}" class="nav-link" aria-current="page">내 매물관리</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'user.review'}" class="nav-link">이용후기</router-link>
                        </li>
                        <li class="my-member">
                            <img src="../../img/myprofile_ex.png" class="nav-profile" alt="Profile Image"><a class="nav-link" href="#">{{ user.name }}</a>
                        </li>
                    </template>
                    <!-- 게스트 일때 -->
                    <template v-else-if="!user?.name">
                        <li class="nav-item">
                            <router-link to="/" class="nav-link" aria-current="page">내차조회</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'index.allreview'}" class="nav-link">이용후기</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'index.introduce'}" class="nav-link" to="/register">서비스소개</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/login">로그인</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/register">회원가입</router-link>
                        </li>
                    </template>
                    <!-- 딜러 일때 -->
                    <template v-else-if="isDealer">
                        <li class="nav-item">
                            <router-link :to="{ name: 'auction.index'}" class="nav-link" to="/register" >입찰하기</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'dealer.bids'}" class="nav-link">선택 완료 차량</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'dealer.bidList'}" class="nav-link">과거 낙찰 이력</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'index.claim'}" class="nav-link">클레임</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'index.notices' }" class="nav-link">공지사항</router-link>
                        </li>
                        <li class="my-member"  @click="redirectByName('dealer.profile')" >
                            <img src="../../img/myprofile_ex.png" class="nav-profile" alt="Profile Image"><a :to="{ name: 'dealer.profile' }"  class="nav-link" href="#">{{ user.name }}</a>
                        </li>
                    </template>
                    <!-- 로그인 시 -->
                    <ul v-if="user?.name" class="navbar-nav mt-lg-0 ms-auto">
                        <li><a class="nav-link tc-light-gray logout" href="/login" @click="logout">로그아웃</a></li>
                    </ul>
                </ul>
            </div>
            <a class="navbar-toggler p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span :class="{'toggle-nav-wh': isDealer, 'toggle-nav-black': !isDealer}"></span>
            </a>
        </div>
    </nav>
</template>

<script setup>
import { ref } from "vue";
import { useStore } from "vuex";
import useAuth from "@/composables/auth";
import { computed } from "vue";
import { useRouter, useRoute } from "vue-router";

const router = useRouter();
const route = useRoute();

const store = useStore();
const user = computed(() => store.getters["auth/user"]);
const isDealer = computed(() => user.value?.roles?.includes('dealer'));
const isUser = computed(() => user.value?.roles?.includes('user'));
const isAdmin = computed(() => user.value?.roles?.includes('admin'));
const navbarClass = computed(() => {
  return isDealer.value ? 'bg-primary' : 'bg-white';
});
const textClass = computed(() => {
  return isDealer.value ? 'text-white' : 'text-dark';
});
const { logout } = useAuth();

const redirectByName = (routeName) => {
    router.push({ name: routeName });
}; //리다이렉션 : 명

const homePath = computed(() => {
    if (isDealer.value) {
        return { name: 'dealer.index' }; 
    } else if (isUser.value) {
        return { name: 'user.index' }; 
    } else {
        return { name: 'home' };
    }
});

// 현재 경로를 기준으로 상세 페이지인지 확인
const isDetailPage = computed(() => {
    // 상세 페이지 경로가 '/auction'으로 시작하고 뒤에 숫자 ID가 있는지 확인
    return /^\/auction\/\d+$/.test(route.path) || route.path === '/selldt' || route.path === '/selldt2';
});

// 뒤로 가기 함수
const goBack = () => {
    router.back();
};

//라우터시 메뉴바 닫기
function toggleNavbar() {
    document.querySelector('.btn-close').click();
}

</script>

<style scoped>
.toggle-nav-content {
    display: flex;
    flex-direction: column;
    overflow-y: hidden;
    justify-content: space-between;
    height: 84vh !important;
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
}

.dealer-check-link {
    height: 68px;
    line-height: 10px;
    font-weight: 500;
    padding: 25px 26px;
    border: solid 1px #f0d;
    background-color: #fff;
    color: #f0d !important;
    text-align: center;
    margin-right: 0px;
}

.menu-illustration,
.menu-footer {
    width: 100%;
}

.menu-illustration {
    position: relative;
}

.menu-illustration img {
    width: 100%;
    height: 100%;
}

.text-start {
    position: absolute;
    top: 50%; 
    left: 25%;
    transform: translate(-50%, -50%); 
    color: #c79595;
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

.font-1{
    font-size: 1rem;
}
a.navbar-toggler.p-2 {
    display: none !important;
}

@media (max-width: 991px){
    a.navbar-toggler.p-2 {
    display: block !important;
}
ul.navbar-nav.mt-2.mt-lg-0.gap-1.ms-auto{
display: none !important;
}
}

/* 뒤로 가기 버튼 스타일 */
.btn-back {
    width:25px;
    height:25px;
    border: none;
    font-size: 1.5rem;
    padding: 0;
    cursor: pointer;
}


.logo-content {
    background-color: #f7f8fb;
    width: 50vh;
    height: 65px;
    position: fixed;
    bottom: 0px;
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
</style>
