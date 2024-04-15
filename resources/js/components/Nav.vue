<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container nav-font">
            <router-link to="/" class="navbar-brand"></router-link>
            <!-- 사용자 별 toggle-navbar  -->
            <!-- dealer navbar-->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
                            <a class="menu-item" href="#">
                                <div class="icon settings-icon"></div>
                                <span class="menu-text">정보수정</span>
                                <div class="icon right-icon"></div>
                            </a>
                            <a class="menu-item mb-3" href="javascript:void(0)" @click="logout">
                                <div class="icon logout-icon"></div>
                                <span class="menu-text" >로그아웃</span>
                                <div class="icon right-icon"></div>
                            </a>
                        </div>
                        <a class="menu-item mb-3">
                                <div class="icon icon-tag"></div>
                                <span class="menu-text recent-new">입찰하기</span>
                                <div class="icon right-icon"></div>
                            </a>
                            <a class="menu-item mb-3">
                                <div class="icon icon-awsome"></div>
                                <span class="menu-text">내 매물관리</span>
                                <div class="icon right-icon"></div>
                            </a>
                            <a class="menu-item mb-3">
                                <div class="icon icon-side"></div>
                                <span class="menu-text recent-new">과거 낙찰 이력</span>
                                <div class="icon right-icon"></div>
                            </a>
                            <a class="menu-item mb-3">
                                <div class="icon icon-document"></div>
                                <span class="menu-text recent-new">클레임</span>
                                <div class="icon right-icon"></div>
                            </a>
                            <a class="menu-item mb-3">
                                <div class="icon icon-dash"></div>
                                <span class="menu-text">공지사항</span>
                                <div class="icon right-icon"></div>
                            </a>
                        </div>
                    </div>
                <!-- user -->
                <ul v-else-if="isUser" class="navbar-nav">
                    <li class="nav-item"><router-link to="/" class="nav-link" aria-current="page">사용자 (로그인후)</router-link></li>
                </ul>
                <ul v-else class="navbar-nav">
                    <li class="nav-item"><router-link to="/general-info" class="nav-link">게스트</router-link></li>
                </ul>
            </div>
            <!-- 공통 -->
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mt-2 mt-lg-0 gap-3 ms-auto">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" aria-current="page">내차조회</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'autction.index'}" class="nav-link">내차팔기</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'public-posts.index'}" class="nav-link">이용후기</router-link>
                    </li>
                    <!-- 게스트 일때 -->
                    <template v-if="!user?.name">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/login">로그인</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/register">회원가입</router-link>
                        </li>
                    </template>
                    <!-- 로그인 시 -->
                    <ul v-if="user?.name" class="navbar-nav mt-lg-0 ms-auto">
                        <li class="my-member"><img src="../../img/car.png" alt="Profile Image" style="width: 36px; height: 36px; border-radius: 50%;object-fit: cover;"><a class="nav-link" href="#">{{ user.name }}</a></li>
                        <li><a class="nav-link tc-light-gray logout" href="javascript:void(0)" @click="logout">로그아웃</a></li>
                    </ul>
                </ul>
            </div>
            <a class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </a>
        </div>
    </nav>
</template>

<script setup>
import { useStore } from "vuex";
import useAuth from "@/composables/auth";
import { computed } from "vue";

const store = useStore();
const user = computed(() => store.getters["auth/user"]);
const isDealer = computed(() => user.value?.roles?.includes('dealer'));
const isUser = computed(() => user.value?.roles?.includes('user'));
const { logout } = useAuth();
</script>
