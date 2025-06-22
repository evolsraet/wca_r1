export default function () {
    return {
      currentStep: 1,
      steps: [
        { label: '판매자', desc: '탁송정보' },
        { label: '구매자', desc: '탁송정보' },
        { label: '구매자', desc: '입금완료' },
      ],
      init() {
        console.log('taksongProgress init');
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