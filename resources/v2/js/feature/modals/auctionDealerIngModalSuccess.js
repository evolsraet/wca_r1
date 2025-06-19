import useBid from '../../util/bids.js';

export default function () {
    return {
        bidAmount: 0,
        init() {
            const data = window.modalData.content;
            console.log('auctionDealerIngModalSuccess init');
            console.log(data.bidAmount);

            console.log('data.id', data.auction.id);
            console.log('window.userId', window.userId);
            this.bidAmount = data.bidAmount;

        },
        submit() {
            console.log('submit');

            // this.bidAmount;
            console.log(this.bidAmount);

            // useBid().submitBid(data.auction.id, this.bidAmount, window.userId).then(res => {
            //     console.log('res', res);
            // });

            useBid().submitBid(data.auction.id, this.bidAmount, window.userId).then(res => {
                console.log('res', res);
            });

            // Alpine.store('modal').close('auctionDealerIngModalSuccess');

        }
    }
}