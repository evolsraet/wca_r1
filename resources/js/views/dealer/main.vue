<template>
    <div>
        <div>
            <div class="banner-top">
                <div>
                    <div class="styled-div mt-0">
                        <h1 class="centered-text fade-in" :class="{ 'fade-in-active': isVisible }">
                            현재 진행중인 경매가
                            <span class="ms-2 underline">{{ ingCount }} 건</span> 있어요.
                        </h1>
                        
                    <swiper
                        :modules="[Navigation, Pagination, Autoplay]"
                        :slides-per-view="1"
                        :loop="true"
                        :pagination="{
                            clickable: true,
                            el: '.swiper-pagination',
                            type: 'custom',
                            renderCustom: renderCustomPagination
                        }"
                        :autoplay="{ delay: 5000 }"
                        :speed="1000"
                        @slideChangeTransitionStart="resetProgressBar"
                        class="swiper-container"
                    >
                    <!-- Slide 1 -->
                    <swiper-slide>
                             <div class="slide-content">
                                <img src="../../../img/main_banner02.png" class="styled-img" alt="배너 이미지" />
                                <div class="content d-flex">
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </swiper-slide>
                        <!-- Slide 2 -->
                        <swiper-slide>
                            <div class="slide-content">
                                <img src="../../../img/main_p2.png" class="styled-img" alt="배너 이미지 2" />
                                <div class="content02 d-flex text-center">
                                </div>
                            </div>
                        </swiper-slide>
                        <!-- Slide 3 -->
                        <swiper-slide>
                            <div class="slide-content">
                                <img src="../../../img/main_p3.png" class="styled-img" alt="배너 이미지 3" />
                                <div class="content d-flex">
                                </div>
                            </div>
                        </swiper-slide>

                        <!-- 페이지 네이션 -->
                        <div class="swiper-pagination mb-3">
                            <div class="progress-bar">
                                <div class="progress"></div>
                            </div>
                        </div>
                    </swiper>
                    <!-- Activity Info -->
                    <div class="web container activity-info bold-18-font process mb-0">
                        <router-link :to="{ name: 'auction.index', state: { currentTab: 'interInfo' }}" class="item">
                            <p class="size_22 tc-black"><span class="tc-primary slide-up mb-0 size_22" ref="item1">{{ myLikeCount }}</span> 건</p>
                            <p class="interest-icon text-secondary opacity-50 size_16 mb-0">관심</p>
                        </router-link>
                        <router-link :to="{ name: 'auction.index', state: { currentTab: 'myBidInfo',status: 'bid' }}" class="item">
                            <p class="size_22 tc-black"><span class="tc-primary mb-0" ref="item2">{{ myBidCount }}</span> 건</p>
                            <p class="bid-icon text-secondary opacity-50 size_16 mb-0">입찰</p>
                        </router-link>
                        <router-link :to="{ name: 'auction.index', state: { currentTab: 'scsbidInfo' }}" class="item">
                            <p class="size_22 tc-black"><span class="tc-primary mb-0 size_22" ref="item3">{{ myScsBidCount }}</span> 건</p>
                            <p class="suc-bid-icon text-secondary opacity-50 size_16 mb-0">낙찰</p>
                        </router-link>
                    </div>
            
                </div>
            </div>
            <div class="mov">
                <router-link :to="{ name: 'auction.index', state: { currentTab: 'interInfo' }}" class="item">
                    <p class="interest-icon text-secondary opacity-50 normal-16-font mb-0">관심</p>
                    <p class="text-center"><span class="tc-primary slide-up mb-0" ref="item1">{{ myLikeCount }}</span> 건</p>
                </router-link>
                <router-link :to="{ name: 'auction.index', state: { currentTab: 'myBidInfo',status: 'bid' }}" class="item">
                    <p class="bid-icon text-secondary opacity-50 normal-16-font mb-0">입찰</p>
                    <p class="text-center"><span class="tc-primary mb-0" ref="item2">{{ myBidCount }}</span> 건</p>
                </router-link>
                <router-link :to="{ name: 'auction.index', state: { currentTab: 'scsbidInfo' }}" class="item">
                    <p class="suc-bid-icon text-secondary opacity-50 normal-16-font mb-0">낙찰</p>
                    <p class="text-center"><span class="tc-primary mb-0" ref="item3">{{ myScsBidCount }}</span> 건</p>
                </router-link>
            </div>
            <UseGuide class="mt-4"/>
            </div>
            <div class="container layout-container02">
    
            
                <div class="tbl_basic container p-4">
                    <div class="d-flex align-items-start justify-content-between">
                    <h3 class="review-title mb-5">공지사항</h3>
                    <router-link
                    :to="{name:'posts.index' , params: {boardId: 'notice'}}"
                    class="btn-apply mt-0 p-0">전체보기</router-link>
                </div>
                    <table class="table">
                    <thead class="d-none">
                        <tr>
                        <th>NO</th>
                        <th>작성일</th>
                        <th>제목</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 데이터가 없는 경우 -->
                        <tr v-if="posts.length === 0">
                        <td colspan="4" class="text-center text-secondary opacity-50">
                            게시물이 없습니다.
                        </td>
                        </tr>

                        <!-- 데이터가 있는 경우 -->
                        <tr 
                            v-for="(post, index) in posts" 
                            :key="post.id"
                            @click="handleRowClick(post.id)" 
                            class="pointer-cursor"
                        >
                            <td style="width: 5%;" class="tc-bold">{{ index + 1 }}</td>
                            <td style="width: 30%;" class="tc-gray">{{ post.created_at }}</td>
                            <td class="tb-title" style="width: 65%;">{{ post.title }}</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <AuctionList/>
            </div>
        </div>
    </div>
    <Footer />
