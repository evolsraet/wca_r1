import { api } from '../../util/axios';

export default function () {
    return {
        auction: [],
        init() {
            this.getMyListings();
        },
        async getMyListings() {
            const response = await api.get('/api/auctions', {
                with: 'bids,likes',
                page: '1',
                paginate: 2
            });
            this.auction = response.data.data;
        }
    };
}