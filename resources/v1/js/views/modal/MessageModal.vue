<template>
    <transition name="fade">
      <div v-if="visible" class="message-modal">
        {{ message }}
      </div>
    </transition>
  </template>
  
  <script setup>
  import { ref, watch, onMounted } from 'vue';
  
  const props = defineProps({
    show: Boolean,
    message: String,
    duration: {
      type: Number,
      default: 3000
    }
  });
  
  const visible = ref(false);
  let timeout = null;
  
  const showTemporaryMessage = () => {
    visible.value = true;
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      visible.value = false;
    }, props.duration);
  };
  
  // Watch for changes to the show prop
  watch(() => props.show, (newVal) => {
    if (newVal) {
      showTemporaryMessage();
    }
  });
  
  // Ensure message visibility is reset when component mounts
  onMounted(() => {
    if (props.show) {
      showTemporaryMessage();
    }
  });
  </script>
  
  <style>
  .message-modal {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 10px 20px;
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    border-radius: 5px;
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.5s;
  }
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
  }
  </style>
  