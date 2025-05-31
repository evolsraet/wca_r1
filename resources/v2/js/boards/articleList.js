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

    // 정렬 상태
    sortColumn: 'id',
    sortDirection: 'desc',

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

        // 페이지 가시성 변경 시 새로고침 (뒤로 가기 등)
        this.setupPageVisibilityHandler();
    },

    // 페이지 가시성 변경 감지 설정
    setupPageVisibilityHandler() {
        // 페이지가 다시 보여질 때 목록 새로고침
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && this.initialized) {
                console.log('Page became visible, refreshing articles...');
                this.loadArticles();
            }
        });

        // 브라우저의 뒤로/앞으로 가기 감지
        window.addEventListener('pageshow', (event) => {
            if (event.persisted && this.initialized) {
                console.log('Page restored from cache, refreshing articles...');
                this.loadArticles();
            }
        });
    },

    // URL에서 필터값 로드
    loadFiltersFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);

        this.filters.category = urlParams.get('category') || '';
        this.filters.search_text = urlParams.get('search_text') || '';
        this.filters.page = parseInt(urlParams.get('page')) || 1;
        this.filters.paginate = parseInt(urlParams.get('paginate')) || 10;

        // 정렬 파라미터도 URL에서 로드
        this.sortColumn = urlParams.get('sort') || 'id';
        this.sortDirection = urlParams.get('direction') || 'desc';
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
                order_column: this.sortColumn,
                order_direction: this.sortDirection
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
        if (this.sortColumn !== 'id') params.set('sort', this.sortColumn);
        if (this.sortDirection !== 'desc') params.set('direction', this.sortDirection);

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

    // 날짜 포맷팅 (board 스토어 메소드 사용)
    formatDate(dateString) {
        return this.$store.board.formatDate(dateString);
    },

    // 성공 메시지 표시 (board 스토어 메소드 사용)
    showSuccess(message) {
        this.$store.board.showSuccess(message);
    },

    // 에러 메시지 표시 (board 스토어 메소드 사용)
    showError(message) {
        this.$store.board.showError(message);
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
    },

    // 정렬 메소드
    sort(column) {
        console.log('Sorting by column:', column);

        // 같은 컬럼을 클릭하면 방향 토글, 다른 컬럼이면 desc로 시작
        if (this.sortColumn === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = column;
            this.sortDirection = 'desc';
        }

        // 정렬 시 첫 페이지로 이동
        this.filters.page = 1;

        // 목록 다시 로드
        this.loadArticles();
    },

    // 정렬 상태 확인 메소드
    isSorted(column) {
        return this.sortColumn === column;
    },

    // 정렬 방향 아이콘 가져오기
    getSortIcon(column) {
        if (!this.isSorted(column)) {
            return 'mdi-sort'; // 기본 정렬 아이콘
        }
        return this.sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending';
    },

    // 게시글 순번 계산
    getArticleNumber(index) {
        const currentStart = (this.pagination.current_page - 1) * this.pagination.per_page;

        // 기본 정렬(id desc) 또는 desc 정렬일 때: 최신 글이 1번
        if (this.sortColumn === 'id' && this.sortDirection === 'desc') {
            return this.pagination.total - (currentStart + index);
        }
        // id 오름차순 정렬일 때: 가장 오래된 글이 1번
        else if (this.sortColumn === 'id' && this.sortDirection === 'asc') {
            return currentStart + index + 1;
        }
        // 다른 컬럼으로 정렬할 때: 현재 검색 결과에서의 순번
        else {
            if (this.sortDirection === 'desc') {
        return this.pagination.total - (currentStart + index);
            } else {
                return currentStart + index + 1;
            }
        }
    }
});
