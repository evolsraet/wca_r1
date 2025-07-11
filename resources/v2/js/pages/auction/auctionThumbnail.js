import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

export default function () {
  return {
    car_thumbnail: null,
    showStepStatus: true,
    init() {
      let lightbox;
      let photoSwipeImages = [];

      try {
          this.car_thumbnail = typeof window.car_thumbnail === 'string' 
              ? JSON.parse(window.car_thumbnail)
              : window.car_thumbnail;
      } catch (e) {
          this.car_thumbnail = [window.car_thumbnail];
      }

      if(this.car_thumbnail?.length) {
          photoSwipeImages = this.car_thumbnail
              .filter(Boolean)
              .map(file => ({
                  src: file,
                  w: 800,
                  h: 600
              }));
      }

      lightbox = new PhotoSwipeLightbox({
        gallery: '#customCarousel',
        children: '.carousel-item',
        pswpModule: () => import('photoswipe'),
        bgOpacity: 0.8,
      });
    
      lightbox.on('itemData', (e) => {
        e.itemData = photoSwipeImages[e.index]
      })
    
      lightbox.init();

    },
    hideStatusStep() {
      
      const box = document.getElementById('step-progress-box');
      const btn = document.getElementById('status-toggle-btn');

      this.showStepStatus = !this.showStepStatus;

      // UI 변경
      if (box && btn) {
        box.style.display = this.showStepStatus ? '' : 'none';
        btn.innerText = this.showStepStatus ? '상태 숨기기' : '상태 보기';
        btn.title = this.showStepStatus
          ? '클릭하면 경매상태바를 숨깁니다.'
          : '클릭하면 경매상태바를 보입니다.';
      }

      // 로컬스토리지 저장
      localStorage.setItem('showStepStatus', this.showStepStatus);

    },
  };
}