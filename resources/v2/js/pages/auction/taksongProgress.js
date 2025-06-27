export default function (auction) {
    return {
      currentStep: 1,
      steps: [
        { label: '판매자', desc: '탁송정보' },
        { label: '구매자', desc: '탁송정보' },
        { label: '구매자', desc: '입금완료' },
      ],
      init() {
        console.log('taksongProgress init');

        // console.log('taksongProgress auction', auction);

        // this.watchAuction();

        // console.log('taksongProgress currentStep', this.currentStep);

      },
      initWithWatch() {
        const check = setInterval(() => {
          if (window.auction && window.auction.id) {
            if(window.auction.status_chosen == 'user'){
              this.currentStep = 1;
            }
            else if(window.auction.status_chosen == 'dealer'){
              this.currentStep = 2;
            }
            else if(window.auction.status_chosen == 'dealer_pay'){
              this.currentStep = 3;
            }

            clearInterval(check);
          }
        }, 50);

      },

      getStepClass(index) {
        return index + 1 <= this.currentStep ? 'active' : '';
      },
      getLabelClass(index) {
        return index + 1 <= this.currentStep ? 'text-dark' : 'text-secondary';
      },
      openModal() {
        Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/dealerDeliveryChoice', {
          id: 'dealerDeliveryChoice',
          title: '탁송 예정일 선택',
          size: 'modal-dialog-centered',
          showFooter: false,
        }, {
          content: {
            auction: this.auction,
            data: this.auction?.bids?.find(bid => bid.id === this.auction.bid_id),
          }
        });
      }
    };
  }