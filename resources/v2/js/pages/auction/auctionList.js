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
    errors: {},
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
        const response = await Alpine.store('api').get('/api/auctions', {
          with: this.form.with,
          page: this.form.page,
          paginate: this.form.paginate,
          search_text: this.form.search_text,
        });

        // console.log('response', response);

        this.form.lists = Array.isArray(response.data?.data) ? response.data.data : [];
        this.form.lists.forEach(item => {
          item.car_km = Number(item.car_km).toLocaleString('ko-KR');
        });

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

    dispatchLikeEvent(el) {
      // auctionId를 버튼에서 가져옴
      const auctionId = el.dataset.auctionId;
      
      // JS에서 auction 객체를 찾아야 함. 이건 너의 앱 구조에 따라 달라.
      // 여기선 예시로 전역 배열을 사용하는 경우를 가정
      const auction = window.auctions?.find(a => a.id == auctionId);
    
      if (!auction) {
        console.warn('Auction 데이터를 찾을 수 없습니다:', auctionId);
        return;
      }
    
      const likeId = auction.likes?.[0]?.id || null;
    
      window.dispatchEvent(new CustomEvent('toggle-like', {
        detail: {
          auctionId: auction.id,
          userId: 999999, // 임시 고정값 (실제 유저 ID로 대체해라 제발)
          likeId: likeId
        }
      }));
    }

  };
}