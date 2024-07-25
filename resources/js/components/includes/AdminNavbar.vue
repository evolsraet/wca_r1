<template>
    <nav class="px-2 navbar navbar-expand-lg sticky-top flex-md-nowrap shadow-sm">
        <div class="container-fluid px-2">
            <router-link to="/admin" class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 nuxt-link-active mini">
            </router-link>
            <div>
                <button v-if="isAdminPage" type="button" @click="toggleMenu" aria-label="Toggle navigation">
                    <span class="admin-icon admin-icon-menu-top mt-1"></span>
                    <p>메뉴</p>
                </button>
                <button v-if="!isAdminPage" type="button" @click="toggledash" aria-label="Toggle navigation">
                    <span class="admin-icon admin-icon-dashboard-top process mx-3 mt-2"></span>
                    <p class="process mx-1 mt-1">대시보드</p>
                </button>
                <button
                type="button"
                to="/admin"
                aria-label="Toggle navigation"
                @click="toggleSettingsMenu"
                :class="{ 'rotate': showSettings }"
                >
                <span class="admin-icon admin-icon-my process ms-3"></span>
                <p class="process ms-3 mt-2">마이</p>
                </button>
            </div>
        </div>
    </nav>
    <div v-if="showSettings" :class="['settings-menu', { show: showSettings }]">
      <router-link to="/edit-profile" class="menu-item mt-0">내 정보 수정</router-link>
      <a class="menu-item mt-0" href="/login" @click="logout">로그아웃</a>
    </div>
    <div :class="['menu-container', { show: isMenuOpen || !isAdminPage }]" id="navbarSupportedContent">
        <ul class="d-flex mb-0 ps-3 ms-auto mb-lg-0 overflow-x-auto">
            <div class="navbar-nav flex-row justify-content-center gap-5 bold-18-font"> 
                <router-link 
                    :to="{ name: 'users.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('users.index') }"
                    @click="toggleNavbar"
                    id="adminNav-user"
                >
                    <span class="text-secondary menu-text opacity-50">회원 관리</span>
                </router-link>
                <router-link 
                    :to="{ name: 'deposit.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('deposit.index') }"
                    @click="toggleNavbar"
                    id="adminNav-deposit"
                >
                    <span class="text-secondary menu-text opacity-50">입금 관리</span>
                </router-link>
                <router-link 
                    :to="{ name: 'auctions.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('auctions.index') }"
                    @click="toggleNavbar"
                    id="adminNav-auction"
                >
                    <span class="text-secondary menu-text opacity-50">매물 관리</span>
                </router-link>
                <router-link 
                    :to="{ name: 'review.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('review.index') }"
                    @click="toggleNavbar"
                    id="adminNav-review"
                >
                    <span class="text-secondary menu-text opacity-50">후기 관리</span>
                </router-link>
                <router-link 
                    :to="{ name: 'review.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('review.index') }"
                    @click="toggleNavbar"
                    id="adminNav-review"
                >
                    <span class="text-secondary menu-text opacity-50">공지사항 관리</span>
                </router-link>
                <router-link 
                    :to="{ name: 'review.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('review.index') }"
                    @click="toggleNavbar"
                    id="adminNav-review"
                >
                    <span class="text-secondary menu-text opacity-50">클레임 관리</span>
                </router-link>
            </div>
        </ul>
    </div>
</template>
<script setup>
import { computed, ref , onMounted , watch , nextTick} from 'vue';
import { useStore } from 'vuex';
import { useRoute, useRouter } from 'vue-router';
import useAuth from "@/composables/auth";
const showSettings = ref(false);
const store = useStore();
const user = computed(() => store.state.auth.user);
const { processing, logout } = useAuth();
const route = useRoute();
const router = useRouter();
const pageName = ref('');

const isMenuOpen = ref(false);

