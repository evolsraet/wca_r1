import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

export default function () {
  return {
    car_thumbnail: null,
    init() {
      let lightbox;
      let photoSwipeImages = [];

      this.car_thumbnail = window.car_thumbnail;

    //   console.log('this.car_thumbnail', this.car_thumbnail);

      if(this.car_thumbnail){  
    
        photoSwipeImages = window.car_thumbnail.map(file => ({
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
    }
  };
}