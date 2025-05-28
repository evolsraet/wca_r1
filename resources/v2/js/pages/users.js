import { api } from '../util/axios';

export default {
    users: [],
    loading: false,
    error: null,

    async init() {
        await this.loadUsers();
    },

    async loadUsers() {
        this.loading = true;
        try {
            const response = await api.get('/users');
            this.users = response.data;
        } catch (error) {
            this.error = '사용자 목록을 불러오는데 실패했습니다.';
            console.error(error);
        } finally {
            this.loading = false;
        }
    }
};
