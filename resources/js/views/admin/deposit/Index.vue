<template>
    <div class="p-3 row justify-content-center my-2">
        <div class="col-md-12"></div>
        <div class="search-type2 mb-5">
            <div class="border-xsl">
                <div class="image-icon-excel"></div>
            </div>
            <input type="text" placeholder="검색어" v-model="search_title" style="width: auto !important;">
            <button type="button" class="search-btn">검색</button>
        </div>
        <div class="container mb-3">
            <div class="d-flex justify-content-end">
                <div class="text-start status-selector">
                    <input type="radio" name="status" value="all" id="all" hidden checked @change="setFilter('all')">
                    <label for="all" class="mx-2">전체</label>
                    <input type="radio" name="status" value="ing" id="ongoing" hidden @change="setFilter('ing')">
                    <label for="ongoing">진단중</label>
                    <input type="radio" name="status" value="done" id="completed" hidden @change="setFilter('done')">
                    <label for="completed" class="mx-2">진단완료</label>
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
                        <tr v-if="filteredAuctionsData.length > 0" v-for="auction in filteredAuctionsData" :key="auction.id">
                            <td class="px-6 py-4 text-sm">
                                {{ auction.created_at }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="blue-box">
                                    {{ auction.car_no }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div :class="{'gray-box me-0': auction.status == 'chosen'}">
                                {{ getStatusLabel(auction.status) }}
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
                                    @click.prevent="deleteAuction(auction.id)"
                                    class="ms-2 fs-6 badge delete"
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
            <Pagination :data="pagination" :limit="3" @pagination-change-page="(page) => fetchAuctions(page)" class="mt-4 justify-content-center" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import useAuctions from '@/composables/auctions';
import useCategories from '@/composables/categories';
import { useAbility } from '@casl/vue';

const search_category = ref('');
const search_id = ref('');
const search_title = ref('');
const search_content = ref('');
const search_global = ref('');
const orderColumn = ref('created_at');
const orderDirection = ref('desc');
const { auctionsData, pagination, getAuctions, deleteAuction, getStatusLabel } = useAuctions();
const { categoryList, getCategoryList } = useCategories();
const { can } = useAbility();

const fetchAuctions = async (page = 1) => {
    try {
        await getAuctions(page);
    } catch (error) {
        console.error('Error fetching auctions:', error);
    }
};

onMounted(() => {
    fetchAuctions();
    getCategoryList();
});

const updateOrdering = (column) => {
    orderColumn.value = column;
    orderDirection.value = orderDirection.value === 'asc' ? 'desc' : 'asc';
    fetchAuctions(
        1,
        search_category.value,
        search_id.value,
        search_title.value,
        search_content.value,
        search_global.value,
        orderColumn.value,
        orderDirection.value
    );
};

const filteredAuctionsData = computed(() => {
    return auctionsData.value.filter(auction => auction.status === 'chosen' || auction.status === 'done');
});

watch(search_category, (current) => {
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
);
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
    top: 63px !important;
}
.gray-box {
    width: 65px !important;
    border-radius: 16px !important;
}
    </style>
    