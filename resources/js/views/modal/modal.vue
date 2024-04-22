<template>
<section class="modal modal-section type-confirm alert-modal-type02" :style="{ display: showModal ? 'block' : 'none' }">
  
  <div class="modal-dialog-ty04">
          <div class="modal-content-ty03 shadow">
      <div class="modal-body">
        <div class="content p-0 mt-0">  
          <div class="enroll_box" style="position: relative;">
            <img src="../../../img/modal/car-objects-blur.png" alt="자동차 이미지" width="160" height="160">
            <p class="overlay_text">경매 신청이 완료 되었습니다</p>
          </div>
          <p class="menu_msg mb-4">진단평가 완료까지 조금만 기다려주세요!</p> 
          <div class="btn-group">
              <button class="btn btn-danger btn-lg shadow btn_ok">확인</button>
            </div>
        </div>
      </div>
        </div>
      </div>
    </section>

    <!-- modal step02 Start : 경매 취소 팝업 -->

<!--
 <section class="modal modal-section type-confirm alert-modal-type02" :style="{ display: showModal ? 'block' : 'none' }">
        
    <div class="modal-dialog-ty04">
            <div class="modal-content-ty03 shadow">
        <div class="modal-body">
          <div class="content p-0 mt-0">  
            <div class="enroll_box" style="position: relative;">
              <img src="../../../img/drift.png" alt="자동차 이미지" width="160" height="160">
              <p class="overlay_text_cancle">경매가 취소되었습니다.</p>
            </div>
            <p class="menu_msg mb-4">재경매를 진행할까요?</p> 
            <div class="btn-group">
              <button class="btn btn-outline-secondary modal_close shadow" @click="handleCloseClick">닫기</button>
              <button class="btn btn_ok btn-dark shadow">재경매</button>
            </div>
          </div>
        </div>
         </div>
        </div>
    </section>-->
    <!-- modal step02 END -->
    
    </template>

<script>
export default {
  data() {
    return {
      showModal: true
    };
  },
  methods: {
    handleCloseClick() {
      this.showModal = false;
      this.$emit('close'); 
    }
  }
}
</script>
