import Alpine from 'alpinejs';
import useBidStore from '../../util/bids.js';
Alpine.store('bid', useBidStore());

export default function () {
    return {
        init() {
            console.log('activeAuctionDealers init');
        },
        cancelAuction(auction) {
            console.log('cancelAuction', auction?.id);

            Alpine.store('swal').fire({
                title: '경매 취소',
                text: '경매를 취소하시겠습니까?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: '확인',
                cancelButtonColor: '#d33',
                cancelButtonText: '취소',
            }).then(result => {
                if (result.isConfirmed) {
                    Alpine.store('bid').cancelBid(auction?.id).then(res => {
                        console.log('res', res);
                    });
                }
            });

        }
    }
}