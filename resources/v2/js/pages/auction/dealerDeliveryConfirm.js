export default function () {
    return {
        auction: {},
        init() {
            const auction = window.modalData.content.auction;
            this.auction = auction;
            console.log('dealerDeliveryConfirm init');
        },

        submit() {
            console.log('submit');

            const data = {
                auction: {
                    account: this.auction.account,
                    bank: this.auction.bank,
                    addr_post: this.auction.addr_post,
                    addr1: this.auction.addr1,
                    addr2: this.auction.addr2,
                    customTel1: this.auction.customTel1,
                    customTel2: this.auction.customTel2,
                    taksong_wish_at: this.auction.selectedDate,
                }
            }

            console.log('data', this.auction.id);
            console.log('sendData', data);

            Alpine.store('auctionEvent').updateAuction(this.auction.id, data);

            // Alpine.store('modal').close('dealerDeliveryConfirm');
        },

        cancel() {
            Alpine.store('modal').close('dealerDeliveryConfirm');
        }
    }
}