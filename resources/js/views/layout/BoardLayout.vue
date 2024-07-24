<template>
    <div :class="`board-layout ${skin}`">
      <router-view></router-view>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import { useRoute } from 'vue-router';
  import axios from 'axios';
  
  const route = useRoute();
  const skin = ref('');
  
  const fetchBoardSkin = async () => {
    try {
      const boardName = route.params.boardName;
      if (boardName) {
        const response = await axios.get(`/api/board/${boardName}`);
        skin.value = response.data.skin;
      } else {
        console.error('Board name is undefined');
      }
    } catch (error) {
      console.error('Error fetching board skin:', error);
    }
  };
  
  onMounted(() => {
    fetchBoardSkin();
  });
  
  watch(() => route.params.boardName, fetchBoardSkin);
  </script>
  
  <style scoped>
  .board-layout.default {
    /* Default skin styles */
  }
  
  .board-layout.fancy {
    /* Fancy skin styles */
  }
  
  .board-layout.modern {
    /* Modern skin styles */
  }
  </style>
  