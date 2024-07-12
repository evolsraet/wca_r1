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
                    <div class="text-start status-selector registration-content">
                        <div v-for="(label, value) in statusLabel" :key="value" class="mx-2">
                            <input type="radio" name="status" :value="value" :id="value" :checked="value === 'all' " @change="event => setFilter(event.target.value)" />
                            <label :for="value">{{ label }}</label>
                        </div>
                        
                    </div>
                    <div class="container my-5" v-if="loading">
                        <div v-if="bidsData.length > 0" class="row">
                            <div class="col-md-3 p-2 mb-2 shadow-hover" v-for="bid in bidsData" :key="bid.id"  @click="navigateToDetail(bid)">
                                <div class="card my-auction">
                                    <div class="card-img-top-placeholder"><img src="../../../img/car_example.png">
                                    </div> 
                                    <span v-if="bid.auction.status === 'dlvr'" class="mx-2 auction-done bg-info">{{ wicas.enum(store).toLabel(bid.auction.status).auctions() }}</span>
                                    <div>
                                        <span v-if="['done', 'cancel', 'chosen', 'diag', 'ask'].includes(bid.auction.status)" class="mx-2 auction-done">
                                            {{ wicas.enum(store).toLabel(bid.auction.status).auctions() }}
                                        </span>
                                    </div>
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
            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ disabled: !bidPagination.prev }">
                    <a class="page-link prev-style" @click="loadPage(bidPagination.current_page - 1)"></a>
                    </li>
                    <li v-for="n in bidPagination.last_page" :key="n" class="page-item" :class="{ active: n === bidPagination.current_page }">
                    <a class="page-link" @click="loadPage(n)">{{ n }}</a>
                    </li>
                    <li class="page-item next-prev" :class="{ disabled: !bidPagination.next }">
                    <a class="page-link next-style" @click="loadPage(bidPagination.current_page + 1)"></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <Footer />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import Footer from "@/views/layout/footer.vue";
import useBid from "@/composables/bids";
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';

const currentPage = ref(1); 
const router = useRouter();
const store = useStore();
const { getAuctions, getAuctionById } = useAuctions();
const { bidsData, getBids , bidPagination } = useBid();
const user = computed(() => store.getters['auth/user']);
const { amtComma , wicas } = cmmn();
const loading = ref(false);
const currentStatus = ref('all');
let statusLabel;

/**
const fetchAuctionDetails = async (bid) => {
    if (!user.value || !user.value.id) {
        console.error('사용자 정보가 올바르지 않습니다.');
        return { ...bid, auctionDetails: null };
    }
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

const fetchFilteredBids = async () => {
    if (!user.value || !user.value.id) {
        console.error('사용자 정보가 올바르지 않습니다.');
        return;
    }
    await getBids();
    console.log('All Bids:', bidsData.value);
    const bidsWithDetails = await Promise.all(bidsData.value.map(fetchAuctionDetails));
    filteredBids.value = bidsWithDetails.filter(bid => bid.auctionDetails.win_bid && bid.auctionDetails.win_bid.user_id === user.value.id);
    console.log('Bids with Auction Details:', filteredBids.value);
}; */

function loadPage(page) { 
    if (page < 1 || page > bidPagination.value.last_page) return;
    currentPage.value = page;
    getBids(1,true,false,user.value.id);
    window.scrollTo(0,0);
}

function navigateToDetail(bid) {
    console.log("Navigate to Detail:", bid.auction_id);
    router.push({ name: 'AuctionDetail', params: { id: bid.auction_id } });
}

function setFilter(status) { 
    currentStatus.value = status;
    getBids(currentPage.value,
            true,
            false,
            user.value.id,
            currentStatus.value);
}

onMounted(async () => {
    if (!user.value || !user.value.id) {
        console.error('사용자 정보가 없습니다.');
        return;
    }
    await getBids(1,true,false,user.value.id);
    statusLabel = wicas.enum(store).addFirst('all','전체').perm('dlvr','chosen').auctions();
    loading.value = true;
});
</script>

<style scoped>
.search-type3 .search-btn {
    right: 13px !important;
    top: 62px !important;
}
.pd-10 {
    padding: 10px;
}
.search-type2 span {
    font-size: 17px;
}
@media (min-width: 300px) and (max-width: 540px) {
    .col-md-3 {
        flex: 0 0 auto;
        width: 100% !important;
    }
}
@media (min-width: 541px) and (max-width: 991px) {
    .col-md-3 {
        flex: 0 0 auto;
        width: 49.3333% !important;
    }
}
@media (min-width: 768px) {
    .col-md-3 {
        flex: 0 0 auto;
        width: 33%;
    }
}
</style>
