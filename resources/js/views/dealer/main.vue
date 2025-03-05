<template>
    <div>
        <div>
            <div class="banner-top">
                <div>
                    <div class="styled-div mt-0">
                        <h1 class="centered-text fade-in" :class="{ 'fade-in-active': isVisible }">
                            현재 진행중인 경매가
                            <span class="ms-2 underline">{{ ingCount }} 건</span> 있어요.
                        </h1>
                        
                    <swiper
                        :modules="[Navigation, Pagination, Autoplay]"
                        :slides-per-view="1"
                        :loop="true"
                        :pagination="{
                            clickable: true,
                            el: '.swiper-pagination',
                            type: 'custom',
                            renderCustom: renderCustomPagination
                        }"
                        :autoplay="{ delay: 5000 }"
                        :speed="1000"
                        @slideChangeTransitionStart="resetProgressBar"
                        class="swiper-container"
                    >
                    <!-- Slide 1 -->
                    <swiper-slide>
                             <div class="slide-content">
                                <img src="../../../img/main_banner02.png" class="styled-img" alt="배너 이미지" />
                                <div class="content d-flex">
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </swiper-slide>
                        <!-- Slide 2 -->
                        <swiper-slide>
                            <div class="slide-content">
                                <img src="../../../img/main_p2.png" class="styled-img" alt="배너 이미지 2" />
                                <div class="content02 d-flex text-center">
                                </div>
                            </div>
                        </swiper-slide>
                        <!-- Slide 3 -->
                        <swiper-slide>
                            <div class="slide-content">
                                <img src="../../../img/main_p3.png" class="styled-img" alt="배너 이미지 3" />
                                <div class="content d-flex">
                                </div>
                            </div>
                        </swiper-slide>

                        <!-- 페이지 네이션 -->
                        <div class="swiper-pagination mb-3">
                            <div class="progress-bar">
                                <div class="progress"></div>
                            </div>
                        </div>
                    </swiper>
                    <!-- Activity Info -->
                    <div class="web container activity-info bold-18-font process mb-0">
                        <router-link :to="{ name: 'auction.index', state: { currentTab: 'interInfo' }}" class="item tc-black">
                            <p class="size_22 tc-black"><span class="tc-primary slide-up mb-0 size_22" ref="item1">{{ myLikeCount }}</span><span>건</span></p>
                            <p class="interest-icon text-secondary opacity-50 size_16 mb-0">관심</p>
                        </router-link>
                        <router-link :to="{ name: 'auction.index', state: { currentTab: 'myBidInfo',status: 'bid' }}" class="item tc-black">
                            <p class="size_22 tc-black"><span class="tc-primary mb-0" ref="item2">{{ myBidCount }}</span><span>건</span></p>
                            <p class="bid-icon text-secondary opacity-50 size_16 mb-0">입찰</p>
                        </router-link>
                        <router-link :to="{ name: 'auction.index', state: { currentTab: 'scsbidInfo' }}" class="item tc-black">
                            <p class="size_22 tc-black"><span class="tc-primary mb-0 size_22" ref="item3">{{ myScsBidCount }}</span><span>건</span></p>
                            <p class="suc-bid-icon text-secondary opacity-50 size_16 mb-0">낙찰</p>
                        </router-link>
                    </div>
            
                </div>
            </div>
            <div class="mov">
                <router-link :to="{ name: 'auction.index', state: { currentTab: 'interInfo' }}" class="item">
                    <p class="interest-icon text-secondary opacity-50 normal-16-font mb-0">관심</p>
                    <p class="text-center tc-primary"><span class="tc-primary slide-up mb-0" ref="item1">{{ myLikeCount }}</span><span>건</span></p>
                </router-link>
                <router-link :to="{ name: 'auction.index', state: { currentTab: 'myBidInfo',status: 'bid' }}" class="item">
                    <p class="bid-icon text-secondary opacity-50 normal-16-font mb-0">입찰</p>
                    <p class="text-center tc-primary"><span class="tc-primary mb-0" ref="item2">{{ myBidCount }}</span><span>건</span></p>
                </router-link>
                <router-link :to="{ name: 'auction.index', state: { currentTab: 'scsbidInfo' }}" class="item">
                    <p class="suc-bid-icon text-secondary opacity-50 normal-16-font mb-0">낙찰</p>
                    <p class="text-center tc-primary"><span class="tc-primary mb-0" ref="item3">{{ myScsBidCount }}</span><span>건</span></p>
                </router-link>
            </div>
            <div class="grid2 container justify-content-between my-5 px-4 gap-4 guide-card-content pt-4">
                <!-- 딜러가이드 카드 -->
                <div class="guide-card pointer" @click.prevent="openAlarmModal01" >
                    <div class="guide-content ">
                        <p class="guide-title">이용가이드</p>
                        <p class="guide-description">위카옥션 이렇게 이용하세요!</p>
                    </div>
                    <img src="../../../img/use_icon1.png" alt="책 아이콘" class="guide-icon" />
                    </div>
                    <div class="guide-card pointer"@click.prevent="openAlarmModal02">
                    <div class="guide-content">
                        <p class="guide-title">수수료 안내</p>
                        <p class="guide-description">소정의 정보제공 수수료가 있어요</p>
                    </div>
                    <img src="../../../img/use_icon2.png" alt="책 아이콘" class="guide-icon" />
                    </div>
                    <div class="guide-card pointer" @click.prevent="openAlarmModal03">
                    <div class="guide-content">
                        <p class="guide-title">진단오류 보상</p>
                        <p class="guide-description">진단오류시 보상기준표를 안내합니다</p>
                    </div>
                    <img src="../../../img/use_icon3.png" alt="책 아이콘" class="guide-icon" />
                </div>
                <div class="guide-card pointer" @click.prevent="openAlarmModal04">
                <div class="guide-content">
                    <p class="guide-title">패널티 부과</p>
                    <p class="guide-description">경매장 이용 오류시 패널티가 부과됩니다</p>
                </div>
                <img src="../../../img/use_icon7.png" alt="책 아이콘" class="guide-icon" />
            </div>
                </div>
            </div>
            <div class="container layout-container02">
    
            
                <div class="tbl_basic container p-4">
                    <div class="d-flex align-items-start justify-content-between">
                    <h3 class="review-title mb-5">공지사항</h3>
                    <router-link
                    :to="{name:'posts.index' , params: {boardId: 'notice'}}"
                    class="btn-apply mt-0 p-0">전체보기</router-link>
                </div>
                    <table class="table">
                    <thead class="d-none">
                        <tr>
                        <th>NO</th>
                        <th>작성일</th>
                        <th>제목</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 데이터가 없는 경우 -->
                        <tr v-if="posts.length === 0">
                        <td colspan="4" class="text-center text-secondary opacity-50">
                            게시물이 없습니다.
                        </td>
                        </tr>

                        <!-- 데이터가 있는 경우 -->
                        <tr 
                            v-for="(post, index) in posts" 
                            :key="post.id"
                            @click="handleRowClick(post.id)" 
                            class="pointer-cursor"
                        >
                            <td style="width: 5%;" class="tc-bold">{{ index + 1 }}</td>
                            <td style="width: 30%;" class="tc-gray">{{ post.created_at }}</td>
                            <td class="tb-title" style="width: 65%;">{{ post.title }}</td>
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
<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";
import { Navigation, Pagination, Autoplay } from "swiper/modules";

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  methods: {
    // 커스텀 페이지 네이션 렌더링
    renderCustomPagination(swiper, current, total) {
      return `
        <span class="pagination-number">${current}</span>
        <div class="progress-bar">
          <div class="progress"></div>
        </div>
        <span class="pagination-number">${total}</span>
      `;
    },
    // 진행 바 리셋
    resetProgressBar() {
      const progress = document.querySelector('.progress');
      if (progress) {
        progress.style.width = '0%';
        setTimeout(() => {
          progress.style.transition = 'width 5s linear';
          progress.style.width = '100%';
        }, 50);
      }
    },
  },
  mounted() {
    this.resetProgressBar();
  },
};
</script>

