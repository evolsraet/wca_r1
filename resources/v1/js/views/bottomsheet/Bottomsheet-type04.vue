<template>
    <div ref="sheet" class="sheet container" :class="{ 'head': showHead, 'half': showBottomSheet, 'dragging': isDragging }">
      <header class="handle-head" @mousedown="startDrag" @touchstart="startDrag" @click="toggleSheet">
        <span class="handle">
          <div class="mdi mdi-chevron-up" v-if="!showBottomSheet"></div>
          <div class="mdi mdi-chevron-down" v-else></div>
        </span>
      </header>
      <div class="content p-3" ref="content" :class="{ 'no-scroll': showHead }" @mousedown.stop @touchstart.stop>
        <slot></slot>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount } from 'vue';
  
  const props = defineProps({
    initial: {
      type: String,
      default: 'half'
    },
    dismissable: Boolean
  });
  
  const showHead = ref(props.initial !== 'half');
  const showBottomSheet = ref(props.initial === 'half');
  const isDragging = ref(false);
  const startY = ref(0);
  const currentY = ref(0);
  const deltaY = ref(0);
  const sheetHeight = ref(props.initial === 'half' ? window.innerHeight / 2 : 30);
  const maxSheetHeight = window.innerHeight * 0.1;
  let animationFrame = null;
  
  const sheet = ref(null);
  
  const toggleSheet = (event) => {
    if (window.innerWidth >= 992) return;
    if (showHead.value) {
      showHead.value = false;
      showBottomSheet.value = true;
      sheetHeight.value = window.innerHeight / 2;
    } else {
      showHead.value = true;
      showBottomSheet.value = false;
      sheetHeight.value = 30;
    }
    requestAnimationFrame(() => {
      sheet.value.style.height = `${sheetHeight.value}px`;
    });
  };
  
  const startDrag = (event) => {
    if (window.innerWidth >= 992) return;
    isDragging.value = true;
    startY.value = event.touches ? event.touches[0].clientY : event.clientY;
    document.body.style.overflow = 'hidden';
    document.addEventListener('mousemove', onDrag);
    document.addEventListener('mouseup', endDrag);
    document.addEventListener('touchmove', onDrag);
    document.addEventListener('touchend', endDrag);
  };
  
  const onDrag = (event) => {
    if (!isDragging.value) return;
    currentY.value = event.touches ? event.touches[0].clientY : event.clientY;
    deltaY.value = startY.value - currentY.value;
  
    if (showBottomSheet.value && deltaY > 0) {
      return;
    }
  
    cancelAnimationFrame(animationFrame);
    animationFrame = requestAnimationFrame(() => {
      let newHeight = Math.max(20, sheetHeight.value + deltaY.value);
      if (showHead.value) {
        newHeight = Math.min(newHeight, maxSheetHeight);
      }
      sheet.value.style.height = `${newHeight}px`;
    });
  };
  
  const endDrag = (event) => {
    if (!isDragging.value) return;
    isDragging.value = false;
    document.body.style.overflow = '';
    document.removeEventListener('mousemove', onDrag);
    document.removeEventListener('mouseup', endDrag);
    document.removeEventListener('touchmove', onDrag);
    document.removeEventListener('touchend', endDrag);
  
    let finalHeight = Math.max(20, sheetHeight.value + deltaY.value);
    if (showHead.value) {
      finalHeight = Math.min(finalHeight, maxSheetHeight);
    }
  
    if (showHead.value && deltaY.value > 10) { 
      showBottomSheet.value = true;
      showHead.value = false;
      sheetHeight.value = window.innerHeight / 2;
      requestAnimationFrame(() => {
        sheet.value.style.height = `${sheetHeight.value}px`;
      });
    } else if (showBottomSheet.value && deltaY.value < -10) { 
      collapseSheet();
    } else if (finalHeight > maxSheetHeight * 0.5) {
      showBottomSheet.value = true;
      showHead.value = false;
      sheetHeight.value = window.innerHeight / 2;
      requestAnimationFrame(() => {
        sheet.value.style.height = `${sheetHeight.value}px`;
      });
    } else {
      collapseSheet();
    }
  
    deltaY.value = 0;
  };
  
  const expandSheet = () => {
    showBottomSheet.value = true;
    showHead.value = false;
    sheetHeight.value = window.innerHeight * 0.5;
    requestAnimationFrame(() => {
      sheet.value.style.height = `${sheetHeight.value}px`;
    });
  };
  
  const collapseSheet = () => {
    showBottomSheet.value = false;
    showHead.value = true;
    sheetHeight.value = 30;
    requestAnimationFrame(() => {
      sheet.value.style.height = `${sheetHeight.value}px`;
    });
  };
  
  const handleResize = () => {
    if (window.innerWidth >= 992) {
      expandSheet();
    } else {
      if (props.initial === 'half') {
        expandSheet();
      } else {
        collapseSheet();
      }
    }
  };
  
  onMounted(() => {
    if (props.initial === 'half') {
      expandSheet();
    } else {
      collapseSheet();
    }
    window.addEventListener('resize', handleResize);
  });
  
  onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize);
  });
  </script>
  
  <style scoped>
  .sheet-wrap {
    position: fixed;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    overflow: hidden;
  }
  
  .sheet {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #ddd;
    border-top-left-radius: 25px;
    border-top-right-radius: 25px;
    transition: height 0.3s ease-in-out, transform 0.3s ease-in-out;
    overflow: hidden;
    box-shadow:0 0px 6px rgba(0, 0, 0, 0.1);
  }
  .sheet.head {
    height: 30px;
  }
  
  .sheet.half {
    height: 50vh;
    max-height: 50vh;
  }
  
  .sheet.dragging {
    transition: none;
  }
  
  .handle {
    display: block;
    /* height: 7px; */
    width: 42px;
    /* border-radius: 4px; */
    /* background: rgba(0, 0, 0, 0.1); */
    margin: 0 auto;
  }

  .handle div {
    height: 7px;
    width: 40px;
    color: rgba(0, 0, 0, 0.2);
    font-size: 40px;
    font-weight: 700;
    text-align: center;
    position: absolute;
    top: -16px;
  }
  
  .handle-head {
    padding: 8px 0;
    cursor: pointer;
  }
  
  .dismiss {
    position: absolute;
    display: inline-block;
    right: 0px;
    top: 0px;
    z-index: 20;
    height: 48px;
    width: 48px;
    line-height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    cursor: pointer;
  }
  
  .content {
    overflow-y: auto;
    height: auto;
    overflow-x: hidden;
    margin-top: 0px !important;
  }
  
  .content.no-scroll {
    overflow: hidden;
  }
  
  @media (min-width: 992px) {
    .sheet {
      height: auto !important;
      transition: none;
     
    }
    .handle {
      display: none;
    }
    .handle-head {
      cursor: default;
    }
  }
  @media (max-width: 991px) {
    .sheet {
      box-shadow: 0 -2px 11px rgba(0, 0, 0, 0.1), 0 -2px 7px rgba(0, 0, 0, 0.08) !important;
    }
    .content {
      height: 100%;
    }
  }
  </style>
  