<div x-data="authModal()" class="auth-modal-wrapper">
    <h4 class="mb-3">본인 인증 수단을 선택해 주세요</h4>
  
    <div class="bank-options">
      <template x-for="option in options" :key="option">
        <label class="bank-label">
          <input type="radio" :value="option" x-model="selected" />
          <span x-text="option"></span>
        </label>
      </template>
    </div>
  
    <div class="mt-3">
      <button class="btn btn-primary w-100" :disabled="!selected" @click="submit">인증하기</button>
    </div>
  </div>