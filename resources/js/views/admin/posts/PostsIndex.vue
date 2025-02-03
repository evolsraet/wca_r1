<template>
  <div :class="isDealer || isUser ? 'container' : ''">
    <div class="p-3 row justify-content-center my-2">
      <div class="col-md-12"></div>
      <h4 v-if="!isDealer && !isUser">
        {{ boardText }}
        <p class="text-secondary opacity-75 fs-6 my-3">
          {{ adminTextMessage }}
        </p>
      </h4>
      <div v-else>
        <h4 class="mt-4">{{ boardText }}</h4>
        <p class="text-secondary opacity-75 fs-6">
          {{ boardTextMessage }}
        </p>
      </div>
      <div v-if="boardId === 'notice'">
        <UseGuide class="mt-4"/>
      </div>
      <IntroModal :show="showModal" @close="closeModal"  @click="openModal"/>
      <router-link v-if="(!isDealer && !isUser) && boardId === 'notice'" :to="{ name: 'posts.create', params: { boardId } }" class="border-red-write">
        <div class="image-icon-pen"></div>
      </router-link>
      <div class="search-type2 justify-content-end mb-2">
        <input type="text" placeholder="검색어" v-model="search_title" style="width: auto !important;" @keyup.enter="fetchPosts">
        <button type="button" class="search-btn" @click="fetchPosts">검색</button>
      </div>
      <div class="container">
        <div class="d-flex justify-content-end">
          <div class="text-start status-selector">
            <input type="radio" name="status" value="all" id="all-notice" hidden checked @change="setBoardFilter('all')">
            <label :class="{ active: filter === 'all' }" for="all-notice" class="mx-2">전체</label>
            <!--
            <input type="radio" name="status" value="all" id="all-notice" hidden @change="setBoardFilter(category)">
            <label for="all-notice">전체</label>
            -->
            <template v-for="(category, index) in categoriesList" :key="index">
              <input type="radio" name="status" :value="category" :id="`category-${index}`" hidden @change="setBoardFilter(category)">
              <label :for="`category-${index}`">{{category}}</label>
              <!--
                <input type="radio" name="status" :value="category" :id="`category-${index}`" hidden v-model="filter">
                <label :class="{ active: filter === category }" :for="`category-${index}`" class="mx-2">{{ category }}</label>
              -->
            </template>
          </div>
        </div>
      </div>
      <div class="o_table_mobile my-5">
        <div class="tbl_basic tbl_dealer">
          <table class="table">
            <thead>
              <tr>
                <th v-if="!isDealer && !isUser && boardId !== 'claim'" class="px-6 py-3 bg-gray-50 justify-content-center" style="width: 15%;">
                  <div class="flex flex-row items-center justify-content-center justify-between cursor-pointer" @click="updateOrdering('created_at')">
                    <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider" :class="{'font-bold text-blue-600': orderColumn === 'created_at'}">
                      등록일
                    </div>
                    <div class="select-none">
                      <span v-if="orderingState.created_at.direction === 'asc' && orderingState.created_at.column === 'created_at'" class="text-blue-600">&uarr;</span>
                      <span v-else-if="orderingState.created_at.direction === 'desc' && orderingState.created_at.column === 'created_at'" class="text-blue-600">&darr;</span>
                      <span v-else-if="orderingState.created_at.direction === '' && orderingState.created_at.column === ''" class="text-blue-600">&uarr;&darr;</span>
                      <!--
                      <span :class="{'text-blue-600': orderDirection === 'asc' && orderColumn === 'created_at', hidden: orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'created_at'}">&uarr;</span>
                      <span :class="{'text-blue-600': orderDirection === 'desc' && orderColumn === 'created_at', hidden: orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'created_at'}">&darr;</span>
                      -->
                    </div>
                  </div>
                </th>
                <th v-if="boardId === 'notice' && (isDealer || isUser)" style="width: 5%;">NO</th>
                <th class="px-6 py-3 bg-gray-50 text-left" style="width: 8%;">
                  <span v-if="boardId === 'claim'" class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">상태</span>
                  <span v-else class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">카테고리</span>
                </th>
                <th class="px-6 py-3 text-left" style="width: 10%;">
                  <div class="flex flex-row justify-content-center" @click="updateOrdering('title')">
                    <div class="font-medium text-uppercase" :class="{'font-bold text-blue-600': orderColumn === 'title'}">
                      제목
                    </div>
                    <div class="select-none">
                      <span v-if="orderingState.title.direction === 'asc' && orderingState.title.column === 'title'" class="text-blue-600">&uarr;</span>
                      <span v-else-if="orderingState.title.direction === 'desc' && orderingState.title.column === 'title'" class="text-blue-600">&darr;</span>
                      <span v-else-if="orderingState.title.direction === '' && orderingState.title.column === ''" class="text-blue-600">&uarr;&darr;</span>
                      <!--
                      <span :class="{'text-blue-600': orderDirection === 'asc' && orderColumn === 'title', hidden: orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'title'}">&uarr;</span>
                      <span :class="{'text-blue-600': orderDirection === 'desc' && orderColumn === 'title', hidden: orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'title'}">&darr;</span>
                      -->
                    </div>
                  </div>
                </th>
                <th v-if="boardId !== 'notice' || (isUser && isDealer) || !isUser&&!isDealer" class="px-6 py-3 bg-gray-50 text-left" style="width: 30%;">
                  <div class="flex flex-row justify-content-center" @click="updateOrdering('content')">
                    <div class="font-medium text-uppercase" :class="{'font-bold text-blue-600': orderColumn === 'content'}">
                      내용
                    </div>
                  </div>
                </th>
                <th v-if="boardId === 'notice' && (isDealer || isUser)" class="px-6 py-3 bg-gray-50 text-left" style="width: 8%;">날짜</th>
                <th v-if="boardId === 'claim'" class="px-6 py-3 bg-gray-50 text-left" style="width: 15%;">차량번호</th>
          
                <th v-if="!isDealer && !isUser || boardId === 'claim'&& isDealer" class="px-6 py-3 bg-gray-50 text-left" style="width: 15%;">수정/삭제</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="posts.length === 0 && boardId === 'claim' && isDealer">
                  <td colspan="5" class="text-center text-secondary opacity-50">클레임 없습니다</td>
              </tr>
              <tr v-if="posts.length === 0 && boardId === 'claim' && !isDealer">
                  <td colspan="5" class="text-center text-secondary opacity-50">클레임 없습니다</td>
              </tr>
              <tr v-else-if="posts.length === 0 && boardId === 'notice' && (isDealer||isUser)">
                  <td colspan="4" class="text-center text-secondary opacity-50">공지사항이 없습니다</td>
              </tr>
              <tr v-else-if="posts.length === 0 && boardId === 'notice' && (!isDealer||!isUser)">
                  <td colspan="5" class="text-center text-secondary opacity-50">공지사항이 없습니다</td>
              </tr>
              <tr 
                  v-for="(post, index) in posts" 
                  :key="post.id" 
                  @click="handleRowClick(post.id)" 
                  class="pointer-cursor"
                >
              <td v-if="boardId === 'notice' && (isDealer || isUser)">{{ index + 1 }}</td>
                <td v-if="!isDealer && !isUser && boardId !== 'claim'" class="px-6 py-4 text-sm text-overflow">{{ post.created_at }}</td>
                <td class="px-6 py-4 text-sm text-overflow"> 
                  <div>[ {{ post.category }} ]</div>
                </td>
                <td class="px-6 py-4 text-sm text-overflow" :class="{'clicked-row': selectedPostId === post.id, 'pointer-cursor': isClickableRow()}"><span v-if="boardId === 'claim'" class="my-2"></span>
                  <img v-if="post.is_secret == 1" src="../../../../img/scret.png" alt="Secret" class="mb-2" width="20px"  />
                  {{ post.title }}</td>
                <td v-if="boardId !== 'notice' || (isUser && isDealer) || !isUser&&!isDealer" class="px-6 py-4 text-sm text-overflow" :class="{'clicked-row': selectedPostId === post.id, 'pointer-cursor': isClickableRow()}">{{ stripHtmlTags(post.content) }}</td>
                <td v-if="boardId === 'claim'"><span class="blue-box mb-0 mx-0">{{ auctionDetails[post.extra1]?.data?.car_no || '' }}</span></td>
                <td v-if="!isDealer && !isUser || boardId === 'claim'&& isDealer" class="px-6 py-4 text-sm" @click.stop>
                  <router-link :to="{ name: 'posts.edit', params: { boardId, id: post.id }, query: { navigatedThroughHandleRowClick: false } }" class="badge">
                    <div class="icon-edit-img"></div>
                  </router-link>
                  <a href="#" @click.stop class="ms-2 badge web_style">
                    <div @click.prevent="deletePost(boardId, post.id)" class="icon-trash-img"></div>
                  </a>
                </td>
                <td  v-if="boardId === 'notice' && (isDealer || isUser)">{{ post.created_at }}</td>
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
  <div v-if="isDealer || isUser">
  <Footer />
