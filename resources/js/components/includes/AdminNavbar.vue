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
        <ul class="navbar-nav ms-auto mb-lg-0">
            <div class="navbar-nav flex-row justify-content-center gap-5 bold-18-font"> 
                <router-link 
                    :to="{ name: 'users.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('users.index') }"
                    @click="toggleNavbar"
                >
                    <span class="tc-gray menu-text">회원</span>
                </router-link>
                <router-link 
                    :to="{ name: 'deposit.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('deposit.index') }"
                    @click="toggleNavbar"
                >
                    <span class="tc-gray menu-text">입금</span>
                </router-link>
                <router-link 
                    :to="{ name: 'auctions.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('auctions.index') }"
                    @click="toggleNavbar"
                >
                    <span class="tc-gray menu-text">매물</span>
                </router-link>
                <router-link 
                    :to="{ name: 'review.index' }" 
                    class="menu-item mt-2" 
                    :class="{ active: isActive('review.index') }"
                    @click="toggleNavbar"
                >
                    <span class="tc-gray menu-text">후기</span>
                </router-link>
            </div>
        </ul>
    </div>
</template>
<script setup>
import { computed, ref } from 'vue';
import { useStore } from 'vuex';
import { useRoute, useRouter } from 'vue-router';
import useAuth from "@/composables/auth";
const showSettings = ref(false);
const store = useStore();
const user = computed(() => store.state.auth.user);
const { processing, logout } = useAuth();
const route = useRoute();
const router = useRouter();

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

.tc-gray {
    color: #aaaebe;
}

.menu-item {
    position: relative;
    color: black !important;
    text-decoration: none;
}

.menu-item:hover .tc-gray,
.menu-item.active .tc-gray {
    color: black !important;
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
