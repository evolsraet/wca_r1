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
      const params = new URLSearchParams(window.location.search);
      const page = parseInt(params.get('page')) || 1;
      const search = params.get('search_text') || '';

      this.form.page = page;
      this.form.search_text = search;

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

        const meta = response.data.meta;
        this.form.totalPages = meta?.last_page || 1;

        this.updateUrlParams();

      } catch (err) {
        console.error('경매 리스트 불러오기 실패:', err);
      } finally {
        this.loading = false;
      }
    },

    changePage(page) {
      if (page < 1 || page > this.form.totalPages || page === this.form.page) return;
      this.form.page = page;
      this.getAuctionList();

      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    },

    handleSearch() {
      this.form.page = 1;
      this.getAuctionList();
    },

    updateUrlParams() {
      const url = new URL(window.location.href);
      url.searchParams.set('page', this.form.page);
      url.searchParams.set('search_text', this.form.search_text);
      history.replaceState({}, '', url);
    },
  };
}