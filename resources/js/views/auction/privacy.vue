<template>
  <div class="my-5 container auction-content" style="margin-top: 6rem !important;">
    <h3 class="mb-5">{{ privacyPolicy.title }}</h3>
    <div v-html="privacyPolicy.content"></div>
  </div>  
</template>


<script>
export default {
  name: 'Privacy',
  data() {
    return {
      title: '개인정보처리방침'
    }
  }
}
</script>

<script setup>
import { ref } from 'vue';
import privacyPolicyData from '@/composables/privacyPolicyData.js';

const privacyPolicy = ref(privacyPolicyData);

</script>
