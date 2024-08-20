<template>
    <div class="row justify-content-center my-2 p-3">
        <div class="col-md-12">
            <h4>입금 관리<p class="text-secondary opacity-75 fs-6 my-3">입금 내역 관리 페이지 입니다.</p></h4>
            </div>
                <div class="container mb-2">
                    <div class="d-flex justify-content-end responsive-flex-end gap-2">
                        <div class="text-end select-option">
                            <select class="form-select select-rank" aria-label="상태" @change="event => setFilter(event.target.value)">
                                <option v-for="(label, value) in statusLabel" :key="value" :value="value" :selected="value == 'all'">{{ label }}</option>
                            </select>
                        </div>
                        <div class="search-type2 p-0">
                            <input type="text" placeholder="검색어" v-model="search_title" style="width: auto !important;">
                            <button type="button" class="search-btn" @click="searchBtn">검색</button>
                        </div>
                    </div>
                </div>
            <div class="o_table_mobile my-5">
                <div class="tbl_basic tbl_dealer">
                    <div class="overflow-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                    <div class="flex flex-row items-center justify-content-center justify-between cursor-pointer" @click="updateOrdering('created_at')">
                                        <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider" :class="{ 'font-bold text-blue-600': orderColumn === 'created_at' }">
                                            등록일
                                        </div>
                                        <div class="select-none">
                                            <span v-if="orderingState.created_at.direction === 'asc' && orderingState.created_at.column === 'created_at'" class="text-blue-600">&uarr;</span>
                                            <span v-else-if="orderingState.created_at.direction === 'desc' && orderingState.created_at.column === 'created_at'" class="text-blue-600">&darr;</span>
                                            <span v-else-if="orderingState.created_at.direction === '' && orderingState.created_at.column === ''" class="text-blue-600">&uarr;&darr;</span>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                   매물번호
                                </th>
                                <th class="px-6 py-3 text-left">
                                    상태
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-center">
                                    비고
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="auctionsData.length > 0" v-for="auction in auctionsData" :key="auction.id">
                                <td class="px-6 py-4 text-sm">
                                    {{ auction.created_at }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="blue-box">
                                        {{ auction.car_no }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div>
                                        <p v-if="auction.status === 'dlvr'" class="ml-auto"><span class="box bg-info">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                        <p v-if="auction.status === 'done'" class="ml-auto"><span class="box bg-black">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <router-link
                                        href="#"
                                        :to="{ 
                                            name: 'deposit.approve', params: { id: auction.id } 
                                        }"
                                        class="ms-2 fs-6 badge edit"
                                        >수정
                                    </router-link>
                                    <span>|</span>
                                    <a
                                        href="#"
                                        @click.prevent="deleteAuction(auction.id,'deposit')"
                                        class="ms-2 fs-6 badge delete"
                                        >삭제</a
                                    >
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="4" class="px-6 py-4 text-sm text-center text-secondary opacity-50">등록 된 입금 데이터가 없습니다.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ disabled: !pagination.prev }">
                    <a class="page-link prev-style" @click="loadPage(pagination.current_page - 1,fetchAuctions)"></a>
                    </li>
                    <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === pagination.current_page }">
                    <a class="page-link" @click="loadPage(n,fetchAuctions)">{{ n }}</a>
                    </li>
                    <li class="page-item next-prev" :class="{ disabled: !pagination.next }">
                    <a class="page-link next-style" @click="loadPage(pagination.current_page + 1,fetchAuctions)"></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import useAuctions from '@/composables/auctions';
import useCategories from '@/composables/categories';
import { useAbility } from '@casl/vue';
import { useStore } from 'vuex';
import { cmmn } from '@/hooks/cmmn';

const { wicas } = cmmn();
const currentStatus = ref('dlvr,done'); 
const currentPage = ref(1); // 현재 페이지 번호
const orderingState = {
        created_at: { direction: '', column: '', hit: 0 },
        car_no: { direction: '', column: '', hit: 0 },
};


const search_title = ref('');
const orderColumn = ref('created_at');
const orderDirection = ref('desc');
const { auctionsData, pagination, deleteAuction, getStatusLabel, adminGetDepositAuctions } = useAuctions();
const { getCategoryList } = useCategories();
const { can } = useAbility();
const store = useStore();
let statusLabel;

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

        fetchAuctions(); 
};


const searchBtn = async() =>{
    fetchAuctions();
}

function fetchAuctions(){
    adminGetDepositAuctions(
        currentPage.value,
        orderColumn.value,
        orderDirection.value,
        currentStatus.value,
        search_title.value
    );
};

onMounted(() => {
    statusLabel = wicas.enum(store).perm('dlvr','done').addFirst('dlvr,done','전체').auctions();
    fetchAuctions();
    getCategoryList();
});

function loadPage(page) { // 페이지 로드
    if (page < 1 || page > pagination.value.last_page) return;
    currentPage.value = page;
    fetchAuctions();
    window.scrollTo(0,0);
}

function setFilter(status) { // 필터 설정
    currentPage.value = 1;
    currentStatus.value = status;
    fetchAuctions();
}

</script>
    <style scoped>
    .tc-red {
    color: red;
}
    .text-overflow{
      max-width: 130px; 
      overflow: hidden; 
      text-overflow: ellipsis;
      white-space: nowrap; 
    }
    .btn-apply::after {

    margin-top: 0px !important;
}

@media screen and (max-width: 481px) 
{
.web_style{
    margin-left: 0rem !important;
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
    right: 4px !important;
}
.blue-box {
    width: auto !important;
    min-width: 55px !important;
    padding: 0 12px !important;
}
.box{
    width: auto !important;
    min-width: 73px !important;
    padding: 0 10px !important; 
}

    </style>
    