<template>
    <nav class="bg-white sidebar" style="right:270px;">
        <div class="d-flex flex-column align-items-center ms-3 pt-0 mb-4">
            <router-link to="/admin" class="navbar-brand col-md-3 col-lg-2 ms-1 px-3 fs-6 nuxt-link-active mini mb-2"></router-link>
            <span class="admin-icon admin-icon-profile admin-icon-large"></span>
            <p class="profile-name">관리자</p>
            <p class="profile-name tc-light-gray">admin@demo.com</p>
            <div class="d-flex mt-2 gap-3 mb-2">
                <a href="/login" @click="logout" class="tc-light-gray nav-link d-flex align-items-center ft-13 mx-1">
                    <span class="d-none d-sm-inline tc-light-gray ms-1">로그아웃</span>
                </a>
                <a href="/login" @click="logout" class="tc-light-gray nav-link d-flex align-items-center ft-13">
                    <span class="d-none d-sm-inline tc-light-gray"><div class="admin-icon admin-icon-settings admin-icon-small-02 me-1 mb-1"></div>수정</span>
                </a>
            </div>
            <router-link to="/admin" class="btn shadow-sm bg-secondary tc-wh bg-opacity-50 w-100 admin-dashboard"><span class="admin-icon admin-icon-dashboard me-2"></span>대시보드</router-link>
        </div>
        <div class="pt-3 sidebar-sticky">
            <ul id="menu" class="nav flex-column mb-2">
                <li class="nav-item mb-4">
                    <router-link :to="{ name: 'users.index' }" class="side-navbar-link nav-link">
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-users admin-icon-small-02 mx-2 "></div>
                            <span class="d-none d-sm-inline ps-2">회원 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
                <li class="nav-item mb-4">
                    <router-link :to="{ name: 'deposit.index' }" class="nav-link side-navbar-link">
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-coin mx-2"></div>
                            <span class="d-none d-sm-inline ps-2">입금 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
                <li class="nav-item side-navbar-link mb-4">
                    <router-link :to="{ name: 'auctions.index' }" class="nav-link side-navbar-link">
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-car admin-icon-small mx-2"></div>
                            <span class="d-none d-sm-inline ps-2">매물 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
                <li class="nav-item mb-4">
                    <router-link :to="{ name: 'review.index' }" class="nav-link side-navbar-link">
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-review admin-icon-small mx-2"></div>
                            <span class="d-none d-sm-inline ps-2">후기 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAbility } from "@casl/vue";
import useAuth from "@/composables/auth";
import { useRouter } from 'vue-router';
const { can } = useAbility();
const { logout } = useAuth();
const router = useRouter();

onMounted(() => {
    //router.push({ name: 'users.index' });
})

</script>
<style scoped lang="scss">
@media (max-width: 767.98px){
 .sidebar {
    min-width: 0px !important;
    max-width: 0px !important;
    padding: 0px;
}
}

.logout-fixed {
    position: fixed;
    bottom: 0;
    right: 100px;
}
.ft-13{
    font-size: 13px !important;
}
</style>
