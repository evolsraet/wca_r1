<template>
    <div>
        <div class="regiest-content">
            <div class="banner-top primary-background">
                <div>
                    <div class="profile dealer-mp">
                        <div>
                            <p class="bold-20-font">현재 진행중인 경매가<br><span class="tc-red me-2">{{ auctionsData.length }}</span>건 있습니다</p>
                        </div>
                        <div>
                            <div style="display: flex; align-items: flex-end;">
                                <div class="car-image"></div>
                            </div>
                        </div>
                    </div>
                    <div class="container slide-up-ani activity-info bold-18-font process mb-0">
                        <router-link :to="{ name: 'auction.index', state: { currentTab: 'interInfo' }}" class="item">
                            <p><span class="tc-red slide-up mb-0" ref="item1">{{ myLikeCount }}</span> 건</p>
                            <p class="interest-icon text-secondary opacity-50 normal-16-font mb-0">관심</p>
                        </router-link>
                        <router-link :to="{ name: 'auction.index' , state: { currentTab: 'myBidInfo',status: 'bid' }}" class="item">
                            <p><span class="tc-red mb-0" ref="item2">{{ myBidCount }}</span> 건</p>
                            <p class="bid-icon text-secondary opacity-50 normal-16-font mb-0">입찰</p>
                        </router-link>
                        <router-link :to="{  name: 'auction.index', state: { currentTab: 'scsbidInfo' }}" class="item">
                            <p><span class="tc-red mb-0" ref="item3">{{ filteredDoneBids.length }}</span> 건</p>
                            <p class="suc-bid-icon text-secondary opacity-50 normal-16-font mb-0">낙찰</p>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="container layout-container02">
                <div class="tbl_basic container content p-4">
                    <div class="container enter-view text-start mb-2">
                        <h3 class="review-title">공지사항</h3>
                        <router-link :to="{ name: 'posts.index', params: { boardId: 'notice' } }" class="btn-apply mt-0">전체보기</router-link>
                    </div>
                    <table class="table custom-border mt-5">
                        <thead>
                            <tr class="px-6 py-3 bg-gray-50 justify-content-center">
                                <th class="col-4">카테고리</th>
                                <th>제목</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="latestNotices.length === 0">
                                <td colspan="2" class="text-center text-secondary opacity-50">공지사항이 없습니다</td>
                            </tr>
                            <tr v-else v-for="notice in latestNotices" :key="notice.id" class="pointer"  @click="goToDetail(notice.id)">
                                <td class="col-4 pointer-cursor text-overflow">[{{notice.category}}]</td>
                                <td class="text-with-marker pointer-cursor">{{ stripHtmlTags(notice.title) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <AuctionList/>
            </div>
        </div>
    </div>
    <Footer />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import useBid from "@/composables/bids";
import Footer from "@/views/layout/footer.vue";
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import AlarmModal from '@/views/modal/AlarmModal.vue';
import useAuctions from '@/composables/auctions';
import useLikes from '@/composables/useLikes';
import AuctionList from "@/views/import/AuctionList.vue";
import { initPostSystem } from "@/composables/posts";

const { posts, getPosts } = initPostSystem();

const { getMyLikesCount } = useLikes();
const item1 = ref(null);
const item2 = ref(null);
const item3 = ref(null);
const myBidsCount = ref(0);
const store = useStore();
const router = useRouter();
const isExpanded = ref(false);
const toggleCard = () => {
    isExpanded.value = !isExpanded.value;
};
const alarmModal = ref(null);
const { getAuctionsByDealer, auctionsData, getAuctionById } = useAuctions();
const { bidsData, getHomeBids, viewBids, bidsCountByUser } = useBid();
const user = computed(() => store.state.auth.user);
const myBidCount = ref(0);
const myLikeCount=ref(0);
const latestNotices = computed(() => {
    return posts.value.slice(0, 3);
});

const stripHtmlTags = (html) => {
    const div = document.createElement('div');
    div.innerHTML = html;
    return div.textContent || div.innerText || '';
};

const fetchPosts = async () => {
    await getPosts('notice', 1, '', '', '', '', '', 'created_at', 'desc');
};

const openAlarmModal = () => {
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
        router.push({ name: 'auction.index' , state: { currentTab: 'scsbidInfo' }});
    }
};

const fetchAuctionDetails = async (bid) => {
    try {
        const auctionDetails = await getAuctionById(bid.auction_id);
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

const filteredDoneBids = ref([]);

const goToDetail = (postId) => {
    router.push({ name: 'posts.edit', params: { boardId: 'notice', id: postId }, query: { navigatedThroughHandleRowClick: true } });
};

onMounted(async () => {
    await getAuctionsByDealer("all");
    const myLikeCountData = await getMyLikesCount(user.value.id);
    myLikeCount.value = myLikeCountData.rawData.data.data_count;
    
    await getHomeBids();
    bidsData.value.forEach(bid => {
        if (
            bid.auction.win_bid &&
            bid.auction.win_bid.user_id === user.value.id &&
            (bid.auction.status == "chosen" || bid.auction.status == "dlvr")
        ) {
            filteredDoneBids.value.push(bid);
        }
        
        if (bid.auction.status == "ing" || bid.auction.status == "wait") {
            myBidCount.value += 1;
        }
    }); 
    
    await fetchPosts();

    setTimeout(() => {
        if (item1 != null && item1.value != null && item1.value.classList != null) 
            item1.value.classList.add('visible');
    }, 0);
    setTimeout(() => {
        if (item2 != null && item2.value != null && item2.value.classList != null)
            item2.value.classList.add('visible');
    }, 200);
    setTimeout(() => {
        if (item3 != null && item3.value != null && item3.value.classList != null)
            item3.value.classList.add('visible');
    }, 400);
});
</script>


<style scoped>
p {
    margin-top: 0;
    margin-bottom: 0rem !important;
}
.layout-container02{
    grid-template-columns: 1fr 1fr !important;
    align-items: baseline;
    gap: 30px !important;
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
    align-items:normal;
    max-width: none !important;
    }
.p-4{
    padding: 0px !important;
}
.layout-container02 .container  {
    max-width: none !important;
}
}

.text-with-marker {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tbl_basic table tr td {
    padding: 13px 5px !important;
}
.tbl_basic table tr th {
    padding: 10px 5px !important;
}
</style>
