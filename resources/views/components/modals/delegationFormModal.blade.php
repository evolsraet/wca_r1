<div x-data="delegationFormModal" x-init="init()" class="container py-4 px-3 border" style="font-family: Arial, sans-serif; line-height: 1.6;">
    <div id="printArea">
    <h2 class="text-center text-decoration-underline">위 &nbsp; 임 &nbsp; 장</h2>
  
    <div class="bg-light border rounded p-3 mt-4 mb-4">
      <h5 class="mb-3 text-dark">수임자 정보</h5>
      <table class="table table-bordered">
        <tbody>
          <tr>
            <th class="w-25">성명</th>
            <td>
              <input type="text" class="form-control" id="recipient-name" placeholder="성명" x-model="data.recipientName">
            </td>
          </tr>
          <tr>
            <th>주민등록번호</th>
            <td>
              <input type="text" class="form-control" id="recipient-id" placeholder="주민등록번호" x-model="data.recipientId">
            </td>
          </tr>
          <tr>
            <th>주소</th>
            <td>
              <input type="text" class="form-control" id="recipient-address" placeholder="주소" x-model="data.recipientAddress">
            </td>
          </tr>
        </tbody>
      </table>
  
      <h5 class="text-dark mt-4 mb-3">위임받은 사항</h5>
      <p>자동차 등록(신규, 전입, 말소, 변경, 이전, 근저당 설정, 말소 등) 등록에 관한 사항을 위임합니다.</p>
  
      <table class="table table-bordered mt-3">
        <tbody>
          <tr>
            <th class="w-25">자동차등록번호</th>
            <td>
              <input type="text" class="form-control" id="car-number" placeholder="차량번호" x-model="data.carNumber">
            </td>
          </tr>
          <tr>
            <th>신규 차대번호</th>
            <td>
              <input type="text" class="form-control" id="car-vin" placeholder="차대번호" x-model="data.carVin">
            </td>
          </tr>
        </tbody>
      </table>
      <p>첨부서류 : 위임인의 인감증명서</p>
    </div>
  
    <div class="text-center mb-4">
      <h5 x-text="data.formattedDate"></h5>
    </div>
  
    <div class="bg-light border rounded p-3 mb-4">
      <h5 class="mb-3 text-dark">위임자 정보</h5>
      <table class="table table-bordered">
        <tbody>
          <tr>
            <th class="w-25">성명</th>
            <td class="position-relative">
              <input type="text" class="form-control" id="delegator-name" placeholder="성명" x-model="data.carOwnerName">
              <span class="position-absolute text-danger fw-bold" style="right: 10px; top: 50%; transform: translateY(-50%);">(인감날인)</span>
            </td>
          </tr>
          <tr>
            <th>주민등록번호</th>
            <td>
              <input type="text" class="form-control" id="delegator-id" placeholder="주민등록번호" x-model="data.carOwnerId">
            </td>
          </tr>
          <tr>
            <th>주소</th>
            <td>
              <input type="text" class="form-control" id="delegator-address" placeholder="주소" x-model="data.carOwnerAddress">
            </td>
          </tr>
          <tr>
            <th>연락처</th>
            <td>
              <input type="text" class="form-control" id="delegator-phone" placeholder="연락처" x-model="data.user_phone">
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  
    <div class="text-muted small border-top pt-3">
      <strong>※ 유의사항</strong><br>
      타인의 서명 또는 인장을 도용해 위임장을 작성할 경우, 형법 제231조~232조에 따라 사문서 위·변조로 형사처벌될 수 있습니다.
    </div>

    </div>
  
    <div class="text-center mt-4">
      <button id="print-button" class="btn btn-primary" @click="printDelegationForm">프린트</button>
    </div>
  </div>