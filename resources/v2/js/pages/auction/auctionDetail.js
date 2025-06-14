import { api } from '../../util/axios.js';

export default function () {
  return {
    // auction: null,
    init() {
      console.log('auctionDetail');
      // this.getAuction();
    },
    // async getAuction() {
    //   const hashid = window.hashid;
    //   const response = await api.get(`/api/auctions/${hashid}`, {
    //     with: 'bids,reviews,likes',
    //     paginate: '12',
    //   });

    //   this.auction = response.data.data;

    //   console.log('this.auction', this.auction);

    // }
  };
}