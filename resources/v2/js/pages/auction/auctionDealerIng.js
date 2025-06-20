import Alpine from 'alpinejs';
import useBidStore from '../../util/bids.js';
Alpine.store('bid', useBidStore());

export default function () {
    return {
        init() {
            console.log('auctionDealerIng init');

            Alpine.store('bid').getMyBidsAll(1, true, true, 'all').then(res => {
                console.log('res', res);
            });

            Alpine.store('bid').getMyBids(1, true, true, 'all').then(res => {
                console.log('res', res);
            });

        }
    }
}