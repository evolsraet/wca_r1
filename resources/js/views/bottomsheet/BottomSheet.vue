<template>
    <div ref="sheet" class="sheet" :class="{ 'head': showHead, 'half': showBottomSheet, 'dragging': isDragging }">
      <header class="handle-head" @mousedown="startDrag" @touchstart="startDrag" @click="toggleSheet">
        <span class="handle"></span>
        <i v-if="dismissable" @click.stop="closeSheet" class="material-icons dismiss"></i>
      </header>
      <div class="content p-3" ref="content" :class="{ 'no-scroll': showHead }" @mousedown.stop @touchstart.stop>
        <slot></slot>
      </div>
    </div>
</template>

<script>
export default {
  name: 'BottomSheet',
  props: {
    initial: String,
    dismissable: Boolean
  },
  data() {
    return {
      showHead: false,
      showBottomSheet: false,
      isDragging: false,
      startY: 0,
      currentY: 0,
      deltaY: 0,
      sheetHeight: 20, // Initial height set to head height
      animationFrame: null
    };
  },
  methods: {
    toggleSheet() {
      if (this.showBottomSheet) {
        this.showBottomSheet = false;
        this.showHead = true;
        this.sheetHeight = 20;
        this.$refs.sheet.style.height = `${this.sheetHeight}px`;
      } else {
        this.showBottomSheet = true;
        this.showHead = false;
        this.sheetHeight = window.innerHeight * 0.5;
        this.$refs.sheet.style.height = `${this.sheetHeight}px`;
      }
    },
    closeSheet() {
      this.showBottomSheet = false;
      this.showHead = true;
      this.sheetHeight = 20;
      this.$refs.sheet.style.height = `${this.sheetHeight}px`;
    },
    startDrag(event) {
      this.isDragging = true;
      this.startY = event.touches ? event.touches[0].clientY : event.clientY;
      document.body.style.overflow = 'hidden';
      document.addEventListener('mousemove', this.onDrag);
      document.addEventListener('mouseup', this.endDrag);
      document.addEventListener('touchmove', this.onDrag);
      document.addEventListener('touchend', this.endDrag);
    },
    onDrag(event) {
      if (!this.isDragging) return;
      this.currentY = event.touches ? event.touches[0].clientY : event.clientY;
      this.deltaY = this.startY - this.currentY;
      cancelAnimationFrame(this.animationFrame);
      this.animationFrame = requestAnimationFrame(() => {
        const newHeight = Math.max(20, Math.min(window.innerHeight * 0.5, this.sheetHeight + this.deltaY));
        this.$refs.sheet.style.height = `${newHeight}px`;
      });
    },
    endDrag() {
      this.isDragging = false;
      document.body.style.overflow = '';
      document.removeEventListener('mousemove', this.onDrag);
      document.removeEventListener('mouseup', this.endDrag);
      document.removeEventListener('touchmove', this.onDrag);
      document.removeEventListener('touchend', this.endDrag);

      if (this.sheetHeight + this.deltaY > window.innerHeight * 0.25) {
        this.showBottomSheet = true;
        this.showHead = false;
        this.sheetHeight = window.innerHeight * 0.5;
      } else {
        this.showBottomSheet = false;
        this.showHead = true;
        this.sheetHeight = 20;
      }
      this.$refs.sheet.style.height = `${this.sheetHeight}px`;
      this.deltaY = 0;
    }
  },
  mounted() {
    if (this.initial === 'half') {
      this.showBottomSheet = true;
      this.sheetHeight = window.innerHeight * 0.5;
      this.$refs.sheet.style.height = `${this.sheetHeight}px`;
    } else {
      this.showHead = true;
      this.sheetHeight = 20;
      this.$refs.sheet.style.height = `${this.sheetHeight}px`;
    }
  }
};
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
  max-height: 100vh;
  transition: height 0.3s ease;
  overflow: hidden;
}


.sheet.head {
  height: 20px;
}

.sheet.half {
  height: 50vh;
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
