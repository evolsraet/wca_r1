// 게시글 목록 컴포넌트
export default () => ({
    // 기본 상태
    boardId: null,
    loading: false,
    articles: [],
    pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
        prev: null,
        next: null
    },

    // 필터 및 검색 - where 절과 일반 필터 분리
    filters: {
        // where 절용 필터들 (blade에서 filters.where.xxx 형태로 추가)
        where: {
            category: ''
        },
        // 일반 필터들
        search_text: '',
        page: 1,
        paginate: 10,
        // 정렬 파라미터도 필터로 포함
        order_column: 'id',
        order_direction: 'desc'
    },

    // Alpine 컴포넌트 초기화 시 자동 실행
    init() {
        // window.boardConfig에서 boardId 가져오기
        const boardId = window.boardConfig?.boardId;
        console.log('boardId from window.boardConfig:', boardId);

        if (!boardId) {
            console.error('boardId is required');
            this.$store.common.showError('게시판 ID가 없습니다.');
            return;
        }

        this.boardId = boardId;

        // URL 파라미터에서 초기 필터값 설정
        this.$store.common.loadFiltersFromUrl(this.filters, this.$store.whereBuilder, {
            search_text: '',
            page: 1,
            paginate: 10,
            order_column: 'id',
            order_direction: 'desc'
        });

        // 게시글 목록 로드
        this.loadArticles();

        // 페이지 가시성 변경 시 새로고침 (뒤로 가기 등)
        this.$store.common.setupPageVisibilityHandler(() => {
            if (this.initialized) {
                this.loadArticles();
            }
        });
    },

    // 게시글 목록 로드
    async loadArticles() {
        this.loading = true;

        try {
            // where 절 생성
            const whereClause = this.$store.whereBuilder.build(this.filters.where, 'articles');
            const filters = {
                ...this.filters,
                where: whereClause
            };

            const params = this.$store.common.buildApiParams(filters, {
                with: 'user'
            });
            const response = await this.$store.api.get(`/api/board/${this.boardId}/articles`, params);

            if (response.data.status === 'ok') {
                this.articles = response.data.data || [];
                this.pagination = response.data.meta || this.pagination;
                this.pagination.prev = response.data.links?.prev;
                this.pagination.next = response.data.links?.next;

                // URL 업데이트
                this.$store.common.updateUrl(filters);
            }
        } catch (error) {
            console.error('게시글 로드 실패:', error);
            this.$store.common.showError('게시글을 불러오는데 실패했습니다.');
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

    // 페이지네이션용 페이지 번호 배열 생성
    get paginationPages() {
        const { current_page: currentPage, last_page: lastPage } = this.pagination;
        const pages = [];

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

    // 새로고침
    refresh() {
        this.loadArticles();
    },

    // 검색 초기화
    resetSearch() {
        window.location.href = window.location.pathname;
    },

    // 정렬 메소드 (common.js 사용)
    sort(column) {
        this.$store.common.sort(column, this.filters, () => this.loadArticles());
    },

    // 정렬 상태 확인 메소드 (common.js 사용)
    isSorted(column) {
        return this.$store.common.isSorted(column, this.filters.order_column);
    },

    // 게시글 순번 계산
    getArticleNumber(index) {
        const currentStart = (this.pagination.current_page - 1) * this.pagination.per_page;

        // 기본 정렬(id desc) 또는 desc 정렬일 때: 최신 글이 1번
        if (this.filters.order_column === 'id' && this.filters.order_direction === 'desc') {
            return this.pagination.total - (currentStart + index);
        }
        // id 오름차순 정렬일 때: 가장 오래된 글이 1번
        else if (this.filters.order_column === 'id' && this.filters.order_direction === 'asc') {
            return currentStart + index + 1;
        }
        // 다른 컬럼으로 정렬할 때: 현재 검색 결과에서의 순번
        else {
            if (this.filters.order_direction === 'desc') {
                return this.pagination.total - (currentStart + index);
            } else {
                return currentStart + index + 1;
            }
        }
    }
});