<script setup>
import { ref, onMounted, computed, watch,inject } from 'vue';
import useBid from "@/composables/bids";
import Footer from "@/views/layout/footer.vue";
import { useStore } from 'vuex';
import { useRouter ,useRoute } from 'vue-router';
import AlarmModal from '@/views/modal/AlarmModal.vue';
import useAuctions from '@/composables/auctions';
import useLikes from '@/composables/useLikes';
import AuctionList from "@/views/import/AuctionList.vue";
import { initPostSystem } from "@/composables/posts";
import { cmmn } from '@/hooks/cmmn';
import UseGuide from "@/views/import/UseGuide.vue";
import arrow from '../../../../resources/img/dash-black.png';
import bid from '../../../../resources/img/bid.png';
import hand from '../../../../resources/img/hand.png';
import Iconcare from '../../../../resources/img/Iconcare.png';
import hand02 from '../../../../resources/img/hand02.png';
import pannel01 from '../../../../resources/img/pannel01.png';
import pannel02 from '../../../../resources/img/pannel02.png';
import pannel03 from '../../../../resources/img/pannel03.png';
import pannel04 from '../../../../resources/img/pannel04.png';
const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();

const isVisible = ref(false);

onMounted(() => {
  setTimeout(() => {
    isVisible.value = true;
  }, 200); // 0.5초 후에 애니메이션 시작
});
const route = useRoute();
const { posts, getPosts,getBoardCategories } = initPostSystem();

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
const boardId = ref('notice');
const fetchPosts = async () => {
  try {
    // 최신 5개 게시물만 가져오기
    await getPosts(boardId.value, 1, '', 'all', 'created_at', 'desc');
    // 최신 5개로 제한
    posts.value = posts.value.slice(0, 5);
  } catch (error) {
    console.error('Error fetching posts:', error);
  }
};

