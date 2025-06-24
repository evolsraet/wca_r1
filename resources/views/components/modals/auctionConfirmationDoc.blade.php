<div x-data="auctionConfirmationDoc">
<div id="printArea" class="container-fluid p-3 border rounded">
  <div class="row">
    <div class="col-12">
      <h2 class="text-center text-decoration-underline mb-3">경 락 확 인 서</h2>
      
      <p class="text-center mt-2 mb-3">「자동차등록규칙 제33조 2항 [나] 목에 근거하여 자동차경매거래를 증명합니다.』</p>

      <div class="mb-4 p-2 rounded">
        <h5 class="mb-3 text-dark">◼︎ 경락 내역</h5>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr>
              <td class="w-25 p-2">출풍사</td>
              <td class="w-25 p-2">{{ config('app.name') }}</td>
              <td class="w-25 p-2">상품번호</td>
              <td class="w-25 p-2" x-text="auction?.hashid ?? '-'"></td>
            </tr>

            <tr>
              <td class="p-2">경매회차</td>
              <td class="p-2" x-text="auction?.auction_count + '회' ?? '-'"></td>
              <td class="p-2">경매일</td>
              <td class="p-2" x-text="auction?.created_at ? new Date(auction?.created_at).toLocaleDateString('ko-KR') : '-'"></td>
            </tr>
            <tr>
              <td class="p-2">차명</td>
              <td class="p-2" x-text="auction?.car_model + ' ' + auction?.car_model_sub"></td>
              <td class="p-2">차량번호</td>
              <td class="p-2" x-text="auction?.car_no ?? '-'"></td>
            </tr>
            <tr>
              <td class="p-2">연식</td>
              <td class="p-2" x-text="auction?.car_year ?? '-'"></td>
              <td class="p-2">최초등록일</td>
              <td class="p-2" x-text="auction?.car_first_reg_date ? new Date(auction?.car_first_reg_date).toLocaleDateString('ko-KR') : '-'"></td>
            </tr>
            <tr>
              <td class="p-2">배기량</td>
              <td class="p-2" x-text="diag?.data?.diag_displacement + ' cc' ?? '-'"></td>
              <td class="p-2">계기판주행</td>
              <td class="p-2" x-text="auction?.car_km + ' km' ?? '-'"></td>
            </tr>
          </table>
        </div>

        <h5 class="mb-3 text-dark mt-2">◼︎ 경락 금액</h5>
        <div class="table-responsive mb-3">
          <table class="table table-bordered">
            <tr>
              <td class="w-25 p-2">경락대금</td>
              <td class="p-2 d-flex justify-content-between align-items-center">
                <span class="print-input" id="done-price" x-text="auction?.final_price + ' 만원' ?? '-'"></span>
                <span class="text-danger small">(VAT 포함금액)</span>
              </td>
            </tr>
          </table>
        </div>
        <p class="mb-2">※ 위 경락대금에 대한 입금사실을 확인합니다.</p>

        <h5 class="mb-2 text-dark mt-2">◼︎ 매도자 정보</h5>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr>
              <td class="w-25 p-2">매도자</td>
              <td class="w-25 p-2" x-text="auction?.owner_name ?? '-'"></td>
              <td class="w-25 p-2">주민(법인)번호</td>
              <td class="w-25 p-2" x-text="auction?.personal_id_number ?? '-'"></td>
            </tr>

            <tr>
              <td class="p-2">주소</td>
              <td class="p-2" colspan="3">
                <span x-text="('(' + auction?.addr_post + ') ' + auction?.addr1 + ' ' + auction?.addr2)"></span>
              </td>
            </tr>
          </table>
        </div>

        <h5 class="mb-2 text-dark mt-2">◼︎ 경락자 정보</h5>
        <div class="table-responsive mb-4">
          <table class="table table-bordered">
            <tr>
              <td class="w-25 p-2">상호명</td>
              <td class="w-25 p-2">
                <span x-text="auction?.win_bid?.user?.dealer?.company ?? '-'"></span>
              </td>
              <td class="w-25 p-2">사업자번호</td>
              <td class="w-25 p-2" x-text="auction?.win_bid?.user?.dealer?.business_registration_number ?? '-'"></td>
            </tr>

            <tr>
              <td class="p-2">대표자명</td>
              <td class="p-2" x-text="auction?.win_bid?.user?.dealer?.name ?? '-'"></td>
              <td class="p-2">연락처</td>
              <td class="p-2" x-text="auction?.win_bid?.user?.dealer?.phone ?? '-'"></td>
            </tr>

            <tr>
              <td class="p-2">주소</td>
              <td class="p-2" colspan="3">
                <span 
                x-text="
                auction?.dest_addr_post 
                    ? auction.dest_addr_post + ' ' + auction.dest_addr1 + ' ' + auction.dest_addr2 
                    : (
                        (auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.company_addr1 
                        && auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.company_addr2)
                            ? auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.company_addr1 + ' ' + auction?.bids?.find(bid => bid.id === auction.bid_id)?.user?.dealer?.company_addr2
                            : '미정'
                    )
            "></span>
              </td>
            </tr>
          </table>
        </div>

        <p class="mb-2">이 문서는 {{ config('app.name') }}의 승인 없이 수정할 수 없습니다.</p>
        <p class="mb-2">「자동차관리법』 제60조 제1항 및 동법시행규칙 제 126조 제3항의 규정에 의하여 개설된 자동차 경매장</p>
        <p class="mb-4">※ 상기차량을 경락 받은 매매업자가 상품용으로 이전등록하는 경우 매도인의 인감증영서 대신 본 경락확인서로 대신할 수 있음.</p>
      </div>

      <div class="text-center my-2">
        <h3 class="text-dark mb-2">{{ config('app.name') }}</h3>
      </div>
    </div>
  </div>
</div>

<div class="d-flex justify-content-center mt-4">
  <button id="print-button" class="btn btn-primary px-4 py-2" @click="printDoc">프린트</button>
</div>

</div>