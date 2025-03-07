<template>
    <div class="row justify-content-center my-2 p-3">
        <div class="col-md-12 d-flex justify-content-between">
            <h4>회원 관리<p class="text-secondary opacity-75 fs-6 my-3">회원을 수정, 삭제, 관리 할수있습니다</p></h4>
            <div class="search-type2 mb-2" style="display: flex; align-items: center;">
                <div class="border-xsl" style="margin-right: 10px;">
                    <div class="image-icon-excel pointer" @click="downloadUsersExcel">
        
                    </div>
                </div>
            </div>
        </div>
                
        <div id="dashadmin">
            <div class="card-container mt-2 mb-2">
                <div class="section">
                    <h5 class="text-start">정상회원 {{ userOkCnt }} 명</h5>
                </div>
                <div class="section">
                    <h5 class="text-start">심사중 {{ userAskCnt }} 명</h5>
                </div>
                <div class="section">
                    <h5 class="text-start">거절 {{ userRejectCnt }} 명</h5>
                </div>
                <div class="section">
                    <h5 class="text-start">경고 {{ userWarningCnt }} 명</h5>
                </div>
                <div class="section">
                    <h5 class="text-start">재명 {{ userExpulsionCnt }} 명</h5>
                </div>
            </div>
        </div>
        
            
        <div class="container mb-3 d-flex flex-column flex-md-row justify-content-between sticky-top">

            <div class="text-end status-selector">
                <input type="radio" name="status" value="all" id="all" hidden checked @change="setUserFilter('all')">
                <label for="all" class="mx-2">전체</label>

                <input type="radio" name="status" value="user" id="user" hidden @change="setUserFilter('user')">
                <label for="user">일반</label>

                <input type="radio" name="status" value="dealer" id="dealer" hidden @change="setUserFilter('dealer')">
                <label for="dealer" class="mx-2">딜러</label>
            </div>

            <div class="d-flex justify-content-end responsive-flex-end gap-3">
                
                
                <div class="text-end select-option">
                    <select class="form-select select-rank" @change="event => setStatusFilter(event.target.value)">
                        <option v-for="(label, value) in statusLabel" :key="value" :value="value" :selected="value == 'all'">{{ label }}</option>
                    </select>
                </div>
                <div class="search-type2 p-0">
                    <input type="text" placeholder="회원 검색" v-model="search_title" id="searchUserName" @keyup.enter="searchBtn" style="width: auto !important; margin-right: 10px;"/>
                    <button type="button" class="search-btn" @click="searchBtn">검색</button>
                </div>
            </div>
        </div>
        

            <a href="/admin/users/create" class="border-red-write"><div class="image-icon-pen"></div></a>

            <!-- <div class="d-flex align-items-center justify-content-end mt-3">
                <button class="btn btn-primary" @click="navigateToCreate">
                    회원 등록
                </button>
            </div> -->
            <div class="o_table_mobile my-5">
            <div class="tbl_basic tbl_dealer">
                <table class="table">
                    <thead>
                <!--         <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <input
                                    v-model="search_id"
                                    type="text"
                                    class="inline-block mt-1 form-control"
                                    placeholder="Filter by ID"
                                />
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <input
                                    v-model="search_title"
                                    type="text"
                                    class="inline-block mt-1 form-control"
                                    placeholder="Filter by Title"
                                />
                            </th>
                            <th class="px-6 py-3 text-start"></th>
                            <th class="px-6 py-3 text-start"></th>
                        </tr>-->
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <div
                                    class="flex flex-row items-center justify-between cursor-pointer justify-content-center"
                                    @click="
                                        updateOrdering('created_at')
                                    "
                                >
                                    <div
                                        class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                        :class="{
                                            'font-bold text-blue-600':
                                                orderColumn ===
                                                'created_at',
                                        }"
                                    >
                                        가입일
                                    </div>
                                    <div class="select-none">
                                        <span v-if="orderingState.created_at.direction === 'asc' && orderingState.created_at.column === 'created_at'" class="text-blue-600">&uarr;</span>
                                        <span v-else-if="orderingState.created_at.direction === 'desc' && orderingState.created_at.column === 'created_at'" class="text-blue-600">&darr;</span>
                                        <span v-else-if="orderingState.created_at.direction === '' && orderingState.created_at.column === ''" class="text-blue-600">&uarr;&darr;</span>
                                    </div>
                                </div>
                            </th>
                            <th class="px-6 py-3 ">
                                <div
                                    class="flex flex-row justify-content-center"
                                    @click="updateOrdering('name')"
                                >
                                    <div
                                        class="leading-4 font-medium text-uppercase"
                                        :class="{
                                            'font-bold text-blue-600':
                                                orderColumn === 'name',
                                        }"
                                    >
                                        회원명/상태
                                    </div>
                                    <div class="select-none">
                                        <span v-if="orderingState.name.direction === 'asc' && orderingState.name.column === 'name'" class="text-blue-600">&uarr;</span>
                                        <span v-else-if="orderingState.name.direction === 'desc' && orderingState.name.column === 'name'" class="text-blue-600">&darr;</span>
                                        <span v-else-if="orderingState.name.direction === '' && orderingState.name.column === ''" class="text-blue-600">&uarr;&darr;</span>
                                    </div>
                                </div>
                            </th>
                            <th class="px-6 py-3 ">
                                <div
                                    class="flex flex-row justify-content-center"
                                    @click="updateOrdering('email')"
                                >
                                    <div
                                        class="leading-4 font-medium text-uppercase"
                                        :class="{
                                            'font-bold text-blue-600':
                                                orderColumn === 'email',
                                        }"
                                    >
                                        이메일
                                    </div>
                                    <div class="select-none">
                                        <span v-if="orderingState.email.direction === 'asc' && orderingState.email.column === 'email'" class="text-blue-600">&uarr;</span>
                                        <span v-else-if="orderingState.email.direction === 'desc' && orderingState.email.column === 'email'" class="text-blue-600">&darr;</span>
                                        <span v-else-if="orderingState.email.direction === '' && orderingState.email.column === ''" class="text-blue-600">&uarr;&darr;</span>
                                    </div>
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                회원
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                페널티
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                수정/삭제
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="users.length>0">
                            <tr v-for="post in users" :key="post.id">
                                <td class="px-6 py-4 pb-3 text-sm">
                                    {{ post.created_at }}
                                </td>
                                <td class="px-6 py-4 pb-3 text-sm">
                                    {{ post.name }}
                                
                                <div :class="{'blue-box ms-2': post.status === 'ok', 'blue-box02 ms-2': post.status === 'ask', 'red-box ms-2': post.status === 'reject'}">
                                    {{ wicas.enum(store).toLabel(post.status).users() === '정상' || wicas.enum(store).toLabel(post.status).users() === '심사중' ? wicas.enum(store).toLabel(post.status).users() : '' }}
                                </div>
                                
                                
                                </td>
                                <td class="px-6 py-4 pb-3 text-sm">
                                    {{ post.email }}
                                </td>
                                <td class="px-6 py-4 pb-3 text-sm">
                                    {{ (post.roles || []).includes('dealer') ? '딜러' : '일반' }}
                                </td>
                                <td>
                                    {{ post.status === 'warning1' ? '경고1' : '' }}
                                    {{ post.status === 'warning2' ? '경고2' : '' }}
                                    {{ post.status === 'expulsion' ? '재명' : '' }}
                                </td>
                                <td class="px-6 py-4 pb-3 text-sm">
                                    <router-link
                                        :to="{
                                            name: 'users.edit',
                                            params: { id: post.id },
                                        }"
                                        class="ms-2 fs-6 badge edit"
                                        >수정
                                    </router-link>
                                    <span>|</span>
                                    <a
                                        href="#"
                                        @click.prevent="deleteUser(post.id)"
                                        class="ms-2 fs-6 badge delete"
                                        >삭제</a
                                    >
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <td class="px-6 py-4 text-sm" style="text-align: center;" colspan="5">
                                회원 목록이 없습니다.
                            </td>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        <div class="card-footer">
            <ul class="pagination justify-content-center">
                <li class="page-item" :class="{ disabled: !pagination.prev }">
                <a class="page-link prev-style" @click="loadPage(pagination.current_page - 1)"></a>
                </li>
                <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === pagination.current_page }">
                <a class="page-link" @click="loadPage(n)">{{ n }}</a>
                </li>
                <li class="page-item next-prev" :class="{ disabled: !pagination.next }">
                <a class="page-link next-style" @click="loadPage(pagination.current_page + 1)"></a>
                </li>
            </ul>
        </div>

