  <template>
    <div class="my-5 container auction-content" style="margin-top: 6rem !important;">
      <h3 class="mb-5">{{ terms.title }}</h3>
      <div v-html="terms.content"></div>
    </div>  
  </template>
  
  
  <script>
  export default {
    name: 'Terms',
    data() {
      return {
        title: '이용약관'
      }
    }
  }
  </script>
  
  <script setup>
  import { ref } from 'vue';
  import termsData from '@/composables/termsData.js';
  
  const terms = ref(termsData);
  
  </script>
  