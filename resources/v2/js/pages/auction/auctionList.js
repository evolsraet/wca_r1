import { api } from '../../util/axios.js';

export default function() {
    return {
        form: {
            with: 'bids,likes',
            page: 1,
            paginate: 12,
            lists: []
        },
        errors: {},
        loading: false,

        init() {
            console.log('auction list');
            this.getAuctionList();
        },
        async getAuctionList() {
            const response = await api.get('/api/auctions', {
                with: this.form.with,
                page: this.form.page,
                paginate: this.form.paginate
            });
            this.form.lists = Array.isArray(response.data?.data) ? response.data.data : [];
            console.log(this.form.lists);
        }
    }
};