</template>
<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";
import { Navigation, Pagination, Autoplay } from "swiper/modules";

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  methods: {
    // 커스텀 페이지 네이션 렌더링
    renderCustomPagination(swiper, current, total) {
      return `
        <span class="pagination-number">${current}</span>
        <div class="progress-bar">
          <div class="progress"></div>
        </div>
        <span class="pagination-number">${total}</span>
      `;
    },
    // 진행 바 리셋
    resetProgressBar() {
      const progress = document.querySelector('.progress');
      if (progress) {
        progress.style.width = '0%';
        setTimeout(() => {
          progress.style.transition = 'width 5s linear';
          progress.style.width = '100%';
        }, 50);
      }
    },
  },
  mounted() {
    this.resetProgressBar();
  },
};
</script>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import useBid from "@/composables/bids";
import Footer from "@/views/layout/footer.vue";
import { useStore } from 'vuex';
import { useRouter ,useRoute } from 'vue-router';
import AlarmModal from '@/views/modal/AlarmModal.vue';
import useAuctions from '@/composables/auctions';
import useLikes from '@/composables/useLikes';
import AuctionList from "@/views/import/AuctionList.vue";
import { initPostSystem } from "@/composables/posts";
import { cmmn } from '@/hooks/cmmn';
import UseGuide from "@/views/import/UseGuide.vue";

const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();

const isVisible = ref(false);

onMounted(() => {
  setTimeout(() => {
    isVisible.value = true;
  }, 200); // 0.5초 후에 애니메이션 시작
});
const route = useRoute();
const { posts, getPosts,getBoardCategories } = initPostSystem();

const { getMyLikesCount } = useLikes();
const item1 = ref(null);
const item2 = ref(null);
const item3 = ref(null);
const myBidsCount = ref(0);
const store = useStore();
const router = useRouter();
const isExpanded = ref(false);
const toggleCard = () => {
    isExpanded.value = !isExpanded.value;
};
const boardId = ref('notice');
const fetchPosts = async () => {
  try {
    // 최신 5개 게시물만 가져오기
    await getPosts(boardId.value, 1, '', 'all', 'created_at', 'desc');
    // 최신 5개로 제한
    posts.value = posts.value.slice(0, 5);
  } catch (error) {
    console.error('Error fetching posts:', error);
  }
};

const alarmModal = ref(null);
const { getAuctionsByDealer, auctionsData, getAuctionById, getAuctions, getAuctionsWithBids, getAuctionsByDealerLike, allIngCount } = useAuctions();
const { bidsData, getHomeBids, viewBids, bidsCountByUser, getscsBids, getMyBidsAll } = useBid();
const user = computed(() => store.state.auth.user);
const myBidCount = ref(0);
const myLikeCount=ref(0);
const myScsBidCount=ref(0);
const categoriesList=ref(0);
const bidsIdString = ref('');

