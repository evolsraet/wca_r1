<template>
  <div ref="sheet" class="sheet" :class="{ 'head': showHead, 'half': showBottomSheet, 'dragging': isDragging }">
    <header class="handle-head" @mousedown="startDrag" @touchstart="startDrag" @click="toggleSheet">
      <span class="handle"></span>
    </header>
    <div class="content p-3" ref="content" :class="{ 'no-scroll': showHead }" @mousedown.stop @touchstart.stop>
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  initial: String,
  dismissable: Boolean
});

const showHead = ref(true); // Initially show head
const showBottomSheet = ref(false);
const isDragging = ref(false);
const startY = ref(0);
const currentY = ref(0);
const deltaY = ref(0);
const sheetHeight = ref(200); // Initial height set to head height
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
  cancelAnimationFrame(animationFrame);
  animationFrame = requestAnimationFrame(() => {
    const newHeight = Math.max(20, sheetHeight.value - deltaY.value);
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

  if (sheetHeight.value - deltaY.value > window.innerHeight * 0.1) {
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
  sheet.value.style.height = 'auto';
};

const collapseSheet = () => {
  showBottomSheet.value = false;
  showHead.value = true;
  sheetHeight.value = 20;
  sheet.value.style.height = `${sheetHeight.value}px`;
};

onMounted(() => {
  if (props.initial === 'half') {
    expandSheet();
  } else {
    collapseSheet();
  }
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
  max-height: 200px;
  transition: height 0.3s ease;
  overflow: hidden;
}

.sheet.head {
  height: 200px;
}

.sheet.half {
  height: auto;
  max-height: auto;
}

.sheet.dragging {
  transition: none;
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
</style>
