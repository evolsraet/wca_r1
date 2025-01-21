<template>
    <div class="row justify-content-center my-2 p-3">
        <div class="col-md-12">
            <h4>후기 관리<p class="text-secondary opacity-75 fs-6 my-3">후기 관리 페이지 입니다.</p></h4>
            </div>
            <div class="search-type2 mb-2" style="display: flex; align-items: center;">
                    <div class="border-xsl" style="margin-right: 10px;">
                        <div class="image-icon-excel pointer" @click="downloadUsersExcel">
            
                        </div>
                    </div>
                </div>
                <div class="container mb-3">
                    <div class="d-flex justify-content-end responsive-flex-end gap-2">
                        <div class="text-end select-option">
                                <select class="form-select select-rank" aria-label="별점" @change="event => setStarFilter(event.target.value)">
                                    <option value="all"selected>전체</option>
                                    <option value="1">1점</option>
                                    <option value="2">2점</option>
                                    <option value="3">3점</option>
                                    <option value="4">4점</option>
                                    <option value="5">5점</option>
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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                        <div
                                            class="flex flex-row items-center justify-content-center justify-between cursor-pointer"
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
                                                등록일
                                            </div>
                                            <div class="select-none">
                                                <span v-if="orderingState['created_at'].direction === 'asc' && orderingState['created_at'].column === 'created_at'" class="text-blue-600">&uarr;</span>
                                                <span v-else-if="orderingState['created_at'].direction === 'desc' && orderingState['created_at'].column === 'created_at'" class="text-blue-600">&darr;</span>
                                                <span v-else class="text-blue-600">&uarr;&darr;</span>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                        <div
                                            class="flex flex-row items-center justify-content-center justify-between cursor-pointer"
                                            @click="
                                                updateOrdering('user.name')
                                            "
                                        >
                                            <div
                                                class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                                :class="{
                                                    'font-bold text-blue-600':
                                                        orderColumn ===
                                                        'user.name',
                                                }"
                                            >
                                                회원명
                                            </div>
                                            
                                            <div class="select-none">
                                                <span v-if="orderingState['user.name'].direction === 'asc'" class="text-blue-600">&uarr;</span>
                                                <span v-else-if="orderingState['user.name'].direction === 'desc'" class="text-blue-600">&darr;</span>
                                                <span v-else class="text-blue-600">&uarr;&darr;</span>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                        <div
                                            class="flex flex-row items-center justify-content-center justify-between cursor-pointer"
                                            @click="
                                                updateOrdering('dealer.name')
                                            "
                                        >
                                            <div
                                                class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                                :class="{
                                                    'font-bold text-blue-600':
                                                        orderColumn ===
                                                        'dealer.name',
                                                }"
                                            >
                                                딜러명
                                            
                                            </div>
                                            <div class="select-none">
                                                <span v-if="orderingState['dealer.name'].direction === 'asc' && orderingState['dealer.name'].column === 'dealer.name'" class="text-blue-600">&uarr;</span>
                                                <span v-else-if="orderingState['dealer.name'].direction === 'desc' && orderingState['dealer.name'].column === 'dealer.name'" class="text-blue-600">&darr;</span>
                                                <span v-else class="text-blue-600">&uarr;&darr;</span>                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                        <div
                                            class="flex flex-row items-center justify-content-center justify-between cursor-pointer"
                                            @click="
                                                updateOrdering('star')
                                            "
                                        >
                                            <div
                                                class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                                :class="{
                                                    'font-bold text-blue-600':
                                                        orderColumn ===
                                                        'star',
                                                }"
                                            >
                                                별점
                                            
                                            </div>
                                            
                                            <div class="select-none">
                                                <span v-if="orderingState['star'].direction === 'asc' && orderingState['star'].column === 'star'" class="text-blue-600">&uarr;</span>
                                                <span v-else-if="orderingState['star'].direction === 'desc' && orderingState['star'].column === 'star'" class="text-blue-600">&darr;</span>
                                                <span v-else class="text-blue-600">&uarr;&darr;</span>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <div
                                            class="flex flex-row items-center justify-content-center justify-between cursor-pointer"
                                            @click="
                                                updateOrdering('content')
                                            "
                                        >
                                            <div
                                                class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                                :class="{
                                                    'font-bold text-blue-600':
                                                        orderColumn ===
                                                        'content',
                                                }"
                                            >
                                                내용
                                            </div>
                                            <div class="select-none">
                                                <span v-if="orderingState['content'].direction === 'asc' && orderingState['content'].column === 'content'" class="text-blue-600">&uarr;</span>
                                                <span v-else-if="orderingState['content'].direction === 'desc' && orderingState['content'].column === 'content'" class="text-blue-600">&darr;</span>
                                                <span v-else class="text-blue-600">&uarr;&darr;</span>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        수정/삭제
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="reviewsData.length>0">
                                        <tr v-for="review in reviewsData" :key="review">
                                        <td class="px-6 py-4 text-sm">
                                            {{ review.created_at }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ review.user.name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ review.dealer.name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ review.star }}점
                                        </td>
                                <!--     <td class="px-6 py-4 text-sm">
                                            <img
                                                :src="post.original_image"
                                                alt="image"
                                                height="70"
                                            />
                                        </td>--> 
                                    <td class="over-text px-6 py-4 text-sm">
                                            {{ review.content }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <router-link
                                                href="#"
                                                :to="{ 
                                                    name: 'review.approve', params: { id: review.id } 
                                                }"
                                                class="ms-2 fs-6 badge edit "
                                                >수정 
                                            </router-link>
                                            <span>|</span>
                                            <a
                                                href="#"
                                                @click.prevent="adminDeleteReview(review.id)"
                                                class="ms-2 fs-6 badge delete "
                                                >삭제</a
                                            >
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-secondary opacity-50" style="text-align: center;" colspan="6">
                                            작성된 이용 후기가 없습니다.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <ul class="pagination justify-content-center">
                            <li class="page-item" :class="{ disabled: !reviewPagination.prev }">
                            <a class="page-link prev-style" @click="loadPage(reviewPagination.current_page - 1)"></a>
                            </li>
                            <li v-for="n in reviewPagination.last_page" :key="n" class="page-item" :class="{ active: n === reviewPagination.current_page }">
                            <a class="page-link" @click="loadPage(n)">{{ n }}</a>
                            </li>
                            <li class="page-item next-prev" :class="{ disabled: !reviewPagination.next }">
                            <a class="page-link next-style" @click="loadPage(reviewPagination.current_page + 1)"></a>
                            </li>
                        </ul>
                </div>
            </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import useCategories from "@/composables/categories";
