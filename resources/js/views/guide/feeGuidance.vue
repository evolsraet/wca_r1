<template>
    <div class="guide-card pointer"@click.prevent="openAlarmModal02">
        <div class="guide-content">
            <p class="guide-title">수수료 안내</p>
            <p class="guide-description">소정의 정보제공 수수료가 있어요</p>
        </div>
        <img src="../../../img/use_icon2.png" alt="책 아이콘" class="guide-icon" />
    </div>
</template>
<script setup>
import { ref, onMounted, computed, watch,inject } from 'vue';
import { cmmn } from '@/hooks/cmmn';
import arrow from '../../../../resources/img/dash-black.png';
import bid from '../../../../resources/img/bid.png';
import hand from '../../../../resources/img/hand.png';
import Iconcare from '../../../../resources/img/Iconcare.png';
import hand02 from '../../../../resources/img/hand02.png';
const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();
const swal = inject('$swal');

const openAlarmModal02 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-5">
          <h5>위카옥션은 간단한 정보제공 수수료 체계를 가지고 있습니다.</h5>
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
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">수수료 <span class="text-danger">(부과세 포함)</span></th>
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
               <tr>
                <td class="px-6 py-4 text-center">1000만원 이하</td>
                <td class="px-6 py-4 text-center">30만원</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">3000만원 이하</td>
                <td class="px-6 py-4 text-center">35만원</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">3000만원 이상</td>
                <td class="px-6 py-4 text-center">40만원</td>
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
</script>