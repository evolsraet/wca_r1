<template>
    <div>
        <div class="banner-top">
            <div class="styled-div mt-0">
                <swiper :modules="[Navigation, Pagination, Autoplay]" :slides-per-view="1" :loop="true" :pagination="{
            clickable: true,
            el: '.swiper-pagination',
            type: 'custom',
            renderCustom: renderCustomPagination
            }" :autoplay="{ delay: 5000 }" :speed="1000" @slideChangeTransitionStart="resetProgressBar">
               <!-- Slide 1 -->
               <swiper-slide>
                        <div class="slide-content">
                            <img src="../../../img/main_banner02.png" class="styled-img" alt="배너 이미지" />
                            <div class="content d-flex">
                                <div>
                                    <img src="../../../img/logo.png" width="100" class="mb-3">
                                    <h1>쉽고 빠른 <span class="bolder">내차팔기,</span></h1>
                                    <h1 class="font-title"><span class="bolder">위카</span>에서 <span class="bolder">높은 가격</span>으로!</h1>
                                    <h5 class="mt-3 normal">자동차 진단 전문가 <span class="bolder">위카모빌리티</span>가 함께합니다.</h5>
                                    <!-- <router-link
                                        class="btn-sell-car d-flex bc-primary border-none mt-5"
                                        :to="{ path: '/' }"
                                        > -->
                                    <a class="btn-sell-car d-flex bc-primary border-none mt-5"href="/carfind-do">
                                    <div>
                                        <span class="icon-car me-1"><img src="../../../img/icon-small-car.png" width="20"></span><span class="size_14">내 차 판매하기</span> 
                                    </div>
                                    <img src="../../../img/Icon-right-wh.png" class="dash-wh" width="10">
                                    </a>
                                    <!-- </router-link> -->
                                </div>
                                
                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide>
                        <div class="slide-content">
                            <img src="../../../img/main_p2.png" class="styled-img" alt="배너 이미지 2" />
                            <div class="content d-flex text-start">
                                <div>
                                    <img src="../../../img/logo.png" width="100" class="mb-3">
                                    <h3>TRANSFORMING USED CAR!</h3>
                                    <h3 class="bold">WECARMOBILITY</h3>
                                    <h1 class="fw-bolder mb-4 mt-3 lh-base animated-text">
                                        내 차 판매는, 위카에서
                                    </h1>
                                    <!-- <router-link
                                        class="btn-sell-car d-flex bc-primary border-none"
                                        :to="{ path: '/' }"
                                        > -->
                                    <a class="btn-sell-car d-flex bc-primary border-none"href="/carfind-do">
                                    <div>
                                        <span class="icon-car me-1"><img src="../../../img/icon-small-car.png" width="20"></span><span class="size_14">내 차 판매하기</span> 
                                    </div>
                                    <img src="../../../img/Icon-right-wh.png" class="dash-wh" width="10">
                                    </a>
                                <!-- </router-link> -->
                                </div>
                            </div>
                        </div>
                    </swiper-slide>
                

                   
                    <div class="swiper-pagination mb-3">
                        <div class="progress-bar">
                            <div class="progress"></div>
                        </div>
                    </div>
                </swiper>
            </div>
        </div>
        <div>
            <div class="regiest-content">
                <div class="container my-4">
                    <div v-if="isMobileView" class="d-flex justify-content-between align-items-sm-end p-3 pb-0 mt-4 mb-0">
                        <h3 class="review-title"> 내 매물 관리</h3>
                        <router-link :to="{ name: 'auction.index' }" href="" class="btn-apply p-0 m-0 animated-button">더 알아보기</router-link>
                    </div>
                    <div class="layout-container02 mt-5">
                        <div class="p-2">
                            <div class="tbl_basic container p-4">
                                <div class="d-flex align-items-start justify-content-between">
                                    <h3 class="review-title mb-5">공지사항</h3>
                                    <router-link :to="{name:'posts.index' , params: {boardId: 'notice'}}" class="btn-apply mt-0 p-0">전체보기</router-link>
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
                                        <tr v-for="(post, index) in posts" :key="post.id" @click="handleRowClick(post.id)" class="pointer-cursor">
                                            <td style="width: 5%;" class="tc-bold">{{ index + 1 }}</td>
                                            <td style="width: 30%;" class="tc-gray">{{ post.created_at }}</td>
                                            <td class="tb-title" style="width: 65%;">{{ post.title }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <AuctionList />
                    </div>
                    <div class="intro-container container text-start  py-4" v-if="!isMobileView">
                        <div class="mt-5">
                            <h2 class="text-center mb-4 bolder ">내 차 판매, 이렇게 진행돼요.</h2>
                        </div>
                        <div class=" align-items-start fee-section line-height-10 mt-5 justify-content-between">
                        <div class="fee-step">
                            <div class="circle">
                                <img class="ms-2" src="../../../img/contract.png" width="40px">
                            </div>
                            <p class="bc-gray tc-gray px-3 py-1 border-6 mt-3">STEP 01</p>
                            <p class="bolder mt-3">경매신청</p>
                            <p class="normal mt-2">차량정보 입력 후 <br>경매 신청</p>
                        </div>
                        <div class="arrow ">
                            <img src="../../../img/dash-black.png" width="50px">
                        </div>
                        <div class="fee-step">
                            <div class="circle">
                                <img src="../../../img/car-insurance.png" width="41px">
                            </div>
                            <p class="bc-gray tc-gray px-3 py-1 border-6 mt-3">STEP 02</p>
                            <p class="bolder mt-3">차량진단</p>
                            <p class="normal mt-2">차량 진단 및<br>경매 등록</p>
                        </div>
                        <div class="arrow">
                            <img src="../../../img/dash-black.png" width="50px">
                        </div>
                        <div class="fee-step">
                            <div class="circle">
                                <img class="ms-1" src="../../../img/bid.png" width="43px">
                            </div>
                            <p class="bc-gray tc-gray px-3 py-1 border-6 mt-3">STEP 03</p>
                            <p class="bolder mt-3">경매</p>
                            <p class="normal mt-2">딜러 입찰 후<br>낙찰자 선정</p>
                        </div>
                        <div class="arrow">
                            <img src="../../../img/dash-black.png" width="50px">
                        </div>
                        <div class="fee-step">
                            <div class="circle">
                                <img class="mb-1" src="../../../img/car-wash.png" width="43px">
                            </div>
                            <p class="bc-gray tc-gray px-3 py-1 border-6 mt-3">STEP 04</p>
                            <p class="bolder mt-3">탁송</p>
                            <p class="normal mt-2">경매대금 입금 및<br>서류 전달, 탁송</p>
                        </div>
                        <div class="arrow">
                            <img src="../../../img/dash-black.png" width="50px">
                        </div>
                        <div class="fee-step">
                            <div class="circle">
                                <img src="../../../img/Iconcare.png" width="43px">
                            </div>
                            <p class="bc-gray tc-gray px-3 py-1 border-6 mt-3">STEP 05</p>
                            <p class="bolder mt-3">사후처리</p>
                            <p class="normal mt-2">클레임 처리 및<br>이전</p>
                        </div>
                       </div>
                    </div>

                </div>
            </div>
            <div class="container mt-5">
                <div v-if="isMobileView" class="px-4 pb-5">
                    <h3 class="text-center mb-4 bolder">내 차 판매, 이렇게 진행돼요.</h3>
                    <div class="d-flex mt-4 gap-3 justify-content-between">
                        <div class="text-center">
                            <img src="../../../img/contract.png" width="40px">
                            <p>경매신청</p>
                        </div>
                        <div class="text-center">
                            <img src="../../../img/car-insurance.png" width="41px">
                            <p>차량진단</p>
                        </div>
                        <div class="text-center">
                            <img src="../../../img/bid.png" width="43px">
                            <p>경매</p>
                        </div>
                        <div class="text-center">
                            <img src="../../../img/car-wash.png" width="43px">
                            <p>탁송</p>
                        </div>
                        <div class="text-center">
                            <img src="../../../img/care.png" width="43px">
                            <p>사후처리</p>
                        </div>
                    </div>
                </div>
            <div class="p-1">
                <div class="apply-top text-start mb-0 px-3">
                    <h3 class="review-title">나의 이용후기</h3>
                    <router-link :to="{ name: 'user.review' }" href="" class="btn-apply">전체보기</router-link>
                </div>
                <div v-if="auctionsData.length > 0" class="container">
                    <div class="row p-3">
                        <div class="col-md-6 p-2 hover-ac pointer" v-for="auction in auctionsData.slice(0,2)" :key="auction.id" @click="navigateToDetail(auction.id)">
                            <div class="card my-auction mt-3">
                                <div>
                                    <div class="card-img-top-placeholder">
                                        <img :src="auction.car_thumbnail">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                        <p>2020년 | 2.4km | 무사고</p>
                                        <div class="d-flex justify-content-between align-items-baseline">
                                            <div>
                                                <span class="blue-box border-6">보험 3건</span><span class="gray-box border-6">재경매</span>
                                                <h5 class="tc-primary fs-5">{{ amtComma(auction.win_bid.price) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-1 mb-4" v-else>
                    <div class="card my-auction mt-3">
                        <div class="card-body">
                            <div class="none-complete flex-column">
                                <div class="text-secondary opacity-50 d-flex align-items-center gap-3 my-3">
                                    <router-link to="/"  class="px-5 btn btn-outline-secondary btn-lg bc-wh">
                                        이용후기 등록 하기
                                    </router-link>
                                </div>
                                <span class="text-secondary opacity-50">내 차 판매 후, 이용후기를 작성해보세요.</span>
                            </div>
                        </div>
                    </div>
                    <router-link v-if="isMobileView" to="/alllist-do" class="anymation mt-5 btn shadow-sm border w-100 hp-50 d-flex align-items-center justify-content-center gap-2">다른 이용자들의 후기 둘러보기<span class="black-right-icon"></span></router-link>
                </div>
            </div>
        </div>
    </div>
        <Footer />
    </div>
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
  import Footer from "@/views/layout/footer.vue";
  import AuctionList from "@/views/import/AuctionList.vue";
  import { onMounted, onBeforeUnmount, ref ,computed} from 'vue';
  import { useRouter } from 'vue-router';
  import { setRandomPlaceholder } from '@/hooks/randomPlaceholder';
  import { initReviewSystem } from '@/composables/review';
  import { cmmn } from '@/hooks/cmmn';
  import { useStore } from 'vuex';
  import useAuctions from "@/composables/auctions";
  import { initPostSystem } from "@/composables/posts";
  const { posts, getPosts,getBoardCategories } = initPostSystem();
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

  const store = useStore();
  const user = computed(() => store.getters['auth/user']);
  const { wicas } = cmmn();
  const { getUserReview , userDeleteReview , reviewsData , reviewPagination } = initReviewSystem(); 
  const { auctionsData, getAuctions , pagination } = useAuctions();
  const { amtComma , splitDate , getDayOfWeek} = cmmn();
  const router = useRouter();
  const isMobileView = ref(window.innerWidth <= 991);

  function navigateToDetail(auctionId) {
      router.push({ name: 'user.create-review', params: { id: auctionId } });
  }
  
  const checkScreenWidth = () => {
      if (typeof window !== 'undefined') {
          isMobileView.value = window.innerWidth <= 991;
      }
  };
  
  onMounted(async() => {
    await getBoardCategories(); // 카테고리 로드
    await fetchPosts(); // 게시물 로드
      await getAuctions(1,true);
      setRandomPlaceholder();
      window.addEventListener('resize', checkScreenWidth);
      checkScreenWidth();
      console.log("리뷰데이터",auctionsData);
  });
  
  onBeforeUnmount(() => {
      window.removeEventListener('resize', checkScreenWidth);
  });
  </script>

<style scoped>
p {
    margin-top: 0;
    margin-bottom: 0rem !important;
}
@media (max-width: 575px) {
    .styled-div .content{ left:0% !important }
}
@media (max-width: 991px){
    .layout-container02 {
        display: flex !important;
        gap: 2rem !important;
        flex-direction: column;
        flex-wrap: nowrap;
    }
    .styled-div .content{
        margin-top: 0px !important;
    }
    .none-content{
        padding: 10px 40px 35.5px !important;
    }
    .scrollable-content{
        padding: 5px 5px 5px !important;
    }
    .blue-box02{
        margin-right:0px !important;
    }
    .box{
        margin-right:0px !important;
    }
}
@media (max-width: 991px){
    .app-style{
        display: none !important;
    }
    .content{
        margin-top: 0px !important;
    }
    .mt-5{
        margin-top: 10px !important;
    }
    .my-4{
        margin-top: 0px !important;
    }
}
@media (max-width: 1200px){
    .container {
        padding-right: 0px;
        padding-left: 0px;
    }
}
@media (max-width: 991px){
.container {
    --bs-gutter-x: 0rem;
}
.card-body {
        padding: 0px;
    }
}
@media (max-width: 991px){
.layout-container02{
    flex-direction: column-reverse;
}
.styled-div .content{
    left: -115px ;
    top:-15px ;
}
}
.styled-div {
    width: 100%;
    margin-top: 57px;
    border-radius: 0px;
    background-color: #f5f5f6;
    overflow: hidden; 
    position: relative; 
}
.ml-auto{
    margin-left: auto;
}
.styled-div .styled-img {
    width: 100%;
    height: 470px;
    object-fit: cover; 
    object-position: center; 
}
.input-container-search {
    position: relative;
    display: flex;
    align-items: center;
}


.row-cols-md-2 .complete-car {
flex: 0 0 50%; 
max-width: 50%; 
}


@media (max-width: 768px) {
.row-cols-md-2 .complete-car {
    flex: 0 0 50%;
    max-width: 50%;
}
}

.card-img-top-ty02 {
width: 100%;
height: 190px;
object-fit: cover;
object-position: center;
border-radius: 10px;
}
@media (max-width: 767px){
.col-md-6 {
    flex: 0 0 auto;
    width: 50%;
}
}
@media (max-width: 400px){
.col-md-6 {
    flex: 0 0 auto;
    width: 100%;
}
}
@media (min-width: 768px) {
.card-img-top-ty02 {
    height: 250px; 
}
}
.bid-bc {
background: none;
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
.tbl_basic table tr td {
    padding:20px 5px !important;
}
.styled-div .content {
position: absolute;
top: -3px;
left: -15%;
width: 100%;
height: 100%;
display: flex;
flex-direction: row;
justify-content: center;
padding: 50px;
color: white;
align-items: center;
}

.layout-container02 {
display: grid;
grid-template-columns: repeat(2, 1fr);
gap: 20px; 
}

.styled-div .top-content {
display: flex;
justify-content: space-between; 
align-items: center;
margin-bottom: 10px; 
}

.styled-div .right-content input {
padding: 10px;
border-radius: 5px;
border: none;
}

.styled-div .bottom-content {
text-align: center;
}

.styled-input {
width: 439px;
height: 40px;
padding: 10px 10px 10px 55px;
border-radius: 5px;
border: 1px solid #ccc;
font-size: 16px;
color: black;
background-color: #fff;
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
outline: none;
transition: border-color 0.3s;
}

.styled-input:focus {
border-color: #66afe9;
}

.tags {
display: flex;
gap: 10px;
margin-top: 10px;
justify-content: flex-start;
width: 439px;
flex-wrap: wrap;
}

.tag {
display: inline-block;
padding: 5px 19px;
border-radius: 20px;
background-color: white;
color: #5d5d5d;
font-size: 14px;
font-weight: bold;
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
border: 1px solid #ccc;
transition: background-color 0.3s, color 0.3s;
}

.tag:hover {
background-color: #fae0e0;
color: white;
}

.blue-box02 {
padding: 0 15px !important;
width: auto !important;
border-radius: 6px !important;
}
.box {
padding: 0 15px !important;
width: auto !important;
border-radius: 6px !important;
}
@media (min-width: 992px){

.col-md-6 {}
}

.animated-text {
    opacity: 0;
    transform: translateY(20px);
    animation: slideUp 1s forwards;
    animation-delay: 0.5s;
}

.animated-button {
    opacity: 0;
    transform: translateX(-20px);
    animation: slideIn 1s forwards;
    animation-delay: 1s;
}

.animated-auction {
    opacity: 0;
    transform: scale(0.9);
    animation: scaleUp 0.5s forwards;
}

@keyframes slideUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes scaleUp {
    to {
        opacity: 1;
        transform: scale(1);
    }
}
.tbl_basic table tr td{
    text-align: left;
}
.line-none{
    line-height:0 !important;
}
.scrollable-content .complete-car:nth-child(1) .animated-auction { animation-delay: 1.2s; }
.scrollable-content .complete-car:nth-child(2) .animated-auction { animation-delay: 1.4s; }
.scrollable-content .complete-car:nth-child(3) .animated-auction { animation-delay: 1.6s; }
.scrollable-content .complete-car:nth-child(4) .animated-auction { animation-delay: 1.8s; }
.scrollable-content .complete-car:nth-child(5) .animated-auction { animation-delay: 2s; }
</style>