const alarmModal = ref(null);
const { getAuctionsByDealer, auctionsData, getAuctionById, getAuctions, getAuctionsWithBids, getAuctionsByDealerLike, allIngCount } = useAuctions();
const { bidsData, getHomeBids, viewBids, bidsCountByUser, getscsBids, getMyBidsAll } = useBid();
const user = computed(() => store.state.auth.user);
const myBidCount = ref(0);
const myLikeCount=ref(0);
const myScsBidCount=ref(0);
const swal = inject('$swal');
const categoriesList=ref(0);
const bidsIdString = ref('');
const openAlarmModal01 = () => {
  const text = `
    <div class="intro-container container text-start">
        <h5>이용안내 페이지</h5>
        <h4 class="bold">위카옥션, 이렇게 이용하세요</h4>
        <div class="bidding-section">
            <h3 class="size_32 bolder mx-2">1<span class="size_24 ms-2">입찰하기</span></h3>
            <ol class="mt-3 step-list">
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">1</span>
                    <div class="step-text">위카옥션에서 매물을 검색합니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">2</span>
                    <div class="step-text">진단평가 결과서와 시세 등을 확인 후 입찰을 합니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-circle">3</span>
                    <div class="step-text">경매 종료 후 48시간 이내 고객이 판매대금을 선택합니다. 선택하지 않을 경우 입찰가 1위로 자동 낙찰됩니다.</div>
                </li>
            </ol>
            <ul class="list-block">
                <li>입찰 딜러 수는 제한이 없습니다.</li>
                <li>입찰후 차량 배송까지 시일이 걸립니다. 8일정도 후의 가격까지 고려하셔서 입찰해 주세요.</li>
                <li>최고가는 경매 종료후 공개됩니다.</li>
                <li>리스차량은 "승계후 완납 조건"으로 입찰해야 합니다.</li>
            </ul>
            
    </div>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intromodal') // 클래스명 설정
    .addOption({ padding: 20 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
    })
    .confirm(text); // 모달 내용 설정
};

const openAlarmModal02 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-5">
          <h5>위카옥션의 수수료 체계를 안내해드려요</h5>
          <h4 class="bold">정보제공 수수료 안내</h4>
        </div>
        <div class="fee-section mt-5">
          <div class="fee-step">
            <div class="circle">
              <img src="${bid}" alt="경매 낙찰" />
            </div>
            <p>경매 낙찰</p>
          </div>
          <div class="arrow">
            <img src="${arrow}" alt="화살표" width="50px"/>
          </div>
          <div class="fee-step">
            <div class="circle">
              <img src="${hand}" alt="대금 에스크로 입금" />
            </div>
            <p>대금 에스크로 입금</p>
          </div>
           <div class="arrow">
            <img src="${arrow}" alt="화살표" width="50px"/>
          </div>
          <div class="fee-step">
            <div class="circle">
              <img src="${Iconcare}" alt="탁송 및 도착" />
            </div>
            <p>탁송 및 도착</p>
          </div>
          <div class="arrow">
            <img src="${arrow}" alt="화살표" width="50px"/>
          </div>
          <div class="fee-step">
            <div class="circle">
              <img src="${hand02}" alt="수수료 입금" />
            </div>
            <p>수수료 입금</p>
          </div>
        </div>
      </div>
      <div class="o_table_mobile my-5">
        <div class="tbl_basic tbl_fee">
          <table class="table">
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">금액</th>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">수수료</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-6 py-4 text-center">100만원 이하</td>
                <td class="px-6 py-4 text-center">14만원</td>
              </tr>
               <tr>
                <td class="px-6 py-4 text-center">500만원 이하</td>
                <td class="px-6 py-4 text-center">25만원</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intromodal') // 클래스명 설정
    .addOption({ padding: 20 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
    })
    .confirm(text); // 모달 내용 설정
};

const openAlarmModal03 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-5">
          <h5>진단오류시 보상은 이렇게 진행돼요</h5>
          <h4 class="bold">진단오류시 보상기준표</h4>
        </div>
      <div class="o_table_mobile my-5">
        <div class="tbl_basic tbl_fee">
          <table class="table">
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">감가 항목</th>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">감가율</th>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">비고</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-6 py-4 text-center">후드</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
               <tr>
                <td class="px-6 py-4 text-center">프론트휀더</td>
                <td class="px-6 py-4 text-center">2%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              </tbody>
              </table>
              <p>※ 클레임 보상 : 매입금액 x 감가율 = 보상금액 (보상한도 100만원)</p>
              <p>※ 교환 / 판금에 대한 정의는 자동차관리법상 성능상태점검기준에 따라 적용</p>
              <p>※ 판금오류시 보상비율을 교환대비 50% 감가</p>
              <p>※ 사이드맴버, 휠하우스, 인사이드 패널은 좌우앞뒤가 상관없이 단일품목으로 감가</p>
        </div>
      </div>
    </div>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intromodal') // 클래스명 설정
    .addOption({ padding: 20 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
    })
    .confirm(text); // 모달 내용 설정
};
const openAlarmModal04 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-4 mb-4">
          <h5>경매장 이용 오류시 패널티를 안내해드려요</h5>
          <h4 class="bold">패널티 부과 안내</h4>
        </div>
        <div class="grid2">
            <div class="pannel-box">
                <img src="${pannel01}" alt="패널티 아이콘 1" width="35px" class="mb-3"/>
                <h5 class="mb-3">이의제기</h5>
                <p>패널티 부과시점으로부터 1주일 <br>
                이내에 이메일 부탁드립니다.<br>
                (Email : wecar@wecar.auction)</p>
            </div>
            <div class="pannel-box">
                <img src="${pannel02}" alt="패널티 아이콘 2" width="35px" class="mb-3"/>
                <h5 class="mb-3">거래정지</h5>
                <p>· 직접거래 유도</p>
                <p>· 고의로 차량 훼손 후, 클레임 청구</p>
                <p>· 수수료 입금을 피하고자 거래 실패 보고</p>
                <p>· 딜러나 매매상이 아닌자에게 계정 양도</p>
                <p>· 반말, 욕설 등 불량한 언행으로 인한 클레임 발생</p>
            </div>
            <div class="pannel-box">
                <img src="${pannel03}" alt="패널티 아이콘 3" width="35px" class="mb-3"/>
                <h5 class="mb-3">견적오류</h5>
                <p>· 1회 견적실수 패널티 3일 이용정지</p>
                <p>· 2회 견적실수 패널티 1달 이용정지</p>
                <p>· 3회 견적실수 패널티 계정 정지</p>
            </div>
            <div class="pannel-box">
                <img src="${pannel04}" alt="패널티 아이콘 4" width="35px" class="mb-3"/>
                <h5 class="mb-3">경고</h5>
                <p>· 거래 후 48시간 내 등록증 미업로드</p>
                <p>· 딜러 책임으로 분쟁 발생</p>
                <p>· 특별한 사유없이 48시간 이상 연락 두절</p>
            </div>
        </div>
    </div>
  `;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('intromodal') // 클래스명 설정
    .addOption({ padding: 20 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
    })
    .confirm(text); // 모달 내용 설정
};

