<template>
    <div class="container mt-auto mb-auto" id="dashadmin">
        <div class="card-container mt-5">
            <div class="section">
                <h5 class="text-start"><span class="admin-icon admin-icon-menu"></span>회원 {{ userAllCnt }}</h5>
                <div class="card">
                    <div class="number d-flex justify-content-between">심사중 <span class="tc-red">{{ animatedUserAskCnt }}</span></div>
                    <div class="number d-flex justify-content-between">정&nbsp;&nbsp;상 <span>{{ animatedUserOkCnt }}</span></div>
                    <div class="divider"></div>
                    <!--<div class="d-flex justify-content-around text-secondary opacity-50">탈&nbsp;&nbsp;&nbsp;&nbsp;퇴 <span>12</span></div>-->
                    <div class="d-flex justify-content-between text-secondary opacity-50">가입거부 <span>{{ animatedUserRejectCnt }}</span></div>
                </div>
            </div>
            <div class="section">
                <h5 class="text-start"><span data-v-831d09df="" class="admin-icon admin-icon-menu02"></span>입금</h5>
                <div class="card">
                    <div class="number d-flex justify-content-between">입금 대기 <span class="tc-red">{{ animatedAuctionDlvrCnt }}</span></div>
                    <div class="divider"></div>
                    <div class="d-flex justify-content-between text-secondary opacity-50">입금 완료 <span>{{ animatedAuctionDoneCnt }}</span></div>
                </div>
            </div>
            <div class="section">
                <h5 class="text-start"><span data-v-ee657362="" class="admin-icon admin-icon-menu03"></span>매물 {{ auctionAllCnt }}</h5>
                <div class="card">
                    <div class="number d-flex justify-content-between">신청 완료<span class="tc-red">{{ animatedAuctionAskCnt }}</span></div>
                    <div class="number d-flex justify-content-between">진단 중<span class="tc-red">{{ animatedAuctionDiagCnt }}</span></div>
                    <div class="number d-flex justify-content-between">경매 중<span class="tc-red">{{ animatedAuctionWIAddCnt }}</span></div>
                    <div class="divider"></div>
                    <div class="d-flex justify-content-between text-secondary opacity-50">신청 취소<span>{{ animatedAuctionCancelCnt }}</span></div>
                    <div class="d-flex justify-content-between text-secondary opacity-50">선택 완료<span>{{ animatedAuctionChosenCnt }}</span></div>
                    <div class="d-flex justify-content-between text-secondary opacity-50">경매 완료<span>{{ animatedAuctionDoneCnt }}</span></div>
                </div>
            </div>
            <div class="section">
                <h5 class="text-start"><span data-v-ee657362="" class="admin-icon admin-icon-menu04"></span>후기 {{ reviewAllCnt }}</h5>
                <div class="card">
                    <div class="number d-flex justify-content-between">작성<span class="tc-red">{{ animatedReviewWriteCnt }}</span></div>
                    <div class="divider"></div>
                    <div class="d-flex justify-content-between text-secondary opacity-50">미작성 <span>{{ animatedReviewNotWriteCnt }}</span></div>
                </div>
            </div>
        </div>
        <div class="mt-5 p-3">
            <div class="d-flex justify-content-between align-center">
                <h4><span data-v-ee657362="" class="admin-icon admin-icon-menu05"></span>공지사항</h4>
                <router-link class="btn-apply" :to="{ name: 'posts.index', params: { boardId: 'notice' } }">자세히 보기</router-link>
            </div>
            <p class="text-secondary opacity-50" style="margin-top: 10px;">
                최근 공지사항 5개만 표시됩니다. 전체 목록을 보려면 ' 자세히 보기 '를 클릭하세요.
            </p>
                <div class="tab-nav my-4">
                    <ul>
                        <li class="col-2">
                            <a href="#" @click.prevent="setActiveTab('available')" :class="{ 'active': activeTab === 'available' }" title="공통">공통</a>
                        </li>
                    </ul>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            <div class="o_table_mobile">
                <div class="tbl_basic tbl_dealer">    
                    <div class="mt-2">
                        <table class="table" v-if="activeTab === 'available'">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>제목</th>
                                    <th>카테고리</th>
                                    <th>날짜</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="limitedNotices.length === 0">
                                    <td class="text-center text-secondary opacity-50">공지사항이 없습니다</td>
                                </tr>
                                <tr v-for="(notice, index) in limitedNotices" :key="notice.id">
                                    <td style="width: 10%;">{{ index + 1 }}</td>
                                    <td style="width: 15%;">[ {{ notice.category }} ]</td>
                                    <td class="text-overflow">{{ stripHtmlTags(notice.title) }}</td>
                                    <td class="date">{{ formatDate(notice.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import useUsers from '@/composables/users';
import useAuctions from '@/composables/auctions';
import { initReviewSystem } from '@/composables/review';
import { initPostSystem } from '@/composables/posts'; 

const { getUserStatus } = useUsers();
const { getStatusAuctionsCnt } = useAuctions();
const { getWriteReviewCnt } = initReviewSystem();
const { posts, getPosts } = initPostSystem(); // Fetching notices

const activeTab = ref('available');
const notices = ref([]);
const limitedNotices = ref([]);

function setActiveTab(tab) {
  activeTab.value = tab;
}

//회원
const userAskCnt = ref(0);
const userOkCnt = ref(0);
const userRejectCnt = ref(0);
const userAllCnt = ref(0);
const animatedUserAskCnt = ref(0);
const animatedUserOkCnt = ref(0);
const animatedUserRejectCnt = ref(0);

//입금
const auctionDlvrCnt = ref(0);
const animatedAuctionDlvrCnt = ref(0);

//매물
const auctionAskCnt = ref(0);
const auctionDiagCnt = ref(0);
const auctionIngCnt = ref(0);
const auctionWaitCnt = ref(0);
const auctionChosenCnt = ref(0);
const auctionDoneCnt = ref(0);
const auctionAllCnt = ref(0);
const auctionWIAddCnt = ref(0);
const auctionCancelCnt = ref(0);
const animatedAuctionAskCnt = ref(0);
const animatedAuctionDiagCnt = ref(0);
const animatedAuctionWIAddCnt = ref(0);
const animatedAuctionCancelCnt = ref(0);
const animatedAuctionChosenCnt = ref(0);
const animatedAuctionDoneCnt = ref(0);

//후기
const reviewWriteCnt = ref(0);
const reviewNotWriteCnt = ref(0);
const reviewAllCnt = ref(0);
const animatedReviewWriteCnt = ref(0);
const animatedReviewNotWriteCnt = ref(0);

function animateValue(ref, end, duration) {
  let start = 0;
  let increment = Math.ceil(end / (duration / 50));
  let counting = setInterval(() => {
    start += increment;
    if (start >= end) {
      start = end;
      clearInterval(counting);
    }
    ref.value = new Intl.NumberFormat().format(start);
  }, 10);
}

function formatDate(date) {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
  return new Date(date).toLocaleDateString('ko-KR', options);
}

function stripHtmlTags(html) {
  const div = document.createElement('div');
  div.innerHTML = html;
  return div.textContent || div.innerText || '';
}

async function fetchNotices() {
  await getPosts('notice', 1, '', '', '', '', 5, 'created_at', 'desc');
  notices.value = posts.value;
  limitedNotices.value = notices.value.slice(0, 5); // Limit to the latest 5 notices
}

onMounted(async () => {
  // Fetch data for members
  userAskCnt.value = await getUserStatus('ask');
  userOkCnt.value = await getUserStatus('ok');
  userRejectCnt.value = await getUserStatus('reject');
  userAllCnt.value = await getUserStatus('all');

  // Fetch data for auctions
  auctionAskCnt.value = await getStatusAuctionsCnt('ask');
  auctionDiagCnt.value = await getStatusAuctionsCnt('diag');
  auctionIngCnt.value = await getStatusAuctionsCnt('ing');
  auctionWaitCnt.value = await getStatusAuctionsCnt('wait');
  auctionDlvrCnt.value = await getStatusAuctionsCnt('dlvr');
  auctionWIAddCnt.value = auctionIngCnt.value + auctionWaitCnt.value + auctionDlvrCnt.value;
  auctionChosenCnt.value = await getStatusAuctionsCnt('chosen');
  auctionDoneCnt.value = await getStatusAuctionsCnt('done');
  auctionCancelCnt.value = await getStatusAuctionsCnt('cancel');
  auctionAllCnt.value = await getStatusAuctionsCnt('all');

  // Fetch data for reviews
  reviewWriteCnt.value = await getWriteReviewCnt();
  reviewNotWriteCnt.value = auctionDoneCnt.value - reviewWriteCnt.value;
  reviewAllCnt.value = auctionDoneCnt.value;

  // Animate values
  animateValue(animatedUserAskCnt, userAskCnt.value, 2000);
  animateValue(animatedUserOkCnt, userOkCnt.value, 2000);
  animateValue(animatedUserRejectCnt, userRejectCnt.value, 2000);
  animateValue(animatedAuctionDlvrCnt, auctionDlvrCnt.value, 2000);
  animateValue(animatedAuctionAskCnt, auctionAskCnt.value, 2000);
  animateValue(animatedAuctionDiagCnt, auctionDiagCnt.value, 2000);
  animateValue(animatedAuctionWIAddCnt, auctionWIAddCnt.value, 2000);
  animateValue(animatedAuctionCancelCnt, auctionCancelCnt.value, 2000);
  animateValue(animatedAuctionChosenCnt, auctionChosenCnt.value, 2000);
  animateValue(animatedAuctionDoneCnt, auctionDoneCnt.value, 2000);
  animateValue(animatedReviewWriteCnt, reviewWriteCnt.value, 2000);
  animateValue(animatedReviewNotWriteCnt, reviewNotWriteCnt.value, 2000);

  // Fetch notices
  await fetchNotices();
});
</script>

<style scoped>
#dashadmin table thead th {
    background-color: #f5f5f5;
    color: #333;
    padding: 10px;
    text-align: left;
    border-bottom: 2px solid #ddd;
}

#dashadmin table tbody tr:hover {
    background-color: #f9f9f9;
}

#dashadmin table tbody td {
    padding: 10px;
    vertical-align: middle;
    border-bottom: 1px solid #ddd;
}

#dashadmin tbody .date {
    width: 150px;
    text-align: left;
}

#dashadmin tbody .content {
    width: 100% !important;
    text-align: left;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

#dashadmin tbody .actions {
    width: 10%;
    text-align: right;
}
</style>
