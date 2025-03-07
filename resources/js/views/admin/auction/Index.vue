<template>
    <div class="row justify-content-center my-2 p-3">
        
        <div class="col-md-12 d-flex justify-content-between">
            <h4>매물 관리<p class="text-secondary opacity-75 fs-6 my-3">매물의 상세 관리, 상태 수정이 가능합니다.</p></h4>
            <div class="search-type2 mb-2" style="display: flex; align-items: center;">
                <div class="border-xsl" style="margin-right: 10px;">
                    <div class="image-icon-excel pointer" @click="downloadUsersExcel">
        
                    </div>
                </div>
            </div>
        </div>


        <div class="container mb-3 d-flex flex-column flex-md-row justify-content-between sticky-top">

            <div class="text-end status-selector">
                <input type="radio" name="auction_type" value="all" id="all" hidden checked @change="setAuctionTypeFilter('all')">
                <label for="all" class="mx-2">전체</label>

                <input type="radio" name="auction_type" value="auction" id="auction" hidden @change="setAuctionTypeFilter('auction')">
                <label for="auction">경매</label>

                <input type="radio" name="auction_type" value="tender" id="tender" hidden @change="setAuctionTypeFilter('tender')">
                <label for="tender" class="mx-2">공매</label>
            </div>

            <div class="d-flex justify-content-end responsive-flex-end gap-3">
                
                
                <div class="text-end select-option">
                    <select class="form-select select-rank" @change="event => setStatusFilter(event.target.value)">
                        <option v-for="(label, value) in statusLabel" :key="value" :value="value" :selected="value == 'all'">{{ label }}</option>
                    </select>
                </div>
                <div class="search-type2 p-0">
                    <input type="text" placeholder="매물 검색" v-model="search_title" id="searchUserName" @keyup.enter="searchBtn" style="width: auto !important; margin-right: 10px;"/>
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
                            <!-- <th class="px-6 py-3 bg-gray-50 justify-content-center">
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
                            </th> -->
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                경매/공매
                            </th>
                            
                            <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                매물번호
                            </th>

                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <div class="flex flex-row items-center justify-content-center justify-between cursor-pointer" @click="updateOrdering('car_no')">
                                    <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider" :class="{ 'font-bold text-blue-600': orderColumn === 'car_no' }">
                                        차량번호
                                    </div>
                                    <div class="select-none">
                                        <span v-if="orderingState.car_no.direction === 'asc' && orderingState.car_no.column === 'car_no'" class="text-blue-600">&uarr;</span>
                                        <span v-else-if="orderingState.car_no.direction === 'desc' && orderingState.car_no.column === 'car_no'" class="text-blue-600">&darr;</span>
                                        <span v-else-if="orderingState.car_no.direction === '' && orderingState.car_no.column === ''" class="text-blue-600">&uarr;&darr;</span>
                                    </div>
                                </div>
                            </th>

                            <th class="px-6 py-3 text-left">
                                차종
                            </th>

                            <th class="px-6 py-3 text-left">
                                등록년도
                            </th>

                            
                            <th class="px-6 py-3 bg-gray-50 text-center">
                                입찰 종료일
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-center">
                                입찰 건수
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-center">
                                입찰최고가
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
                            <!-- <td class="px-6 py-4 text-sm">
                                {{ auction.created_at }}
                            </td> -->
                            <td class="px-6 py-4 text-sm">
                                <div :class="auction.auction_type === 1 ? 'tc-red' : 'tc-blue'">
                                    {{ auction.auction_type === 1 ? '공매' : '경매' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ auction.unique_number }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ auction.car_no }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ auction.car_model }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ auction.car_year }}
                            </td>
                            
                            <td class="px-6 py-4 text-sm">
                                {{ auction.final_at ? auction.final_at : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ auction.bids_count ? auction.bids_count : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ auction.final_price ? auction.final_price : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div>
                                    <p v-if="auction.status === 'chosen'" class="ml-auto"><span class="blue-box02 bg-opacity-50">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                    <p v-if="auction.status === 'cancel'" class="ml-auto"><span class="box bg-secondary">{{  wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                    <p v-if="auction.status === 'wait'" class="ml-auto"><span class="blue-box02">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                    <p v-if="auction.status === 'diag'" class="ml-auto"><span class="box bg-success bg-opacity-75">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                    <p v-if="auction.status === 'ask'" class="ml-auto"><span class="box bg-warning bg-opacity-75">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                    <p v-if="auction.status === 'ing'" class="ml-auto"><span class="box bg-danger">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                    <p v-if="auction.status === 'done'" class="ml-auto"><span class="box bg-black">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                    <p v-if="auction.status === 'dlvr'" class="ml-auto"><span class="box bg-info">{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span></p>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm">
                                <router-link
                                    :to="{ 
                                        name: 'auction.approve', params: { id: auction.unique_number } 
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
                            <td colspan="9" class="px-6 py-4 text-sm text-center text-secondary opacity-50">등록 된 매물이 없습니다.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        <div class="card-footer">
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
    import { downloadExcel } from '@/composables/excel';

    const downloadUsersExcel = () => {
        downloadExcel('auctions', orderColumn.value, orderDirection.value, 'auctions_data.xlsx',currentStatus.value,'',search_title.value);
    };

    const { wicas } = cmmn();
    const store = useStore();
    let hit = 0;
    const orderColumn = ref('');
    const orderDirection = ref('');
    const { auctionsData, pagination, adminGetAuctions, deleteAuction } = useAuctions();
    const { categoryList, getCategoryList } = useCategories();
    const { can } = useAbility();
    const currentStatus = ref('all'); 
    const currentPage = ref(1); // 현재 페이지 번호
    const currentAuctionType = ref('all');
    const orderingState = {
        created_at: { direction: '', column: '', hit: 0 },
        car_no: { direction: '', column: '', hit: 0 },
    };
    let statusLabel;
    const search_title = ref('');

    /**
    const fetchAuctions = async (page = 1) => {
        try {
            await getAuctions(1,false,currentStatus.value);
        } catch (error) {
            console.error('Error fetching auctions:', error);
        }
    }; */
    
    onMounted(() => {
        statusLabel = wicas.enum(store).addFirst('all','전체').auctions();
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

    const searchBtn = async() =>{
        fetchAuctions();
    }

    const setStatusFilter = (status) => {
        currentPage.value = 1;
        currentStatus.value = status;
        fetchAuctions();
    }

    const setAuctionTypeFilter = (type) => {
        currentPage.value = 1;
        switch(type){
            case 'all':
                currentAuctionType.value = 'all';
                break;
            case 'auction':
                currentAuctionType.value = '0';
                break;
            case 'tender':
                currentAuctionType.value = '1';
                break;
        }
        fetchAuctions();
    }

    function fetchAuctions() {
        adminGetAuctions(   currentPage.value,
                            orderColumn.value,
                            orderDirection.value,
                            currentStatus.value,
                            currentAuctionType.value,
                            search_title.value
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
.blue-box02{
    width: auto !important;
    min-width: 73px !important;
    padding: 0 10px !important;  
}
.search-type2 .search-btn{
    top: 47px !important;  
    right: 4px !important;
}

.sticky-top {
    background-color: #fff !important;
    padding:5px 0px !important;
}
    </style>
    