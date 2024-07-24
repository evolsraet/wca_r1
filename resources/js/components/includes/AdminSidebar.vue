<template>
    <nav class="bg-white sidebar" style="right:270px;">
        <div class="d-flex flex-column align-items-center ms-3 pt-0 mb-4">
            <router-link to="/admin" class="navbar-brand col-md-3 col-lg-2 ms-1 px-3 fs-6 nuxt-link-active mini mb-2"></router-link>
            <span class="admin-icon admin-icon-profile admin-icon-large"></span>
            <p class="profile-name">{{ userName }}</p>
            <p class="profile-name text-secondary opacity-50">{{ userEmail }}</p>
            <div class="d-flex mt-2 gap-3 mb-2">
                <a href="/login" @click="logout" class="text-secondary nav-link d-flex align-items-center ft-13 mx-1">
                    <span class="d-none d-sm-inline text-secondary opacity-75 ms-1">로그아웃</span>
                </a>
                <router-link :to="{ name: 'myinfo.edit' }" class="">
                    <a href="#"  class="text-secondary nav-link d-flex align-items-center ft-13">
                        <span class="d-none d-sm-inline text-secondary opacity-75"><div class="admin-icon admin-icon-settings admin-icon-small-02 me-1 mb-1"></div>
                            수정
                        </span>
                    </a>  
                </router-link>
            </div>
            <router-link to="/admin" class="btn shadow-sm bg-secondary tc-wh bg-opacity-50 w-100 admin-dashboard"><span class="admin-icon admin-icon-dashboard me-2"></span>대시보드</router-link>
        </div>
        <div class="pt-3 sidebar-sticky">
            <ul id="menu" class="nav flex-column mb-2">
                <li class="nav-item mb-4">
                    <router-link :to="{ name: 'users.index' }" class="side-navbar-link nav-link" id="adminSidebar-user"> 
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-users admin-icon-small-02 mx-2 "></div>
                            <span class="d-none d-sm-inline ps-2" id="adminSidebar-user">회원 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
                <li class="nav-item mb-4">
                    <router-link :to="{ name: 'deposit.index' }" class="nav-link side-navbar-link" id="adminSidebar-deposit">
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-coin mx-2"></div>
                            <span class="d-none d-sm-inline ps-2" id="adminSidebar-deposit">입금 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
                <li class="nav-item side-navbar-link mb-4">
                    <router-link :to="{ name: 'auctions.index' }" class="nav-link side-navbar-link" id="adminSidebar-auction">
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-car admin-icon-small mx-2"></div>
                            <span class="d-none d-sm-inline ps-2">매물 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
                <li class="nav-item mb-4">
                    <router-link :to="{ name: 'review.index' }" class="nav-link side-navbar-link" id="adminSidebar-review">
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-review admin-icon-small mx-2"></div>
                            <span class="d-none d-sm-inline ps-2" >후기 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
                <li class="nav-item mb-4">
                    <router-link :to="{ name: 'posts.index', params: { boardId: 'notice' } }" class="nav-link side-navbar-link" id="adminSidebar-review">
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-review admin-icon-small mx-2"></div>
                            <span class="d-none d-sm-inline ps-2" >공지 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
                <li class="nav-item mb-4">
                    <router-link :to="{ name: 'posts.index', params: { boardId: 'claim' } }" class="nav-link side-navbar-link" id="adminSidebar-review">
                        <div class="d-flex align-items-center">
                            <div class="admin-icon admin-icon-review admin-icon-small mx-2"></div>
                            <span class="d-none d-sm-inline ps-2" >클레임 관리</span>
                            <div class="admin-icon admin-icon-coin admin-icon-pass m-auto"></div>
                        </div>
                    </router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { onMounted , watch , nextTick , ref } from 'vue';
import { useAbility } from "@casl/vue";
import useAuth from "@/composables/auth";
import useUsers from "@/composables/users";
import { useRouter , useRoute } from 'vue-router';
import { useStore } from 'vuex';
const { can } = useAbility();
const { logout } = useAuth();
const router = useRouter();
const route = useRoute();
const pageName = ref('');
const store = useStore();

const userName = ref('');
const userEmail = ref('');
const {
        getUser,
} = useUsers();

function addSideBar(element, routeName, to){
    
    //console.log(element);
    
    if(to === routeName){
        nextTick(() => {
            element.classList.remove('nav-link', 'side-navbar-link'); 
            element.classList.add('router-link-active', 'router-link-exact-active', 'nav-link', 'side-navbar-link');
        });
    } else if(pageName.value != routeName.split('.')[0]){
        element.classList.remove('router-link-active', 'router-link-exact-active', 'nav-link', 'side-navbar-link');
        element.classList.add('nav-link', 'side-navbar-link'); 

    } 
}

onMounted(async () => {
    const userInfo =store.getters['auth/user'];
    let userId = userInfo.id;
    const response = await getUser(userId);

    userName.value = response.name;
    userEmail.value = response.email;

    let user = document.getElementById('adminSidebar-user');
    let review = document.getElementById('adminSidebar-review');
    let deposit = document.getElementById('adminSidebar-deposit');
    let auction = document.getElementById('adminSidebar-auction');

    watch(() => route.name, (to, from) => {
        //console.log('라우터 이름:', to);
        pageName.value = to.split('.')[0];
        addSideBar(user,'users.edit',to);
        addSideBar(deposit,'deposit.approve',to);
        addSideBar(auction,'auction.approve',to);
        addSideBar(review,'review.approve',to);

    }, { immediate: true });

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
