<template>
  <ul :class="[isTitle ? 'machine-inform-title' : 'machine-inform', numClass]">
    <li class="text-secondary opacity-50">{{ label }}</li>
    <li :class="[isTitle ? 'info-num' : 'sub-title', customClass]">{{ value }}</li>
    <li v-if="icon" :class="icon"></li> 
  </ul>
</template>

<script setup>
defineProps({
  label: String, 
  value: String,
  icon: String, 
  isTitle: Boolean,
  customClass: String,
  numClass: String
});
</script>
