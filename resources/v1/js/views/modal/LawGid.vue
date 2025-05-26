<template>
  <transition name="fade" mode="out-in">
    <section v-if="content === 'privacy'" @click.self="closeModal" v-html="privacyPolicy.content">
    </section>
  </transition>
  <transition name="fade" mode="out-in">
    <section v-if="content === 'terms'" v-html="terms.content">
    </section>
  </transition>
</template>

<script setup>
import { defineProps, defineEmits, ref } from 'vue';
import privacyPolicyData from '@/composables/privacyPolicyData.js';
import termsData from '@/composables/termsData.js';

const privacyPolicy = ref(privacyPolicyData);
const terms = ref(termsData);

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
ol, ul {
  list-style: revert;
}
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
@media screen and (min-height: 1025px) {
  .modal-dialog {
    margin: 0.75rem auto;
  }
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
.content ul li{
  font-size: 14px;
}
</style>
