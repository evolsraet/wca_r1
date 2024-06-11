<!--
    TODO: 조회시 조회수 2,3개 올라가는 문제 onmount문제? 같음
-->
<template>
    <div class="regiest-content">
        <div class="banner-top">
            <div class="styled-div mt-0">
                <img src="../../../img/main_banner.png" class="styled-img" alt="배너 이미지">
                <div class="content d-flex">
                    <div>
                        <h2 class="fw-bolder mb-4">내 차 판매는 <br>위카에서!</h2>
                        <router-link :to="{ name: 'index.allreview' }" href="" class="btn-apply">더 알아보기</router-link>
                    </div>
                    <div>
                        <input type="text" class="styled-input" placeholder="검색">
                    <div>
                        <div class="tags">
                            <div class="tag">무사고</div>
                            <div class="tag">최신형</div>
                            <div class="tag">국산차</div>
                            <div class="tag">흰색</div>
                            <div class="tag">실차주</div>
                            <div class="tag">경매진행</div>
                            <div class="tag">진단평가</div>
                            <div class="tag">국산차</div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container my-4">
            <div class="layout-container02">
                <!-- 딜러 프로필 요약 정보 -->
                <div class="content">
                    <div class="row row-cols-1 row-cols-md-2">
                        <div class="apply-top text-start mb-0">
                        <h3 class="review-title">이용후기</h3>
                        <router-link :to="{ name: 'index.allreview' }" href="" class="btn-apply">전체보기</router-link>
                        </div>
                        <div class="complete-car">
                                <div class="card my-auction mt-3">
                                    <input class="toggle-heart" type="checkbox">
                                    <label class="heart-toggle"></label>
                                    <div>
                                        <div class="card-img-top-ty02 border-rad"></div>
                                        <div class="card-body">
                                                <p>더 뉴 그랜저 IG 2.5 가솔린 르블랑</p>
                                                <p class="card-text tc-light-gray">2020년 / 2.4 km / 무사고</p>
                                                <p class="card-text tc-light-gray">현대 소나타(DN8)</p>
                                            <h5 class="card-title"><span class="blue-box">무사고</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="complete-car">
                                <div class="card my-auction mt-3">
                                    <input class="toggle-heart" type="checkbox">
                                    <label class="heart-toggle"></label>
                                    <div>
                                        <div class="card-img-top-ty02 border-rad"></div>
                                        <div class="card-body">
                                                <p>더 뉴 그랜저 IG 2.5 가솔린 르블랑</p>
                                                <p class="card-text tc-light-gray">2020년 / 2.4 km / 무사고</p>
                                                <p class="card-text tc-light-gray">현대 소나타(DN8)</p>
                                            <h5 class="card-title"><span class="blue-box">무사고</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                </div>
                <div class="border-0" :style="cardStyle" @click="toggleCard">
                    <div class="card-body">
                        <div class="enter-view mt-3">
                            <h5>낙찰 완료 차량</h5>
                            <router-link :to="{ name: 'dealer.bids' }" class="btn-apply">전체보기</router-link>
                        </div>
                        <!-- 차량이 존재 할 경우-->
                        <div v-if="filteredViewBids.length > 0">
                            <div class="complete-car" v-for="bid in filteredViewBids" :key="bid.id">
                            
                            </div>
                        </div>
                        <!-- 선택 완료된 차량이 없는경우-->
                        <div v-else>
                            <div class="complete-car">
                                <div class="card my-auction mt-3">
                                    <div class="none-complete">
                                        <span class="tc-light-gray">선택 완료된 차량이 없습니다.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    
    <Footer />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import useBid from "@/composables/bids";
import Footer from "@/views/layout/footer.vue"
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import AlarmModal from '@/views/modal/AlarmModal.vue';
import useAuctions from '@/composables/auctions'; // 경매 관련 작업을 위한 컴포저블
const item1 = ref(null);
const item2 = ref(null);
const item3 = ref(null);
const item4 = ref(null);
const myBidsCount = ref(0);
const store = useStore();
const router = useRouter(); 
const isExpanded = ref(false);
const toggleCard = () => {
    isExpanded.value = !isExpanded.value;
};
const alarmModal = ref(null);
const { getAuctions, auctionsData, getAuctionById } = useAuctions(); // 경매 관련 함수를 사용
const { bidsData, getBids, viewBids, bidsCountByUser } = useBid();
const user = computed(() => store.state.auth.user);
const calculateMyBidsCount = () => {
    if (bidsData.value && user.value) {
        myBidsCount.value = bidsData.value.filter(bid => bid.user_id === user.value.id).length;
    }
};
const openAlarmModal = () => {
console.log("openAlarmModal called");
if (alarmModal.value) {
  alarmModal.value.openModal();
}
};
const ingCount = computed(() => {
    return auctionsData.value.filter(auction => auction.status === 'ing').length;
});
const alertNoVehicle = (event) => {
    event.preventDefault();
    if (viewBids.value.length === 0) {
        alert("선택 완료된 차량이 없습니다.");
    } else {
        router.push({ name: 'dealer.bids' });
    }
};
const fetchAuctionDetails = async (bid) => {
    try {
        const auctionDetails = await getAuctionById(bid.auction_id);
        console.log('Auction Details for Bid ID:', bid.auction_id, auctionDetails);
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
const filteredViewBids = ref([]);
const fetchFilteredViewBids = async () => {
    // 필터링을 제거하고 모든 입찰을 가져옵니다.
    console.log('Original Bids:', bidsData.value);
    const bidsWithDetails = await Promise.all(bidsData.value.map(fetchAuctionDetails));
    filteredViewBids.value = bidsWithDetails.filter(bid => bid.auctionDetails && bid.auctionDetails.bid_id === user.value.id);
    console.log('Bids with Auction Details:', filteredViewBids.value);
};


function navigateToDetail(bid) {
    console.log("Navigate to Detail:", bid.auction_id);
    router.push({ name: 'AuctionDetail', params: { id: bid.auction_id } });
}
onMounted(async () => {
    await getBids();
    calculateMyBidsCount();
    await getAuctions();
    await fetchFilteredViewBids();
    setTimeout(() => {
    item1.value.classList.add('visible');
  }, 0);
  setTimeout(() => {
    item2.value.classList.add('visible');
  }, 200);
  setTimeout(() => {
    item3.value.classList.add('visible');
  }, 400);
  setTimeout(() => {
    item4.value.classList.add('visible');
  }, 800);
});
</script>
<style scoped>
p {
    margin-top: 0;
    margin-bottom: 0rem !important;
}

@media (max-width: 640px){
    .layout-container02 {
        display: flex !important;
        gap: 2rem !important;
        flex-direction: column;
        flex-wrap: nowrap;
    }
}
.styled-div {
    width: 100%;
    height: 40vh;
    margin-top: 57px;
    border-radius: 0px;
    background-color: #f5f5f6;
    overflow: hidden; /* 이미지가 div를 넘치지 않도록 설정 */
    position: relative; /* content 위치 조정을 위해 추가 */
}

.styled-div .styled-img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* 이미지가 div를 꽉 채우도록 설정 */
    object-position: center; /* 이미지의 중심을 div의 중심에 맞추기 */
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
    padding: 10px 10px 10px 60px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
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
    background-color: #66afe9; /* 호버 시 배경색 변경 */
    color: white; /* 호버 시 글자색 변경 */
}
</style>