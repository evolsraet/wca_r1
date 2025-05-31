// 게시글 목록 컴포넌트
export default () => ({
    // 기본 상태
    boardId: null,
    loading: false,
    articles: [],
    initialized: false,
    pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
        prev: null,
        next: null
    },

    // 필터 및 검색
    filters: {
        category: '',
        search_text: '',
        page: 1,
        paginate: 10
    },

    // Alpine 컴포넌트 초기화 시 자동 실행
    init() {
        console.log('Alpine component initialized');

        // window.boardConfig에서 boardId 가져오기
        const boardId = window.boardConfig?.boardId;
        console.log('boardId from window.boardConfig:', boardId);

        if (this.initialized) {
            console.log('Already initialized, skipping...');
            return;
        }

        if (!boardId) {
            console.error('boardId is required');
            this.showError('게시판 ID가 없습니다.');
            return;
        }

        this.boardId = boardId;
        this.initialized = true;

        console.log('Proceeding with initialization for boardId:', boardId);

        // URL 파라미터에서 초기 필터값 설정
        this.loadFiltersFromUrl();

        // 게시글 목록 로드
        this.loadArticles();
    },

    // URL에서 필터값 로드
    loadFiltersFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);

        this.filters.category = urlParams.get('category') || '';
        this.filters.search_text = urlParams.get('search_text') || '';
        this.filters.page = parseInt(urlParams.get('page')) || 1;
        this.filters.paginate = parseInt(urlParams.get('paginate')) || 10;
    },

    // 게시글 목록 로드
    async loadArticles() {
        this.loading = true;

        try {
            // API 파라미터 구성
            const params = {
                page: this.filters.page,
                paginate: this.filters.paginate,
                with: 'user',
                order_column: 'id',
                order_direction: 'desc'
            };

            // 카테고리 필터
            if (this.filters.category) {
                params.where = `articles.category:${this.filters.category}`;
            }

            // 검색어 필터
            if (this.filters.search_text) {
                params.search_text = this.filters.search_text;
            }

            const response = await this.$store.api.get(`/api/board/${this.boardId}/articles`, params);

            if (response.data.status === 'ok') {
                this.articles = response.data.data || [];
                this.pagination = response.data.meta || this.pagination;
                this.pagination.prev = response.data.links?.prev;
                this.pagination.next = response.data.links?.next;

                // URL 업데이트
                this.updateUrl();
            }
        } catch (error) {
            console.error('게시글 로드 실패:', error);
            this.showError('게시글을 불러오는데 실패했습니다.');
        } finally {
            this.loading = false;
        }
    },

    // 페이지 로드
    async loadPage(page) {
        if (page < 1 || page > this.pagination.last_page) return;

        this.filters.page = page;
        await this.loadArticles();
    },

    // 검색 실행
    async search() {
        this.filters.page = 1; // 검색시 첫 페이지로
        await this.loadArticles();
    },

    // 카테고리 변경
    async onCategoryChange() {
        console.log('Category changed to:', this.filters.category);
        this.filters.page = 1;
        await this.loadArticles();
    },

    // URL 업데이트 (브라우저 히스토리)
    updateUrl() {
        const params = new URLSearchParams();

        if (this.filters.category) params.set('category', this.filters.category);
        if (this.filters.search_text) params.set('search_text', this.filters.search_text);
        if (this.filters.page > 1) params.set('page', this.filters.page);
        if (this.filters.paginate !== 10) params.set('paginate', this.filters.paginate);

        const url = params.toString() ? `?${params.toString()}` : window.location.pathname;
        window.history.replaceState({}, '', url);
    },

    // 페이지네이션용 페이지 번호 배열 생성
    get paginationPages() {
        const pages = [];
        const currentPage = this.pagination.current_page;
        const lastPage = this.pagination.last_page;

        // 최대 5개 페이지 표시
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(lastPage, startPage + 4);

        // 끝에서 5개 미만일 경우 시작점 조정
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        for (let i = startPage; i <= endPage; i++) {
            pages.push(i);
        }

        return pages;
    },

    // 날짜 포맷팅 (board.js 공통 메소드 활용)
    formatDate(dateString) {
        if (!dateString) return '';

        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        // 오늘 날짜면 시간만 표시
        if (diffDays <= 1) {
            return date.toLocaleTimeString('ko-KR', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        }
        // 1년 이내면 월/일 표시
        else if (diffDays <= 365) {
            return date.toLocaleDateString('ko-KR', {
                month: '2-digit',
                day: '2-digit'
            });
        }
        // 1년 초과면 년/월/일 표시
        else {
            return date.toLocaleDateString('ko-KR', {
                year: '2-digit',
                month: '2-digit',
                day: '2-digit'
            });
        }
    },

    // 성공 메시지 표시
    showSuccess(message) {
        this.$store.toastr.success(message);
    },

    // 에러 메시지 표시
    showError(message) {
        this.$store.toastr.error(message);
    },

    // 새로고침
    refresh() {
        this.loadArticles();
    },

    // 검색 초기화
    resetSearch() {
        this.filters.category = '';
        this.filters.search_text = '';
        this.filters.page = 1;
        this.loadArticles();
    }
});