</template>

<script setup>
import { ref, onMounted, watch, computed} from "vue";
import useUsers from "../../../composables/users";
import { useAbility } from "@casl/vue";
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { cmmn } from '@/hooks/cmmn';
import { downloadExcel } from '@/composables/excel';

const downloadUsersExcel = () => {
    downloadExcel('users', orderColumn.value, orderDirection.value, 'users_data.xlsx',currentStatus.value,currentRoleStatus.value,search_title.value);
};


const { wicas } = cmmn();
const store = useStore();
const router = useRouter();
const orderColumn = ref("created_at");
const orderDirection = ref("desc");
const { users, getUsers, deleteUser, adminGetUsers , pagination, getUserStatus } = useUsers();
const { can } = useAbility();
const currentRoleStatus = ref('all');
const currentStatus = ref('all');
const currentPage = ref(1);
const orderingState = {
    created_at: { direction: '', column: '', hit: 0 },
    name: { direction: '', column: '', hit: 0 },
    email: { direction: '', column: '', hit: 0 },
};
let statusLabel;
const search_title = ref('');

const userAskCnt = ref(0);
const userOkCnt = ref(0);
const userRejectCnt = ref(0);
const userAllCnt = ref(0);
const userWarningCnt = ref(0);
const userExpulsionCnt = ref(0);

