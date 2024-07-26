<template>
  <div :class="isDealer || isUser ? 'container' : ''">
    <div class="p-3 row justify-content-center my-2">
      <div class="col-md-12"></div>
      <h4 v-if="!isDealer && !isUser">{{ boardText }}</h4>
      <div v-else>
        <h4 class="mt-4">{{ boardText }}</h4>
        <p class="text-secondary opacity-75 fs-6">
          {{ boardTextMessage }}
        </p>
      </div>
      <router-link v-if="(!isDealer && !isUser) && boardId === 'notice'" :to="{ name: 'posts.create', params: { boardId } }" class="border-red-write">
        <div class="image-icon-pen"></div>
      </router-link>
      <div class="search-type2 justify-content-end mb-2">
        <input type="text" placeholder="검색어" v-model="search_title" style="width: auto !important;">
        <button type="button" class="search-btn" @click="fetchPosts">검색</button>
      </div>
      <div v-if="boardId === 'claim'" class="container">
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
          <table class="table">
            <thead>
              <tr>
                <th v-if="!isDealer && !isUser && boardId !== 'claim'" class="px-6 py-3 bg-gray-50 justify-content-center">
                  <div class="flex flex-row items-center justify-content-center justify-between cursor-pointer" @click="updateOrdering('created_at')">
                    <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider" :class="{'font-bold text-blue-600': orderColumn === 'created_at'}">
                      등록일
                    </div>
                    <div class="select-none">
                      <span :class="{'text-blue-600': orderDirection === 'asc' && orderColumn === 'created_at', hidden: orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'created_at'}">&uarr;</span>
                      <span :class="{'text-blue-600': orderDirection === 'desc' && orderColumn === 'created_at', hidden: orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'created_at'}">&darr;</span>
                    </div>
                  </div>
                </th>
                <th v-if="boardId === 'notice'" class="px-6 py-3 bg-gray-50 text-left" style="width: 15%;">
                  <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">카테고리</span>
                </th>
                <th class="px-6 py-3 text-left" style="width: 20%;">
                  <div class="flex flex-row justify-content-center" @click="updateOrdering('title')">
                    <div class="font-medium text-uppercase" :class="{'font-bold text-blue-600': orderColumn === 'title'}">
                      제목
                    </div>
                    <div class="select-none">
                      <span :class="{'text-blue-600': orderDirection === 'asc' && orderColumn === 'title', hidden: orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'title'}">&uarr;</span>
                      <span :class="{'text-blue-600': orderDirection === 'desc' && orderColumn === 'title', hidden: orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'title'}">&darr;</span>
                    </div>
                  </div>
                </th>
                <th  class="px-6 py-3 bg-gray-50 text-left" style="width: 45%;">
                  <div class="flex flex-row justify-content-center" @click="updateOrdering('content')">
                    <div class="font-medium text-uppercase" :class="{'font-bold text-blue-600': orderColumn === 'content'}">
                      내용
                    </div>
                  </div>
                </th>
                <th v-if="boardId === 'claim'" class="px-6 py-3 bg-gray-50 text-left">차량번호</th>
          
                <th v-if="boardId === 'claim'" class="px-6 py-3 bg-gray-50 text-left">수정/삭제</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="post in posts" :key="post.id">
                <td v-if="!isDealer && !isUser && boardId !== 'claim'" class="px-6 py-4 text-sm text-overflow">{{ post.created_at }}</td>
                <td v-if="boardId === 'notice'" class="px-6 py-4 text-sm text-overflow">
                  <div>{{ post.category }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-overflow"><span v-if="boardId === 'claim'" class="my-2">[문의] </span>{{ post.title }}</td>
                <td class="px-6 py-4 text-sm text-overflow" v-html="post.content"></td>
                <td v-if="boardId === 'claim'"><span class="blue-box mb-0 mx-0">{{ auctionDetails[post.extra1]?.data?.car_no || '' }}</span></td>
                <td v-if="boardId === 'claim'" class="px-6 py-4 text-sm text-overflow">
                  <router-link :to="{ name: 'posts.edit', params: { boardId, id: post.id } }" class="badge">
                    <div class="icon-edit-img"></div>
                  </router-link>
                  <a href="#" @click.prevent="deletePost(boardId, post.id)" class="ms-2 badge web_style">
                    <div class="icon-trash-img"></div>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link prev-style" @click="loadPage(currentPage - 1)" :disabled="currentPage === 1"></a>
        </li>
        <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === currentPage }">
          <a class="page-link" @click="loadPage(n)">{{ n }}</a>
        </li>
        <li class="page-item next-prev" :class="{ disabled: currentPage === pagination.last_page }">
          <a class="page-link next-style" @click="loadPage(currentPage + 1)" :disabled="currentPage === pagination.last_page"></a>
        </li>
      </ul>
    </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useRoute } from 'vue-router';
