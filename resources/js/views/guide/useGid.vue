<template>
    <div class="guide-card pointer" @click.prevent="openAlarmModal01" >
            <div class="guide-content ">
                <p class="guide-title">이용가이드</p>
                <p class="guide-description">위카옥션 이렇게 이용하세요!</p>
            </div>
        <img src="../../../img/use_icon1.png" alt="책 아이콘" class="guide-icon" />
    </div>
</template>
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
const swal = inject('$swal');
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
</script>