</div>
</template>

<script setup>
import { ref, onMounted, watch, computed,inject } from "vue";
import { useRoute, useRouter } from 'vue-router';
import { initPostSystem } from "@/composables/posts";
import { useStore } from 'vuex';
import useAuctions from "@/composables/auctions";
import axios from 'axios';
import Footer from "@/views/layout/footer.vue";
import IntroModal from '@/views/modal/introModal.vue';
import UseGuide from "@/views/import/UseGuide.vue";
import carObjects from '../../../../../resources/img/modal/car-objects-blur.png';
import arrow from '../../../../../resources/img/dash-black.png';
import bid from '../../../../../resources/img/bid.png';
import hand from '../../../../../resources/img/hand.png';
import Iconcare from '../../../../../resources/img/Iconcare.png';
import hand02 from '../../../../../resources/img/hand02.png';
import { cmmn } from '@/hooks/cmmn';
const swal = inject('$swal');
const { wica } = cmmn();

const selectedPostId = ref(null);

const { posts, getPosts, deletePost, isLoading, getBoardCategories, pagination } = initPostSystem();
const { getAuctionById } = useAuctions(); 
const route = useRoute();
const router = useRouter();
const boardId = ref(route.params.boardId);
const currentPage = ref(1);
const store = useStore();
const search_title = ref("");
const orderColumn = ref("created_at");
const orderDirection = ref("desc");
const user = computed(() => store.getters['auth/user']);
const isDealer = computed(() => user.value?.roles?.includes('dealer'));
const isUser = computed(() => user.value?.roles?.includes('user'));
const filter = ref('all');
const categoriesList = ref([]);
const showModal = ref(false);
const setFilter = (selectedFilter) => {
  filter.value = selectedFilter;
  fetchPosts();  // 필터링된 게시물을 가져오기 위해 fetchPosts 호출
};
const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};
const auctionDetails = ref({});
const hideButton = ref(false);

