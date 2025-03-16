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
import { cmmn } from '@/hooks/cmmn';
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
                    <div class="step-text">경매 종료 후 <span class="text-danger"><strong>48시간 이내</strong></span> 고객이 판매대금을 선택합니다. 선택하지 않을 경우 입찰가 1위로 자동 낙찰됩니다.</div>
                </li>
            </ol>
            <ul class="list-block tc-sub05">
                <li>· &nbsp; 입찰 딜러 수는 제한이 없습니다.</li>
                <li>· &nbsp; 입찰후 차량 배송까지 시일이 걸립니다. 8일정도 후의 가격까지 고려하셔서 입찰해 주세요.</li>
                <li>· &nbsp; 최고가는 경매 종료후 공개됩니다.</li>
                <li>· &nbsp; 리스차량은 "승계후 완납 조건"으로 입찰해야 합니다.</li>
            </ul>

            <div style="height: 20px;"></div>
            
            <h3 class="size_32 bolder mx-2">2<span class="size_24 ms-2">견적취소</span></h3>
            <ol class="mt-3 step-list">
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">1</span>
                    <div class="step-text">입찰을 한 후에도 취소 후 재견적을 입력할 수 있습니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">2</span>
                    <div class="step-text">견적취소는 입찰 종료 전 <span class="text-danger"><strong>30분 전까지</strong></span> 가능합니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-circle">3</span>
                    <div class="step-text">견적실수시 본사( <span class="text-danger"><strong>1544-2165</strong></span> )에 즉시 연락주시기 바랍니다.</div>
                </li>
            </ol>

            <ul class="list-block tc-sub05">
                <li>· &nbsp; 1회 견적실수 패널티 <strong>3일 이용정지</strong></li>
                <li>· &nbsp; 2회 견적실수 패널티 <strong>1달 이용정지</strong></li>
                <li>· &nbsp; 3회 견적실수 패널티 <strong>계정 정지</strong></li>
            </ul>

            <div style="height: 20px;"></div>

            <h3 class="size_32 bolder mx-2">3<span class="size_24 ms-2">차량인수</span></h3>
            <ol class="mt-3 step-list">
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">1</span>
                    <div class="step-text margin-zero">
                        <ul>
                            <li>낙찰이 확정되면 인수정보를 입력합니다.(딜러:매수자, 고객:탁송정보)</li>
                            <li>※ 낙찰 확정일 익일부터 <span class="text-danger"><strong>48시간 이내</strong></span> 인수정보 반드시 입력.</li>
                            <li>※ 압류/저당, 사업자유무, 비사업자용 여부등을 파알하여 이전 필요서를 차와 함께 보내드립니다.</li>
                        </ul>
                    </div>
                </li>
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">2</span>
                    <div class="step-text margin-zero">
                        <ul>
                            <li>탁송 2시간전까지 나이스에스크로 계좌로 차량 대금 입금이 필요합니다.(입금증 완료시 입금증 발송)</li>
                            <li>※ 탁송 2시간전까지 대금이 입금되지 않으면 낙찰 취소가 됩니다. 11시 이전에 탁송이 진행되는 경우는 전라 18시까지 차량 대금을 입금해야합니다.</li>
                        </ul>
                    </div>
                </li>
                <li class="step-item">
                    <span class="step-circle">3</span>
                    <div class="step-text margin-zero">
                        <ul>
                            <li>※ 인수후 영업일 기준 2일내 진단오류에 대한 클레임을 제기할 수 있습니다.(성능기록부 및 증빙 자료 제출)</li>
                        </ul>
                    </div>
                </li>
            </ol>
            
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

<style>
.list-block {
    border: 2px solid #ddd;
    padding:10px 10px 10px 10px;
    border-radius: 10px;
    line-height: 1.8;
    background-color: #f8f8f8;
    list-style: none !important;
}
.margin-zero ul{
    margin-top:10px !important;
    padding: 0 !important;
}
</style>