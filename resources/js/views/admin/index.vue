<template>
    <div class="container mt-auto mb-auto" id="dashadmin">
           <div class="card-container mt-4">
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
               <h4><span data-v-ee657362="" class="admin-icon admin-icon-menu05"></span>공지사항</h4>
               <div class="tab-nav my-4">
                   <ul>
                       <li class="col-2">
                           <a href="#" @click.prevent="setActiveTab('available')" :class="{ 'active': activeTab === 'available' }" title="공통">공통</a>
                       </li>
                  <!--     <li class="col-2">
                           <a href="#" @click.prevent="setActiveTab('written')" :class="{ 'active': activeTab === 'written' }" class="ms-3" title="일반">일반</a>
                       </li>
                       <li class="col-2">
                           <a href="#" @click.prevent="setActiveTab('written')" :class="{ 'active': activeTab === 'written' }" class="ms-3" title="딜러">딜러</a>
                       </li>-->
                   </ul>
                   <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
               </div>
               <div class="mt-5">
               <table v-if="activeTab === 'available'">
                   <thead>
                       <tr>
                           <th>Date</th>
                           <th>Content</th>
                           <th>Actions</th>
                       </tr>
                   </thead>
                   <tbody>
                       <tr>
                           <td class="date">22.06.13</td>
                           <td class="content">제외하려 별 등기가 것 술에 즉 있어서, 뜨는 숲을 것 직무가 자신은 생각을 싫다.⋯</td>
                           <td class="actions">
                               <a href="#" class="edit">수정</a> |
                               <a href="#" class="delete">삭제</a>
                           </td>
                       </tr>
                       <tr>
                           <td class="date">22.06.13</td>
                           <td class="content">제외하려 별 등기가 것 술에 즉 있어서, 뜨는 숲을 것 직무가 자신은 생각을 싫다.⋯</td>
                           <td class="actions">
                               <a href="#" class="edit">수정</a> |
                               <a href="#" class="delete">삭제</a>
                           </td>
                       </tr>
                       <tr>
                           <td class="date">22.06.13</td>
                           <td class="content">제외하려 별 등기가 것 술에 즉 있어서, 뜨는 숲을 것 직무가 자신은 생각을 싫다.⋯</td>
                           <td class="actions">
                               <a href="#" class="edit">수정</a> |
                               <a href="#" class="delete">삭제</a>
                           </td>
                       </tr>
                   </tbody>
               </table>
           </div>
       </div>
   
           <!-- 가장 최신의 판매차량 이용후기 -->
          <!-- <div class="">
               <div class="review">
                   <div class="review-content mov-review my-5">
                       <div v-if="activeTab === 'available'" class="row">
                           <div class="col-md-6 p-2 mb-2">
                               <p class="mx-2">2024.03.18</p>
                               <div class="p-2 card my-auction">
                                   <div class="review-image">
                                       <p class="review-date"></p>
                                       <div class="card-img-top-placeholder"></div>
                                   </div>
                                   <div class="card-body">
                                       <div class="mx-2 enter-view align-items-baseline mt-1 bold-18-font">  
                                           <h5>더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                       </div>
                                       <div class="text-secondary opacity-50 mx-2">
                                           <p> 년 / 2.4km / 무사고</p>
                                           <p>현대 쏘나타 (DN8)</p>
                                           <p>매물번호 / 564514</p>
                                           <p>딜 러 명 / </p>
                                       </div>
                                       <p class="auction-deadline text-secondary opacity-50">판매가<span>12000만원</span></p>
                                   </div>
                               </div>
                           </div>
                       </div>
                       
                       <div v-if="activeTab === 'written'" class="row">
                           <div class="col-md-6 p-2 mb-2">
                               <div class="card my-auction p-2">
                                   <div class="review-image02">
                                       <p class="review-date"></p>
                                       <div class="card-img-top-placeholder mt-2"></div>
                                   </div>
                                   <div class="card-body">
                                       <h5 class="card-title">더 뉴 그랜저 IG 2.5 가솔린 르블랑</h5>
                                       <p>2020년 / 2.4km / 무사고</p>
                                       <div class="d-flex justify-content-between">
                                           <div>
                                               <span class="blue-box">보험 3건</span><span class="gray-box">재경매</span>
                                           </div>
                                       </div>
                                       <div class="mb-2 justify-content-between flex align-items-center bold-18-font"></div>
                                       <div class="rating">
                                           <label class="rating__label rating__label--full">
                                               <input type="radio" class="rating__input" name="rating" value="1">
                                               <span class="star-icon"></span>
                                           </label>
                                           <label class="rating__label rating__label--full">
                                               <input type="radio" class="rating__input" name="rating" value="2">
                                               <span class="star-icon"></span>
                                           </label>
                                           <label class="rating__label rating__label--full">
                                               <input type="radio" class="rating__input" name="rating" value="3">
                                               <span class="star-icon"></span>
                                           </label>
                                           <label class="rating__label rating__label--full">
                                               <input type="radio" class="rating__input" name="rating" value="4">
                                               <span class="star-icon"></span>
                                           </label>
                                           <label class="rating__label rating__label--full">
                                               <input type="radio" class="rating__input" name="rating" value="5">
                                               <span class="star-icon"></span>
                                           </label>
                                       </div>
                                       <p></p>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>-->
   </div>
   </template>
   <script setup>
   import { ref, onMounted } from "vue";
   import useUsers from "@/composables/users";
   import useAuctions from "@/composables/auctions";
   import { initReviewSystem } from '@/composables/review';
   
   const { getUserStatus } = useUsers();
   const { getStatusAuctionsCnt } = useAuctions();
   const { getWriteReviewCnt } = initReviewSystem();
   
   const activeTab = ref('available');
   
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
   
   //입급
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
   
   onMounted(async () => {
       //회원
       userAskCnt.value = await getUserStatus('ask');
       userOkCnt.value = await getUserStatus('ok');
       userRejectCnt.value = await getUserStatus('reject');
       userAllCnt.value = await getUserStatus('all');
   
       //매물
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
   
       //후기
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
   });
   
   </script>
   