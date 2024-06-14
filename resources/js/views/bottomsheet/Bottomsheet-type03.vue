<template>
  <!-- 끌어올리기 내리기 불가능한 모달 bottom-sheet -->
  <div class="sheet-wrap">
    <div ref="overlay" class="overlay" v-if="showBottomSheet" @click="closeSheet"></div>
    <div ref="sheet" class="sheet" :class="{ 'half': showBottomSheet }">
      <header class="handle-head">
      </header>
      <div class="p-3" ref="content" :class="{ 'no-scroll': showHead }" @mousedown.stop @touchstart.stop>
        <slot></slot>
      </div>
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
      sheetHeight: 20 
    };
  },
  methods: {
    toggleSheet() {
      if (this.showBottomSheet) {
        this.closeSheet();
      } else {
        this.showBottomSheet = true;
        this.showHead = false;
        this.sheetHeight = window.innerHeight * 0.5;
        this.$refs.sheet.style.height = `${this.sheetHeight}px`;
      }
    },
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
  z-index: 10;
}

.overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1; 
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
  z-index: 2; 
}

.sheet.head {
  height: 20px;
}

.sheet.half {
  height: 50vh;
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
