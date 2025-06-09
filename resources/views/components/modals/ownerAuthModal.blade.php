@php
$user = auth()->user();
@endphp

<div class="container auth-modal form-custom" x-data="ownerAuthModal()">
    <!-- 1단계: 고지사항 -->
    <div class="form-group" x-show="step === 1">
      <h5 class="fw-bold mb-3">개인사업자등록상태 조회 고지사항</h5>
      <div class="bg-light p-3 rounded">
        <p class="mb-2">타인의 주민등록번호를 이용한 사업자등록 여부 조회는 <span class="text-danger">개인정보보호법 제 15조 내지 제 24조에 따라 정보주체(주민등록번호 소유자)로부터 동의를 받은 경우</span>에 가능합니다.</p>
        <p class="mb-2">또한 조회대상자의 주민등록번호를 이용하여 사업자등록 여부를 조회한다는 동의를 받아 그 근거를 보관하고 있어야 합니다.</p>
        <p class="mb-2">- 위와 같은 동의를 거치지 않고 주민등록번호를 이용하여 사업자등록 여부를 조회하는 것은 <span class="text-danger">개인정보보호법 제 72조에 따라 3년 이하의 징역 또는 3천만원 이하의 벌금</span>에 처할 수 있습니다.</p>
        <p class="mb-2">- 정부주체(<span class="text-danger">주민등록번호 소유자</span>)의 손해배상청구 소송의 대상이 될 수 있습니다.</p>
        <p class="text-danger mb-0">* 소유자의 사업자여부 확인을 위해 주민번호 & 휴대폰번호를 사용하며, 이후 명의이전등록, 경락확인서 정보를 위해 보관됩니다.</p>
      </div>
      <div class="form-check mt-3">
        <input type="checkbox" id="agree" class="form-check-input" x-model="agreed">
        <label for="agree" class="form-check-label fw-bold">동의합니다</label>
      </div>
    </div>
  
    <!-- 2단계: 간편인증 선택 -->
    <div class="form-group" x-show="step === 2">
      <label class="form-label fw-bold"><span class="text-danger">*</span> 간편인증</label>
      <div class="row g-3">
        <template x-for="(label, index) in authMethods" :key="index">
          <div class="col-4 col-md-3 text-center">
            <div class="rounded p-2 h-100 auth_box" @click="selectAuth(index + 1)" role="button">
              <img :src="label.img" class="img-fluid mb-2" style="max-height: 45px;">
              <p class="mb-0" x-text="label.name"></p>
            </div>
          </div>
        </template>
      </div>
    </div>
  
    <!-- 3단계: 인증 정보 입력 -->
    <div class="form-group" x-show="step === 3">
      <div class="mb-3">
        <label class="form-label"><span class="text-danger">*</span> 통신사</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="telecom" value="0" x-model="telecom">
          <label class="form-check-label">SKT</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="telecom" value="1" x-model="telecom">
          <label class="form-check-label">KT</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="telecom" value="2" x-model="telecom">
          <label class="form-check-label">LGU+</label>
        </div>
      </div>
  
      <div class="mb-3">
        <label class="form-label"><span class="text-danger">*</span> 휴대폰번호 <span class="text-danger">(숫자만)</span></label>
        <input type="text" class="form-control" id="phoneNo" x-model="phoneNo" placeholder="예) 01012345678">
      </div>
  
      <div class="mb-3">
        <label class="form-label"><span class="text-danger">*</span> 소유자 이름</label>
        <input type="text" class="form-control" id="ownerName" :value="ownerName" disabled>
      </div>
  
      <div class="mb-3">
        <label class="form-label"><span class="text-danger">*</span> 주민번호 <span class="text-danger">(숫자만)</span></label>
        <input type="text" class="form-control" id="identity" x-model="identity" placeholder="예) 9001011234567">
      </div>
    </div>


    <div class="form-group" x-show="step === 4">
        <div class="none-info">
            <div class="complete-car">
                <div class="card">
                    <div class="d-flex align-items-center justify-content-center" style="height: 340px;">
                        <span class="text-secondary text-center fs-5">간편인증 완료후 <br>하단의 인증완료 버튼을 클릭 해 주세요.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group" x-show="step === 5">
        <div class="none-info">
            <div class="complete-car">
                <div class="card">
                    <div class="d-flex align-items-center justify-content-center" style="height: 340px;">
                        <span class="text-secondary text-center fs-5">간편인증 이 실패 하였습니다.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
  
    <!-- 인증 버튼 -->
    <div class="form-group mt-4" x-show="step === 1 || step === 2 || step === 3">
      <button class="btn btn-primary w-100 border-0" :disabled="step !== 3" @click="submit">소유자 인증</button>
    </div>
    <div class="form-group mt-4" x-show="step === 4">
        <button class="btn btn-primary w-100 border-0" @click="authSubmit">인증완료</button>
    </div>
    <div class="form-group mt-4" x-show="step === 5">
        <button class="btn btn-primary w-100 border-0" @click="closeModal">확인</button>
    </div>
  </div>

  <style>
    .complete-car {
        position: relative;
        height: 340px;
        background-color: #f0f0f0;
    }
  </style>