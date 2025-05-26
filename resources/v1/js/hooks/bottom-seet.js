// bottomSheet.js
class BottomSheetController {
    constructor() {
      this.bottomSheet = document.getElementsByClassName('bottom_sheet')[0];
      this.upSensor = document.getElementsByClassName('up_sensor')[0];
      this.handleWrap = document.getElementsByClassName('bottom_sheet_handle_wrap')[0];
      this.bottomTouchStart = 0;
      this.bottomScrollStart = 0;
  
      this.initEventListeners();
    }
  
    initEventListeners() {
      this.upSensor.addEventListener("touchmove", this.raiseBottomSheet.bind(this));
  
      this.bottomSheet.addEventListener("touchstart", (e) => {
        this.bottomTouchStart = e.touches[0].pageY;
        this.bottomScrollStart = this.bottomSheet.scrollTop;
      });
  
      this.bottomSheet.addEventListener("touchmove", this.lowerBottomSheet.bind(this));
      this.handleWrap.addEventListener("touchstart", (e) => {
        this.bottomTouchStart = e.touches[0].pageY;
      });
  
      this.handleWrap.addEventListener("touchmove", this.manualLowerBottomSheet.bind(this));
    }
  
    raiseBottomSheet() {
      this.bottomSheet.style.height = '70%';
      this.upSensor.style.height = '70%';
      setTimeout(() => {
        this.upSensor.style.display = 'none';
      }, 1000);
    }
  
    lowerBottomSheet(e) {
      if ((this.bottomTouchStart - e.touches[0].pageY < 0) && (this.bottomScrollStart <= 0)) {
        this.bottomSheet.style.height = '10%';
        this.upSensor.style.display = 'block';
        this.upSensor.style.height = '10%';
      }
    }
  
    manualLowerBottomSheet(e) {
      if (this.bottomTouchStart - e.touches[0].pageY < 0) {
        this.bottomSheet.style.height = '10%';
        this.upSensor.style.display = 'block';
        this.upSensor.style.height = '10%';
      }
    }
  }
  
  export default BottomSheetController;
  