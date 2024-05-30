<template>
<section>
<h5>차량 조회중 입니다.</h5>
  <div class="loader-container mt-3">
    <div class="animated-background"></div>
    <div class="grid">
      <div class="grid-right">
        <div class="loading item"></div>
        <div class="loading item" style="width: 85%"></div>
        <div class="loading item"></div>
        <div class="loading item" style="width: 80%"></div>
        <div class="loading item" style="width: 75%"></div>
        <div class="loading item"></div>
        <div class="loading item" style="width: 80%"></div>
        <div class="loading item" style="width: 75%"></div>
        <div class="loading item"></div>
        <div class="loading item" style="width: 80%"></div>
        <div class="loading item" style="width: 75%"></div>
        <div class="loading item"></div>
        <div class="loading item" style="width: 50%"></div>
        <div class="loading item" style="width: 45%"></div>
        <div class="loading item" style="width: 35%"></div>
        <div class="loading item" style="width: 35%"></div>
        <div class="loading item" style="width: 25%"></div>
      </div>
    </div>
  </div>
</section>

  </template>
  
<style scoped>
.loader-container {
  
  margin: 0 auto;
  display: flex;
  flex-direction: column;
}

@keyframes shimmer {
  0% {
    left: -50%;
  }
  100% {
    left: 150%;
  }
}
.animated-background {
  -webkit-animation: shimmer 1s infinite ease-out;
  -moz-animation: shimmer 1s infinite ease-out;
  -o-animation: shimmer 1s infinite ease-out;
  animation: shimmer 1s infinite ease-out;
  background: -moz-linear-gradient(to right, rgba(255, 255, 255, 0) 30%, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0) 80%);
  background: -webkit-linear-gradient(to right, rgba(255, 255, 255, 0) 30%, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0) 80%);
  background: linear-gradient(to right, rgba(255, 255, 255, 0) 30%, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0) 80%);
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
}
.loading {
  background-color: #EEE;
}
.grid-left {
  margin-right: 1rem;
}

.item {
  height: 1rem;
  width: 100%;
  border-radius: 10px;
  margin-top: 20px;
}
@media (max-width: 640px) {
  .item {
    margin-top: 1em;
  }
}

.seller {
  width: 100%;
  height: 2rem;
  margin: 1rem auto 0 auto;
}
  </style>
  