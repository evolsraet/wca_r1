<template>
<div class="guide-card pointer" @click.prevent="openAlarmModal04">
<div class="guide-content">
    <p class="guide-title">패널티 부과</p>
    <p class="guide-description">경매장 이용 오류시 패널티가 부과됩니다</p>
</div>
<img src="../../../img/use_icon7.png" alt="책 아이콘" class="guide-icon" />
</div>   
 </template>
 <script setup>
import { ref, onMounted, computed, watch,inject } from 'vue';
import { cmmn } from '@/hooks/cmmn';
import pannel01 from '../../../img/pannel01.png';
import pannel02 from '../../../img/pannel02.png';
import pannel03 from '../../../img/pannel03.png';
import pannel04 from '../../../img/pannel04.png';
 const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();
 const swal = inject('$swal');


const openAlarmModal04 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-4 mb-4">
          <h5>경매장 이용 오류시 패널티를 안내해드려요</h5>
          <h4 class="bold">패널티 부과 안내</h4>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-md-6 ">
              <div class="pannel-box" style="height: 290px;">
                <img src="${pannel01}" alt="패널티 아이콘 1" width="35px" class="mb-3"/>
                <h5 class="mb-3">이의제기</h5>
                <p>패널티 부과시점으로부터 1주일 <br>
                이내에 이메일 부탁드립니다.<br>
                (Email : wecar@wecar.auction)</p>
                <p></p>
                <p></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="pannel-box">
                <img src="${pannel02}" alt="패널티 아이콘 2" width="35px" class="mb-3"/>
                <h5 class="mb-3">거래정지</h5>
                <p>· 직접거래 유도</p>
                <p>· 고의로 차량 훼손 후, 클레임 청구</p>
                <p>· 수수료 입금을 피하고자 거래 실패 보고</p>
                <p>· 딜러나 매매상이 아닌자에게 계정 양도</p>
                <p>· 반말, 욕설 등 불량한 언행으로 인한 클레임 발생</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="pannel-box">
                <img src="${pannel03}" alt="패널티 아이콘 3" width="35px" class="mb-3"/>
                <h5 class="mb-3">견적오류</h5>
                <p>· 1회 견적실수 패널티 3일 이용정지</p>
                <p>· 2회 견적실수 패널티 1달 이용정지</p>
                <p>· 3회 견적실수 패널티 계정 정지</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="pannel-box">
                <img src="${pannel04}" alt="패널티 아이콘 4" width="35px" class="mb-3"/>
                <h5 class="mb-3">경고</h5>
                <p>· 거래 후 48시간 내 등록증 미업로드</p>
                <p>· 딜러 책임으로 분쟁 발생</p>
                <p>· 특별한 사유없이 48시간 이상 연락 두절</p>
              </div>
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

 </script>