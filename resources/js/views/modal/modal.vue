<template>
  <transition name="fade" mode="out-in">
  <section class="modal modal-section type-confirm alert-modal-type02">
      <div class="modal-dialog-ty04" key="modal">
        <div class="modal-content-ty03 shadow">
          <div class="modal-body">
            <div class="content p-0 mt-0">  
              <div class="enroll_box" style="position: relative;">
                <img src="../../../img/modal/car-objects-blur.png" alt="자동차 이미지" width="160" height="160">
                <p class="overlay_text02">경매 신청이 완료되었습니다.</p>
                <p class="overlay_text03">진단평가 완료까지 조금만 기다려주세요!</p>
              </div>
              <div class="btn-group tc-wh">
                <router-link :to="{ name: 'auction.index' }" class="btn btn-primary btn-lg shadow btn_ok">확인</router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </transition>
</template>


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

    <!-- modal step03 Start : 입찰 완료 팝업 -->

<!--
 <section class="modal modal-section type-confirm alert-modal-type02" :style="{ display: showModal ? 'block' : 'none' }">
        
    <div class="modal-dialog-ty04">
            <div class="modal-content-ty03 shadow">
        <div class="modal-body">
          <div class="content p-0 mt-0">  
            <div class="enroll_box" style="position: relative;">
              <p class="overlay_text_cancle">입찰이 완료되었습니다.</p>
            </div>
            <div class="btn-group">
              <button class="btn btn_ok btn-dark shadow">확인</button>
            </div>
          </div>
        </div>
         </div>
        </div>
    </section>-->
    <!-- modal step02 END -->
    <style scoped>
    .fade-enter-active, .fade-leave-active {
      transition: opacity 0.5s ease, transform 0.5s ease;
    }
    .fade-enter, .fade-leave-to {
      opacity: 0;
      transform: translateY(20px);
    }
    </style>
    
    