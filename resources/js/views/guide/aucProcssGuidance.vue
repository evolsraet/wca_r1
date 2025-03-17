<template>
<button class="btn primary-btn w-100 bolder" @click="openAlarmModal04" v-if="props.propData === true" >경매 신청하기</button>
 <div class="guide-card pointer" @click.prevent="openAlarmModal04" v-if="props.propData === false" >
    <div class="guide-content ">
        <p class="guide-title">경매 절차 안내</p>
        <p class="guide-description">이런 순서대로 경매가 이루어져요</p>
    </div>
    <img src="../../../img/use_icon4.png" alt="책 아이콘" class="guide-icon" />
</div>
</template>
<script setup>
import { ref, onMounted, computed, watch,inject, defineProps } from 'vue';
import { cmmn } from '@/hooks/cmmn';
import { useRouter } from 'vue-router';
import imgInfo from'../../../img/auction-detil.png';
import imgInfoIcon from'../../../../resources/img/q-mark.png';
import pannel01 from '../../../../resources/img/pannel01.png';
import pannel02 from '../../../../resources/img/pannel02.png';
import pannel03 from '../../../../resources/img/pannel03.png';
import pannel04 from '../../../../resources/img/pannel04.png';
    const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();
    const swal = inject('$swal');
    const router = useRouter();
    const props = defineProps({
        propData: {
            type: Boolean,
            default: false
        }
    });
   
const openAlarmModal04 = () => {
  const text = `
    <h5 class="text-start mb-3">경매 진행 순서 안내</h5>
    <div class="sellInfo my-3 p-4 mb-4" style="position: relative;">
        <div class="auction-guid-popup-container">
            <img src="${imgInfo}" alt="Auction Detail" class="auction-detail-img">
            <div class="auction-guid-popup">
                <div class="step01">
                    <p class="steps">STEP 01</p>
                    <p class="title">경매신청</p>
                    <p class="sub text-end">경매 신청 후,<br>24시간 이후부터<br>진단 일자를 지정해<br>차량을 진단합니다.</p>
                </div>
                <div class="step02">
                    <p class="steps">STEP 02</p>
                    <p class="title">평가사 방문 진단</p>
                    <p class="sub text-start">세부적인 진단 시간은<br>평가사와 협의하여<br>정해주세요.</p>
                </div>
                 <div class="step03">
                    <p class="steps">STEP 03</p>
                    <p class="title">경매</p>
                    <p class="sub text-end">경매는 48시간동안<br>진행됩니다.</p>
                </div>
                <div class="step04">
                    <p class="steps">STEP 04</p>
                    <p class="title">낙찰(딜러 선택)</p>
                    <p class="sub text-start">경매가 종료된 이후<br>48시간 이내에 고객이<br>판매딜러를 선택합니다.</p>
                </div>
                <div class="step05">
                    <p class="steps">STEP 05</p>
                    <p class="title new-style-step">탁송 및 대금<img src="${imgInfoIcon}" alt="Auction Detail" class="auction-icon"></p>
                    <p class="sub text-end">탁송은 당사 직접 진행하며,<br>탁송 출발전 1시간까지<br>나이스 에스크 계좌로<br>매매대금이 입금됩니다.</p>
                </div>
                <div class="step06">
                    <p class="steps">STEP 06</p>
                    <p class="title">매매대금 입금</p>
                    <p class="sub text-start">탁송기사가 이전에 필요한 <br>서류를 받고 확인을 누르면<br>매매대금이 나이스 에스크로<br>계좌에서 개인 계좌로<br>송부됩니다.</p>
                </div>
                <div class="step07">
                    <p class="steps">STEP 07</p>
                    <p class="title">이전</p>
                    <p class="sub text-end">탁송완료후 48시간 내에<br/> 이전이 완료 됩니다.</p>
                </div>
            </div>
        </div>
        <div id="tooltip-area" class="tooltip-area" style="cursor: pointer; position: absolute; width: 37px; height: 43px;"></div>
        <div id="tooltip" class="p-4 tooltip2" style="display: none; position: absolute; background: white; color: black; padding: 10px; border-radius: 5px; border:1px solid #aaaebe;">
            <div class="text-center">
                <h5>※ 탁송 및 대금</h5>
                <div class="text-start mt-3">
                    <p>판매딜러가 선택되면 고객의 탁송 희망날짜에 <br>탁송이 진행됩니다.</p>
                    <p class="tc-red">( 탁송정보 낙찰확정 후 48시간 이내에 입력 )</p>
                    <p class="mt-2 tc-blue">탁송진행전 1시간전까지 나이스 에스크로 <br>계좌로 판매대금이 입금됩니다.</p>
                    <p class="mt-2">입금이 완료되면 나이​스에서 입급 완료<br>증명서가 고객에게 송부됩니다.</p>
                </div>
            </div>
            <button id="close-tooltip" style="position: absolute; top: 5px; right: 5px; border: none; background: transparent; cursor: pointer;">X</button>
        </div>
    </div>`;

  wica.ntcn(swal)
    .useHtmlText() // HTML 태그 활성화
    .useClose()
    .addClassNm('primary-check') // 클래스명 설정
    .addOption({ padding: 20 }) // swal 옵션 추가
    .callback(function (result) {
      // 결과 처리 로직
      if(props.propData === true){
        //if (result.isOk) {
            router.push({ path: '/selldt2' }); 
        //}
      }
    })
    .confirm(text); // 모달 내용 설정



    setTimeout(() => {
        const tooltipArea = document.getElementById('tooltip-area');
        const tooltip = document.getElementById('tooltip');
        const closeTooltipButton = document.getElementById('close-tooltip');

        const showTooltip = () => {
            tooltip.style.display = 'block';
        };

        const hideTooltip = () => {
            tooltip.style.display = 'none';
        };

        // Tooltip 영역 클릭 이벤트
        tooltipArea.addEventListener('click', (event) => {
            event.stopPropagation(); // 클릭 이벤트가 전파되지 않도록 방지
            if (tooltip.style.display === 'block') {
                hideTooltip();
            } else {
                showTooltip();
            }
        });

        // Tooltip 닫기 버튼 클릭 이벤트
        closeTooltipButton.addEventListener('click', (event) => {
            event.stopPropagation();
            hideTooltip();
        });

        // 다른 곳 클릭 시 Tooltip 닫기
        document.addEventListener('click', () => {
            hideTooltip();
        });

        // 모바일 터치 이벤트
        tooltipArea.addEventListener('touchstart', (event) => {
            event.stopPropagation();
            event.preventDefault();
            if (tooltip.style.display === 'block') {
                hideTooltip();
            } else {
                showTooltip();
            }
        });

        closeTooltipButton.addEventListener('touchstart', (event) => {
            event.stopPropagation();
            event.preventDefault();
            hideTooltip();
        });

        document.addEventListener('touchstart', (event) => {
            if (!tooltip.contains(event.target)) {
                hideTooltip();
            }
        });
    }, 100);

};
</script>
