<template>
    <div class="btn btn-outline-primary w-100 expectedPrice" @click.prevent="openAlarmModal06" v-if="props.propData === true" >차량출품 조건 및 유의사항</div>
    <div class="guide-card pointer" @click.prevent="openAlarmModal06" v-if="props.propData === false" >
       <div class="guide-content ">
           <p class="guide-title">차량출품 조건 및 유의사항</p>
           <p class="guide-description">차량출품전 내용을 확인하세요</p>
       </div>
       <img src="../../../img/use_icon1.png" alt="책 아이콘" class="guide-icon" />
   </div>
</template>
<script setup>
   import { ref, onMounted, computed, watch,inject, defineProps } from 'vue';
   import { cmmn } from '@/hooks/cmmn';
    
   const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();
   const swal = inject('$swal');
   const isPopup = ref(true);
   const isPopupStorage = localStorage.getItem('carEntryGuidePopup') ? localStorage.getItem('carEntryGuidePopup') : 'false';
   // 부모 컴포넌트에서 props 내용이 전달 되었는지 확인 
   const props = defineProps({
    propData: {
        type: Boolean,
        default: false
    }
   });
      
   const openAlarmModal06 = () => {

    localStorage.removeItem('carEntryGuidePopup');

     const text = `
       <div class="container text-start">
        <div class="bidding-section">
            <h3 class="size_32 bolder mx-2"><span class="size_24 ms-2">자동차 출품 조건</span></h3>
            <ul class="list-block" style=" list-style-type: none !important; margin-left: 0px !important; border: 0px !important; background-color: transparent !important;">
                <li class="size_15 mt-2"> - 입찰 딜러 수는 제한이 없습니다.</li>
                <li class="size_15 mt-2"> - 입찰후 차량 배송까지 시일이 걸립니다. 8일정도 후의 가격까지 고려하셔서 입찰해 주세요.</li>
                <li class="size_15 mt-2"> - 최고가는 경매 종료후 공개됩니다.</li>
                <li class="size_15 mt-2"> - 리스차량은 "승계후 완납 조건"으로 입찰해야 합니다.</li>
            </ul>

            <h3 class="size_32 bolder mx-2"><span class="size_24 ms-2">유의사항</span></h3>
            <ul class="list-block" style=" list-style-type: none !important; margin-left: 0px !important; border: 0px !important; background-color: transparent !important;">
                <li class="size_15 mt-2"> - 출품자는 차량 등록 시 주요 사항(차량의 상태, 사고 이력, 침수 여부, 주행거리, 접합차량, 특이이력, 엔진 및 미션 이상 등 가격에 중대한 영향을 미치는 사항)을 정확히 기재해야 합니다. 만약 허위 기재나 미기재시는 출품자가 보상 또는 환불을 해야 합니다.</li>
                <li class="size_15 mt-2"> - 출품자는 출품차량의 소유자이거나 출품차량 소유자로부터 적법한 위임을 받은 자이어야 하며, 처분권을 가진 자로부터 적법한 위임을 받지 않거나 관련 법령에 의하여 부적격자로 판단될 경우에는 경매장은 출품을 제한할 수 있습니다.</li>
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
         if(result.isOk){
            // ok 를 하면, 이 창을 띄우는걸 잠시 멈춘다. 
            isPopup.value = false;
            // 로컬스토리지에 저장 
            if(props.propData){
                localStorage.setItem('carEntryGuidePopup', 'true');
            }
         }
       })
       .confirm(text); // 모달 내용 설정
   };

   onMounted(() => {

    if(props.propData === true && isPopupStorage === 'false'){
        openAlarmModal06();
    }
   });

   </script>
   
   <style scoped>   
    .list-block {
        list-style-type: none !important;
        margin-left: 0px !important;
        background-color: transparent !important;
        border: 0px !important;
    }
   </style>