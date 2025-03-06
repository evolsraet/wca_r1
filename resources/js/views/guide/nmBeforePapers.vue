<template>
<div class="guide-card pointer"@click.prevent="openAlarmModal05">
    <div class="guide-content">
        <p class="guide-title">명의 이전시 필요 서류</p>
        <p class="guide-description">이런 서류들이 필요해요</p>
    </div>
    <img src="../../../img/use_icon5.png" alt="책 아이콘" class="guide-icon" />
    </div>
</template>
<script setup>
import { ref, onMounted, computed, watch,inject } from 'vue';
import { cmmn } from '@/hooks/cmmn';
import pannel01 from '../../../../resources/img/pannel01.png';
import pannel02 from '../../../../resources/img/pannel02.png';
import pannel03 from '../../../../resources/img/pannel03.png';
import pannel04 from '../../../../resources/img/pannel04.png';
const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();
const swal = inject('$swal');
const openAlarmModal05 = () => {
  const text = `
    <div>
        <div>
          <h5>명의 이전시 필요서류 안내 데모</h5>
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