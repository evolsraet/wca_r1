<div class="p-1">
    <form @submit.prevent="submit" x-data="dealerDeliveryChoice()">
    {{-- 딜러 정보 --}}
    <div class="d-flex align-items-center mb-3">
        <div class="me-3">
            <div 
                class="rounded-circle bg-light d-flex justify-content-center align-items-center" 
                style="width: 80px; height: 80px; background-color: #f8f9fa;"
                x-data="(() => {
                const user = data?.user;
                return {
                    user: user,
                };
            })()">
                <x-auctions.userIcon />
            </div>
            
        </div>
        <div>
            <div class="text-muted small">낙찰액</div>
            <div class="fw-bold fs-5 mb-1"><span x-text="data?.price ?? 0"></span> <span class="small">만원</span></div>
            <div class="fw-semibold" x-text="data?.user?.dealer?.name ?? '-'"></div>
            <div class="text-secondary small">
                <i class="bi bi-star-fill text-warning me-1"></i> <span x-text="data?.points ?? 0"></span>점
            </div>
        </div>
    </div>

    {{-- 안내 문구 --}}
    <div class="fw-bold mb-2">원하는 탁송일을 선택해 주세요</div>
    <div class="mb-2"> <span x-text="currentMonthText" class="fw-bold fs-6 mb-2"></span></div>
    <div class="text-muted small mb-3">
        ※탁송일은 익일 9시 이후부터 3일 이내로 탁송이 가능해요.
    </div>

    {{-- 날짜 선택 영역 --}}
    <div class="mb-3">
        <h5>
            <span class="text-danger">*</span>
            탁송일 선택
        </h5>
        <div class="d-flex gap-3 overflow-auto">
          <template x-for="(day, index) in days" :key="index">
            <div class="text-center">
              <div class="small text-muted" x-text="day.label"></div>
              <button
                type="button"
                class="btn btn-outline-primary mt-1"
                :class="{ 'active': selectedDay === index }"
                @click="selectDay(index)"
                x-text="day.date.getDate()"
              ></button>
              <div class="text-danger mt-1 small" x-show="day.isHoliday">휴일입니다</div>
            </div>
          </template>
        </div>
      </div>
    
      <template x-if="selectedDay !== null">
        <div class="mb-4">
          <h6>오전</h6>
          <div class="d-flex flex-wrap gap-2">
            <template x-for="time in morningTimes" :key="time">
              <button
                type="button"
                class="btn btn-sm"
                :class="{
                  'btn-outline-secondary': !isDisabled(time) && selectedTime !== time,
                  'btn-primary': selectedTime === time,
                  'btn-secondary disabled': isDisabled(time)
                }"
                @click="selectTime(time)"
                x-text="time"
              ></button>
            </template>
          </div>
    
          <h6 class="mt-3">오후</h6>
          <div class="d-flex flex-wrap gap-2">
            <template x-for="time in afternoonTimes" :key="time">
              <button
                type="button"
                class="btn btn-sm"
                :class="{
                  'btn-outline-secondary': !isDisabled(time) && selectedTime !== time,
                  'btn-primary': selectedTime === time,
                  'btn-secondary disabled': isDisabled(time)
                }"
                @click="selectTime(time)"
                x-text="time"
              ></button>
            </template>
          </div>
        </div>
    </template>

    <div class="alert alert-info mt-4" x-show="selectedDay !== null && selectedTime">
        탁송일시: <strong x-text="formatSelectedDate() + ' ' + selectedTime"></strong>
    </div>

    <hr class="my-4">

    <label class="mb-2">
        <h5>
            <span class="text-danger">*</span>
            입금계좌
        </h5>
    </label>
    <div id="bankSelectorBox" @click="bankSelector()">
    <x-forms.input
        name="auction.bank"
        placeholder="예: 농협"
        required
        :errors="true"
    />
    </div>

    <x-forms.input
        name="auction.account"
        placeholder="계좌번호를 입력해주세요"
        required
        :errors="true"
    />

    <hr class="my-4">
    {{-- 탁송주소 입력 --}}
    <label>
        <h5>
            <span class="text-danger">*</span>
            탁송주소
        </h5>
    </label>
    <p class="text-danger">탁송주소는 탁송 출발지 배송주소로 사용됩니다.</p>
    <x-forms.address
        postName="auction.addr_post"
        addr1Name="auction.addr1"
        addr2Name="auction.addr2"
        postCodeId="auction.addr_post"
        required
        :errors="true"
    />

    <hr class="my-4">

    <label>
        <h5>
            <span class="text-danger">*</span>
            매도용 인감증명서
        </h5>
    </label>
    
    <div class="mt-2">
      <button 
        type="button" 
        class="btn btn-outline-secondary w-100"
        @click='openDocModal(data?.user?.dealer?.biz_check)'
        >매도용 인감증명서 안내</button>
    </div>

    <hr class="my-4">

    <label>
        <h5>
            <span class="text-danger">*</span>
            고객 연락처
        </h5>
    </label>
    <p class="text-danger">고객 연락처를 입력해 주세요.</p>

    <x-forms.input
        name="auction.customTel1"
        placeholder="연락처1 (필수)"
        required
        :errors="true"
    />

    <x-forms.input
        name="auction.customTel2"
        placeholder="연락처2 (선택)"
        :errors="true"
    />

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-outline-secondary w-50 me-2" @click="closeModal()">취소</button>
        <button type="submit" class="btn btn-danger w-50">확인</button>
    </div>

    </form>

</div>