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
                <div class="text-start status-selector">
                    <input type="radio" name="status" value="all" id="all" hidden checked @change="setFilter('all')">
                    <label for="all" class="mx-2">전체</label>
                    <input type="radio" name="status" value="ing" id="ongoing" hidden @change="setFilter('ing')">
                    <label for="ongoing">진행중</label>
                    <input type="radio" name="status" value="done" id="completed" hidden @change="setFilter('done')">
                    <label for="completed" class="mx-2">완료</label>
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
                                        <span :class="{ 'text-blue-600': orderDirection === 'asc' && orderColumn === 'created_at', hidden: orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'created_at' }">&uarr;</span>
                                        <span :class="{ 'text-blue-600': orderDirection === 'desc' && orderColumn === 'created_at', hidden: orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'created_at' }">&darr;</span>
                                    </div>
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">매물번호</span>
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
                                <div :class="{'gray-box me-0': auction.status == 'ask'}">
                                {{ getStatusLabel(auction.status) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <router-link
                                    :to="{ 
                                        name: 'auction.approve', params: { id: auction.id } 
                                    }"
                                    class="ms-2 badge bg-danger tc-wh"
                                    >수정
                                </router-link>
                                <a
                                    href="#"
                                    @click.prevent="deleteAuction(auction.id)"
                                    class="ms-2 badge bg-danger tc-wh"
                                    >삭제</a
                                >
                            </td>
                        </tr>
                        <tr v-else>
                            <td colspan="4" class="px-6 py-4 text-sm text-center">No data available</td>
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
                    <a class="page-link prev-style" @click="loadPage(pagination.current_page - 1)"></a>
                    </li>
                    <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === pagination.current_page }">
                    <a class="page-link" @click="loadPage(n)">{{ n }}</a>
                    </li>
                    <li class="page-item next-prev" :class="{ disabled: !pagination.next }">
                    <a class="page-link next-style" @click="loadPage(pagination.current_page + 1)"></a>
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
    
    const orderColumn = ref('created_at');
    const orderDirection = ref('desc');
    const { auctionsData, pagination, adminGetAuctions, deleteAuction,getStatusLabel } = useAuctions();
    const { categoryList, getCategoryList } = useCategories();
    const { can } = useAbility();
    const currentStatus = ref('all'); 
    const currentPage = ref(1); // 현재 페이지 번호
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
        currentStatus.value = status;
        fetchAuctions();
    }

    function loadPage(page) { // 페이지 로드
        if (page < 1 || page > pagination.value.last_page) return;
        currentPage.value = page;
        fetchAuctions();
    }

    const updateOrdering = (column) => {
        orderColumn.value = column;
        orderDirection.value = orderDirection.value === 'asc' ? 'desc' : 'asc';
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
    </style>
    