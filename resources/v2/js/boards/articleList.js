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

    // 필터 및 검색 - where 절과 일반 필터 분리
    filters: {
        // where 절용 필터들 (blade에서 filters.where.xxx 형태로 추가)
        where: {
            category: ''
        },
        // 일반 필터들
        search_text: '',
        page: 1,
        paginate: 10
    },

    // 정렬 상태
    order_column: 'id',
    order_direction: 'desc',

    // Alpine 컴포넌트 초기화 시 자동 실행
    init() {
        console.log('List initialized');

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

    // URL 파라미터를 안전하게 파싱하는 유틸리티 함수
    parseUrlParam(urlParams, key, defaultValue = '') {
        const value = urlParams.get(key);
        if (value === null || value === undefined) return defaultValue;

        // 빈 문자열 체크
        const trimmedValue = value.trim();
        if (trimmedValue === '') return defaultValue;

        // 숫자 변환 시도
        const numValue = Number(trimmedValue);
        return !isNaN(numValue) ? numValue : trimmedValue;
    },

    // URL에서 필터값 로드 - 통합된 버전
    loadFiltersFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);

        // this.filters 전체를 한번에 순회하여 처리
        Object.entries(this.filters).forEach(([key, value]) => {
            if (key === 'where') {
                // where 절 필터들 처리
                Object.keys(value).forEach(whereKey => {
                    this.filters.where[whereKey] = this.parseUrlParam(urlParams, whereKey, '');
                });
            } else {
                // 일반 필터들 처리
                this.filters[key] = this.parseUrlParam(urlParams, key, this.getDefaultFilterValue(key));
            }
        });

        // 정렬 파라미터 로드
        this.order_column = this.parseUrlParam(urlParams, 'sort', 'id');
        this.order_direction = this.parseUrlParam(urlParams, 'direction', 'desc');
    },

    // 필터의 기본값 반환 유틸리티
    getDefaultFilterValue(key) {
        const defaults = {
            search_text: '',
            page: 1,
            paginate: 10
        };
        return defaults[key] || '';
    },

    // API where 절 자동 생성 함수 - API 스펙 준수
    buildWhereClause() {
        const whereClauses = [];

        Object.entries(this.filters.where).forEach(([key, value]) => {
            // null, undefined 체크
            if (value == null) return;

            const stringValue = String(value).trim();
            if (stringValue === '') return;

            // _null 값 처리 (null 비교)
            if (stringValue === '_null') {
                whereClauses.push(`articles.${key}:_null`);
                return;
            }

            // 비교 연산자 포함된 값 처리
            // 예: ">:10", "<:5", "like:검색어", "whereIn:1,2,3"
            if (stringValue.includes(':') && this.isOperatorValue(stringValue)) {
                whereClauses.push(`articles.${key}:${stringValue}`);
            } else {
                // 단순 등호 비교
                whereClauses.push(`articles.${key}:${stringValue}`);
            }
        });

        // '|'로 구분하여 반환 (API 스펙: 여러 조건을 '|'로 구분)
        return whereClauses.join('|');
    },

    // 비교 연산자가 포함된 값인지 확인하는 유틸리티
    isOperatorValue(value) {
        const operators = ['>', '<', '>=', '<=', 'like', 'whereIn', 'orWhere'];
        const firstPart = value.split(':')[0];
        return operators.includes(firstPart);
    },

    // where 절 조건 추가 헬퍼 메소드들
    addWhereCondition(key, operator, value) {
        if (operator === 'null') {
            this.filters.where[key] = '_null';
        } else {
            this.filters.where[key] = `${operator}:${value}`;
        }
    },

    // 편의 메소드들
    addWhereLike(key, value) {
        this.addWhereCondition(key, 'like', value);
    },

    addWhereIn(key, values) {
        this.addWhereCondition(key, 'whereIn', Array.isArray(values) ? values.join(',') : values);
    },

    addWhereGreaterThan(key, value) {
        this.addWhereCondition(key, '>', value);
    },

    addWhereLessThan(key, value) {
        this.addWhereCondition(key, '<', value);
    },

    addWhereNull(key) {
        this.addWhereCondition(key, 'null', null);
    },

    // 범위 검색 헬퍼 메소드들 (중복 조건 방지)
    setWhereRange(baseKey, minValue, maxValue) {
        // 기존 범위 관련 키들 정리
        const keysToRemove = Object.keys(this.filters.where).filter(key =>
            key.startsWith(baseKey)
        );
        keysToRemove.forEach(key => {
            delete this.filters.where[key];
        });

        // 새로운 범위 조건 설정
        if (minValue !== null && minValue !== '' && minValue !== undefined) {
            this.filters.where[`${baseKey}_min`] = `>=:${minValue}`;
        }
        if (maxValue !== null && maxValue !== '' && maxValue !== undefined) {
            this.filters.where[`${baseKey}_max`] = `<=:${maxValue}`;
        }
    },

    // 단일 범위 조건 설정 (min 또는 max만)
    setWhereMin(key, value) {
        // 기존 min 조건 제거
        delete this.filters.where[`${key}_min`];
        delete this.filters.where[key];

        if (value !== null && value !== '' && value !== undefined) {
            this.filters.where[key] = `>=:${value}`;
        }
    },

    setWhereMax(key, value) {
        // 기존 max 조건 제거
        delete this.filters.where[`${key}_max`];
        delete this.filters.where[key];

        if (value !== null && value !== '' && value !== undefined) {
            this.filters.where[key] = `<=:${value}`;
        }
    },

    // 복합 조건 설정 (여러 조건을 한번에)
    setWhereConditions(conditions) {
        Object.entries(conditions).forEach(([key, value]) => {
            if (value === null || value === '' || value === undefined) {
                delete this.filters.where[key];
            } else {
                this.filters.where[key] = value;
            }
        });
        this.onWhereFilterChange();
    },

    // 게시글 목록 로드
    async loadArticles() {
        this.loading = true;

        try {
            const params = this.buildApiParams();
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

    // API 파라미터 구성 함수
    buildApiParams() {
        const params = {
            page: this.filters.page,
            paginate: this.filters.paginate,
            with: 'user',
            order_column: this.order_column,
            order_direction: this.order_direction
        };

        // where 절 자동 생성
        const whereClause = this.buildWhereClause();
        if (whereClause) {
            params.where = whereClause;
        }

        // 검색어 필터
        if (this.filters.search_text?.trim()) {
            params.search_text = this.filters.search_text;
        }

        return params;
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

    // 카테고리 변경 (where 절 필터 변경 시 호출)
    async onCategoryChange() {
        console.log('Category changed to:', this.filters.where.category);
        await this.onWhereFilterChange();
    },

    // where 절 필터 변경 시 공통 처리 함수
    async onWhereFilterChange() {
        this.filters.page = 1;
        await this.loadArticles();
    },

    // URL 파라미터 값이 기본값인지 확인하는 유틸리티
    shouldIncludeInUrl(key, value) {
        // null, undefined 체크
        if (value == null) return false;

        const defaultConditions = {
            page: value > 1,
            paginate: value !== 10,
            search_text: value && typeof value === 'string' && value.trim() !== ''
        };

        // 기본 조건이 있으면 사용, 없으면 일반적인 빈 값 체크
        if (key in defaultConditions) {
            return defaultConditions[key];
        }

        // 일반적인 값 체크 - 안전한 문자열 변환
        const stringValue = String(value).trim();
        return stringValue !== '';
    },

    // URL 업데이트 - 리팩토링된 버전
    updateUrl() {
        const params = new URLSearchParams();

        // where 절 필터들 추가
        Object.entries(this.filters.where)
            .filter(([key, value]) => this.shouldIncludeInUrl(key, value))
            .forEach(([key, value]) => params.set(key, value));

        // 일반 필터들 추가
        Object.entries(this.filters)
            .filter(([key, value]) => key !== 'where' && this.shouldIncludeInUrl(key, value))
            .forEach(([key, value]) => params.set(key, value));

        // 정렬 파라미터 추가 (기본값이 아닐 경우만)
        if (this.order_column !== 'id') params.set('order_column', this.order_column);
        if (this.order_direction !== 'desc') params.set('order_direction', this.order_direction);

        const url = params.toString() ? `?${params.toString()}` : window.location.pathname;
        window.history.replaceState({}, '', url);
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
        window.location.href = window.location.pathname;
        // 필터 제거
        // this.loadArticles();
    },

    // 정렬 메소드
    sort(column) {
        console.log('Sorting by column:', column);

        // 같은 컬럼을 클릭하면 방향 토글, 다른 컬럼이면 desc로 시작
        if (this.order_column === column) {
            this.order_direction = this.order_direction === 'asc' ? 'desc' : 'asc';
        } else {
            this.order_column = column;
            this.order_direction = 'desc';
        }

        // 정렬 시 첫 페이지로 이동
        this.filters.page = 1;

        // 목록 다시 로드
        this.loadArticles();
    },

    // 정렬 상태 확인 메소드
    isSorted(column) {
        return this.order_column === column;
    },

    // 정렬 방향 아이콘 가져오기
    getSortIcon(column) {
        if (!this.isSorted(column)) {
            return 'mdi-sort'; // 기본 정렬 아이콘
        }
        return this.order_direction === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending';
    },

    // 게시글 순번 계산
    getArticleNumber(index) {
        const currentStart = (this.pagination.current_page - 1) * this.pagination.per_page;

        // 기본 정렬(id desc) 또는 desc 정렬일 때: 최신 글이 1번
        if (this.order_column === 'id' && this.order_direction === 'desc') {
            return this.pagination.total - (currentStart + index);
        }
        // id 오름차순 정렬일 때: 가장 오래된 글이 1번
        else if (this.order_column === 'id' && this.order_direction === 'asc') {
            return currentStart + index + 1;
        }
        // 다른 컬럼으로 정렬할 때: 현재 검색 결과에서의 순번
        else {
            if (this.order_direction === 'desc') {
        return this.pagination.total - (currentStart + index);
            } else {
                return currentStart + index + 1;
            }
        }
    }
});
