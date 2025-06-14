import { api } from '../../util/axios.js';

export default function () {
    return {
      options: [],
      optionIcons: {
        1: { svg: '/images/options/option-icon-01.svg?raw' },
        2: { svg: '/images/options/option-icon-02.svg?raw' },
        3: { svg: '/images/options/option-icon-03.svg?raw' },
        4: { svg: '/images/options/option-icon-04.svg?raw' },
        5: { svg: '/images/options/option-icon-05.svg?raw' },
        6: { svg: '/images/options/option-icon-06.svg?raw' },
        7: { svg: '/images/options/option-icon-07.svg?raw' },
        8: { svg: '/images/options/option-icon-08.svg?raw' },
        9: { svg: '/images/options/option-icon-09.svg?raw' },
        10: { svg: '/images/options/option-icon-10.svg?raw' },
        11: { svg: '/images/options/option-icon-11.svg?raw' },
        12: { svg: '/images/options/option-icon-12.svg?raw' },
        13: { svg: '/images/options/option-icon-13.svg?raw' },
        14: { svg: '/images/options/option-icon-14.svg?raw' },
        15: { svg: '/images/options/option-icon-15.svg?raw' },
        16: { svg: '/images/options/option-icon-16.svg?raw' },
        17: { svg: '/images/options/option-icon-17.svg?raw' },
        18: { svg: '/images/options/option-icon-18.svg?raw' },
        19: { svg: '/images/options/option-icon-19.svg?raw' },
        71: { svg: '/images/options/option-icon-71.svg?raw' },
        86: { svg: '/images/options/option-icon-86.svg?raw' },
        88: { svg: '/images/options/option-icon-88.svg?raw' },
        92: { svg: '/images/options/option-icon-92.svg?raw' },
        93: { svg: '/images/options/option-icon-93.svg?raw' },
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
      async init() {
        await this.getAuctionOptions();
      },
      async getAuctionOptions() {
        const carNumber = window.carNumber;
        const response = await api.get(`/api/diagRequest?diag_car_no=${carNumber}`);
        this.options = response.data.data.diag_option_view;
      }
    };
  }