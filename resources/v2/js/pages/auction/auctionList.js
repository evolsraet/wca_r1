import { api } from '../../util/axios.js';

export default function () {
  return {
    form: {
      with: 'bids,likes',
      page: 1,
      paginate: 12,
      lists: [],
      totalPages: 1,
      search_text: '',
    },
    loading: false,

    init() {
      this.getAuctionList();
    },

    async getAuctionList() {
      this.loading = true;
      try {
        const response = await api.get('/api/auctions', {
          with: this.form.with,
          page: this.form.page,
          paginate: this.form.paginate,
          search_text: this.form.search_text,
        });

        this.form.lists = Array.isArray(response.data?.data) ? response.data.data : [];

        // meta 에서 페이지 정보 업데이트
        const meta = response.data.meta;
        this.form.totalPages = meta?.last_page || 1;

      } catch (err) {
        console.error('경매 리스트 불러오기 실패:', err);
      } finally {
        this.loading = false;
      }
    },

    changePage(page) {
      if (page < 1 || page > this.form.totalPages || page === this.form.page) return;
      this.form.page = page;
      this.getAuctionList(); // 반드시 호출!

      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    },

    handleSearch() {
        this.form.page = 1;
        this.getAuctionList();
    }
  };
}