onMounted(async () => {
    statusLabel = wicas.enum(store).addFirst('all','상태').users();
    adminGetUsers(1,currentStatus.value,currentRoleStatus.value);                  

    userAskCnt.value = await getUserStatus('ask');
    userOkCnt.value = await getUserStatus('ok');
    userRejectCnt.value = await getUserStatus('reject');
    userAllCnt.value = await getUserStatus('all');
    userWarningCnt.value = await getUserStatus('warning1') + await getUserStatus('warning2');
    userExpulsionCnt.value = await getUserStatus('expulsion');
});

function loadPage(page) {
    if (page < 1 || page > pagination.value.last_page) return;
    currentPage.value = page;
    fetchUsers();
    window.scrollTo(0,0);
}

function setUserFilter(status) {
    currentPage.value = 1;
    currentRoleStatus.value = status;
    fetchUsers();
}

function setStatusFilter(status) {
    currentPage.value = 1;
    currentStatus.value = status;
    fetchUsers();
}
function navigateToCreate() {
    router.push({ name: 'users.create' });
}


const updateOrdering = (column) => {
    let columnState = orderingState[column];

    columnState.hit += 1;
    
    if (columnState.hit == 3) {
        columnState.column = '';
        columnState.direction = '';
        columnState.hit = 0;
    } else {
        columnState.column = column;
        columnState.direction = columnState.direction === 'asc' ? 'desc' : 'asc';
    }
    orderColumn.value = columnState.column;
    orderDirection.value = columnState.direction;

    fetchUsers(); 
};

function fetchUsers() {
    adminGetUsers(
        currentPage.value,
        currentStatus.value,
        currentRoleStatus.value,
        orderColumn.value,
        orderDirection.value,
        search_title.value
    );
}

const searchBtn = async() =>{
    fetchUsers();
}


/** 
watch(search_id, (current, previous) => {
    getUsers(1, current, search_title.value, search_global.value);
});
watch(search_title, (current, previous) => {
    getUsers(1, search_id.value, current, search_global.value);
});
watch(
    search_global,
    _.debounce((current, previous) => {
        getUsers(1, search_id.value, search_title.value, current);
    }, 200)
);*/

</script>
<style scoped>
.search-type2 .search-btn{
    top: 44px !important;
}
.sidebar {
    height: 116vh !important;
}
@media screen and (max-width: 481px){
.o_table_mobile .tbl_basic table {
    width: 100%;
    min-width: 600px !important;
}
}
.select-option {
    width: 112px !important;
}
@media (max-width: 360px) {
  .responsive-flex-end {
    align-items: flex-end;
    flex-direction: column;
  }
}
.search-type2 .search-btn{
    top: 47px !important;   
}

@media screen and (max-width: 937px) {
  .o_table_mobile {
    width: 100% !important;
    overflow-x: auto !important;
  }
  .o_table_mobile .tbl_basic {
    overflow-x: auto !important;
  }
  .o_table_mobile .tbl_basic table {
    width: 100% !important;
    min-width: 600px !important;
  }
  .o_table_mobile .tbl_dealer table {
    width: 100% !important;
    min-width: 600px !important;
  }
}

.sticky-top {
    background-color: #fff !important;
    padding:5px 0px !important;
}

</style>