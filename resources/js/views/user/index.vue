<template>
    <div>
      <div v-if="!isMobileView" class="banner-top">
          <div class="styled-div mt-0">
              <img src="../../../img/main_banner.png" class="styled-img" alt="배너 이미지">
              <div class="content d-flex">
                  <div>
                      <h1 class="fw-bolder mb-4 mt-3 lh-base animated-text">내 차 판매는 <br>위카에서 !</h1>
                  </div>
                  <div>
                      <p>&nbsp;</p>
                  </div>
              </div>
          </div>
      </div>
      <div>
          <div class="regiest-content">
              <div class="container my-4">
                  <div v-if="isMobileView" class="d-flex justify-content-between align-items-sm-end p-3 pb-0 mt-4 mb-0">
                      <h2 class="fw-bolder lh-base animated-text">내 차 판매는 <br>위카에서 !</h2>
                      <router-link :to="{ name: 'auction.index' }" href="" class="btn-apply p-0 m-0 animated-button">더 알아보기</router-link>
                  </div>
                  <div class="layout-container02 mt-5">
                      <div class="p-2">
                          <div class="apply-top text-start mb-0">
                              <h3 class="review-title">이용후기</h3>
                              <router-link :to="{ name: 'user.review' }" href="" class="btn-apply">전체보기</router-link>
                          </div>
                          <div  v-if="auctionsData.length > 0" class="container">
                              <div class="row">
                                  <div class="col-md-6 p-2 hover-ac pointer" v-for="auction in auctionsData.slice(0,2)" :key="auction.id" @click="navigateToDetail(auction.id)">
                                      <div class="card my-auction mt-3">
                                          <div>
                                            <div class="card-img-top-placeholder">
                                                <img src="../../../img/car_example.png">
                                            </div> 
                                              <div class="card-body">
                                                  <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                                  <p>2020년 | 2.4km | 무사고</p>
                                                  <div class="d-flex justify-content-between align-items-baseline">
                                                    <div>
                                                        <span class="blue-box border-6">보험 3건</span><span class="gray-box border-6">재경매</span>
                                                        <h5 class="tc-red fs-5">{{ amtComma(auction.win_bid.price) }}</h5>
                                                    </div>
                                                </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="row row-cols-1 row-cols-md-1" v-else>
                              <div class="card my-auction mt-3">
                                  <div class="card-body">
                                      <div class="none-complete">
                                          <span class="text-secondary opacity-50">아직 작성가능한 이용후기가 없습니다.</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <AuctionList />
                  </div>
              </div>
          </div>
      </div>
      <Footer />
    </div>
  </template>
  
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


  const store = useStore();
  const user = computed(() => store.getters['auth/user']);
  const { wicas } = cmmn();
  const { getUserReview , userDeleteReview , reviewsData , reviewPagination } = initReviewSystem(); 
  const { auctionsData, getAuctions , pagination } = useAuctions();
  const { amtComma , splitDate , getDayOfWeek} = cmmn();
  const router = useRouter();
  const isMobileView = ref(window.innerWidth <= 640);

  function navigateToDetail(auctionId) {
      router.push({ name: 'user.create-review', params: { id: auctionId } });
  }
  
  const checkScreenWidth = () => {
      if (typeof window !== 'undefined') {
          isMobileView.value = window.innerWidth <= 640;
      }
  };
  
  onMounted(async() => {
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

@media (max-width: 650px){
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
@media (max-width: 650px){
.layout-container02{
    flex-direction: column-reverse;
}
}
.styled-div {
    width: 100%;
    height: 40vh;
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
    height: 100%;
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

.styled-div .content {
position: absolute;
top: 0;
left: 0;
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

.scrollable-content .complete-car:nth-child(1) .animated-auction { animation-delay: 1.2s; }
.scrollable-content .complete-car:nth-child(2) .animated-auction { animation-delay: 1.4s; }
.scrollable-content .complete-car:nth-child(3) .animated-auction { animation-delay: 1.6s; }
.scrollable-content .complete-car:nth-child(4) .animated-auction { animation-delay: 1.8s; }
.scrollable-content .complete-car:nth-child(5) .animated-auction { animation-delay: 2s; }
</style>
