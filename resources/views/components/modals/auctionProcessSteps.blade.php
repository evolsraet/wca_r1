<div class="timeline" x-data="auctionProcessSteps()">
    <div class="d-flex mb-4">
      <div class="position-relative">
        <div class="step-icon">01</div>
        <div class="step-line"></div>
      </div>
      <div class="ms-3">
        <h6 class="fw-bold">경매신청</h6>
        <p class="text-muted mb-0">경매 신청 후, 24시간 이후부터 진단 일자를 지정해 차량을 진단합니다.</p>
      </div>
    </div>
  
    <div class="d-flex mb-4">
      <div class="position-relative">
        <div class="step-icon">02</div>
        <div class="step-line"></div>
      </div>
      <div class="ms-3">
        <h6 class="fw-bold">평가사 방문 진단</h6>
        <p class="text-muted mb-0">세부적인 진단 시간은 평가사와 협의하여 정해주세요.</p>
      </div>
    </div>
  
    <div class="d-flex mb-4">
      <div class="position-relative">
        <div class="step-icon">03</div>
        <div class="step-line"></div>
      </div>
      <div class="ms-3">
        <h6 class="fw-bold">경매</h6>
        <p class="text-muted mb-0">경매는 48시간동안 진행됩니다.</p>
      </div>
    </div>
  
    <div class="d-flex mb-4">
      <div class="position-relative">
        <div class="step-icon">04</div>
        <div class="step-line"></div>
      </div>
      <div class="ms-3">
        <h6 class="fw-bold">낙찰(딜러 선택)</h6>
        <p class="text-muted mb-0">경매가 종료된 이후 48시간 이내에 고객이 판매딜러를 선택합니다.</p>
      </div>
    </div>
  
    <div class="d-flex mb-4">
      <div class="position-relative">
        <div class="step-icon">05</div>
        <div class="step-line"></div>
      </div>
      <div class="ms-3">
        <h6 class="fw-bold">탁송 및 대금</h6>
        <p class="text-muted mb-0">탁송은 당사 직접 진행하며, 출발 1시간 전까지 나이스 에스크 계좌로 매매대금이 입금됩니다.</p>
      </div>
    </div>
  
    <div class="d-flex mb-4">
      <div class="position-relative">
        <div class="step-icon">06</div>
        <div class="step-line"></div>
      </div>
      <div class="ms-3">
        <h6 class="fw-bold">매매대금 입금</h6>
        <p class="text-muted mb-0">탁송기사가 필요한 서류를 받고 확인을 누르면 나이스 에스크 계좌에서 개인 계좌로 송부됩니다.</p>
      </div>
    </div>
  
    <div class="d-flex mb-0">
      <div class="position-relative">
        <div class="step-icon">07</div>
      </div>
      <div class="ms-3">
        <h6 class="fw-bold">이전</h6>
        <p class="text-muted mb-0">탁송완료 후 48시간 내에 이전이 완료됩니다.</p>
      </div>
    </div>

    <button type="button" class="btn btn-primary w-100 mt-4" id="auctionApplyBtn" @click="submit()">경매 신청하기</button>
    
  </div>

  

  <style>
    .timeline {
      padding-left: 10px;
      position: relative;
    }
    .step-icon {
      width: 32px;
      height: 32px;
      background-color: #f44336;
      color: #fff;
      font-weight: bold;
      font-size: 14px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .step-line {
      position: absolute;
      top: 36px;
      left: 15px;
      width: 2px;
      height: calc(100% - 36px);
      background-color: #dee2e6;
    }
    @media (max-width: 576px) {
      .step-icon {
        width: 28px;
        height: 28px;
        font-size: 12px;
      }
    }
  </style>
  