import { api } from '../../util/axios.js';

export default function () {
    return {
        auction: null,
        diag: null,
        init() {
            console.log('auctionDetail');
            this.getAuction();
            this.getDiag();
        },
        async getAuction() {
            const hashid = window.hashid;
            const response = await api.get(`/api/auctions/${hashid}`, {
                with: 'bids,reviews,likes',
                paginate: '12',
            });

            const status = window.status;

            if(response.data.data.status === 'ask' || response.data.data.status === 'diag' || status === 'ask' || status === 'diag') {
                this.auction = null;
            }else{
                response.data.data.car_first_reg_date = response.data.data.car_first_reg_date.split('T')[0];
                this.auction = response.data.data;
            }
        },
        async getDiag() {
            const carNumber = window.carNumber;
            const response = await api.get(`/api/diagRequest?diag_car_no=${carNumber}`);
            console.log('diag', response);

            if(response.data.data.status === 'ask' || response.data.data.status === 'diag' || status === 'ask' || status === 'diag') {
                this.diag = null;
            }else{
                this.diag = response.data.data;
            
                Alpine.store('shared', {
                diag: this.diag,
                });
            }

              
        },
    }
}