<template>
  <div ref="sheet" class="sheet container" :class="{ 'head': showHead, 'half': showBottomSheet, 'dragging': isDragging }">
    <header class="handle-head" @mousedown="startDrag" @touchstart="startDrag" @click="toggleSheet">
      <span class="handle"></span>
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
const sheetHeight = ref(props.initial === 'half' ? 'fit-content' : 30);
let animationFrame = null;

const sheet = ref(null);

const toggleSheet = () => {
  if (showHead.value) {
    expandSheet();
  } else {
    collapseSheet();
  }
};

const closeSheet = () => {
  showBottomSheet.value = false;
  showHead.value = true;
  sheetHeight.value = 20;
  sheet.value.style.height = `${sheetHeight.value}px`;
};

const startDrag = (event) => {
  if (window.innerWidth >= 992) return; // Disable dragging on larger screens
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
  deltaY.value = startY.value - currentY.value; // 헤더가 위로 드래그될 때 시트가 확장되도록
  cancelAnimationFrame(animationFrame);
  animationFrame = requestAnimationFrame(() => {
    const newHeight = Math.max(20, sheetHeight.value + deltaY.value);
    sheet.value.style.height = `${newHeight}px`;
  });
};

const endDrag = () => {
  isDragging.value = false;
  document.body.style.overflow = '';
  document.removeEventListener('mousemove', onDrag);
  document.removeEventListener('mouseup', endDrag);
  document.removeEventListener('touchmove', onDrag);
  document.removeEventListener('touchend', endDrag);

  const finalHeight = Math.max(20, sheetHeight.value + deltaY.value);
  if (finalHeight > window.innerHeight * 0.2) {
    expandSheet();
  } else {
    collapseSheet();
  }
  deltaY.value = 0;
};

const expandSheet = () => {
  showBottomSheet.value = true;
  showHead.value = false;
  sheetHeight.value = 'auto';
  requestAnimationFrame(() => {
    sheet.value.style.height = 'auto';
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
  border-top-left-radius: 25px;
  border-top-right-radius: 25px;
  box-shadow: 0 -2px 11px rgba(0, 0, 0, 0.1), 0 -2px 7px rgba(0, 0, 0, 0.08);
  max-height: 280px;
  transition: height 0.5s ease, transform 0.5s ease;
  overflow: hidden;
}

.sheet.head {
  height: 300px;
}

.sheet.half {
  height: auto;
  max-height: auto;
}

.sheet.dragging {
  transition: height 0.1s ease;
}

.handle {
  display: block;
  height: 4px;
  width: 42px;
  border-radius: 4px;
  background: rgba(0, 0, 0, 0.1);
  margin: 0 auto;
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
  height: 100%;
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
</style>
