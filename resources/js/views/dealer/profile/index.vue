<template>
    <div class="container">
        <div class="regiest-content">
            <!-- 딜러 프로필 요약 정보 -->
            <div class="banner-top mt-3">
                <div class="top-info gap-1 align-items-center">현재 진행중인 경매<p class="tc-primary">{{ bidsCountByUser[user.dealer.user_id] || 0 }} </p> 건</div>
                <div class="d-flex justify-content-end">
                    <a href="/addr" class="btn btn-outline-primary mb-3 me-3">주소지 관리</a>
                    <a href="/edit-profile" class="btn btn-outline-primary mb-3">내 정보수정</a>
                </div>
                <div class="styled-div mt-0">
                    <div class="profile">
                        <div class="dealer-info align-items-center">
                            <img :src="photoUrl" alt="Profile Photo" class="profile-photo" />
                            <div class="deal-info align-items-center">
                                <p class="text-secondary opacity-50">{{ user.dealer.company }} </p>
                                <p>딜러 <span class="fw-medium">{{ user.dealer.name }}</span>님</p>
                                <p class="restar">4.5점</p>
                            </div>
                        </div>
                    </div>
                    <div class="activity-info bold-18-font mt-5">
                        <router-link :to="{ name: 'auction.index', state: { currentTab: 'interInfo' }}" class="item">
                        <p><span class="tc-primary slide-up mb-0" ref="item1">{{ myLikeCount }}</span> 건</p>
                        <p class="interest-icon text-secondary opacity-50 normal-16-font mb-0">관심</p>
                        </router-link>
                        <router-link :to="{ name: 'auction.index' , state: { currentTab: 'myBidInfo',status: 'bid' }}" class="item">
                        <p><span class="tc-primary mb-0" ref="item2">{{ myBidCount }}</span> 건</p>
                        <p class="bid-icon text-secondary opacity-50 normal-16-font mb-0">입찰</p>
                        </router-link>
                        <router-link :to="{  name: 'auction.index' , state: { currentTab: 'scsbidInfo' }}" class="item">
                        <p><span class="tc-primary mb-0" ref="item3">{{ filteredDoneBids.length }}</span> 건</p>
                        <p class="suc-bid-icon text-secondary opacity-50 normal-16-font mb-0">낙찰</p>
                        </router-link>
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
                        <div class="info-item mt-3">
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
                    <h5 class="text-secondary opacity-50 mb-3">소개</h5>
                    <div style="height: 200px; overflow-y: auto;">
                        <p>{{ user.dealer.introduce }}</p>
                    </div>
                </div>
            </div>
            <!-- 지난 낙찰건 -->
            <div class="p-3" v-if="currentTab === 'pastAuctions'">
                <div class="enter-view mt-3 mb-2">
                    <h5>지난 낙찰건</h5>
                </div>
                <span class="text-secondary opacity-50">딜러가 낙찰받은 매물들이에요.</span>
                <!--
                <div v-if="filteredViewBids.length == 0">
                    <p class="tc-primary bold-18-font my-2">낙찰 받은 매물이 없습니다.</p>
                </div>
                -->
                <div class="container my-4">
                    <div class="row">
                        
                        <!--<div class="col-6 col-md-4 mb-4 pt-2 shadow-hover" v-for="auctionsDoneData in filteredDone" :key="auctionsDoneData.id" @click="navigateToDetail(auctionsDoneData)" :style="getAuctionStyle(auctionsDoneData)">-->
                        <div class="col-6 col-md-4 mb-4 pt-2 hover-anymate" v-for="(bid, index) in auctionsDoneData" :key="bid.id" @click="navigateToDetail(bid)" :style="getAuctionStyle(bid)">
                            <div class="card my-auction">
                                <div class="card-img-top-placeholder grayscale_img"><img src="../../../../img/car_example.png"></div>
                                <span v-if="bid.status === 'done'" class="mx-2 auction-done">경매완료</span>
                                <div class="card-body">
                                    <!--<h5 class="card-title"><span class="blue-box">무사고</span>{{bid.car_no}}</h5>-->
                                    <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                    <h5>[ {{bid.car_no}} ]</h5>
                                    <p>2020년 | 2.4km | 무사고</p>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="blue-box border-6">보험 3건</span><span class="gray-box border-6">재경매</span>
                                        </div>
                                        <!--<p class="tc-primary">{{ amtComma(review.auction.win_bid.price) }}</p>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!isData" class="complete-car my-3">
                            <div class="card my-auction mt-3">
                                <div class="none-complete">
                                    <span class="text-secondary opacity-50">낙찰 받은 매물이 없습니다.</span>
                                </div>
                            </div>
                        </div>
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item" :class="{ disabled: !pagination.prev }">
                                    
                                    <a class="page-link prev-style" @click="loadPage( pagination.current_page - 1, pagination)"></a>
                                </li>
                                <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === pagination.current_page }">
                                    <a class="page-link" @click="loadPage( n , pagination)">{{ n }}</a>
                                </li>
                                <li class="page-item next-prev" :class="{ disabled: !pagination.next }">
                                    <a class="page-link next-style" @click="loadPage( pagination.current_page + 1, pagination)"></a>
                                </li>
                            </ul>
                        </nav>
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
import useUsers from '@/composables/users';
import Footer from "@/views/layout/footer.vue"
import profileDom from '/resources/img/profile_dom.png'; 
import useLikes from '@/composables/useLikes';
import { useRouter } from 'vue-router';

