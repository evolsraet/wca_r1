<div class="accordion custom-accordion mb-3 mt-5 animate__animated animate__fadeIn" id="accordionPanelsStayOpenExample" x-init="init(isHistory = true)">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button"  data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
          추가옵션
        </button>
      </h2>
      <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
        <div class="accordion-body">
          <div x-show="diag?.data?.diag_base_option">
            <div x-html="diag?.data?.diag_base_option"></div>
          </div>
          <div x-show="!diag?.data?.diag_base_option">
            내용이 없습니다.
          </div>
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
          <div x-show="diag?.data?.diag_add_option">
            <div x-text="diag?.data?.diag_add_option"></div>
          </div>
          <div x-show="!diag?.data?.diag_add_option">
            내용이 없습니다.
          </div>
        </div>
      </div>
    </div>
    <div class="accordion-item" x-data="auctionDetailInfo">
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
            <div class="bg-gray-100 p-2 rounded mb-4">
              <p><strong>용도 변경이력</strong>
                 : 
                 <span x-text="niceDnrHistory?.resUseHistYn === 'Y' ? '용도이력 / ' : '용도이력 없음 / '"></span>
                 <span x-text="niceDnrHistory?.resUseHistBiz === 'Y' ? '사업자 / ' : '사업자 없음 / '"></span>
                 <span x-text="niceDnrHistory?.resUseHistRent === 'Y' ? '렌트 / ' : '렌트 없음 / '"></span>
                 <span x-text="niceDnrHistory?.resUseHistGov === 'Y' ? '공공기관' : '공공기관 없음'"></span>
              </p>


              <p><strong>소유자 변경</strong> : <span x-text="niceDnrHistory?.userChangeCount ?? '-'"></span> 회</p>
              <p><strong>압류/저당</strong> : 압류 <span x-text="niceDnrHistory?.seizCt ?? '0'"></span>건 / 저당 <span x-text="niceDnrHistory?.mortCt ?? '0'"></span>건</p>
              <p><strong>특수사고 이력</strong> : 전손 <span x-text="carHistoryCrash?.special_crash?.basic_length ?? '0'"></span>건 / 침수 <span x-text="carHistoryCrash?.special_crash?.partial_length ?? '0'"></span>건 / 도난 <span x-text="carHistoryCrash?.special_crash?.theft_length ?? '0'"></span>건</p>


            </div>
          
            {{-- 내차피해 테이블 --}}
            <h6 class="fw-bold text-danger">내차피해 (<span x-text="carHistoryCrash?.self_length ?? '0'"></span>건)</h6>
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
              <tbody x-show="carHistoryCrash?.self_length > 0">
                <template x-for="selfItem in carHistoryCrash?.self" :key="selfItem.id">
                <tr>
                  <td x-text="selfItem.crashDate ? selfItem.crashDate : '-'"></td>
                  <td x-text="selfItem.part ? selfItem.part + ' 원' : '-'"></td>
                  <td x-text="selfItem.labor ? selfItem.labor + ' 원' : '-'"></td>
                  <td x-text="selfItem.paint ? selfItem.paint + ' 원' : '-'"></td>
                  <td x-text="selfItem.cost ? selfItem.cost + ' 원' : '-'"></td>
                </tr>
                </template>
              </tbody>
            </table>
          
            {{-- 타차피해 테이블 --}}
            <h6 class="fw-bold text-danger">타차피해 (<span x-text="carHistoryCrash?.other_length ?? '0'"></span>건)</h6>
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
              <tbody x-show="carHistoryCrash?.other_length > 0">
                <template x-for="(otherItem, idx) in carHistoryCrash?.other" :key="idx">
                <tr>
                  <td x-text="otherItem.crashDate ? otherItem.crashDate : '-'"></td>
                  <td x-text="otherItem.part ? otherItem.part + ' 원' : '-'"></td>
                  <td x-text="otherItem.labor ? otherItem.labor + ' 원' : '-'"></td>
                  <td x-text="otherItem.paint ? otherItem.paint + ' 원' : '-'"></td>
                  <td x-text="otherItem.cost ? otherItem.cost + ' 원' : '-'"></td>
                </tr>
                </template>
              </tbody>
              <tbody x-show="carHistoryCrash?.other_length === 0">
                <tr>
                  <td colspan="5" class="text-muted">피해 이력이 없습니다.</td>
                </tr>
              </tbody>
            </table>
          
            {{-- 메모 및 의견 --}}
            <div class="row mb-4">
              <div class="col-md-12">
                <label class="form-label fw-bold">판매자 메모</label>
                <textarea class="form-control" rows="2" readonly x-text="auction?.memo ?? '판매자 메모사항이 없습니다.'"></textarea>
              </div>
              <div class="col-md-12">
                <label class="form-label fw-bold">평가자 의견</label>
                <textarea class="form-control" rows="2" readonly x-text="diag?.data?.diag_opinion ?? '판매자 메모사항이 없습니다.'"></textarea>
              </div>
            </div>
          
            {{-- 기타 정보 --}}
            <div class="row">
              <div class="col-md-12"><strong>거래지역</strong> : <span x-text="auction?.region ?? '-'"></span></div>
              {{-- <div class="col-md-12"><strong>기타이력</strong> : -</div> --}}
              <div class="col-md-12"><strong>차량명의</strong> : <span x-text="carHistoryCrash?.car_use ?? '-'"></span></div>
            </div>
          </div>

          </div>


        </div>
      </div>
    </div>
  </div>