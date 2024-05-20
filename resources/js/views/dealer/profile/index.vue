<template>
    <div class="container">
        <div class="regiest-content">
                <!-- 딜러 프로필 요약 정보 -->
                <div class="banner-top ">
                    <div class="top-info">현재 진행중인 경매<p class="tc-red"> 1 </p> 건</div>
                    <div class="styled-div">
                        <div class="profile">
                            <div class="dealer-info">
                                <img src="../../../../img/myprofile_ex.png" alt="Profile Image" class="main-profile">
                                <div class="deal-info">
                                    <p class="tc-light-gray">{{ user.dealer.company }} </p>
                                    <p>딜러 <span class="fw-medium">{{ user.dealer.name }}</span>님</p>
                                    <p class="restar">(4.5점)</p>
                                    <p class="no-bidding mt-3"><span>입찰 불가</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="activity-info bold-18-font mt-5">
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
                                <p><span class="tc-red">32</span> 건</p>
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
                    <div v-if="viewBids.length > 0">
                        <div class="complete-car mt-3" v-for="bid in viewBids" :key="bid.id">
                            <div class="card my-auction mt-3">
                                <div class="card-img-top-placeholder border-rad"></div>
                                <div class="card-body">
                                    <div class="enter-view align-items-center ">
                                    <h5 class="card-title my-0">현대 쏘나타(DN8)</h5>
                                    <p class="tc-red bold-18-font">1,000만원</p>
                                </div>
                                    <div class="enter-view">
                                        <p class="card-text tc-light-gray">12 삼 4567</p>
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
import useAuctions from '@/composables/auctions'; // 경매 관련 작업을 위한 컴포저블


const currentTab = ref('dealerInfo');
const store = useStore();
const { getAuctions,auctionsData } = useAuctions(); // 경매 관련 함수를 사용
const isExpanded = ref(false);


const toggleCard = () => {
    isExpanded.value = !isExpanded.value;
};
const setCurrentTab = (tab) => {
    currentTab.value = tab;
};
const { bidsData, bidsCountByUser, getBids, viewBids } = useBid();
const user = computed(() => store.state.auth.user);

onMounted(async () => {
    await getBids();
    await getAuctions();
});

</script>