const stripHtmlTags = (html) => {
  const div = document.createElement('div');
  div.innerHTML = html;
  return div.textContent || div.innerText || '';
};

const getBoardData = async () => {
  try {
    const response = await axios.get('/api/board');
    if (Array.isArray(response.data.data)) {
      const board = response.data.data.find(board => board.id === boardId.value);
      if (board) {
        categoriesList.value = JSON.parse(board.categories);
      }
    }
  } catch (error) {
    console.error('Error fetching board data:', error);
  }
};

async function loadPage(page) { // 페이지 로드
  if (page < 1 || page > pagination.value.last_page) return;
  currentPage.value = page;
  await fetchPosts(page);
  window.scrollTo(0, 0);
}

const fetchPosts = async (page = 1) => {
  hideButton.value = false;  // Reset button visibility

  await getPosts(
    boardId.value,  // 전달된 boardId 사용
    page,
    search_title.value,
    filter.value,  // 필터링 조건 전달
    orderColumn.value,
    orderDirection.value
  );

  currentPage.value = pagination.value.current_page;
  if (boardId.value === 'claim') {
    await fetchAllAuctionDetails();
  }
};

const orderingState = {
    created_at: { direction: '', column: '', hit: 0 },
    title: { direction: '', column: '', hit: 0 },
};

const updateOrdering = (column) => {
  /*
  if (orderColumn.value === column) {
    orderDirection.value = orderDirection.value === "asc" ? "desc" : "asc";
  } else {
    orderColumn.value = column;
    orderDirection.value = "asc";
  }
  */

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

  fetchPosts();
};

const navigatedThroughHandleRowClick = ref(false);
const handleRowClick = (postId) => {
    hideButton.value = true;
    navigatedThroughHandleRowClick.value = true; 
    router.push({ name: 'posts.edit', params: { boardId: boardId.value, id: postId }, query: { navigatedThroughHandleRowClick: true } });
};

