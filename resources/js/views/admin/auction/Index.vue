<template>
    <div class="p-3 row justify-content-center my-2">
        <div class="col-md-12"></div>
        <!--
        <div class="search-type2 mb-5">
            <div class="border-xsl">
                <div class="image-icon-excel"></div>
            </div>
            <input type="text" placeholder="검색어" v-model="search_title" style="width: auto !important;">
            <button type="button" class="search-btn">검색</button>
        </div>-->
        <div class="container mb-3">
            <div class="d-flex justify-content-end">
                <div class="text-end select-option">
                    <select class="form-select select-rank" aria-label="상태" @change="event => setFilter(event.target.value)">
                        <option value="all" selected>전체</option>
                        <option value="done">경매완료</option>
                        <option value="chosen">선택완료</option>
                        <option value="wait">선택대기</option>
                        <option value="ing">경매진행</option>
                        <option value="status">진단대기</option>
                        <option value="ask">신청완료</option>
                        <option value="cancel">취소</option>
                    </select>
                </div>
            </div>
                <!--
                <div class="text-start status-selector">
                    <input type="radio" name="status" value="all" id="all" checked @change="setFilter('all')">
                    <label for="all" class="mx-2">전체</label>

                    <input type="radio" name="status" value="done" id="done"  @change="setFilter('done')">
                    <label for="done" class="mx-2">경매완료</label>

                    <input type="radio" name="status" value="chosen" id="chosen"  @change="setFilter('chosen')">
                    <label for="chosen" class="mx-2">선택완료</label>

                    <input type="radio" name="status" value="wait" id="wait"  @change="setFilter('wait')">
                    <label for="wait" class="mx-2">선택대기</label>

                    <input type="radio" name="status" value="ing" id="ing"  @change="setFilter('ing')">
                    <label for="ing" class="mx-2">경매진행</label>

                    <input type="radio" name="status" value="diag" id="diag"  @change="setFilter('diag')">
                    <label for="diag" class="mx-2">진단대기</label>

                    <input type="radio" name="status" value="ask" id="ask"  @change="setFilter('ask')">
                    <label for="ask" class="mx-2">신청완료</label>

                </div>-->

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
                                <div class="flex flex-row items-center justify-content-center justify-between cursor-pointer" @click="updateOrdering('car_no')">
                                    <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider" :class="{ 'font-bold text-blue-600': orderColumn === 'car_no' }">
                                        매물번호
                                    </div>
                                    <div class="select-none">
                                        <span v-if="orderingState.car_no.direction === 'asc' && orderingState.car_no.column === 'car_no'" class="text-blue-600">&uarr;</span>
                                        <span v-else-if="orderingState.car_no.direction === 'desc' && orderingState.car_no.column === 'car_no'" class="text-blue-600">&darr;</span>
                                        <span v-else-if="orderingState.car_no.direction === '' && orderingState.car_no.column === ''" class="text-blue-600">&uarr;&darr;</span>
                                    </div>
                                </div>
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
                                    <p v-if="auction.status === 'chosen'" class="ml-auto"><span class="blue-box02 bg-opacity-50">{{ getStatusLabel(auction.status) }}</span></p>
                                    <p v-if="auction.status === 'cancel'" class="ml-auto"><span class="box bg-secondary">{{ getStatusLabel(auction.status) }}</span></p>
                                    <p v-if="auction.status === 'wait'" class="ml-auto"><span class="blue-box02">{{ getStatusLabel(auction.status) }}</span></p>
                                    <p v-if="auction.status === 'diag'" class="ml-auto"><span class="box bg-success bg-opacity-75">{{ getStatusLabel(auction.status) }}</span></p>
                                    <p v-if="auction.status === 'ask'" class="ml-auto"><span class="box bg-warning bg-opacity-75">{{ getStatusLabel(auction.status) }}</span></p>
                                    <p v-if="auction.status === 'ing'" class="ml-auto"><span class="box bg-danger">{{ getStatusLabel(auction.status) }}</span></p>
                                    <p v-if="auction.status === 'done'" class="ml-auto"><span class="box bg-black">{{ getStatusLabel(auction.status) }}</span></p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <router-link
                                    :to="{ 
                                        name: 'auction.approve', params: { id: auction.id } 
                                    }"
                                    class="ms-2 fs-6 badge edit"
                                    >수정
                                </router-link>
                                <span>|</span>
                                <a
                                    href="#"
                                    @click.prevent="deleteAuction(auction.id,'auction')"
                                    class="ms-2 fs-6 badge delete "
                                    >삭제</a
                                >
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="4" class="px-6 py-4 text-sm text-center">등록 된 매물이 없습니다.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        <div class="card-footer">
            <nav v-if="currentTab !== 'interInfo' && currentTab !== 'auctionDone'">
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
    let hit = 0;
    const orderColumn = ref('');
    const orderDirection = ref('');
    const { auctionsData, pagination, adminGetAuctions, deleteAuction,getStatusLabel } = useAuctions();
    const { categoryList, getCategoryList } = useCategories();
    const { can } = useAbility();
    const currentStatus = ref('all'); 
    const currentPage = ref(1); // 현재 페이지 번호
    const orderingState = {
        created_at: { direction: '', column: '', hit: 0 },
        car_no: { direction: '', column: '', hit: 0 },
    };
    /**
    const fetchAuctions = async (page = 1) => {
        try {
            await getAuctions(1,false,currentStatus.value);
        } catch (error) {
            console.error('Error fetching auctions:', error);
        }
    }; */
    
    onMounted(() => {
        fetchAuctions();
        getCategoryList();
    });
    
    function setFilter(status) { // 필터 설정
        currentPage.value = 1;
        currentStatus.value = status;
        fetchAuctions();
    }


    function loadPage(page) { // 페이지 로드
        if (page < 1 || page > pagination.value.last_page) return;
        currentPage.value = page;
        fetchAuctions();
        window.scrollTo(0,0);
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

        fetchAuctions(); 
    };


    function fetchAuctions() {
        adminGetAuctions(   currentPage.value,
                            orderColumn.value,
                            orderDirection.value,
                            currentStatus.value
                        );
                    }   
    /**
    watch(search_category, (current) => {s
        fetchAuctions(
            1,
            current,
            search_id.value,
            search_title.value,
            search_content.value,
            search_global.value
        );
    });
    watch(search_id, (current) => {
        fetchAuctions(
            1,
            search_category.value,
            current,
            search_title.value,
            search_content.value,
            search_global.value
        );
    });
    watch(search_title, (current) => {
        fetchAuctions(
            1,
            search_category.value,
            search_id.value,
            current,
            search_content.value,
            search_global.value
        );
    });
    watch(search_content, (current) => {
        fetchAuctions(
            1,
            search_category.value,
            search_id.value,
            search_title.value,
            current,
            search_global.value
        );
    });
    watch(
        search_global,
        _.debounce((current) => {
            fetchAuctions(
                1,
                search_category.value,
                search_id.value,
                search_title.value,
                search_content.value,
                current
            );
        }, 200)
    ); */
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
.blue-box02{
    width: 66px !important;
}
.box{
    width: 66px !important;  
}

    </style>
    