<template>
    <nav class="px-2 navbar navbar-expand-lg sticky-top flex-md-nowrap shadow-sm" style="background-color: #e3f2fd;">
        <div class="container-fluid">
            <router-link to="/admin" class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 nuxt-link-active mini">
            </router-link>
            <button  class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <!-- <li class="nav-item">
                        <router-link :to="{ name: 'posts.index' }" class="nav-link active" aria-current="page">
                            Posts
                        </router-link>
                    </li> -->
            <!--         <LocaleSwitcher />
                 <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Hi, {{ user.name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            <li><router-link :to="{ name: 'profile.index' }" class="dropdown-item">Profile</router-link></li>
                            <li><a class="dropdown-item" href="#">Setting</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" :class="{ 'opacity-25': processing }" :disabled="processing"
                                   href="javascript:void(0)" @click="logout">Logout</a></li>
                        </ul>
                    </li>-->
                    <div class="navbar-nav">
                    <div class="nav-header p-3">
                        <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close"></button>
                    </div>
                    <div class="toggle-nav-content">
                        <div class="top-content mt-2">
                            <p class="nav-gray-box">안녕하세요,<span>{{ user.name }}</span></p>
                            <div class="tc-light-gray text-end fs-6 mt-2 fw-medium">
                                <p class="mt-1"> </p>
                            </div>
                        </div>
                        <div class="middle-content-ty02 mt-4">
                        <p class="tc-light-gray fs-6">관리</p>
                            <router-link :to="{ name: 'users.index' }" class="menu-item " @click="toggleNavbar">
                                <div class="icon icon-side"></div>
                                <span class="menu-text">회원 관리</span>
                                <div class="icon right-icon"></div>
                            </router-link >
                            <router-link :to="{ name: 'deposit.index' }" class="menu-item mb-4"@click="toggleNavbar">
                                <div class="icon icon-awsome"></div>
                                <span class="menu-text">입금 관리</span>
                                <div class="icon right-icon"></div>
                            </router-link >
                            <router-link :to="{ name: 'auction.index' }" class="menu-item mb-4"@click="toggleNavbar">
                                <div class="icon icon-nav-car"></div>
                                <span class="menu-text">매물 관리</span>
                                <div class="icon right-icon"></div>
                            </router-link >
                            <router-link :to="{ name: 'deposit.index' }" class="menu-item mb-4"@click="toggleNavbar">
                                <div class="icon icon-nav-bulb"></div>
                                <span class="menu-text">후기 관리</span>
                                <div class="icon right-icon"></div>
                            </router-link >
                            <router-link :to="{ name: 'posts.index' }" class="menu-item mt-0 mb-4" @click="toggleNavbar">
                                <div class="icon icon-ratings me-3"></div>
                                <span class="menu-text">공지 관리</span>
                                <div class="icon right-icon"></div>
                            </router-link>
                        </div>
                        <div class="footer-content">
                            <p class="tc-light-gray fs-6">계정</p>
                            <a class="menu-item mb-3" href="/login" @click="logout" >
                                <div class="icon logout-icon"></div>
                                <span class="menu-text">로그아웃</span>
                                <div class="icon right-icon"></div>
                            </a>
                        </div>
                    </div>
                </div>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import {computed} from "vue";
import { useStore } from 'vuex';
import useAuth from "@/composables/auth";
import LocaleSwitcher from "../../components/LocaleSwitcher.vue";

    const store = useStore();
    const user = computed(() => store.state.auth.user)
    const {processing, logout} = useAuth();

//라우터시 메뉴바 닫기
function toggleNavbar() {
    document.querySelector('.btn-close').click();
}
</script>

<style scoped>
@media (max-width: 767.98px){
    .navbar-toggler{
        display: block !important;
    }
}
@media (min-width: 768px){
    .navbar-toggler{
        display:none !important;
    }
}
</style>