const router = useRouter();
const photoUrl = ref(profileDom);
const currentTab = ref('dealerInfo');
const store = useStore();
const { getDoneAuctions, pagination } = useAuctions(); // 경매 관련 함수를 사용
const { getMyLikesCount } = useLikes();
const { bidsData, bidsCountByUser, getHomeBids, getBidsByUserId } = useBid();
const { getUser } = useUsers();
const myBidCount = ref(0);
const myLikeCount=ref(0);
const filteredDoneBids = ref([]);
const auctionsDoneData = ref([]);
const currentPage = ref(1); 
let isData = false;
let bidsNumList ='';

const setCurrentTab = (tab) => {
    currentTab.value = tab;
};

const user = computed(() => store.state.auth.user);

function fileExstCheck(info){
    if(info.hasOwnProperty('files')){
        if(info.files.hasOwnProperty('file_user_photo')){
            if(info.files.file_user_photo[0].hasOwnProperty('original_url')){
                photoUrl.value = info.files.file_user_photo[0].original_url;
            }
        }
    }
}

onMounted(async () => {
    const userInfo = await getUser(user.value.id);
    fileExstCheck(userInfo);
    await getHomeBids();
    bidsData.value.forEach(bid => {
        if (
            bid.auction.win_bid &&
            bid.auction.win_bid.user_id === user.value.id &&
            (bid.auction.status == "chosen" || bid.auction.status == "dlvr")
           
        ){
            filteredDoneBids.value.push(bid);
        }
        
        if(bid.auction.status == "ing" || bid.auction.status == "wait"){
            myBidCount.value += 1;
        }

    });

    const myLikeCountData = await getMyLikesCount(user.value.id);
    myLikeCount.value = myLikeCountData.rawData.data.data_count;

    let bidsList = await getBidsByUserId(user.value.id);
    bidsNumList = '';
    if(bidsList){
        for(let i=0; i<bidsList.length; i++){
            bidsNumList += bidsList[i].id;
            if (i < bidsList.length - 1) {
                bidsNumList += ',';
            }
        }
    }

    getDoneAuctionsByBidsNum(bidsNumList);

});

const getDoneAuctionsByBidsNum = async(numList) =>{
    auctionsDoneData.value = await getDoneAuctions(numList,currentPage.value);
    if(auctionsDoneData._rawValue.length>0){
        isData = true;
    }else{
        isData = false;
    }
}

function navigateToDetail(auction) {
    router.push({ name: 'AuctionDetail', params: { id: auction.id } });
}

function getAuctionStyle(auction) { 
    const validStatuses = ['done'];
    return validStatuses.includes(auction.status) ? { cursor: 'pointer' } : {};
}

function loadPage( page, pagination) {
    if (page < 1 || page > pagination.last_page) return;
    window.scrollTo(0, 0);
    
    currentPage.value = page;
    getDoneAuctionsByBidsNum(bidsNumList);

}

</script>
<style scoped>
@media (max-width: 340px) {
    .profile {
        padding-left: 5px;
        padding-right: 7px;
    }
}
</style>