const latestNotices = computed(() => {
  if (!posts.value || posts.value.length === 0) {
    console.warn("공지사항 데이터가 비어 있습니다.");
    return [];
  }
  return posts.value.slice(0, 3);
});

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
// HTML 태그 제거 함수
const stripHtmlTags = (html) => {
  const div = document.createElement("div");
  div.innerHTML = html;
  return div.textContent || div.innerText || "";
};
const navigatedThroughHandleRowClick = ref(false);
const hideButton = ref(false);
const handleRowClick = (postId) => {
    hideButton.value = true;
    navigatedThroughHandleRowClick.value = true; 
    router.push({ name: 'posts.edit', params: { boardId: boardId.value, id: postId }, query: { navigatedThroughHandleRowClick: true } });
};


const openAlarmModal = () => {
    if (alarmModal.value) {
        alarmModal.value.openModal();
    }
};

const ingCount = ref(0);

const ingCountFetch = async() => {
    // return auctionsData.value.filter(auction => auction.status === 'ing').length;
    const allIngCnt =  await allIngCount();
    ingCount.value = allIngCnt;
};

const alertNoVehicle = (event) => {
    event.preventDefault();
    if (viewBids.value.length === 0) {
        alert("선택 완료된 차량이 없습니다.");
    } else {
        router.push({ name: 'auction.index' , state: { currentTab: 'scsbidInfo' }});
    }
};

const fetchAuctionDetails = async (bid) => {
    try {
        const auctionDetails = await getAuctionById(bid.auction_id);
        return {
            ...bid,
            auctionDetails: auctionDetails.data
        };
    } catch (error) {
        console.error('Error fetching auction details:', error);
        return {
            ...bid,
            auctionDetails: null
        };
    }
};

const filteredDoneBids = ref([]);

const goToDetail = (postId) => {
  router.push({ name: "posts.edit", params: { boardId: "notice", id: postId } });
};


const getIngData = async() => {
    const auctionDatas = await getAuctions('1',false,'all','');
    console.log('getIngData', auctionDatas);
}

// 입찰 데이터 가져오기
const getMyBidsGetData = async() => {
    const myAuctionBidsInfo = await getAuctionsWithBids('1','all',user.value.id,'',bidsIdString.value);
    myBidCount.value = myAuctionBidsInfo.data.filter(auction => auction.status === 'ing').length;
    console.log('getMyBidsGetData', myAuctionBidsInfo);
    console.log('getMyBidsGetDataLength', myAuctionBidsInfo.data.filter(auction => auction.status === 'ing').length);
}

// 관심 데이터 가져오기
const getMyLikeGetData = async() => {
    const myAuctionBidsInfo = await getAuctionsByDealerLike('1',user.value.id,'all');
    myLikeCount.value = myAuctionBidsInfo.rawData.data.data_count;
    console.log('getMyLikeGetData', myAuctionBidsInfo.rawData);
}

// 낙찰 데이터 가져오기
const getScsBidsGetData = async() => {
    const myAuctionBidsInfo = await getscsBids('1','all','',bidsIdString.value);
    myScsBidCount.value = myAuctionBidsInfo.rawData.data.data_count;
    console.log('getScsBidsGetData', myAuctionBidsInfo.rawData);
}

onMounted(async () => {

    const myBidsList = await getMyBidsAll();
    const bidsIdList = [];
    for (let i = 0; i < myBidsList.data.length; i++) {
        bidsIdList.push(myBidsList.data[i].id);
    }

    bidsIdString.value = bidsIdList.join(',');

    await getBoardCategories(); // 카테고리 로드
    await fetchPosts(); // 게시물 로드
    ingCountFetch();
    getBoardData();
    getIngData();
    getMyBidsGetData();
    getMyLikeGetData();
    getScsBidsGetData();
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
    await fetchPosts();
    await getAuctionsByDealer("all");
    const myLikeCountData = await getMyLikesCount(user.value.id);
    myLikeCount.value = myLikeCountData.rawData.data.data_count;
 
    await getHomeBids();
    bidsData.value.forEach(bid => {
        if (
            bid.auction.win_bid &&
            bid.auction.win_bid.user_id === user.value.id &&
            (bid.auction.status == "chosen" || bid.auction.status == "dlvr")
        ) {
            filteredDoneBids.value.push(bid);
        }
        
        if (bid.auction.status == "ing" || bid.auction.status == "wait") {
            myBidCount.value += 1;
        }
    }); 

    setTimeout(() => {
        if (item1 != null && item1.value != null && item1.value.classList != null) 
            item1.value.classList.add('visible');
    }, 0);
    setTimeout(() => {
        if (item2 != null && item2.value != null && item2.value.classList != null)
            item2.value.classList.add('visible');
    }, 200);
    setTimeout(() => {
        if (item3 != null && item3.value != null && item3.value.classList != null)
            item3.value.classList.add('visible');
    }, 400);
});
</script>


