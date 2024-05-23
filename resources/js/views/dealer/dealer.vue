<template>
    <div class="container">
        <div class="main-contenter">
            <div class="review">
                <div class="mov-review my-5">
                    <div class="apply-top02 text-start">
                        <div class="search-type3">
                            <h3 class="review-title mov-hidden">낙찰 완료 차량<br><span class="mb-2 tc-light-gray">24시간 내 응대해 주세요!</span></h3>
                            <input type="text" placeholder="모델명,차량번호,지역">
                            <button type="button" class="search-btn">검색</button>
                        </div>
                    </div>
                    <div class="registration-content">
                        <div class="text-start"></div>
                        <div class="text-end pd-10">
                            <select class="form-select select-rank" aria-label="최근 등록 순">
                                <option selected>최근 등록 순</option>
                                <option value="1">가격 낮은 순</option>
                                <option value="2">가격 높은 순</option>
                                <option value="3">연식 오래된 순</option>
                                <option value="4">연식 최신 순</option>
                            </select>
                        </div>
                    </div>
                    <div class="container my-5">
                        <div class="row">
                            <div class="col-md-3 p-2 mb-2 shadow-hover" v-for="bid in filteredBids" :key="bid.id"  @click="navigateToDetail(bid)">
                                <div class="card my-auction">
                                    <input class="toggle-heart" type="checkbox" checked/>
                                    <label class="heart-toggle"></label>
                                    <div class="selection-complete-img"></div> 
                                    <div class="wait-selection">낙찰가 {{ bid.price }}</div>
                                    <div class="card-body">
                                        <h5 class="card-title"><span class="blue-box">무사고</span>{{ bid.auctionDetails ? bid.auctionDetails.car_no : '차량 정보 없음' }}</h5>
                                        <p class="card-text tc-light-gray">현대 쏘나타(DN8){{ bid.auctionDetails ? bid.auctionDetails.model : '모델 정보 없음' }}</p>
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
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import useBid from "@/composables/bids";
import useAuctions from '@/composables/auctions';

const router = useRouter(); 
const store = useStore(); // Vuex 스토어 인스턴스
const { getAuctions, getAuctionById } = useAuctions();
const { bidsData, getBids } = useBid();
const user = computed(() => store.getters['auth/user']); // 현재 사용자를 가져오는 계산된 속성

const filteredBids = ref([]);

const fetchAuctionDetails = async (bid) => {
    try {
        const auctionDetails = await getAuctionById(bid.auction_id);
        console.log('Auction Details for Bid ID:', bid.id, auctionDetails);
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

function navigateToDetail(bid) {
    console.log("Navigate to Detail:", bid.auction_id);
    router.push({ name: 'AuctionDetail', params: { id: bid.auction_id } });
}

const fetchFilteredBids = async () => {
    await getBids(); 
    const filteredBidsData = bidsData.value.filter(bid => bid.status === 'ask');
    console.log('Filtered Bids:', filteredBidsData); 
    const bidsWithDetails = await Promise.all(filteredBidsData.map(fetchAuctionDetails));
    filteredBids.value = bidsWithDetails.filter(bid => bid.auctionDetails && bid.auctionDetails.bid_id === user.value.id);
    console.log('Bids with Auction Details:', filteredBids.value); 
};

onMounted(async () => {
    await getAuctions();
    await fetchFilteredBids();
});
</script>

<style scoped>
.pd-10{
    padding: 10px;
}
.search-type2 span{
    font-size: 17px;
}
</style>
