export default function articleView() {
    return {
        // 기본 상태
        boardId: null,
        articleId: null,
        loading: false,
        article: null,
        board_attach: [],
        _initialized: false,

        // 초기화
        async init(boardId, articleId) {
            // 이미 초기화되었다면 중복 실행 방지
            if (this._initialized) {
                return;
            }

            // 파라미터가 없거나 undefined인 경우 window.boardConfig에서 가져오기
            this.boardId = boardId || window.boardConfig?.boardId;
            this.articleId = articleId || window.boardConfig?.articleId;

            console.log('articleView init:', { boardId: this.boardId, articleId: this.articleId });

            // 필수 파라미터 검증
            if (!this.boardId || !this.articleId) {
                console.error('boardId 또는 articleId가 없습니다:', { boardId: this.boardId, articleId: this.articleId });
                this.showError('게시판 또는 게시글 정보가 올바르지 않습니다.');
                return;
            }

            // 초기화 플래그 설정
            this._initialized = true;

            // board 스토어 초기화
            this.$store.board.init(this.boardId);

            await this.loadArticle();
        },

        // 게시글 로드
        async loadArticle() {
            this.loading = true;

            try {
                const response = await this.$store.api.get(`/api/board/${this.boardId}/articles/${this.articleId}`, {
                    with: 'user,media'
                });

                if (response.data.status === 'ok') {
                    this.article = response.data.data;
                } else {
                    this.showError('게시글을 불러올 수 없습니다.');
                }
            } catch (error) {
                this.showError('게시글을 불러오는데 실패했습니다.');
            } finally {
                this.loading = false;
            }
        },

        // 게시글 삭제
        async deleteArticle() {
            const confirmed = await this.$store.board.confirm(
                '게시글 삭제',
                '정말로 이 게시글을 삭제하시겠습니까? 삭제된 게시글은 복구할 수 없습니다.'
            );

            if (!confirmed) return;

            try {
                const response = await this.$store.api.delete(`/api/board/${this.boardId}/articles/${this.articleId}`);

                if (response.data.status === 'ok') {
                    this.showSuccess('게시글이 삭제되었습니다.');
                    // 삭제 성공 후 강제 새로고침으로 목록 이동
                    setTimeout(() => {
                        this.goToListWithRefresh();
                    }, 1000);
                } else {
                    this.showError(response.data.message || '게시글 삭제에 실패했습니다.');
                }
            } catch (error) {
                console.error('게시글 삭제 실패:', error);
                this.showError('게시글 삭제에 실패했습니다.');
            }
        },

        // 목록으로 이동 (히스토리 백 우선, 없으면 목록 페이지로)
        goToList() {
            // 히스토리에서 게시판 목록 페이지 찾기
            const boardListPath = `/v2/board/${this.boardId}`;

            // 현재 페이지가 직접 접근이 아니고 이전 페이지가 게시판 목록인 경우
            if (window.history.length > 1 && document.referrer.includes(boardListPath)) {
                window.history.back();
            } else {
                // 직접 접근이거나 다른 페이지에서 온 경우 목록 페이지로 이동
                window.location.href = boardListPath;
            }
        },

        // 삭제 후 강제 새로고침으로 목록 이동 (캐시 문제 해결)
        goToListWithRefresh() {
            const boardListPath = `/v2/board/${this.boardId}`;

            // URL 파라미터 보존 (검색/필터 상태 유지)
            const urlParams = new URLSearchParams(window.location.search);
            const queryString = urlParams.toString();
            const finalUrl = queryString ? `${boardListPath}?${queryString}` : boardListPath;

            // 강제 새로고침으로 이동 (캐시 무시)
            window.location.href = finalUrl;
        },

        // 권한 체크
        canEdit() {
            // 로그인이 안 되어 있으면 권한 없음
            if (!window.user) {
                // console.log('canEdit: false - 로그인 안됨');
                return false;
            }

            // 관리자는 모든 게시글 수정 가능
            if (window.boardConfig?.permissions?.admin) {
                // console.log('canEdit: true - 관리자 권한');
                return true;
            }

            // 글쓰기 권한이 있고, 본인의 게시글인 경우만 수정 가능
            const hasWritePermission = window.boardConfig?.permissions?.write;
            const isOwner = this.article?.user_id === window.user?.id;
            const canEdit = hasWritePermission && isOwner;

            // console.log('canEdit:', {
            //     hasWritePermission,
            //     isOwner,
            //     articleUserId: this.article?.user_id,
            //     currentUserId: window.user?.id,
            //     result: canEdit
            // });

            return canEdit;
        },

        canDelete() {
            // 로그인이 안 되어 있으면 권한 없음
            if (!window.user) {
                // console.log('canDelete: false - 로그인 안됨');
                return false;
            }

            // 관리자는 모든 게시글 삭제 가능
            if (window.boardConfig?.permissions?.admin) {
                // console.log('canDelete: true - 관리자 권한');
                return true;
            }

            // 글쓰기 권한이 있고, 본인의 게시글인 경우만 삭제 가능
            const hasWritePermission = window.boardConfig?.permissions?.write;
            const isOwner = this.article?.user_id === window.user?.id;
            const canDelete = hasWritePermission && isOwner;

            // console.log('canDelete:', {
            //     hasWritePermission,
            //     isOwner,
            //     articleUserId: this.article?.user_id,
            //     currentUserId: window.user?.id,
            //     result: canDelete
            // });

            return canDelete;
        },

        // 유틸리티 메소드들
        formatDate(dateString) {
            return this.$store.board.formatDate(dateString);
        },

        formatFileSize(bytes) {
            return this.$store.board.formatFileSize(bytes);
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

        showSuccess(message) {
            this.$store.board.showSuccess(message);
        },

        showError(message) {
            this.$store.board.showError(message);
        }
    };
}