import { initPostSystem } from "@/composables/posts";
import { useStore } from 'vuex';
import useAuctions from "@/composables/auctions";

const { posts, getPosts, deletePost, isLoading, getBoardCategories, pagination } = initPostSystem();
const { getAuctionById } = useAuctions(); // Assuming getAuctionById is the correct function
const route = useRoute();
const boardId = ref(route.params.boardId);
const currentPage = ref(1);
const store = useStore();
const search_title = ref("");
const orderColumn = ref("created_at");
const orderDirection = ref("desc");
const user = computed(() => store.getters['auth/user']);
const isDealer = computed(() => user.value?.roles?.includes('dealer'));
const isUser = computed(() => user.value?.roles?.includes('user'));

// Create a reactive object to store auction details
const auctionDetails = ref({});

async function loadPage(page) { // 페이지 로드
  if (page < 1 || page > pagination.value.last_page) return;
  currentPage.value = page;
  await fetchPosts(page);
  window.scrollTo(0, 0);
}

const fetchPosts = async (page = 1) => {
  await getPosts(
    boardId.value,  // 전달된 boardId 사용
    page,
    '',
    '',
    search_title.value,
    '',
    '',
    orderColumn.value,
    orderDirection.value
  );
  currentPage.value = pagination.value.current_page;
  if (boardId.value === 'claim') {
    await fetchAllAuctionDetails();
  }
};

const updateOrdering = (column) => {
  if (orderColumn.value === column) {
    orderDirection.value = orderDirection.value === "asc" ? "desc" : "asc";
  } else {
    orderColumn.value = column;
    orderDirection.value = "asc";
  }
  fetchPosts();
};

const setFilter = (filter) => {
  fetchPosts();
};

const boardText = computed(() => {
  switch(boardId.value) {
    case 'notice':
      return '게시판';
    case 'claim':
      return '클레임';
    default:
      return boardId.value;
  }
});

const boardTextMessage = computed(() => {
  if (boardId.value === 'notice') {
    return `빠르고 신속하게 위카 ${boardText.value} 전해드립니다.`;
  } else if (boardId.value === 'claim') {
    return `클레임 작성은 내 정보 > 지난 낙찰건 > 클레임 작성 에서 가능합니다.`;
  } else {
    return '';
  }
});

const fetchAllAuctionDetails = async () => {
  try {
    // Iterate over each post and fetch auction details
    for (const post of posts.value) {
      console.log("post.extra1:", post.extra1); // Check if post.extra1 is valid
      if (post.extra1) {
        const details = await getAuctionById(post.extra1);
        auctionDetails.value[post.extra1] = details;
        console.log("Fetched Details:", details); // Verify fetched details
      }
    }
    console.log("Auction Details Object:", auctionDetails.value);
  } catch (error) {
    console.error("Error fetching auction details: ", error);
  }
};

onMounted(async () => {
  getBoardCategories();
  await fetchPosts();
});

watch(route, (newRoute) => {
  if (newRoute.params.boardId !== boardId.value) {
    boardId.value = newRoute.params.boardId;
    fetchPosts();
  }
});
</script>


<style scoped>
.search-type2 .search-btn{
  top: 55px !important;
}

.text-overflow {
  max-width: 300px; 
  overflow: hidden; 
  text-overflow: ellipsis;
  white-space: nowrap; 
}

@media screen and (max-width: 481px) {
    .web_style {
        margin-left: 0rem !important;
    }
}

@media screen and (max-width: 767px) {
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
.tbl_basic table tr td{
  padding: 20px 11px !important;
}
</style>
