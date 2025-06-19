// v2/js/util/useBidStore.js
export default function useBidStore() {
    return {
        bidsData: [],
        bids: [],
        bid: {
            auction_id: '',
            price: ''
        },
        validationErrors: {},
        isLoading: false,
        auctionsData: [],
        bidPagination: {},

        async getBids(page = 1, isSelect = false, isMyBid = false, status = 'all', search_text = '') {
            let request = Alpine.store('api').get('/api/bids', {
                with: ['auction'],
                search: search_text,
                page: page
            });

            if (isSelect) {
                const statusFilter = status === 'all' ? 'dlvr,chosen' : status;
                request.whereOr('auction.status', statusFilter);
            }

            if (isMyBid) {
                if (status !== 'all') {
                    if (status === 'bid') {
                        request.whereOr('auction.status', 'ing,wait');
                    } else if (status === 'cnsgnmUnregist') {
                        request.addWhere('auction.status', 'dlvr');
                        request.doesnthave(['auction.memo_digician:dlvr']);
                    } else {
                        request.whereOr('auction.status', status);
                    }
                }
            }

            return request.callback(result => {
                this.bidsData = result.data;
                this.bidPagination = result.rawData.data.meta;
                return result;
            }).get();
        },

        async getMyBids(page = 1, isSelect = false, isMyBid = false, status = 'all') {
            let request = Alpine.store('api').get('/api/bids', {
                with: ['auction'],
                page: page
            });

            if (isSelect) {
                const statusFilter = status === 'all' ? 'dlvr,chosen' : status;
                request.whereOr('auction.status', statusFilter);
            }

            if (isMyBid) {
                request.whereOr('auction.status', 'ing,wait');
            }

            return request.callback(result => result).get();
        },

        async getMyBidsAll() {
            let request = Alpine.store('api').get('/api/bids');
            return request.callback(result => result).get();
        },

        async getscsBids(page = 1, status = 'all', search_title = '', bidIdStringList = '') {
            const statusFilter = status === 'all' ? 'dlvr,chosen' : status;
            let request = Alpine.store('api').get('/api/auctions', {
                search: search_title,
                with: ['bids'],
                page: page,
                whereOr: [
                    { auction_id: bidIdStringList },
                    { status: statusFilter }
                ]
            }).whereOr('auctions.status', statusFilter);

            return request.callback(result => result).get();
        },

        async getHomeBids(mainIsOk = false) {
            let request = Alpine.store('api').get('/api/bids', {
                with: ['auction'],
                pageLimit: 10000
            });

            if (mainIsOk) {
                request.whereOr('auction.status', 'ing,wait');
            }

            return request.callback(result => {
                this.bidsData = result.data;
                return result.data;
            }).get();
        },

        async getBidById(id) {
            return Alpine.store('api').get(`/api/bids/${id}`, {
                with: ['auction']
            }).callback(result => {
                if (result.isSuccess) {
                    return result.data;
                } else {
                    Alpine.store('swal').fire({
                        title: '오류가 발생하였습니다.',
                        text: '관리자에게 문의해주세요.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                }
            }).get();
        },

        async submitBid(auctionId, bidAmount, userId) {
            if (this.isLoading) return;
            this.isLoading = true;
            this.validationErrors = {};

            return Alpine.store('api').post('/api/bids', {
                user_id: userId,
                bid: {
                    auction_id: auctionId,
                    price: bidAmount
                }
            }).callback(result => {
                this.isLoading = false;

                if (result.isSuccess) {
                    return {
                        success: true,
                        bidId: result.data.id,
                        message: result.data.message
                    };
                } else {
                    let message = '입찰 제출 중 오류가 발생했습니다. 다시 시도해 주세요.';
                    if (result.rawData?.response?.data?.message?.includes('Numeric value out of range')) {
                        message = '입찰 금액이 너무 큽니다. 다시 시도해 주세요.';
                    } else {
                        message = result.rawData?.response?.data?.message || message;
                    }
                    return { success: false, message };
                }
            }).post();
        },

        async cancelBid(bidId) {
            return Alpine.store('api').delete(`/api/bids/${bidId}`).callback(result => {
                return { success: result.isSuccess };
            }).delete();
        },

        async getBidsByUserId(userId) {
            return Alpine.store('api').get('/api/bids', {
                where: [`bids.user_id:${userId}`],
                pageLimit: 10000
            }).callback(result => {
                if (result.isSuccess) {
                    return result.data;
                } else {
                    Alpine.store('swal').fire({
                        title: '오류가 발생하였습니다.',
                        text: '관리자에게 문의해주세요.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                }
            }).get();
        },

        get bidsCountByUser() {
            const count = {};
            this.bidsData.forEach(bid => {
                const userId = bid.user_id;
                count[userId] = (count[userId] || 0) + 1;
            });
            return count;
        },

        get viewBids() {
            return this.bidsData.slice(0, 2);
        }
    };
}  
