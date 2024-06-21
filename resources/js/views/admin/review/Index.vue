<template>
    <div class="p-3 row justify-content-center my-2">
        <div class="col-md-12">
        </div>
        <router-link
                        :to="{ name: 'posts.create' }"
                        class="border-red-write"
                    >
                    <div class="image-icon-pen">

                        </div>
                    </router-link>
                    <div class="proceeding"></div>
                    <!--
                    <div class="search-type2 mb-5">
                        <div class="border-xsl">
                            <div class="image-icon-excel"></div>
                        </div>
                        <input type="text" placeholder="검색어" v-model="search_title" style="width: auto !important;">
                        <button type="button" class="search-btn">검색</button>
                    </div>-->
                    <!--
                    <div class="container mb-3">
                        <div class="d-flex justify-content-end">
                            <div class="text-start status-selector">
                            <input type="radio" name="status" value="all" id="all" hidden checked @change="setFilter('all')">
                            <label for="all" class="mx-2">전체</label>

                            <input type="radio" name="status" value="user" id="user" hidden @change="setFilter('user')">
                            <label for="ongoing">일반</label>

                            <input type="radio" name="status" value="dealer" id="dealer" hidden @change="setFilter('dealer')">
                            <label for="completed" class="mx-2">딜러</label>
                        </div>
                        <div class="text-end select-option">
                                <select class="form-select select-rank" aria-label="최근 등록 순">
                                    <option selected>최근 등록 순</option>
                                    <option value="1">가격 낮은 순</option>
                                    <option value="2">가격 높은 순</option>
                                    <option value="3">연식 오래된 순</option>
                                    <option value="4">연식 최신 순</option>
                                </select>
                            </div>
                        </div>
                    </div>-->
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
                                                <span v-if="orderingState.created_at.direction === 'asc' && orderingState.created_at.column === 'created_at'" class="text-blue-600">&uarr;</span>
                                                <span v-else-if="orderingState.created_at.direction === 'desc' && orderingState.created_at.column === 'created_at'" class="text-blue-600">&darr;</span>
                                                <span v-else-if="orderingState.created_at.direction === '' && orderingState.created_at.column === ''" class="text-blue-600">&uarr;&darr;</span>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                        <div
                                            class="flex flex-row items-center justify-content-center justify-between cursor-pointer"
                                            @click="
                                                updateOrdering('id')
                                            "
                                        >
                                            <div
                                                class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                                :class="{
                                                    'font-bold text-blue-600':
                                                        orderColumn ===
                                                        'id',
                                                }"
                                            >
                                                후기번호
                                            </div>
                                            <div class="select-none">
                                                <span v-if="orderingState.id.direction === 'asc' && orderingState.id.column === 'id'" class="text-blue-600">&uarr;</span>
                                                <span v-else-if="orderingState.id.direction === 'desc' && orderingState.id.column === 'id'" class="text-blue-600">&darr;</span>
                                                <span v-else-if="orderingState.id.direction === '' && orderingState.id.column === ''" class="text-blue-600">&uarr;&darr;</span>
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
                                                <span v-if="orderingState.content.direction === 'asc' && orderingState.content.column === 'content'" class="text-blue-600">&uarr;</span>
                                                <span v-else-if="orderingState.content.direction === 'desc' && orderingState.content.column === 'content'" class="text-blue-600">&darr;</span>
                                                <span v-else-if="orderingState.content.direction === '' && orderingState.content.column === ''" class="text-blue-600">&uarr;&darr;</span>
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
                                            <div class="blue-box"
                                                
                                            >
                                                {{ review.id }}
                                            </div>
                                        </td>
                                <!--     <td class="px-6 py-4 text-sm">
                                            <img
                                                :src="post.original_image"
                                                alt="image"
                                                height="70"
                                            />
                                        </td>--> 
                                    <td class="px-6 py-4 text-sm">
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
                                    <td class="px-6 py-4 text-sm" style="text-align: center;" colspan="4">
                                        작성된 이용 후기가 없습니다.
                                    </td>
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
import usePosts from "@/composables/posts";
import useCategories from "@/composables/categories";
import { useAbility } from "@casl/vue";
import { initReviewSystem } from '@/composables/review';

const orderColumn = ref("created_at");
const orderDirection = ref("desc");
const { categoryList, getCategoryList } = useCategories();
const { can } = useAbility();
const { adminGetReviews , adminDeleteReview , reviewsData , reviewPagination } = initReviewSystem(); 
const currentStatus = ref('all');
const currentPage = ref(1);
const orderingState = {
    created_at: { direction: '', column: '', hit: 0 },
    id: { direction: '', column: '', hit: 0 },
    content: { direction: '', column: '', hit: 0 },
};
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
    fetchReviews();
};

function fetchReviews() {
    adminGetReviews(    currentPage.value,
                        orderColumn.value,
                        orderDirection.value,
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
</style>
