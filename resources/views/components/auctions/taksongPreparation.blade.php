<div class="py-4 border-bottom p-2">
    <div class="fw-bold mb-3 fs-5">탁송 전, 준비해 주세요</div>

    <div class="accordion custom-accordion" id="delivery-accordion">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button"  data-bs-target="#delivery-accordion-item-1" aria-expanded="true" aria-controls="delivery-accordion-item-1">
              1. 차에 있는 짐 빼기
            </button>
          </h2>
          <div id="delivery-accordion-item-1" class="accordion-collapse collapse">
            <div class="accordion-body">

              <h5 class="text-center fw-bold mb-4">자주 분실하는 물건이에요!</h5>

              <div class="row row-cols-3 row-cols-md-3 row-cols-lg-4 g-4 text-center">
                <div class="col">
                  <div>
                    <img src="{{ asset('images/find-icons/find-icon-01.svg') }}" alt="선글라스" class="img-fluid w-50">
                  </div>
                  <div>선글라스</div>
                </div>
                <div class="col">
                  <div>
                    <img src="{{ asset('images/find-icons/find-icon-02.svg') }}" alt="하이패스" class="img-fluid w-50">
                  </div>
                  <div>하이패스</div>
                </div>
                <div class="col">
                  <div>
                    <img src="{{ asset('images/find-icons/find-icon-03.svg') }}" alt="키링" class="img-fluid w-50">
                  </div>
                  <div>키링</div>
                </div>
                <div class="col">
                  <div>
                    <img src="{{ asset('images/find-icons/find-icon-04.svg') }}" alt="캐롯 플러그" class="img-fluid w-50">
                  </div>
                  <div>캐롯 플러그</div>
                </div>
                <div class="col">
                  <div>
                    <img src="{{ asset('images/find-icons/find-icon-05.svg') }}" alt="블랙박스 SD칩" class="img-fluid w-50">
                  </div>
                  <div>블랙박스 SD칩</div>
                </div>
                <div class="col">
                  <div>
                    <img src="{{ asset('images/find-icons/find-icon-06.svg') }}" alt="USB" class="img-fluid w-50">
                  </div>
                  <div>USB</div>
                </div>
                <div class="col">
                  <div>
                    <img src="{{ asset('images/find-icons/find-icon-07.svg') }}" alt="아파트 출입증" class="img-fluid w-50">
                  </div>
                  <div>아파트 출입증</div>
                </div>
                <div class="col">
                  <div>
                    <img src="{{ asset('images/find-icons/find-icon-08.svg') }}" alt="주차 연락처" class="img-fluid w-50">
                  </div>
                  <div>주차 연락처</div>
                </div>
                <div class="col">
                  <div>
                    <img src="{{ asset('images/find-icons/find-icon-09.svg') }}" alt="수납함 내 물품" class="img-fluid w-50">
                  </div>
                  <div>수납함 내 물품</div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-target="#delivery-accordion-item-2" aria-expanded="false" aria-controls="delivery-accordion-item-2">
              2. 필수서류 준비하기
            </button>
          </h2>
          <div id="delivery-accordion-item-2" class="accordion-collapse collapse">
            <div class="accordion-body">
              
              <div class="text-center mb-4">
                <p class="fw-semibold">탁송기사님 도착 전까지,<br>아래 2가지 서류를 준비해주세요.</p>
              </div>
            
              <div class="row text-center">
                <div class="col-6 col-md-6 mb-4">
                  <!-- 첫 번째 서류 -->
                  <div class="border rounded p-3 h-100">
                    <div class="mb-3">
                      <!-- 이미지 자리, 나중에 추가 가능 -->
                      <div class="bg-light" style="height: 180px;">
                        <img src="{{ asset('images/find-icons/car_licence_icon.png') }}" alt="car_licence_icon" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
                      </div>
                    </div>
                    <p class="fw-semibold mt-2 small">자동차등록증 원본</p>
                  </div>
                </div>
            
                <div class="col-6 col-md-6 mb-4">
                  <!-- 두 번째 서류 -->
                  <div class="border rounded p-3 h-100">
                    <div class="mb-3">
                      <!-- 이미지 자리, 나중에 추가 가능 -->
                      <div class="bg-light" style="height: 180px;">
                        <img src="{{ asset('images/find-icons/auth_licence_icon.png') }}" alt="auth_licence_icon" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
                      </div>
                    </div>
                    <p class="fw-semibold mt-2 small">
                      자동차매도용 <span class="text-danger">본인서명사실확인서</span> 또는<br>
                      인감증명서 (매수자 인적사항 기재)
                    </p>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
    </div>
</div>