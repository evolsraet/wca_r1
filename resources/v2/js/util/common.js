export default {
    // 날짜 포맷팅
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
            return date.toLocaleString('ko-KR', {
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
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

    // 파일 크기 포맷팅
    formatFileSize(bytes) {
        if (!bytes) return '0 B';

        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    },

    // 문자열 자르기
    truncateText(text, length = 50) {
        if (!text) return '';
        return text.length > length ? text.substring(0, length) + '...' : text;
    },

    // HTML 태그 제거
    stripHtml(html) {
        if (!html) return '';
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    },

    // 게시글 내용 포맷팅 (줄넘김 처리)
    formatContent(content) {
        if (!content) return '';

        // 기본적인 XSS 방지를 위해 스크립트 태그 제거
        let formatted = content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');

        // 줄넘김 문자를 <br> 태그로 변환 (이미 HTML이 포함된 경우를 고려)
        if (!formatted.includes('<br>') && !formatted.includes('<p>')) {
            formatted = formatted.replace(/\n/g, '<br>');
        }

        return formatted;
    },

    // 권한 체크 (현재 사용자 기준)
    hasPermission(permission) {
        if (!permission) return true;

        // 로그인이 필요한 경우
        if (permission === 'act.login') {
            return !!window.user;
        }

        // 관리자 권한이 필요한 경우
        if (permission === 'act.admin') {
            return window.user?.permissions?.includes('act.admin') || false;
        }

        // 기타 권한 체크
        return window.user?.permissions?.includes(permission) || false;
    },

    // 에러 메시지 처리
    handleErrors(errors) {
        this.errors = {};

        if (typeof errors === 'object' && errors !== null) {
            // Laravel validation errors 형태 처리
            Object.keys(errors).forEach(field => {
                if (Array.isArray(errors[field])) {
                    this.errors[field] = errors[field][0];
                } else {
                    this.errors[field] = errors[field];
                }
            });
        }
    },

    // 에러 초기화
    clearErrors() {
        this.errors = {};
    },

    // 성공 메시지 표시
    showSuccess(message) {
        // toastr 스토어가 있으면 사용, 없으면 기본 alert
        if (Alpine.store('toastr')) {
            Alpine.store('toastr').success(message);
        } else {
            alert(message);
        }
    },

    // 에러 메시지 표시
    showError(message) {
        // toastr 스토어가 있으면 사용, 없으면 기본 alert
        if (Alpine.store('toastr')) {
            Alpine.store('toastr').error(message);
        } else {
            alert(message);
        }
    },

    // 확인 대화상자
    async confirm(title, text = '') {
        // swal 스토어가 있으면 사용, 없으면 기본 confirm
        if (Alpine.store('swal')) {
            const result = await Alpine.store('swal').fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '확인',
                cancelButtonText: '취소',
                reverseButtons: true
            });
            return result.isConfirmed;
        } else {
            return confirm(`${title}\n${text}`);
        }
    },

    // URL 파라미터 생성
    buildQueryString(params) {
        const filtered = Object.entries(params)
            .filter(([key, value]) => value !== '' && value !== null && value !== undefined)
            .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
            .join('&');

        return filtered ? `?${filtered}` : '';
    },

    // 페이지 이동
    goToPage(url) {
        window.location.href = url;
    },

    // 페이지 새로고침
    reload() {
        window.location.reload();
    },

    // 페이지 가시성 변경 감지 설정
    setupPageVisibilityHandler(callback) {
        if (typeof callback !== 'function') {
            console.error('setupPageVisibilityHandler: callback must be a function');
            return;
        }

        // 페이지가 다시 보여질 때 콜백 실행
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                console.log('Page became visible, executing callback...');
                callback();
            }
        });

        // 브라우저의 뒤로/앞으로 가기 감지
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                console.log('Page restored from cache, executing callback...');
                callback();
            }
        });
    },

    // 정렬 메소드
    sort(column, filters, loadCallback) {
        if (!filters || typeof loadCallback !== 'function') {
            console.error('sort: filters and loadCallback are required');
            return;
        }

        console.log('Sorting by column:', column);

        // 같은 컬럼을 클릭하면 방향 토글, 다른 컬럼이면 desc로 시작
        if (filters.order_column === column) {
            filters.order_direction = filters.order_direction === 'asc' ? 'desc' : 'asc';
        } else {
            filters.order_column = column;
            filters.order_direction = 'desc';
        }

        // 정렬 시 첫 페이지로 이동
        filters.page = 1;

        // 목록 다시 로드
        loadCallback();
    },

    // 정렬 상태 확인 메소드
    isSorted(column, currentColumn) {
        return currentColumn === column;
    },

    // 정렬 방향 아이콘 가져오기
    getSortIcon(column, currentColumn, currentDirection) {
        if (column !== currentColumn) {
            return 'mdi-sort'; // 기본 정렬 아이콘
        }
        return currentDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending';
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

    // URL 파라미터 값이 기본값인지 확인하는 유틸리티
    shouldIncludeInUrl(key, value) {
        // null, undefined 체크
        if (value == null) return false;

        const defaultConditions = {
            page: value > 1,
            paginate: value !== 10,
            search_text: value && typeof value === 'string' && value.trim() !== '',
            order_column: value !== 'id',
            order_direction: value !== 'desc'
        };

        // 기본 조건이 있으면 사용, 없으면 일반적인 빈 값 체크
        if (key in defaultConditions) {
            return defaultConditions[key];
        }

        // 일반적인 값 체크 - 안전한 문자열 변환
        const stringValue = String(value).trim();
        return stringValue !== '';
    },

    // URL 업데이트 - 범용 버전
    updateUrl(filters) {
        const params = new URLSearchParams();

        // where 절 추가
        if (filters.where) {
            params.set('where', filters.where);
        }

        // 일반 필터들 추가 (where 제외)
        Object.entries(filters)
            .filter(([key, value]) => key !== 'where' && this.shouldIncludeInUrl(key, value))
            .forEach(([key, value]) => params.set(key, value));

        const url = params.toString() ? `?${params.toString()}` : window.location.pathname;
        window.history.replaceState({}, '', url);
    },

    // API 파라미터 구성 함수 - 범용 버전
    buildApiParams(filters, additionalParams = {}) {
        const params = {
            page: filters.page || 1,
            paginate: filters.paginate || 10,
            order_column: filters.order_column || 'id',
            order_direction: filters.order_direction || 'desc',
            ...additionalParams
        };

        // where 절 추가
        if (filters.where) {
            params.where = filters.where;
        }

        // 검색어 필터
        if (filters.search_text?.trim()) {
            params.search_text = filters.search_text;
        }

        return params;
    },

    // URL에서 필터값 로드 - 범용 버전
    loadFiltersFromUrl(filters, whereBuilder, defaultFilters = {}) {
        const urlParams = new URLSearchParams(window.location.search);

        // where 절 파라미터 처리
        if (whereBuilder) {
            const whereParam = urlParams.get('where');
            if (whereParam) {
                const parsedWhere = whereBuilder.parse(whereParam);
                filters.where = { ...filters.where, ...parsedWhere };
            }
        }

        // 일반 필터들 처리 (where 제외)
        Object.entries(filters).forEach(([key, value]) => {
            if (key !== 'where') {
                filters[key] = this.parseUrlParam(urlParams, key, defaultFilters[key] || '');
            }
        });
    }
};
