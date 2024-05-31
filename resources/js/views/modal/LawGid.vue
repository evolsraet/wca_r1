<template>
<!----------------------------------------------
    카피라이트, 개인정보 처리방침, 이용약관 모달
----------------------------------------------->
    <!-- Copywrite  -->
  <transition name="fade" mode="out-in">
    <!-- Privacy  -->
    <section v-if="content === 'privacy'" class="modal modal-section type-confirm alert-modal-type02"  @click.self="closeModal">
        <div class="modal-dialog" @click.self="closeModal">
        <div class="modal-content shadow" @click.self="closeModal">
            <div class="modal-body">
                <div class="nav-header"><button type="button" class="btn-close" @click="closeModal"></button></div>
                <div class="demo"></div>
            <div class="content p-0 mt-0">
                <p class="overlay_text_cancle">개인정보 처리방침</p>
            </div>
            </div>
        </div>
        </div>
    </section>
  </transition>
    <transition name="fade" mode="out-in">
    <!-- Terms  -->
    <section v-if="content === 'terms'" class="modal modal-section type-confirm alert-modal-type02">
        <div class="modal-dialog" @click.self="closeModal">
        <div class="modal-content shadow" @click.self="closeModal">
            <div class="modal-body">
                <div class="nav-header"><button type="button" class="btn-close" @click="closeModal"></button></div>
                <div class="demo"></div>
            <div class="content p-0 mt-0">
                <p class="overlay_text_cancle">이용 약관</p>
            </div>
            </div>
        </div>
        </div>
    </section>
  </transition>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  
  const props = defineProps({
    content: String
  });
  const emit = defineEmits(['close']);
  
  const closeModal = () => {
    emit('close');
  };
  
  const onModalClick = (event) => {
    event.stopPropagation();
  };
  </script>
  
  
    
<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}


.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s, transform 0.5s;
  transition-timing-function: ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(20px); 
}
.fade-enter-to, .fade-leave-from {
  opacity: 1;
  transform: translateY(0);
}
</style>