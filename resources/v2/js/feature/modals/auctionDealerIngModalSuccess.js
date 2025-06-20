import Alpine from 'alpinejs';
import useBidStore from '../../util/bids.js';
Alpine.store('bid', useBidStore());

export default function () {
    return {
        id: '',
        bidAmount: 0,
        init() {
            const data = window.modalData.content;
            console.log('auctionDealerIngModalSuccess init');
            console.log(data.bidAmount);

            console.log('data.id', data.auction.id);
            console.log('window.userId', window.userId);
            this.bidAmount = data.bidAmount;
            this.id = data.auction.id;
        },
        submit() {
            console.log('submit');

            // this.bidAmount;
            console.log(this.bidAmount);

            // useBid().submitBid(data.auction.id, this.bidAmount, window.userId).then(res => {
            //     console.log('res', res);
            // });

            Alpine.store('bid').submitBid(this.id, this.bidAmount, window.userId).then(res => {
                console.log('res', res);

                if (res.success) {
                    Alpine.store('modal').close('auctionDealerIngModalSuccess');

                    Alpine.store('swal').fire({
                        title: '입찰 완료',
                        text: '입찰이 완료되었습니다.',
                        icon: 'success',
                        confirmButtonText: '확인'
                    });

                } else {
                    Alpine.store('swal').fire({
                        title: '오류가 발생하였습니다.',
                        text: res.message,
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                }

            });

            // Alpine.store('modal').close('auctionDealerIngModalSuccess');

        }
    }
}