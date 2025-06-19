export default function () {
    return {
        bidAmount: 0,
        init() {
            const data = window.modalData.content;
            console.log('auctionDealerIngModalSuccess init');
            console.log(data.bidAmount);

            this.bidAmount = data.bidAmount;

        },
        submit() {
            console.log('submit');

            // this.bidAmount;
            console.log(this.bidAmount);

            

            Alpine.store('modal').close('auctionDealerIngModalSuccess');

        }
    }
}