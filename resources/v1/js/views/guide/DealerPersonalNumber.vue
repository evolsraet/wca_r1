<template>
    
    <a @click="isDealerNumberEvent" class="icon-link mt-3">
        <img src="../../../img/Icon-file.png" class="ms-2" alt="주민등록번호(법인등록번호)고지사항">주민등록번호(법인등록번호) 수집 동의
    </a>

</template>
<script setup>
   import { ref, onMounted, computed, watch,inject, defineProps } from 'vue';
   import { cmmn } from '@/hooks/cmmn';
    
   const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();
   const swal = inject('$swal');
   const isPopup = ref(true);
   // 부모 컴포넌트에서 props 내용이 전달 되었는지 확인 
   const props = defineProps({
    propData: {
        type: Boolean,
        default: false
    },
    mode: {
        type: String,
        default: 'check1'
    }
   });
      
   const isDealerNumberEvent = () => {


    const text = `<div style="height: 500px; overflow-y: auto; text-align: left;" class="info-popup">
        <h4 style="color: #333; margin-bottom:10px; text-align: center;">주민등록번호(법인등록번호) 수집 및 이용 동의</h4>

        <p style="font-size:16px; padding-left:20px;">차량 명의이전 행정처리를 위해 아래와 같이 주민등록번호(또는 법인등록번호)를 수집·이용합니다.</p>

        <h5 style="color: #555; margin-bottom:10px; margin-top:10px;">1. 수집</h5>
        <ul>
            <li style="padding-bottom:5px;">- 개인: 주민등록번호</li>
            <li style="padding-bottom:5px;">- 법인: 법인등록번호</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">2. 수집목적</h5>
        <ul>
            <li style="padding-bottom:5px;">- 자동차관리법 등 관계 법령에 따라 차량 명의이전 신청 및 서류 검토.</li>
            <li style="padding-bottom:5px;">- 본인 식별 및 실명 확인.</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">3. 수집 근거</h5>
        <ul>
            <li style="padding-bottom:5px;">- 주민등록법 제29조 제1항</li>
            <li style="padding-bottom:5px;">- 자동차관리법 시행규칙 제25조</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">4. 보유 및 이용기간</h5>
        <ul>
            <li style="padding-bottom:5px;">- 명의이전 처리 완료 후 5년 (전자상거래법에 따른 보존 의무 포함)</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">5. 동의 거부 권리 및 불이익</h5>
        <ul>
            <li style="padding-bottom:5px;">- 귀하는 본 수집·이용에 대해 동의하지 않으실 수 있으나, 이 경우 명의이전 신청이 불가능합니다.</li>
        </ul>

        <h5 style="color: #555; margin-bottom:10px;">6. 주민등록법 제 29조 제 1항</h5>
        <p style="font-size:16px; padding-left:20px;">「주민등록법」 제29조 제1항에 따라, 주민등록표(등본 또는 초본)의 열람 또는 교부는 정해진 수수료 납부 후, 시장·군수·구청장(읍·면·동장 포함)에게 신청할 수 있습니다.</p>
        

        <h5 style="color: #555; margin-bottom:10px; margin-top:20px;">7. 자동차관리법 시행규칙 제25조</h5>
        <p style="font-size:16px; padding-left:20px;">1. 방치자동차는 원칙적으로 일반경쟁입찰을 통해 매각됩니다. 단, 아래의 경우에는 수의계약으로 매각할 수 있습니다.</p>
        <ul>
            <li style="padding-bottom:5px;">1) 매각 예정가격이 500만 원 이하인 경우</li>
            <li style="padding-bottom:5px;">2) 2회 이상 일반경쟁입찰이 유찰된 경우</li>
        </ul>

        <p style="font-size:16px; padding-left:20px;">2. 일반경쟁입찰로 매각 시, 매각 예정일 7일 전까지 다음의 내용이 공고됩니다.</p>
        <ul>
            <li>1. 매각 대상 자동차의 등록번호 및 주요 제원</li>
            <li>2. 매각 장소 및 매각 일시</li>
        </ul>

        <p style="font-size:16px; padding-left:20px;">3. 매각 완료 시, 다음의 내용을 매수인에게 서면 통지합니다.</p>
        <ul>
            <li>1. 매각된 자동차의 등록번호 및 매각일시</li>
            <li>2. 매각 과정</li>
            <li>3. 매수인의 성명 및 주소 (법인의 경우 명칭 및 대표자 포함)</li>
        </ul>

      </div>
    `;

    wica.ntcn(swal)
      .useHtmlText() // HTML 태그 활성화
      .useClose()
      .addClassNm('intromodal') // 클래스명 설정
      .addOption({ padding: 20, height:840 }) // swal 옵션 추가
      .callback(function (result) {
        // 결과 처리 로직
      })
      .confirm(text); // 모달 내용 설정
    }

   onMounted(() => {

   });

   </script>
   