const isClickableRow = () => {
  return (boardId.value === 'claim' || boardId.value === 'notice');
};
const openAlarmModal = () => {
  const text = `
    <div class="intro-container container text-start">
        <h5>이용안내 페이지</h5>
        <h4 class="bold">위카옥션, 이렇게 이용하세요</h4>
        <div class="bidding-section">
            <h3 class="size_32 bolder mx-2">1<span class="size_24 ms-2">입찰하기</span></h3>
            <ol class="mt-3 step-list">
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">1</span>
                    <div class="step-text">위카옥션에서 매물을 검색합니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">2</span>
                    <div class="step-text">진단평가 결과서와 시세 등을 확인 후 입찰을 합니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-circle">3</span>
                    <div class="step-text">경매 종료 후 48시간 이내 고객이 판매대금을 선택합니다. 선택하지 않을 경우 입찰가 1위로 자동 낙찰됩니다.</div>
                </li>
            </ol>
            <ul class="list-block">
                <li>입찰 딜러 수는 제한이 없습니다.</li>
                <li>입찰후 차량 배송까지 시일이 걸립니다. 8일정도 후의 가격까지 고려하셔서 입찰해 주세요.</li>
                <li>최고가는 경매 종료후 공개됩니다.</li>
                <li>리스차량은 "승계후 완납 조건"으로 입찰해야 합니다.</li>
            </ul>
            <div class="bidding-section">
            <h3 class="size_32 bolder">2<span class="size_24 ms-2">견적 취소</span></h3>
            <ol class="mt-3 step-list">
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">1</span>
                    <div class="step-text">입찰을 한 후에도 취소 후 재견적을 입력할 수 있습니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">2</span>
                    <div class="step-text">견적취소는 입찰 종료 전 30분전까지 가능합니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-circle">3</span>
                    <div class="step-text">견적실수시 본사(1544-2165)에 즉시 연락주시기 바랍니다.</div>
                </li>
            </ol>
            <ul class="list-block">
                <li>1회 견적실수 패널티 3일 이용정지</li>
                <li>2회 견적실수 패널티 1달 이용정지</li>
                <li>3회 견적실수 패널티 계정 정지</li>
            </ul>
             <div class="bidding-section">
            <h3 class="size_32 bolder">3<span class="size_24 ms-2">차량 인수</span></h3>
            <ol class="mt-3 step-list">
                <li class="step-item align-items-start">
                    <span class="step-connector02"></span>
                    <span class="step-circle">1</span>
                    <div class="step-text">낙찰이 확정되면 인수정보를 입력합니다. (딜러 : 매수자, 고객 : 탁송정보)
                      <p>※ 낙찰 확정일 익일부터 48시간 이내 인수정보 반드시 입력</p>
                      <p>※ 압류/저당, 사업자유무, 비사업용 여부등을 파악하여 이전 필요서를 차와 함께 보내드립니다.</p>
                    </div>
                </li>
                <li class="step-item align-items-start">
                    <span class="step-connector02"></span>
                    <span class="step-circle">2</span>
                    <div class="step-text">탁송 2시간전까지 나이스에스크로 계좌로 차량 대금 입금이 필요합니다. (입급증 완료시 입금증 발송)
                      <p>※ 탁송 2시간전까지 대금이 입금되지 않으면 낙찰 취소가 됩니다. 11시 이전에 탁송이 진행되는 경우는 전라 18시까지 차량 대금을 입급해야합니다.</p>
                    </div>
                </li>
                <li class="step-item align-items-start">
                    <span class="step-circle">3</span>
                    <div class="step-text">거래 마무리
                      <p>※ 인수후 영업일 기준 2일내 진단오류에 대한 클레임을 제기할 수 있습니다. (성능기록부 및 증빙 자료 제출)</p>
                    </div>
                </li>
            </ol>
            <ul class="list-block">
                <li>1회 견적실수 패널티 3일 이용정지</li>
                <li>2회 견적실수 패널티 1달 이용정지</li>
                <li>3회 견적실수 패널티 계정 정지</li>
            </ul>
        </div>
    </div>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intromodal') // 클래스명 설정
    .addOption({ padding: 20 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
    })
    .confirm(text); // 모달 내용 설정
};

const openAlarmModal02 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-5">
          <h5>위카옥션의 수수료 체계를 안내해드려요</h5>
          <h4 class="bold">정보제공 수수료 안내</h4>
        </div>
        <div class="fee-section mt-5">
          <div class="fee-step">
            <div class="circle">
              <img src="${bid}" alt="경매 낙찰" />
            </div>
            <p>경매 낙찰</p>
          </div>
          <div class="arrow">
            <img src="${arrow}" alt="화살표" width="50px"/>
          </div>
          <div class="fee-step">
            <div class="circle">
              <img src="${hand}" alt="대금 에스크로 입금" />
            </div>
            <p>대금 에스크로 입금</p>
          </div>
           <div class="arrow">
            <img src="${arrow}" alt="화살표" width="50px"/>
          </div>
          <div class="fee-step">
            <div class="circle">
              <img src="${Iconcare}" alt="탁송 및 도착" />
            </div>
            <p>탁송 및 도착</p>
          </div>
          <div class="arrow">
            <img src="${arrow}" alt="화살표" width="50px"/>
          </div>
          <div class="fee-step">
            <div class="circle">
              <img src="${hand02}" alt="수수료 입금" />
            </div>
            <p>수수료 입금</p>
          </div>
        </div>
      </div>
      <div class="o_table_mobile my-5">
        <div class="tbl_basic tbl_fee">
          <table class="table">
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">금액</th>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">수수료</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-6 py-4 text-center">100만원 이하</td>
                <td class="px-6 py-4 text-center">14만원</td>
              </tr>
               <tr>
                <td class="px-6 py-4 text-center">500만원 이하</td>
                <td class="px-6 py-4 text-center">25만원</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intromodal') // 클래스명 설정
    .addOption({ padding: 20 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
    })
    .confirm(text); // 모달 내용 설정
};

const openAlarmModal03 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-5">
          <h5>진단오류시 보상은 이렇게 진행돼요</h5>
          <h4 class="bold">진단오류시 보상기준표</h4>
        </div>
      <div class="o_table_mobile my-5">
        <div class="tbl_basic tbl_fee">
          <table class="table">
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">감가 항목</th>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">감가율</th>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">비고</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-6 py-4 text-center">후드</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
               <tr>
                <td class="px-6 py-4 text-center">프론트휀더</td>
                <td class="px-6 py-4 text-center">2%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              </tbody>
              </table>
              <p>※ 클레임 보상 : 매입금액 x 감가율 = 보상금액 (보상한도 100만원)</p>
              <p>※ 교환 / 판금에 대한 정의는 자동차관리법상 성능상태점검기준에 따라 적용</p>
              <p>※ 판금오류시 보상비율을 교환대비 50% 감가</p>
              <p>※ 사이드맴버, 휠하우스, 인사이드 패널은 좌우앞뒤가 상관없이 단일품목으로 감가</p>
        </div>
      </div>
    </div>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intromodal') // 클래스명 설정
    .addOption({ padding: 20 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
    })
    .confirm(text); // 모달 내용 설정
};
const boardText = computed(() => {
  switch(boardId.value) {
    case 'notice':
      return '공지사항';
    case 'claim':
      return '클레임';
    default:
      return boardId.value;
  }
});
const adminTextMessage = computed(() => {
  if(boardId.value){
    return `${boardText.value} 관리 페이지 입니다.`
  } 
});

const boardTextMessage = computed(() => {
  if (boardId.value === 'notice') {
    return `빠르고 신속하게 ${boardText.value} 전해드립니다.`;
  } else if (boardId.value === 'claim') {
    return `클레임 작성은 내 정보 > 지난 낙찰건 > 클레임 작성 에서 가능합니다.`;
  } else {
    return '';
  }
});

const setBoardFilter = (selectedFilter) =>{
  filter.value = selectedFilter;
  fetchPosts();
}

const fetchAllAuctionDetails = async () => {
  try {
    for (const post of posts.value) {
      if (post.extra1) {
        const details = await getAuctionById(post.extra1);
        auctionDetails.value[post.extra1] = details;
      }
    }
  } catch (error) {
    console.error("Error fetching auction details: ", error);
  }
};

onMounted(async () => {
  getBoardCategories();
  await fetchPosts();
  getBoardData();
});
watch(route, async (newRoute) => {
  if (newRoute.params.boardId !== boardId.value) {
    boardId.value = newRoute.params.boardId;
    
    search_title.value = '';
    // 필터 값을 초기화합니다.
    filter.value = 'all';

    // 새로운 boardId에 대한 카테고리 데이터를 다시 로드합니다.
    await getBoardData();
    
    // 새로운 boardId에 해당하는 게시물 목록을 다시 로드합니다.
    await fetchPosts();
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

.pointer-cursor {
  cursor: pointer;
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
.status-selector {
  display: flex;
  overflow-x: auto;
  white-space: nowrap;
}

.status-selector label {
  white-space: nowrap;
  margin-right: 10px;
}
@media (max-width: 767px) {
  .status-selector {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
}

</style>
