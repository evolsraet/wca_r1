<template>
  <div>
    <!-- 공통 UI 구조 -->
    <div class="border-0" @click="toggleCard">
      <div class="card-body">
        <div class="enter-view">
          <h5 v-if="isUser">내 매물관리</h5>
          <div v-if="isDealer">
          <h5>탁송지 미등록 매물</h5>
          <p class="text-sendary opacity-50">낙찰된 매물중 탁송지 미등록 매물입니다</p>
           </div>
          <router-link :to="{ name: 'auction.index' }" class="btn-apply">전체보기</router-link>
        </div>

        <!-- 차량이 존재할 경우 -->
        <div v-if="filteredAuctionsData.length > 0" class="scrollable-content mt-4 mb-4">
          <div v-for="(auction, index) in filteredAuctionsData"
            :key="auction.id"
            @click="navigateToDetail(auction)"
            :style="getAuctionStyle(auction)"
            :class="['animated-auction', `delay-${index}`]">
            <div class="complete-car">
              <div class="my-auction">
                <div class="bid-bc p-2">
                  <ul v-if="isUser" class="px-0 inspector_list max_width_900">
                    <li class="m-auto">
                      <div>
                        <div class="d-flex gap-4 align-items-center">
                          <div class="img_box">
                            <img src="../../../img/car_example.png" alt="차량 사진" class="mb-2">
                          </div>
                          <h5 class="mb-0 fs-4">{{ auction.car_no }}</h5>
                          <p :class="getStatusClass(auction.status)" class="ml-auto">
                            <span>{{ wicas.enum(store).toLabel(auction.status).auctions() }}</span>
                          </p>
                        </div>
                      </div>
                    </li>
                  </ul>
                  <ul v-else-if="isDealer" class="px-0 inspector_list max_width_900">
                    <div class="ribbon-holder">
                      <div class="ribbon">{{ amtComma(auction.final_price) }}</div>
                    </div>
                    <li>
                      <div>
                        <div class="d-flex gap-4 align-items-center app-d-flex">
                          <div class="col-auto d-flex flex-column align-items-center">
                          <div class=" img_box">
                            <img src="../../../img/car_example.png" alt="차량 사진" class="mb-2">
                          </div>
                          <h5 class="mt-2 mb-0">{{ auction.car_no }}</h5>
                        </div>
                          <div class="col-auto">
                            <p class="mb-0">우편번호 : {{ addrInfo.addr_post }}</p>
                            <!--<h5 class="mb-0 fs-4 tc-red">{{ auction.car_no }}</h5>-->
                            <p class="mb-0">주 소 : {{ addrInfo.addr1+' , '+addrInfo.addr2 }}</p>
                          </div>
                          <div class="col text-end">
                            <button @click.stop="dealerAddrConnect(auction.id)" class="btn btn-primary">주소지 등록</button>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          
          <router-link v-if="isUser" :to="{ name: 'home' }" class="bid-bc p-3">
            <ul class="px-0 inspector_list max_width_900">
              <li class="m-auto">
                <div>
                  <p class="text-secondary opacity-50 d-flex justify-content-center">새 차량 등록하기<span class="ms-2 icon-auction-plus"></span></p>
                </div>
              </li>
            </ul>
          </router-link>
        </div>

        <div v-else>
          <div class="complete-car my-4">
            <div class="my-auction none-content">
              <div class="none-complete-img"></div>
              <div class="d-flex align-items-center flex-column gap-3">
                <div class="text-secondary opacity-50 d-flex align-items-center flex-column gap-5">
                  <h4 v-if="isUser">등록된 차가 없어요</h4>
                  <h5 v-if="isUser">차량 등록 후, 경매를 시작해보세요.</h5>
                  <h4 v-if="isDealer" class="mt-4 text-center">탁송지를 등록해야 할 매물이 없습니다.</h4>
                  <div v-if="isUser" class="px-2">
                  <router-link :to="{ name:'home' }" class="w-100 btn primary-btn btn-apply-ty02 justify-content-between p-4">
                    <span>차량 등록하기</span>
                  </router-link>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { setRandomPlaceholder } from '@/hooks/randomPlaceholder';
import useAuctions from "@/composables/auctions";
import { initReviewSystem } from '@/composables/review';
import { cmmn } from '@/hooks/cmmn';
import { useStore } from 'vuex';

const { wicas ,amtComma } = cmmn();
const { auctionsData, getAuctions, setdestddress } = useAuctions();
const { getUserReview, deleteReviewApi, reviewsData } = initReviewSystem();
const router = useRouter();
const isMobileView = ref(window.innerWidth <= 640);
const store = useStore();
const addrInfo = ref({});

const user = computed(() => store.getters['auth/user']);

const isDealer = computed(() => user.value?.roles?.includes('dealer'));
const isUser = computed(() => user.value?.roles?.includes('user'));

function navigateToDetail(auction) {
  router.push({ name: 'AuctionDetail', params: { id: auction.id } });
}

const checkScreenWidth = () => {
  if (typeof window !== 'undefined') {
    isMobileView.value = window.innerWidth <= 640;
  }
};

function getAuctionStyle(auction) {
  const validStatuses = ['done', 'wait', 'ing', 'diag'];
  return validStatuses.includes(auction.status) ? { cursor: 'pointer' } : {};
}

function getStatusClass(status) {
  switch (status) {
    case 'chosen':
      return 'blue-box02 bg-opacity-50';
    case 'cancel':
      return 'box bg-secondary';
    case 'wait':
      return 'blue-box02';
    case 'diag':
      return 'box bg-success bg-opacity-75';
    case 'ask':
      return 'box bg-warning bg-opacity-75';
    case 'ing':
      return 'box bg-danger';
    case 'done':
      return 'box bg-black';
    case 'dlvr':
      return 'box bg-info';
    default:
      return '';
  }
}

const filteredAuctionsData = computed(() => {
  if (isDealer.value) {
    return auctionsData.value.filter(auction => auction.status === 'chosen');
  }
  return auctionsData.value;
});

onMounted(async () => {
  await getAuctions();
  const user = store.getters['auth/user'];
  const userId = user.id;
  console.log(userId);
  await getUserReview(userId);
  console.log(reviewsData.value);
  setRandomPlaceholder();
  window.addEventListener('resize', checkScreenWidth);
  checkScreenWidth();


  //탁송지 미등록 주소 설정
  if(isDealer.value){
      addrInfo.value = {
        addr1 : user.dealer.company_addr1,
        addr2 : user.dealer.company_addr2,
        addr_post : user.dealer.company_post,
      }
  }
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkScreenWidth);
});

const dealerAddrConnect = (auctionId) => {
  setdestddress(auctionId,addrInfo.value);
}
</script>

<style scoped>
.none-content{
  padding: 0px !important;
}
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
    .enter-view{
      display: none;
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

.scrollable-content {
    height: auto;
    overflow-y: hidden;
    padding: 30px 30px 30px 30px;
    background-color: $sub-color02;
}

.styled-div .content {
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 100%;
display: flex;
flex-direction: row;
justify-content: space-evenly;
padding: 50px;
color: white;
align-items: center;
}

.layout-container02 {
display: grid;
grid-template-columns: repeat(2, 1fr); /* 2열 설정 */
gap: 20px; /* 행과 열 사이의 간격 */
}

.styled-div .top-content {
display: flex;
justify-content: space-between; /* 양쪽으로 나눔 */
align-items: center;
margin-bottom: 10px; /* 아래 공간 확보 */
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
.none-complete-img{
  width: 133px !important;
  height: 146px !important;
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
