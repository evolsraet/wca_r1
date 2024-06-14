<template>
    <div class="container">
        <div class="main-contenter">
            <div class="review">
                <div class="mov-review my-5">
                    <div class="registration-content">
                        <div class="apply-top02 text-start">
                            <div class="search-type3">
                                <input type="text" class="w-100" placeholder="모델명,차량번호,지역">
                                <button type="button" class="search-btn">검색</button>
                            </div>
                        </div>
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
                        <div v-if="filteredBids.length > 0" class="row">
                            <div class="col-md-3 p-2 mb-2 shadow-hover" v-for="bid in filteredBids" :key="bid.id"  @click="navigateToDetail(bid)">
                                <div class="card my-auction">
                                    <div class="card-img-top-placeholder"></div> 

                                    <div class="card-body">
                                        <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                        <p>2020년 / 2.4km / 무사고</p>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="blue-box">보험 3건</span><span class="gray-box">재경매</span>
                                            </div>
                                            <h5 class="tc-red">{{ amtComma(bid.price) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="tc-red">선택 차량이 존재하지 않습니다.</p>
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
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import Footer from "@/views/layout/footer.vue"
import useBid from "@/composables/bids";
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';

const router = useRouter(); 
const store = useStore(); // Vuex 스토어 인스턴스
const { getAuctions, getAuctionById } = useAuctions();
const { bidsData, getBids } = useBid();
const user = computed(() => store.getters['auth/user']); // 현재 사용자를 가져오는 계산된 속성
const { amtComma } = cmmn();
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
    console.log('All Bids:', bidsData.value); 
    const bidsWithDetails = await Promise.all(bidsData.value.map(fetchAuctionDetails));
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

@media (min-width: 300px) and (max-width:540px ){
.col-md-3{
    flex: 0 0 auto;
    width: 100% !important;
}
}
@media(min-width:541px) and (max-width: 991px){
    .col-md-3 {
        flex: 0 0 auto;
        width: 49.3333% !important;
    }
}
@media (min-width: 768px){
.col-md-3 {
    flex: 0 0 auto;
    width: 33%;
}
}
</style>
