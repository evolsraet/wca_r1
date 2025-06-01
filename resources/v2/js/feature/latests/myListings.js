import { api } from '../../util/axios';

export default function () {
    return {
        cars: [],
        status: {
            'cancel' : '취소',
            'done'   : '경매완료',
            'chosen' : '선택완료',
            'wait'   : '선택대기',
            'ing'    : '경매진행',
            'diag'   : '진단대기',
            'dlvr'   : '탁송중',
            'ask'    : '신청완료',
        },
        init() {
            this.getMyListings();
        },
        async getMyListings() {
            const response = await api.get('/api/auctions', {
                with: 'bids,likes',
                page: '1',
                paginate: 2
            });
            console.log(response.data.data);
            this.cars = response.data.data;
            console.log(this.status);
        }
    };
}