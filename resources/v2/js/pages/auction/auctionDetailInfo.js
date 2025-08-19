export default function auctionDetailInfo() {
  return {
    
    auction: {},
    // diag : {},
    niceDnrHistory: {},
    carHistoryCrash: {},
    
    init() {
      console.log('auctionDetailInfo');

      this.initWithWatch();
    },

    initWithWatch() {
        const check = setInterval(() => {
            if (window.auction && window.auction.id) {
                this.auction = window.auction;
                this.getNiceDnrHistory();
                this.getCarHistoryCrash();
                clearInterval(check);
            }
        }, 50);
    },

    getNiceDnrHistory() {

      Alpine.store('auctionEvent').getNiceDnrHistory(this.auction.owner_name, this.auction.car_no).then(res => {
        this.niceDnrHistory = res.data.data;
      });
    },

    getCarHistoryCrash() {
      Alpine.store('auctionEvent').getCarHistoryCrash(this.auction.car_no).then(res => {
        this.carHistoryCrash = res.data?.data;
      });
    }
  }
}