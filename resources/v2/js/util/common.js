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

    // 정렬 방향 아이콘 가져오기
    getSortIcon(column, currentColumn, currentDirection) {
        if (column !== currentColumn) {
            return 'mdi-sort'; // 기본 정렬 아이콘
        }
        return currentDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending';
    }
};
