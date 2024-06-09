<template>
    <transition name="fade">
      <div v-if="isOpen" class="modal-backdrop" @click="close">
        <div class="modal-dialog-ty04" key="modal">
          <div class="modal-content-ty03 shadow">
            <div class="modal-body p-4">
              <div class="content p-0 mt-0">  
          <h3>클레임 규정</h3>
            <div class="text-start my-3 process"> 
                <p>1. 거래 완료 후 30일 이내 작성가능 합니다.</p>
                <p>2. 제출된 클레임은 7일 이내에 처리됩니다.</p>
                <p>3. 모든 클레임은 검토 후 승인 또는 거부됩니다.</p>
                </div>
            <button class="btn btn-primary btn-lg shadow btn_ok" @click="close">닫기</button>
          </div>
          </div>
          </div>
        </div>
      </div>
    </transition>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  
  const props = defineProps({
    isOpen: {
      type: Boolean,
      required: true
    }
  });
  
  const emit = defineEmits(['close']);
  
  const close = () => {
    emit('close');
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
  
  .modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    max-width: 500px;
    width: 100%;
    text-align: center;
  }
  </style>
  