<div class="accordion custom-accordion mb-3 mt-5" id="accordionPanelsStayOpenExample" x-init="init(isHistory = false)">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button"  data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
          추가옵션
        </button>
      </h2>
      <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
        <div class="accordion-body">
          <div x-html="diag?.data?.diag_base_option"></div>
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
          기타옵션
        </button>
      </h2>
      <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
        <div class="accordion-body">
          <div x-text="diag?.data?.diag_add_option ?? '내용이 없습니다.'"></div>
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
          차량 세부정보
        </button>
      </h2>
      <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
        <div class="accordion-body bg-white">

          <div x-show="!isHistory">
            내용이 없습니다.
          </div>

          <div x-show="isHistory">
          <div class="my-4">
            {{-- 이력 정보 --}}
            <div class="bg-gray-100 p-3 rounded mb-4">
              <p><strong>용도 변경이력</strong> : 용도이력 없음 / 사업자 없음 / 렌트 없음 / 공공기관 없음</p>
              <p><strong>소유자 변경</strong> : 2 회</p>
              <p><strong>압류/저당</strong> : 압류 0건 / 저당 0건</p>
              <p><strong>특수사고 이력</strong> : 전손 0건 / 침수 0건 / 도난 0건</p>
            </div>
          
            {{-- 내차피해 테이블 --}}
            <h6 class="fw-bold text-danger">내차피해 (1건)</h6>
            <table class="table table-bordered text-center mb-4">
              <thead class="table-light">
                <tr>
                  <th>일시</th>
                  <th>부품</th>
                  <th>공임</th>
                  <th>도장</th>
                  <th>비용</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>2024-04-10</td>
                  <td>1,788,090원</td>
                  <td>31,500원</td>
                  <td>0원</td>
                  <td>1,819,590원</td>
                </tr>
              </tbody>
            </table>
          
            {{-- 타차피해 테이블 --}}
            <h6 class="fw-bold text-secondary">타차피해 (0건)</h6>
            <table class="table table-bordered text-center mb-4">
              <thead class="table-light">
                <tr>
                  <th>일시</th>
                  <th>부품</th>
                  <th>공임</th>
                  <th>도장</th>
                  <th>비용</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="5" class="text-muted">피해 이력이 없습니다.</td>
                </tr>
              </tbody>
            </table>
          
            {{-- 메모 및 의견 --}}
            <div class="row mb-4">
              <div class="col-md-12">
                <label class="form-label fw-bold">판매자 메모</label>
                <textarea class="form-control" rows="2" readonly>해결됨</textarea>
              </div>
              <div class="col-md-12">
                <label class="form-label fw-bold">평가자 의견</label>
                <textarea class="form-control" rows="2" readonly>이 차량은 무사고 차량입니다.</textarea>
              </div>
            </div>
          
            {{-- 기타 정보 --}}
            <div class="row">
              <div class="col-md-12"><strong>거래지역</strong> : 경기 > 용인시 > 기흥구</div>
              <div class="col-md-12"><strong>기타이력</strong> : -</div>
              <div class="col-md-12"><strong>차량명의</strong> : 자가용</div>
            </div>
          </div>

          </div>


        </div>
      </div>
    </div>
  </div>