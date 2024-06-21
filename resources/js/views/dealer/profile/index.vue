<template>
    <div class="container">
        <div class="regiest-content">
            <!-- 딜러 프로필 요약 정보 -->
            <div class="banner-top ">
                <div class="top-info">현재 진행중인 경매<p class="tc-red">{{ bidsCountByUser[user.dealer.user_id] || 0 }} </p> 건</div>
                <div class="styled-div">
                    <div class="profile">
                        <div class="dealer-info">
                            <img src="../../../../img/profile_dom.png" width="100%" alt="Profile Image" class="main-profile">
                            <div class="deal-info">
                                <p class="tc-light-gray">{{ user.dealer.company }} </p>
                                <p>딜러 <span class="fw-medium">{{ user.dealer.name }}</span>님</p>
                                <p class="restar">4.5점</p>
                                <p class="no-bidding"><span>입찰 불가</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="activity-info bold-18-font mt-5">
                        <div class="item">
                            <p><span class="tc-red">0</span> 건</p>
                            <p class="interest-icon tc-light-gray normal-16-font">관심</p>
                        </div>
                        <div class="item">
                            <p><span class="tc-red">{{ bidsCountByUser[user.dealer.user_id] || 0 }}</span> 건</p>
                            <p class="bid-icon tc-light-gray normal-16-font">입찰</p>
                        </div>
                        <div class="item">
                            <p><span class="tc-red">{{ filteredViewBids.length }}</span>건</p>
                            <p class="suc-bid-icon tc-light-gray normal-16-font">낙찰</p>
                        </div>
                        <div class="item">
                            <p><span class="tc-red">{{ bidsCountByUser[user.dealer.user_id] || 0 }}</span> 건</p>
                            <p class="purchase-icon tc-light-gray normal-16-font">매입</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inform-content">
                <div class="tab-nav my-4">
                    <ul>
                        <li class="col"><a href="#" @click="setCurrentTab('dealerInfo')" :class="{ active: currentTab === 'dealerInfo' }">딜러 정보</a></li>
                        <li class="col"><a href="#" @click="setCurrentTab('pastAuctions')" :class="{ active: currentTab === 'pastAuctions' }">지난 낙찰건</a></li>
                    </ul>
                </div>
            </div>
            <!-- 딜러 정보 -->
            <div v-if="currentTab === 'dealerInfo'">
                <div class="dealinfo-content ">
                    <div class="my-info">
                        <div class="info-item">
                            <p class="building"></p>
                            <p>{{ user.dealer.company }}</p>
                        </div>
                        <div class="info-item">
                            <div class="clip-card"></div>
                            <p>{{ user.dealer.company_duty }}</p>
                        </div>
                        <div class="info-item">
                            <div  class="phone"></div>
                            <p>010-1234-1234</p>
                        </div>
                        <div class="info-item">
                            <div class="location"></div >
                            <p>
                                <span>{{ user.dealer.company_addr1 }},</span>
                                <span>{{ user.dealer.company_addr2 }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="intro mt-3">
                    <h5 class="tc-light-gray mb-3">소개</h5>
                    <p>{{ user.dealer.introduce }}</p>
                </div>
            </div>
            <!-- 지난 낙찰건 -->
            <div class="p-3" v-if="currentTab === 'pastAuctions'">
                <div class="enter-view mt-3 mb-2">
                    <h5>지난 낙찰건</h5>
                </div>
                <span class="tc-light-gray">딜러가 낙찰받은 매물들이에요.</span>
                <div v-if="filteredViewBids.length > 0">
                    <div class="complete-car mt-3"  v-for="bid in filteredViewBids" :key="bid.id">
                        <div class="card my-auction mt-3">
                            <div class="card-img-top-placeholder border-rad"></div>
                            <div class="card-body">
                                <div class="enter-view align-items-center ">
                                    <h5 class="card-title my-0">현대 쏘나타 (DN8)</h5>
                                    <p class="tc-red bold-18-font">{{ amtComma(bid.price) }}만원</p>
                                </div>
                                <div class="enter-view">
                                    <p class="card-text tc-light-gray">{{bid.auctionDetails.car_no}}</p>
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
import { useStore } from 'vuex';
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';
import Footer from "@/views/layout/footer.vue"

const currentTab = ref('dealerInfo');
const { amtComma } = cmmn();
const store = useStore();
const { getAuctions, auctionsData, getAuctionById } = useAuctions(); // 경매 관련 함수를 사용
const isExpanded = ref(false);
const ingCount = computed(() => {
    return auctionsData.value.filter(auction => auction.status === 'ing').length;
});

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

const toggleCard = () => {
    isExpanded.value = !isExpanded.value;
};
const setCurrentTab = (tab) => {
    currentTab.value = tab;
};
const { bidsData, getBids, viewBids, bidsCountByUser } = useBid();
const user = computed(() => store.state.auth.user);

// 낙찰된 건수 필터링
const filteredViewBids = ref([]);

const fetchFilteredViewBids = async () => {
    console.log('Original Bids:', bidsData.value);
    const bidsWithDetails = await Promise.all(bidsData.value.map(fetchAuctionDetails));
    console.log('Bids with Details:', bidsWithDetails);  // Bids with Details 데이터 출력
    filteredViewBids.value = bidsWithDetails.filter(bid => {
        console.log('Checking Bid:', bid);  // 각 Bid 데이터 출력
        return bid.auctionDetails && bid.auctionDetails.bid_id === user.value.id;
    });
    console.log('Bids with Auction Details:', filteredViewBids.value);
};

onMounted(async () => {
    await getBids();
    console.log('Loaded Bids:', bidsData.value);  // Bids 데이터 출력
    await getAuctions();
    await fetchFilteredViewBids();
});
</script>