const latestNotices = computed(() => {
  if (!posts.value || posts.value.length === 0) {
    console.warn("공지사항 데이터가 비어 있습니다.");
    return [];
  }
  return posts.value.slice(0, 3);
});

const getBoardData = async () => {
  try {
    const response = await axios.get('/api/board');
    if (Array.isArray(response.data.data)) {
      const board = response.data.data.find(board => board.id === boardId.value);
      if (board) {
        categoriesList.value = JSON.parse(board.categories);
      }
    }
  } catch (error) {
    console.error('Error fetching board data:', error);
  }
};
// HTML 태그 제거 함수
const stripHtmlTags = (html) => {
  const div = document.createElement("div");
  div.innerHTML = html;
  return div.textContent || div.innerText || "";
};
const navigatedThroughHandleRowClick = ref(false);
const hideButton = ref(false);
const handleRowClick = (postId) => {
    hideButton.value = true;
    navigatedThroughHandleRowClick.value = true; 
    router.push({ name: 'posts.edit', params: { boardId: boardId.value, id: postId }, query: { navigatedThroughHandleRowClick: true } });
};


const openAlarmModal = () => {
    if (alarmModal.value) {
        alarmModal.value.openModal();
    }
};

const ingCount = ref(0);

const ingCountFetch = async() => {
    // return auctionsData.value.filter(auction => auction.status === 'ing').length;
    const allIngCnt =  await allIngCount();
    ingCount.value = allIngCnt;
};

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
  router.push({ name: "posts.edit", params: { boardId: "notice", id: postId } });
};


