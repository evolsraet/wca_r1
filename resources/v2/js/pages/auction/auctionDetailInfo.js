export default function auctionDetailInfo() {
  return {
    
    auction: {},
    // diag : {},
    niceDnrHistory: {},
    carHistoryCrash: {},
    
    init() {
      console.log('auctionDetailInfo');

      this.initWithWatch();

      console.log('this.auction', this.auction);
    //   console.log('this.diag', this.diag);

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

      console.log('this.auction', this.auction);
      

      Alpine.store('auctionEvent').getNiceDnrHistory(this.auction.owner_name, this.auction.car_no).then(res => {
        console.log('getNiceDnrHistory res', res);
        this.niceDnrHistory = res.data.data;
      });
    },

    getCarHistoryCrash() {
      Alpine.store('auctionEvent').getCarHistoryCrash(this.auction.car_no).then(res => {
        console.log('getCarHistoryCrash res', res);
        this.carHistoryCrash = res.data.data;
      });
    }
  }
}