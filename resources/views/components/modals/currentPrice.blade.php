<div class="form-custom" x-data="currentPrice">
  <p class="text-muted">보다 정확한 가격 산정을 위해 아래 사항을 확인해 주세요.</p>

  <!-- 주행거리 -->
  <div class="mb-4">
    <label for="distance" class="form-label fw-semibold">주행거리</label>
    <div class="input-group">
      <input type="number" class="form-control" id="distance" placeholder="키로수" x-model="carInfo.km">
      <span class="input-group-text">Km</span>
    </div>
  </div>

  <!-- 사고이력 -->
  <div class="mb-4">
    <label class="form-label fw-semibold">과거 사고이력</label>
    <div class="d-flex flex-wrap gap-2">
      <button type="button" class="btn btn-outline-secondary">완전 무사고</button>
      <button type="button" class="btn btn-outline-secondary">교환</button>
      <button type="button" class="btn btn-outline-secondary">판금 도색</button>
      <button type="button" class="btn btn-outline-secondary">전손이력</button>
    </div>
  </div>

  <!-- 경고 문구 -->
  <div class="mb-4 text-danger small fw-semibold">
    ※ 이 감가 기준은 일반적인 감가 기준이며, 실제 평가 금액과는 차이가 있을 수 있습니다.
  </div>

  <!-- 수리필요 항목들 -->
  <div class="mb-3 fw-semibold">수리필요</div>
  <div class="row g-3">
    <div class="col-md-12">
      <label class="form-label">키 갯수</label>
      <div class="input-group">
        <input type="number" class="form-control" placeholder="0">
        <span class="input-group-text">개</span>
      </div>
    </div>

    <div class="col-md-12">
      <label class="form-label">휠스크래치</label>
      <div class="input-group">
        <input type="number" class="form-control" placeholder="0">
        <span class="input-group-text">개</span>
      </div>
    </div>

    <div class="col-md-12">
      <label class="form-label text-primary">타이어 상태 <small class="text-muted">교환</small></label>
      <div class="input-group">
        <input type="number" class="form-control" placeholder="0">
        <span class="input-group-text">개</span>
      </div>
    </div>

    <div class="col-md-12">
      <label class="form-label text-primary">외판수리필요 <small class="text-muted">판금, 도색</small></label>
      <div class="input-group mb-2">
        <input type="number" class="form-control" placeholder="0">
        <span class="input-group-text">개</span>
      </div>
      <div class="input-group mb-2">
        <input type="number" class="form-control" placeholder="0">
        <span class="input-group-text">교환</span>
      </div>
      <div class="input-group">
        <input type="number" class="form-control" placeholder="0">
        <span class="input-group-text">깨짐</span>
      </div>
    </div>
  </div>

  <button type="button" class="btn btn-danger w-100 py-2 mt-4 border-0" @click="clickCheckPrice">예상 가격 확인</button>
</div>