const getIngData = async() => {
    const auctionDatas = await getAuctions('1',false,'all','');
    console.log('getIngData', auctionDatas);
}

// 입찰 데이터 가져오기
const getMyBidsGetData = async() => {
    const myAuctionBidsInfo = await getAuctionsWithBids('1','all',user.value.id,'',bidsIdString.value);
    myBidCount.value = myAuctionBidsInfo.data.filter(auction => auction.status === 'ing').length;
    console.log('getMyBidsGetData', myAuctionBidsInfo);
    console.log('getMyBidsGetDataLength', myAuctionBidsInfo.data.filter(auction => auction.status === 'ing').length);
}

// 관심 데이터 가져오기
const getMyLikeGetData = async() => {
    const myAuctionBidsInfo = await getAuctionsByDealerLike('1',user.value.id,'all');
    myLikeCount.value = myAuctionBidsInfo.rawData.data.data_count;
    console.log('getMyLikeGetData', myAuctionBidsInfo.rawData);
}

// 낙찰 데이터 가져오기
const getScsBidsGetData = async() => {
    const myAuctionBidsInfo = await getscsBids('1','all','',bidsIdString.value);
    myScsBidCount.value = myAuctionBidsInfo.rawData.data.data_count;
    console.log('getScsBidsGetData', myAuctionBidsInfo.rawData);
}

