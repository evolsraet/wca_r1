<!--
    TODO: 조회시 조회수 2,3개 올라가는 문제 onmount문제? 같음
-->
<template>
    <div>
        <div class="regiest-content">
            <div class="layout-container02">
                <!-- 딜러 프로필 요약 정보 -->
                <div class="banner-top primary-background">
                    <div>
                        <div class="profile dealer-mp">
                           <!-- <div class="dealer-info">
                                <img src="../../../img/myprofile_ex.png" alt="Profile Image" class="main-profile">
                                <div class="deal-info">
                                    <p class="tc-light-gray">{{ user.dealer.company }}</p>
                                    <p>딜러 <span class="fw-medium">{{ user.dealer.name }}</span>님</p>
                                    <p class="restar">(4.5점)</p>
                                    <div>
                                        <p v-if="user.status === 'fail'" class="no-bidding mt-3"><span>입찰 불가</span></p>
                                        <p v-else-if="user.status === 'ok'" class="bidding mt-3"><span>입찰 가능</span></p>
                                    </div>
                                </div>
                            </div>-->

                        <div>
                            <p class="bold-20-font">현재 진행중인 경매가<br><span class="tc-red me-2">{{ bidsCountByUser[user.dealer.user_id] || 0 }}</span>건 있습니다</p>
                            <div class="w-50">
                                <p v-if="user.status === 'fail'" class="no-bidding mt-1 mb-1 shadow-sm"><span>입찰 불가</span></p>
                                <p v-else-if="user.status === 'ok'" class="bidding mt-1 mb-1 shadow-sm"><span>입찰 가능</span></p>
                            </div>
                            <p class="tc-light-gray mb-3 mt-2">입찰가능 유효시간 2024.03.20</p>
                        </div>
                            <div>
                                <div style="display: flex; align-items: flex-end;">
                                    <div class="car-image"></div>
                                </div>
                            </div>
                        </div>
                        <div class="slide-up-ani activity-info bold-18-font process mb-0">
                            <router-link :to="{  name: 'auction.index' }" class="item">
                            <p><span class="tc-red slide-up mb-0" ref="item1">0</span> 건</p>
                            <p class="interest-icon tc-light-gray normal-16-font mb-0">관심</p>
                            </router-link>
                            <router-link :to="{ name: 'auction.index' }" class="item">
                            <p><span class="tc-red mb-0" ref="item2">{{ bidsCountByUser[user.dealer.user_id] || 0 }}</span> 건</p>
                            <p class="bid-icon tc-light-gray normal-16-font mb-0">입찰</p>
                            </router-link>
                            <router-link :to="{  name: 'dealer.bids' }" class="item">
                            <p><span class="tc-red mb-0" ref="item3">{{ filteredViewBids.length }}</span> 건</p>
                            <p class="suc-bid-icon tc-light-gray normal-16-font mb-0">낙찰</p>
                            </router-link>
                         <!--   <div class="item">
                            <p><span class="tc-red" ref="item4">{{ bidsCountByUser[user.dealer.user_id] || 0 }}</span> 건</p>
                            <p class="purchase-icon tc-light-gray normal-16-font">완료</p>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="container content p-4">
                    <div class="container enter-view text-start mb-2">
                        <h3 class="review-title">공지사항</h3>
                        <router-link :to="{ name: 'index.notices' }" class="btn-apply mt-0">전체보기</router-link>
                    </div>
                    <table class="table custom-border">
                        <thead style="display: none;">
                            <tr>
                                <th class="tc-light-gray">날짜</th>
                                <th>제목</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="tc-light-gray note-date">2024-03-21</td>
                                <td>
                                    <span class="text-with-marker">부정행위 제보 처리 결과 공유 드립니다.</span>
                                </td>
                            </tr>
                            <tr class="tr-recent-new">
                                <td class="tc-light-gray note-date">2024-03-21</td>
                                <td>
                                    <span>2024 설 연휴 영업시간 안내</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="tc-light-gray note-date">2024-03-21</td>
                                <td>
                                    <span>신규 딜러가입 일시 중단 안내</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                 <!--   <div class="enter-view text-start mt-5 mb-2">
                        <h3 class="review-title">클레임 현황</h3>
                        <router-link :to="{ name: 'index.claim' }" class="btn-apply mt-0">전체보기</router-link>
                    </div>
                    <div class="o_table_mobile my-5">
            <div class="tbl_basic tbl_dealer">
                    <table class="table custom-border text-center">
                        <thead class="tr-style">
                            <tr>
                                <th>No.</th>
                                <th>등록일</th>
                                <th>매물번호</th>
                                <th>상태</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>24-03-15</td>
                                <td><span class="blue-box list-num">475192</span></td>
                                <td>접수</td>
                                <td class="tc-light-gray"><a href="#" class="btn-apply" @click.prevent="openAlarmModal">상세</a></td>
                            </tr>
                        </tbody>
                    </table>
                    <AlarmModal ref="alarmModal" />
                     </div>
                </div>-->
                </div>
                <div class="container p-3 my-4" :style="cardStyle" @click="toggleCard">
                    <div class="container card-body">
                        <div class="enter-view mt-3">
                            <h5>낙찰 완료 차량</h5>
                            <router-link :to="{ name: 'dealer.bids' }" class="btn-apply">전체보기</router-link>
                        </div>
                        <span class="tc-light-gray">24시간 내 응대해 주세요!</span>
                        <!-- 차량이 존재 할 경우-->
                        <div v-if="filteredViewBids.length > 0" class="container">
                            <div class="row">
                            <div class="col-md-6 p-2" v-for="bid in filteredViewBids" :key="bid.id">
                                <div class="card my-auction mt-3">
                                    <div class="card-img-top-placeholder border-rad"></div>
                                    <div class="card-body">
                                        <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                        <p>2020년 / 2.4km / 무사고</p>
                                        <p class="tc-light-gray">현대 쏘나타 (DN8)</p>
                                       <!-- <p class="card-text tc-light-gray">{{bid.auctionDetails.car_no}}</p>-->
                                        <h5 class="card-title"><span class="blue-box">무사고</span></h5>
                                    </div>
                                </div>
                            </div>
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
.layout-container02{
    grid-template-columns: 1fr 1fr !important;
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
}
}
</style>