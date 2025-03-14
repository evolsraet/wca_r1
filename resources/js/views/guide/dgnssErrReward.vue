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
              <tr>
                <td class="px-6 py-4 text-center">도어</td>
                <td class="px-6 py-4 text-center">2%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">트렁크리드</td>
                <td class="px-6 py-4 text-center">2%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">쿼터패널</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">루프패널</td>
                <td class="px-6 py-4 text-center">6%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">사이드실패널</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">프론트패널</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">인사이드패널</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">사이드멤버</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">휠하우스</td>
                <td class="px-6 py-4 text-center">5%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">필러패널</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">대쉬패널</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">트렁크플로어</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">리어패널</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">트렁크패널</td>
                <td class="px-6 py-4 text-center">3%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">라디에이터 서포트</td>
                <td class="px-6 py-4 text-center">1%</td>
                <td class="px-6 py-4 text-center">-</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">패키지트레이</td>
                <td class="px-6 py-4 text-center">3%</td>
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
                <th class="px-6 py-3 bg-gray-50 text-start mx-4" style="width: 80%;">주변 장치</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                  <td class="px-6 py-4 text-center">엔진</td>
                  <td class="px-6 py-4 text-start">· 엔진 전장품 일체 (제너레이터, 콤프레셔, 인젝터, 각종 모터류, 예열장치, 케이블, 배선류, 센서류, 릴레이, 점화 플러그, 배전기, 스위치류 등)<br>
                  · 냉각장치 (라디에이터) 터보</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">변속기추진축</td>
                <td class="px-6 py-4 text-start">· 클러치 케이블 및 변속 조작장치<br>· 입/출력 센서<br>· 인히비터 스위치</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">앞뒤 차축</td>
                <td class="px-6 py-4 text-start">· 현가장치 부품 (쇼바[전자, 에어 등], 판스프링 등) 제동장치 부품 (라이닝, 드럼, 브레이크 디스크 등) 조향장치 부품 (웜기어, MDPS, 조인트 등) ※ 휠 허브, 너클, 킹핀, 볼조인트, 허브베어링 등 앞뒤 차축 관련 부품 포함</td>
              </tr>
              <tr>
                <td class="px-6 py-4 text-center">기타</td>
                <td class="px-6 py-4 text-start">· 배터리(AGM 포함), 고무부트, 상시 4WD, 연료펌프, 연료필터, 플랜저, 벨트류, 엔진/미션 마운팅, 소음기, 오일쿨러, 크로스멤버, 타이어, 휠 등 주기적인 교환 필요 부품의 결품 또는 결함</td>
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