function toggleMenu() {
    isMenuOpen.value = !isMenuOpen.value;
}
function toggleSettingsMenu() {
  showSettings.value = !showSettings.value;
}
function toggleNavbar() {
    isMenuOpen.value = false;
}

const isActive = (name) => route.name === name;
const isAdminPage = computed(() => route.path === '/admin');

function toggledash() {
    router.push('/admin');  // 이 부분에서 '/admin' 페이지로 리디렉션
}

function addSideBar(element, routeName, to){
    
    //console.log(element);
    
    if(to === routeName){
        nextTick(() => {
            element.classList.remove('nav-link', 'side-navbar-link');
            element.classList.add('router-link-active', 'router-link-exact-active', 'active');
        });
    } else if(pageName.value != routeName.split('.')[0]){
        element.classList.remove('router-link-active', 'router-link-exact-active', 'active');
        element.classList.add('nav-link', 'side-navbar-link');
    } 
}

onMounted(() => {

let user = document.getElementById('adminNav-user');
let review = document.getElementById('adminNav-review');
let deposit = document.getElementById('adminNav-deposit');
let auction = document.getElementById('adminNav-auction');

watch(() => route.name, (to, from) => {
    console.log('라우터 이름:', to);
    pageName.value = to.split('.')[0];
    addSideBar(user,'users.edit',to);
    addSideBar(deposit,'deposit.approve',to);
    addSideBar(auction,'auction.approve',to);
    addSideBar(review,'review.approve',to);

}, { immediate: true });

})

</script>


<style scoped>
@media (max-width: 767.98px) {
    .navbar-toggler {
        display: block !important;
    }
}
@media (min-width: 768px) {
    .navbar-toggler {
        display: none !important;
    }
}

.menu-container {
    overflow: hidden;
    max-height: 0;
    transition: max-height 0.5s ease-in-out, opacity 0.5s ease-in-out;
    opacity: 0;
    background-color: white;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.28);
}

.menu-container.show {
    max-height: 500px; /* Adjust as needed to fit the content */
    opacity: 1;
}

.nav-profile {
    width: 36px;
    height: auto;
    margin-left: 5px;
    border-radius: 50%;
    object-fit: cover;
}
.nav-profile-login {
    width: 28px;
    height: auto;
    margin-left: 5px;
    border-radius: 50%;
    object-fit: cover;
}
.logout {
    font-size: 12px;
    margin-top: 5px;
    font-weight: bold;
}
.nav-header {
    text-align: end;
    padding: 0.5rem;
    height: 60px;
}
.btn-close {
    font-size: 1.5rem;
    border: none;
    cursor: pointer;
}
.toggle-nav-content {
    display: block;
    width: 100% !important;
}
.middle-content {
    margin-top: 6rem !important;
}
#navbarSupportedContent {
    position: static;
    right: 7px;
    height: auto;
    width: 100%;
    overflow-x: hidden;
    transition: width 0.3s;
    background-color: white;
    box-shadow: 0 0.5px 2px rgba(0, 0, 0, 0.28);
    z-index: 1020;
}

.text-secondary {
    color: #aaaebe;
}

.menu-item {
    white-space: nowrap; 
    position: relative;
    text-decoration: none;
}

.menu-item:hover .text-secondary,
.menu-item.active .text-secondary {
    color: black !important;
}
.settings-menu.show .menu-item.active::after,
.settings-menu.show .menu-item:hover::after{
    content: '';
    background-color: rgba(255, 255, 255, 0) !important;
}
.menu-item:hover::after,
.menu-item.active::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: -2px;
    height: 5px;
    background-color: red;
}
.sticky-top{
    position: relative !important;
}

.settings-menu {
    position: absolute;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    width: 150px;
    right: 10px;
    top: 74px;
    z-index: 1023;
    transform: translateY(-20px);
    opacity: 0;
    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    border-radius: 6px;
}
.setting-web{
    right:170px;
}
.settings-menu.show {
    transform: translateY(0);
    opacity: 1;
}

</style>
