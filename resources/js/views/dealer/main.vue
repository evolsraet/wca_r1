<!--
    TODO: 조회시 조회수 2,3개 올라가는 문제 onmount문제? 같음
-->
<template>
    <div class="container">
        <div class="regiest-content">
            <div class="layout-container02">
                <!-- 딜러 프로필 요약 정보 -->
                <div class="banner-top">
                    <div class="top-info">
                        현재 진행중인 경매<p class="tc-red">{{ ingCount }}</p> 건
                    </div>
                    <div class="styled-div">
                        <div class="profile dealer-mp">
                            <div class="dealer-info">
                                <img src="../../../img/myprofile_ex.png" alt="Profile Image" class="main-profile">
                                <div class="deal-info">
                                    <p class="tc-light-gray">{{ user.dealer.company }}</p>
                                    <p>딜러 <span class="fw-medium">{{ user.dealer.name }}</span>님</p>
                                    <p class="restar">(4.5점)</p>
                                    <p class="no-bidding mt-3"><span>입찰 불가</span></p>
                                </div>
                            </div>
                            <div class="footer mob-info">
                                <p class="tc-light-gray mt-3">입찰가능 유효시간 2024.03.20</p>
                            </div>
                            <div class="auction-info">
                                <p class="bold-20-font">현재 진행중인 경매가<br><span class="tc-red me-2">{{ ingCount }}</span>건 있습니다</p>
                                <div style="display: flex; align-items: flex-end;">
                                    <div class="car-image"></div>
                                    <p class="tc-light-gray mb-3">입찰가능 유효시간 2024.03.20</p>
                                </div>
                            </div>
                        </div>
                        <div class="activity-info bold-18-font">
                            <div class="item">
                                <p><span class="tc-red">100</span> 건</p>
                                <p class="interest-icon tc-light-gray normal-16-font">관심</p>
                            </div>
                            <div class="item">
                                <p><span class="tc-red">{{ auctionsData.length }}</span> 건</p>
                                <p class="bid-icon tc-light-gray normal-16-font">입찰</p>
                            </div>
                            <div class="item">
                                <p><span class="tc-red">6</span> 건</p>
                                <p class="suc-bid-icon tc-light-gray normal-16-font">낙찰</p>
                            </div>
                            <div class="item">
                                <p><span class="tc-red">{{ bidsCountByUser[user.dealer.user_id] || 0 }}</span> 건</p>
                                <p class="purchase-icon tc-light-gray normal-16-font">매입</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="enter-view text-start mb-2">
                        <h3 class="review-title">공지사항</h3>
                        <router-link :to="{ name: 'index.notices' }" class="btn-apply mt-0">전체보기</router-link>
                    </div>
                    <table class="table custom-border">
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
                    <div class="enter-view text-start mt-5 mb-2">
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
                                <td class="tc-light-gray"><a href="#" class="btn-apply">상세</a></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>24-03-15</td>
                                <td><span class="blue-box list-num">475192</span></td>
                                <td><span class="gray-bl-box">처리중</span></td>
                                <td class="tc-light-gray"><a href="#" class="btn-apply">상세</a></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>24-03-15</td>
                                <td><span class="blue-box list-num">475192</span></td>
                                <td><span class="red-box">완료</span></td>
                                <td class="tc-light-gray"><a href="#" class="btn-apply">상세</a></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>24-03-15</td>
                                <td><span class="blue-box list-num">475192</span></td>
                                <td><span class="gray-bl-box">처리중</span></td>
                                <td class="tc-light-gray"><a href="#" class="btn-apply">상세</a></td>
                            </tr>
                        </tbody>
                    </table>
                     </div>
                </div>
                </div>
                <div class="card completed-card border-0" :style="cardStyle" @click="toggleCard">
                    <div class="card-body">
                        <div class="enter-view mt-3">
                            <h5>선택 완료 차량</h5>
                            <router-link :to="{ name: 'dealer.bids' }" class="btn-apply">전체보기</router-link>
                        </div>
                        <span class="tc-light-gray">24시간 내 응대해 주세요!</span>
                        <!-- 차량이 존재 할 경우-->
                        <div v-if="filteredViewBids.length > 0">
                            <div class="complete-car" v-for="bid in filteredViewBids" :key="bid.id">
                                <div class="card my-auction mt-3">
                                    <input class="toggle-heart" type="checkbox">
                                    <label class="heart-toggle"></label>
                                    <div class="card-img-top-placeholder border-rad"></div>
                                    <div class="card-body">
                                        <h5 class="card-title"><span class="blue-box">무사고</span>{{bid.auctionDetails.car_no}}</h5>
                                        <div class="enter-view">
                                            <p class="card-text tc-light-gray">{{bid.auctionDetails.car_no}}</p>
                                            <span class="red-box-type02 pass-red" @click="navigateToDetail(bid)">자세히 보기</span>
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
                                        <span>선택 완료된 차량이 없습니다.</span>
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
import { ref, onMounted, computed } from 'vue';
import useBid from "@/composables/bids";
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import useAuctions from '@/composables/auctions'; // 경매 관련 작업을 위한 컴포저블

const myBidsCount = ref(0);
const store = useStore();
const router = useRouter(); 
const isExpanded = ref(false);

const toggleCard = () => {
    isExpanded.value = !isExpanded.value;
};

const { getAuctions, auctionsData, getAuctionById } = useAuctions(); // 경매 관련 함수를 사용
const { bidsData, getBids, viewBids, bidsCountByUser } = useBid();
const user = computed(() => store.state.auth.user);

const calculateMyBidsCount = () => {
    if (bidsData.value && user.value) {
        myBidsCount.value = bidsData.value.filter(bid => bid.user_id === user.value.id).length;
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

// Filter bids and fetch auction details
const filteredViewBids = ref([]);

const fetchFilteredViewBids = async () => {
    const filteredBids = viewBids.value.filter(bid => bid.status === 'ask');
    console.log('Filtered Bids:', filteredBids);
    const bidsWithDetails = await Promise.all(filteredBids.map(fetchAuctionDetails));
    filteredViewBids.value = bidsWithDetails;
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
});
</script>