import { useAbility } from "@casl/vue";
import { initReviewSystem } from '@/composables/review';
import { downloadExcel } from '@/composables/excel';

const downloadUsersExcel = () => {
    downloadExcel('reviews', orderColumn.value, orderDirection.value, 'reviews_data.xlsx',currentStar.value,'',search_title.value);
};

const orderColumn = ref("created_at");
const orderDirection = ref("desc");
const { categoryList, getCategoryList } = useCategories();
const { can } = useAbility();
const { getAllReview , adminDeleteReview , reviewsData , reviewPagination } = initReviewSystem(); 
const currentStatus = ref('all');
const currentPage = ref(1);
const currentStar = ref('all');
const orderingState = {
    'created_at': { direction: '', column: 'created_at', hit: 0 },
    'id': { direction: '', column: 'id', hit: 0 },
    'content': { direction: '', column: 'content', hit: 0 },
    'star': { direction: '', column: 'star', hit: 0 },
    'user.name': { direction: '', column: 'user.name', hit: 0 },
    'dealer.name': { direction: '', column: 'dealer.name', hit: 0 },
};
const search_title = ref('');

onMounted(async () => {
    fetchReviews();
    getCategoryList();
});


async function loadPage(page) { // 페이지 로드
    if (page < 1 || page > reviewPagination.value.last_page) return;
    currentPage.value = page;
    fetchReviews();
    window.scrollTo(0,0);
}

function setStarFilter(star) {
    currentPage.value = 1;
    currentStar.value = star;
    fetchReviews();
}
function navigateToCreate() {
    router.push({ name: 'users.create' });
}

const updateOrdering = (column) => {
    let columnState = orderingState[column];
    columnState.hit += 1;

    if (columnState.hit === 3) {
        columnState.direction = '';
        columnState.hit = 0;
    } else {
        columnState.direction = columnState.direction === 'asc' ? 'desc' : 'asc';
    }
    
    orderColumn.value = columnState.column;
    orderDirection.value = columnState.direction;
    fetchReviews(); 
};

const searchBtn = async() =>{
    fetchReviews();
}

function fetchReviews() {
    getAllReview(    currentPage.value,
                        false,
                        orderColumn.value,
                        orderDirection.value,
                        currentStar.value,
                        search_title.value
                );
}   

/** 
const filterReviews = computed(() => {
    
    if (currentStatus.value === "all") {
        return users.value.data;
    } else {
        return users.value.data.filter(user => 
            user.roles.some(role => role === currentStatus.value)
        );
    }
});*/
/** 
watch(search_category, (current, previous) => {
    getPosts(
        1,
        current,
        search_id.value,
        search_title.value,
        search_content.value,
        search_global.value
    );
});
watch(search_id, (current, previous) => {
    getPosts(
        1,
        search_category.value,
        current,
        search_title.value,
        search_content.value,
        search_global.value
    );
});
watch(search_title, (current, previous) => {
    getPosts(
        1,
        search_category.value,
        search_id.value,
        current,
        search_content.value,
        search_global.value
    );
});
watch(search_content, (current, previous) => {
    getPosts(
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
    _.debounce((current, previous) => {
        getPosts(
            1,
            search_category.value,
            search_id.value,
            search_title.value,
            search_content.value,
            current
        );
    }, 200)
);*/
</script>
<style scoped>
.text-overflow{
  max-width: 130px; 
  overflow: hidden; 
  text-overflow: ellipsis;
  white-space: nowrap; 
}

.search-type2 .search-btn{
    top: 54px !important;  
}

.select-option {
    width: 112px !important;
}
.search-type2 .search-btn{
    top: 47px !important;  
    right: 4px !important;
}
/* 이미지 스타일 */
.img-wrapper {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
}
.img-container {
  width: 100%; 
  height: 500px;
  display: flex; 
  justify-content: center;
  align-items: center;
  overflow: hidden; 
}
.img-wrapper img {
  width: 100%; 
  height: 100%; 
  object-fit: cover; 
}
</style>
