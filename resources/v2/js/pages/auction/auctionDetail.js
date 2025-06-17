import { likeListeners } from '../../util/likes.js';

likeListeners();


export default function () {
  return {
    auction: null,
    diag: null,
    options: [],
    optionIcons: {
      1: { svg: '/images/options/option-icon-01.svg?raw' }, // 네비게이션
      2: { svg: '/images/options/option-icon-02.svg?raw' }, // 어라운드뷰
      3: { svg: '/images/options/option-icon-03.svg?raw' }, // 썬루프
      4: { svg: '/images/options/option-icon-04.svg?raw' }, // 파노라마선루프
      5: { svg: '/images/options/option-icon-05.svg?raw' }, // S-크루즈컨트롤
      6: { svg: '/images/options/option-icon-06.svg?raw' }, // HUD
      7: { svg: '/images/options/option-icon-07.svg?raw' }, // 전동트렁크
      8: { svg: '/images/options/option-icon-08.svg?raw' }, // 열선시트
      9: { svg: '/images/options/option-icon-09.svg?raw' }, // 통풍시트
      10: { svg: '/images/options/option-icon-10.svg?raw' }, // 열선시트
      11: { svg: '/images/options/option-icon-11.svg?raw' }, // 통풍시트
      12: { svg: '/images/options/option-icon-12.svg?raw' }, // 전동시트
      13: { svg: '/images/options/option-icon-13.svg?raw' }, // 메모리시트
      14: { svg: '/images/options/option-icon-14.svg?raw' }, // 뒷좌석모니터
      15: { svg: '/images/options/option-icon-15.svg?raw' }, // 파워슬라이딩 도어
      16: { svg: '/images/options/option-icon-16.svg?raw' }, // 후측방경보시스템
      17: { svg: '/images/options/option-icon-17.svg?raw' }, // 차선이탈경보시스템 (LDWS)
      18: { svg: '/images/options/option-icon-18.svg?raw' }, // 고정형 하이패스
      19: { svg: '/images/options/option-icon-19.svg?raw' }, // 전자제어현가장치 (ECS)
      71: { svg: '/images/options/option-icon-71.svg?raw' }, // 스페어타이어
      86: { svg: '/images/options/option-icon-86.svg?raw' }, // 후방카메라
      88: { svg: '/images/options/option-icon-88.svg?raw' }, // 키 (사제키)
      92: { svg: '/images/options/option-icon-92.svg?raw' }, // 썬루프(유)
      93: { svg: '/images/options/option-icon-93.svg?raw' }, // 네비게이션(유)
    },
    async init() {
      try {
        const hashid = window.hashid;
        const carNumber = window.carNumber;

        // API 호출
        const auctionRes = await Alpine.store('api').get('/api/auctions/' + hashid, {
          with: 'bids,reviews,likes',
          paginate: '12',
        });

        const diagRes = await Alpine.store('api').get(`/api/diagRequest?diag_car_no=${carNumber}`);

        // 로컬 상태 반영
        this.auction = auctionRes?.data?.data;
        this.diag = diagRes?.data?.data;

        // console.log('auction data:', this.auction);
        // console.log('diag data:', this.diag);
      } catch (err) {
        console.error('API 호출 실패:', err);
      }

      this.options = this.diag.diag_option_view ?? [];

      
    },

    async getRenderedSvg(option) {
      const icon = this.optionIcons[option.id];
      if (!icon) return '';
      try {
        const response = await fetch(icon.svg);
        let svg = await response.text();
        const color = option.is_ok ? '#da3138' : '#bbbbbb';
        const renderedSvg = svg.replace(/#000000/gi, color);
        return renderedSvg;
      } catch (e) {
        console.warn('SVG fetch 실패', e);
        return '';
      }
    },

  };
}