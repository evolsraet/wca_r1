import { api } from '../../util/axios';

export default function () {
    return {
        notices: [],
        init() {
            this.getNotice();
        },
        async getNotice() {
            const response = await api.get('/api/board/notice/articles', {
                page: 1,
                order_column: 'created_at',
                order_direction: 'desc',
                paginate: 5
            });

            this.notices = response.data.data;
        }
    };
}
