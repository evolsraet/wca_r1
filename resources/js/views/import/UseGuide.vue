<template>
    <div class="d-flex container justify-content-between my-5 px-4 gap-4 guide-card-content pt-4">
      <!-- 이용가이드 카드 -->
      <div class="guide-card pointer" @click.prevent="openAlarmModal" >
        <div class="guide-content ">
            <p class="guide-title">이용가이드</p>
            <p class="guide-description">위카옥션 이렇게 이용하세요!</p>
        </div>
        <img src="../../../img/use_icon1.png" alt="책 아이콘" class="guide-icon" />
        </div>
        <div class="guide-card pointer"@click.prevent="openAlarmModal02">
        <div class="guide-content">
            <p class="guide-title">수수료 안내</p>
            <p class="guide-description">소정의 정보제공 수수료가 있어요</p>
        </div>
        <img src="../../../img/use_icon2.png" alt="책 아이콘" class="guide-icon" />
        </div>
        <div class="guide-card pointer" @click.prevent="openAlarmModal03">
        <div class="guide-content">
            <p class="guide-title">진단오류 보상</p>
            <p class="guide-description">진단오류시 보상기준표를 안내합니다</p>
        </div>
        <img src="../../../img/use_icon3.png" alt="책 아이콘" class="guide-icon" />
      </div>
    </div>
  </template>
  
  <script setup>
  import { inject } from "vue";
  import arrow from '../../../../resources/img/dash-black.png';
  import bid from '../../../../resources/img/bid.png';
  import hand from '../../../../resources/img/hand.png';
  import Iconcare from '../../../../resources/img/Iconcare.png';
  import hand02 from '../../../../resources/img/hand02.png';
  import { cmmn } from '@/hooks/cmmn';
const swal = inject('$swal');
const { wica } = cmmn();
  const openAlarmModal = () => {
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
                    <div class="step-text">경매 종료 후 48시간 이내 고객이 판매대금을 선택합니다. 선택하지 않을 경우 입찰가 1위로 자동 낙찰됩니다.</div>
                </li>
            </ol>
            <ul class="list-block">
                <li>입찰 딜러 수는 제한이 없습니다.</li>
                <li>입찰후 차량 배송까지 시일이 걸립니다. 8일정도 후의 가격까지 고려하셔서 입찰해 주세요.</li>
                <li>최고가는 경매 종료후 공개됩니다.</li>
                <li>리스차량은 "승계후 완납 조건"으로 입찰해야 합니다.</li>
            </ul>
            <div class="bidding-section">
            <h3 class="size_32 bolder">2<span class="size_24 ms-2">견적 취소</span></h3>
            <ol class="mt-3 step-list">
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">1</span>
                    <div class="step-text">입찰을 한 후에도 취소 후 재견적을 입력할 수 있습니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-connector"></span>
                    <span class="step-circle">2</span>
                    <div class="step-text">견적취소는 입찰 종료 전 30분전까지 가능합니다.</div>
                </li>
                <li class="step-item">
                    <span class="step-circle">3</span>
                    <div class="step-text">견적실수시 본사(1544-2165)에 즉시 연락주시기 바랍니다.</div>
                </li>
            </ol>
            <ul class="list-block">
                <li>1회 견적실수 패널티 3일 이용정지</li>
                <li>2회 견적실수 패널티 1달 이용정지</li>
                <li>3회 견적실수 패널티 계정 정지</li>
            </ul>
             <div class="bidding-section">
            <h3 class="size_32 bolder">3<span class="size_24 ms-2">차량 인수</span></h3>
            <ol class="mt-3 step-list">
                <li class="step-item align-items-start">
                    <span class="step-connector02"></span>
                    <span class="step-circle">1</span>
                    <div class="step-text">낙찰이 확정되면 인수정보를 입력합니다. (딜러 : 매수자, 고객 : 탁송정보)
                      <p>※ 낙찰 확정일 익일부터 48시간 이내 인수정보 반드시 입력</p>
                      <p>※ 압류/저당, 사업자유무, 비사업용 여부등을 파악하여 이전 필요서를 차와 함께 보내드립니다.</p>
                    </div>
                </li>
                <li class="step-item align-items-start">
                    <span class="step-connector02"></span>
                    <span class="step-circle">2</span>
                    <div class="step-text">탁송 2시간전까지 나이스에스크로 계좌로 차량 대금 입금이 필요합니다. (입급증 완료시 입금증 발송)
                      <p>※ 탁송 2시간전까지 대금이 입금되지 않으면 낙찰 취소가 됩니다. 11시 이전에 탁송이 진행되는 경우는 전라 18시까지 차량 대금을 입급해야합니다.</p>
                    </div>
                </li>
                <li class="step-item align-items-start">
                    <span class="step-circle">3</span>
                    <div class="step-text">거래 마무리
                      <p>※ 인수후 영업일 기준 2일내 진단오류에 대한 클레임을 제기할 수 있습니다. (성능기록부 및 증빙 자료 제출)</p>
                    </div>
                </li>
            </ol>
            <ul class="list-block">
                <li>1회 견적실수 패널티 3일 이용정지</li>
                <li>2회 견적실수 패널티 1달 이용정지</li>
                <li>3회 견적실수 패널티 계정 정지</li>
            </ul>
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

const openAlarmModal02 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-5">
          <h5>위카옥션의 수수료 체계를 안내해드려요</h5>
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
                <th class="px-6 py-3 bg-gray-50 text-center" style="width: 50%;">수수료</th>
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

const openAlarmModal03 = () => {
  const text = `
    <div class="intro-container container text-start">
        <div class="mt-5">
          <h5>진단오류시 보상은 이렇게 진행돼요</h5>
          <h4 class="bold">진단오류시 보상기준표</h4>
        </div>
      <div class="o_table_mobile my-5">
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
<style scoped>

@media (max-width: 480px) { /* 모바일 */
  .guide-card-content{
    gap:10px !important; 
  }
  .guide-title{
    text-align: center;
    font-size: 11px;
}
}

</style>