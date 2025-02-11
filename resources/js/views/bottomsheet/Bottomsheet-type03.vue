<template>
  <div class="sheet-wrap">
    <div ref="overlay" class="overlay" v-if="showBottomSheet"></div>
    <div
      ref="sheet"
      class="sheet container"
      :class="{ 
        'half': showBottomSheet, 
        'animate-slide-up': showBottomSheet, 
        'modal-center': isDesktop 
      }"
    >
      <header class="handle-head"></header>
      <div
        class="p-3"
        ref="content"
        :class="{ 'no-scroll': showHead }"
        @mousedown.stop
        @touchstart.stop
      >
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
      sheetHeight: 'max-content',
      isDesktop: window.innerWidth >= 992
    };
  },
  methods: {
    toggleSheet() {
      if (this.showBottomSheet) {
        this.closeSheet();
      } else {
        this.showBottomSheet = true;
        this.showHead = false;
      }
    },
    closeSheet() {
      this.showBottomSheet = false;
      this.sheetHeight = max-content;
    },
    updateView() {
      this.isDesktop = window.innerWidth >= 992;
      if (this.isDesktop) {
        this.$refs.sheet.style.height = 'max-content';
      }
    }
  },
  mounted() {
    window.addEventListener('resize', this.updateView);
    this.updateView();
    if (this.initial === 'half') {
      this.showBottomSheet = true;

    } else {
      this.showHead = true;
      this.sheetHeight = 10;
    }
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.updateView);
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
  z-index: 100;
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
  max-height: 80vh;
  transition: height 0.3s ease;
  overflow: hidden;
  z-index: 2;
  transform: translateY(100%);
}

.sheet.head {
  height: 20px;
}

.sheet.half {
  height: max-content;
}

.sheet.animate-slide-up {
  animation: slide-up 0.3s ease-out forwards;
}

@keyframes slide-up {
  from {
    transform: translateY(100%);
  }
  to {
    transform: translateY(0);
  }
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
  height: auto;
  overflow-x: hidden;
  margin-top: 0px !important;
}

.content.no-scroll {
  overflow: hidden;
}

@media (min-width: 992px) {
  .sheet {

    transform: translate(-50%, -50%);
    height: max-content !important;
    max-height: none;
    width: auto;
    max-width: 800px;
    transition: none;
  }
  .handle {
    display: none;
  }
  .handle-head {
    cursor: default;
  }
}
.filter-content{
  display: block;
}
@media (max-width: 991px) {
  .content {
    height: 100%;
  }
}
</style>
