<template>
    <a href="#" class="btn-apply-ty05 mt-0 p-0" @click.prevent="openAlarmModal07">탁송운영 안내</a>
</template>
<script setup>
   import { ref, onMounted, computed, watch,inject } from 'vue';
   import { cmmn } from '@/hooks/cmmn';
    
   const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();
   const swal = inject('$swal');

   const openAlarmModal07 = () => {
     const text = `
       <div class="container text-start">
        <div class="bidding-section">
            <h3 class="size_32 bolder mx-2"><span class="size_24 ms-2">탁송운영</span></h3>
            <ul class="list-block">
                <li class="size_15 mt-2">탁송은 평균 2~3일 최대 5일안으로 정해집니다</li>
                <li class="size_15 mt-2">최대한 빠른 날짜로 고객과 조율한뒤 안내해드립니다</li>
                <li class="size_15 mt-2">탁송수행은 위카옥션 지정제휴업체에서 수행하며, 지정된 비용을 청구합니다</li>
                <li class="size_15 mt-2">탁송이 잘 안잡히는 상황에서는 배정지연비가 추가될수 있으며, 사전에 딜러분들과 상의합니다(우천, 금요일, 휴일등)</li>
                <li class="size_15 mt-2">기름이 부족한 경우, 실제 주유비용이 청구됩니다.</li>
                <li class="size_15 mt-2">탁송비용은 지정업체에서 청구합니다.</li>
                <li class="size_15 mt-2">탁송진행경과는 업체의 시스템을 통해 안내해드립니다</li>
            </ul>
        </div>
    </div>`;
   
     wica.ntcn(swal)
       .useHtmlText() // HTML 태그 활성화
       .useClose()
       .addClassNm('primary-check') // 클래스명 설정
       .addOption({ padding: 20 }) // swal 옵션 추가
       .callback(function (result) {
         // 결과 처리 로직
       })
       .confirm(text); // 모달 내용 설정
   };

   </script>
   