<style scoped>
p {
    margin-top: 0;
    margin-bottom: 0rem !important;
}
.layout-container02{
    grid-template-columns: 1fr 1fr !important;
    align-items: baseline;
    gap: 30px !important;
}
@media (max-width: 640px){
    .layout-container02 {
        display: flex !important;
        gap: 2rem !important;
        flex-direction: column;
        flex-wrap: nowrap;
    }
}
@media (max-width: 991px){
.layout-container02 {
    grid-template-columns: none !important;
    display: flex !important;
    gap: 2rem !important;
    flex-direction: column;
    flex-wrap: nowrap;
    align-items:normal;
    max-width: none !important;
    }
.p-4{
    padding: 0px !important;
}
.layout-container02 .container  {
    max-width: none !important;
}
}

.text-with-marker {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tbl_basic table tr td {
    padding:20px 5px !important;
}
.tbl_basic table tr th {
    padding: 10px 5px !important;
}
.styled-div {
    position: relative;
}

.activity-info {
    position: absolute;
    bottom: 0px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255, 255, 255);
    padding: 65px 20px;
    border-radius: 10px;
    text-align: center;
    z-index: 10;
    width: 75%;
}

/* 반응형 디자인 */
@media (max-width: 991px) { /* 태블릿 */
    .activity-info {
        bottom: -95px;
    }
    .styled-div {
        height: auto !important;
    }
    .mov{
        display: flex !important;
        justify-content: space-around;
        margin-top: 27px;
    }
    .web{
        display:none !important;
    }
    .centered-text{
        font-size: 1.6rem !important;
        line-height: 35px !important;
    }
    .swiper-slide .styled-img {
        width: auto; 
        height: 400px;
        object-fit: cover; /* 비율 유지하며 잘리는 부분 처리 */
    }

    .swiper-container {
        height: 400px; /* 전체 슬라이드 높이를 조정 */
    }
}

@media (max-width: 480px) { /* 모바일 */
    .activity-info {
        padding: 8px 12px;
        font-size: 12px;
    }
    .centered-text{
        top: 40% !important;
        left: 11%;
    }
}
@media (max-width:423px) {
    .guide-icon{
    width: 46px;
    height: 46px;
    }
}
.mov{
    display:none;
}
.web{
    display:flex;
}
.styled-div .content02 {
position: absolute;
top: 0;
left: 0px;
width: 100%;
height: 100%;
display: flex;
flex-direction: row;
justify-content: center;
padding: 50px;
color: white;
align-items: center;
} 
.fade-in {
  opacity: 0;
  transition: opacity 1s ease-out;
}

.fade-in.fade-in-active {
  opacity: 1;
  transform: translateY(0);
}
.styled-div .content {
position: absolute;
top: 0;
left: -150px;
width: 100%;
height: 100%;
display: flex;
flex-direction: row;
justify-content: center;
padding: 50px;
color: white;
align-items: center;
}
.centered-text {
    position: absolute;
    top: 40%; /* 수직 중앙 정렬 */
    left: 15%; /* 수평 중앙 정렬 */
    transform: translate(-10%, -50%); /* 중앙 정렬 보정 */
    z-index: 10; /* 슬라이더 위로 배치 */
    text-align: start;
    font-size: 2rem;
    line-height: 50px;
    font-weight: bold;
    color: #ffffff;
}
.venture{
    position: absolute;
    top: 47%;
    right: 12%; /* 수평 중앙 정렬 */
    transform: translate(-10%, -50%); /* 중앙 정렬 보정 */
    z-index: 10; /* 슬라이더 위로 배치 */
    text-align: start;
    font-size: 2rem;
    line-height: 50px;
    font-weight: bold;
    color: #ffffff; 
}
</style>