onMounted(async () => {

    const myBidsList = await getMyBidsAll();
    const bidsIdList = [];
    for (let i = 0; i < myBidsList.data.length; i++) {
        bidsIdList.push(myBidsList.data[i].id);
    }

    bidsIdString.value = bidsIdList.join(',');

    await getBoardCategories(); // 카테고리 로드
    await fetchPosts(); // 게시물 로드
    ingCountFetch();
    getBoardData();
    getIngData();
    getMyBidsGetData();
    getMyLikeGetData();
    getScsBidsGetData();
    });
    watch(route, async (newRoute) => {
    if (newRoute.params.boardId !== boardId.value) {
        boardId.value = newRoute.params.boardId;
        
        search_title.value = '';
        // 필터 값을 초기화합니다.
        filter.value = 'all';

        // 새로운 boardId에 대한 카테고리 데이터를 다시 로드합니다.
        await getBoardData();
        
        // 새로운 boardId에 해당하는 게시물 목록을 다시 로드합니다.
        await fetchPosts();
    }
    await fetchPosts();
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
    padding:20px 5px !important;
}
.tbl_basic table tr th {
    padding: 10px 5px !important;
}
.styled-div {
    position: relative;
}

.activity-info {
    position: absolute;
    bottom: 0px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255, 255, 255);
    padding: 65px 20px;
    border-radius: 10px;
    text-align: center;
    z-index: 10;
    width: 75%;
}

/* 반응형 디자인 */
@media (max-width: 991px) { /* 태블릿 */
    .activity-info {
        bottom: -95px;
    }
    .styled-div {
        height: auto !important;
    }
    .mov{
        display: flex !important;
        justify-content: space-around;
        margin-top: 27px;
    }
    .web{
        display:none !important;
    }
    .centered-text{
        font-size: 1.6rem !important;
        line-height: 35px !important;
    }
    .swiper-slide .styled-img {
        width: auto; 
        height: 400px;
        object-fit: cover; /* 비율 유지하며 잘리는 부분 처리 */
    }

    .swiper-container {
        height: 400px; /* 전체 슬라이드 높이를 조정 */
    }
}

@media (max-width: 480px) { /* 모바일 */
    .activity-info {
        padding: 8px 12px;
        font-size: 12px;
    }
    .centered-text{
        top: 40% !important;
        left: 11%;
    }
}
@media (max-width:423px) {
    .guide-icon{
    width: 46px;
    height: 46px;
    }
}
.mov{
    display:none;
}
.web{
    display:flex;
}
.styled-div .content02 {
position: absolute;
top: 0;
left: 0px;
width: 100%;
height: 100%;
display: flex;
flex-direction: row;
justify-content: center;
padding: 50px;
color: white;
align-items: center;
} 
.fade-in {
  opacity: 0;
  transition: opacity 1s ease-out;
}

.fade-in.fade-in-active {
  opacity: 1;
  transform: translateY(0);
}
.styled-div .content {
position: absolute;
top: 0;
left: -150px;
width: 100%;
height: 100%;
display: flex;
flex-direction: row;
justify-content: center;
padding: 50px;
color: white;
align-items: center;
}
.centered-text {
    position: absolute;
    top: 40%; 
    left: 15%; 
    transform: translate(-10%, -50%); 
    z-index: 10; 
    text-align: start;
    font-size: 2rem;
    line-height: 50px;
    font-weight: bold;
    color: #ffffff;
}
.venture{
    position: absolute;
    top: 47%;
    right: 12%; 
    transform: translate(-10%, -50%); 
    z-index: 10; 
    text-align: start;
    font-size: 2rem;
    line-height: 50px;
    font-weight: bold;
    color: #ffffff; 
}
.swiper-slide{
    max-height: 500px ; 
  }
</style>
