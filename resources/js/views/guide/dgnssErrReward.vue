<template>
   <div class="guide-card pointer" @click.prevent="openAlarmModal03">
    <div class="guide-content">
        <p class="guide-title">진단오류 보상</p>
        <p class="guide-description">진단오류시 보상기준표를 안내합니다</p>
    </div>
    <img src="../../../img/use_icon3.png" alt="책 아이콘" class="guide-icon" />
    </div>
</template>
<script setup>
import { ref, onMounted, computed, watch,inject } from 'vue';
import { cmmn } from '@/hooks/cmmn';
const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();
const swal = inject('$swal');
const openAlarmModal03 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-5">
          <h5>진단오류시 보상은 이렇게 진행돼요</h5>
          <h4 class="bold">진단오류 보상진행 안내</h4>
        </div>
      <div class="bidding-section">
        <div class="headers d-flex">
          <h2 class="bold me-2">1</h2>
          <h5 class="bold">진단결과에 중대한 오류가 있다면, 인수 후 영업일 기준 2일내 클레임이 가능합니다.</h5>
        </div>
      <ul class="p-4 list-block tc-primary tc-sub05">
        <p class="bold">현장진단의 특성상 클레임 제외사항이 발생합니다. 아래의 사항은 클레임 대상에서 제외됩니다.</p>
          <p>· 문콕,생활기스, 사용감, 미세누유, 소모품 상태, 썬팅손상 등 경미한 사항</p>
          <p>· 단순 판금/도장, 라디에이터 서포트, 크로스맴버 교환 풀림, FRP 또는 비금속 재질의 교환/파손 등.</p>
          <p>· 하체, 조향, 제동등의 소모성 부품(비내구성 부품 호함) 관련</p>
          <p>· 하부 진단이 반드시 필요한 누유</p>
          <p>· 자동차 제작사에서 보증수리 가능한 기능문제</p>
          <p>· 정지상태에서는 판단불가한 고장(주행중에 확인가능한 고장)</p>
          <p>· 차량 10년 이상, 주행거리 200,000km 이상 경과된 차량</p>
          <p>· 주관적인 측면에서 문제제기(냄새, 이음, 진동등)</p>
      </ul>
      </div>
      <div class="headers d-flex">
        <h2 class="bold me-2">2</h2>
        <h5 class="bold">감가항목별 보상기준</h5>
      </div>
      <div class="o_table_mobile mb-5">
        <div class="tbl_basic tbl_fee">
          <table class="table">
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">감가 항목</th>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">감가율</th>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">비고</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-6 py-4 text-center">후드</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
               <tr>
                <td class="px-6 py-4 text-center">프론트휀더</td>
                <td class="px-6 py-4 text-center">2%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              </tbody>
              </table>
              <p>※ 클레임 보상 : 매입금액 x 감가율 = 보상금액 (보상한도 100만원)</p>
              <p>※ 교환 / 판금에 대한 정의는 자동차관리법상 성능상태점검기준에 따라 적용</p>
              <p>※ 판금오류시 보상비율을 교환대비 50% 감가</p>
              <p>※ 사이드맴버, 휠하우스, 인사이드 패널은 좌우앞뒤가 상관없이 단일품목으로 감가</p>
        </div>
      </div>
      <div class="headers d-flex">
        <h2 class="bold me-2">3</h2>
        <h5 class="bold">비내구성 부품(소모품)</h5>
      </div>
      <div class="o_table_mobile mb-5">
        <div class="tbl_basic tbl_fee">
          <table class="table">
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 20%;">구분</th>
                <th class="px-6 py-3 bg-gray-50 text-start mx-4" style="width: 80%;">감가율</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-6 py-4 text-center">엔진</td>
                <td class="px-6 py-4 text-start">· 엔진 전장품 일체(제너레이터, 콤프레셔, 인젝터, 각종 모터류, 예열장치, 케이블, 배선류, 센서류, 릴레이, 점화플러그, 배전기, 스위치류 등)<br>
                · 냉각장치(라디에이터)<br>
                · 터보</td>
              </tr>
               <tr>
                <td class="px-6 py-4 text-center">변속기 추진축</td>
                <td class="px-6 py-4 text-start">· 클러치 케이블 및 변속조작장치<br>· 입/출력 센서<br>· 인히비터스위치</td>
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