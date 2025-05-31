export default function articleView() {
    return {
        // 기본 상태
        boardId: null,
        articleId: null,
        loading: false,
        article: null,

        // 초기화
        async init(boardId, articleId) {
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

            // board 스토어 초기화
            this.$store.board.init(this.boardId);

            await this.loadArticle();
        },

        // 게시글 로드
        async loadArticle() {
            this.loading = true;

            try {
                const response = await this.$store.api.get(`/api/board/${this.boardId}/articles/${this.articleId}`, {
                    with: 'user,attachments'
                });

                if (response.data.status === 'ok') {
                    this.article = response.data.data;
                } else {
                    this.showError('게시글을 불러올 수 없습니다.');
                }
            } catch (error) {
                console.error('게시글 로드 실패:', error);
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
                    // 목록으로 이동
                    setTimeout(() => {
                        window.location.href = `/v2/board/${this.boardId}`;
                    }, 1000);
                } else {
                    this.showError(response.data.message || '게시글 삭제에 실패했습니다.');
                }
            } catch (error) {
                console.error('게시글 삭제 실패:', error);
                this.showError('게시글 삭제에 실패했습니다.');
            }
        },

        // 첨부파일 다운로드
        downloadFile(attachment) {
            window.open(`/api/board/${this.boardId}/articles/${this.articleId}/attachments/${attachment.id}/download`, '_blank');
        },

        // 권한 체크
        canEdit() {
            return this.$store.board.hasPermission('write') &&
                   (this.article?.user_id === window.user?.id || this.$store.board.hasPermission('admin'));
        },

        canDelete() {
            return this.$store.board.hasPermission('write') &&
                   (this.article?.user_id === window.user?.id || this.$store.board.hasPermission('admin'));
        },

        // 유틸리티 메소드들
        formatDate(dateString) {
            return this.$store.board.formatDate(dateString);
        },

        formatFileSize(bytes) {
            return this.$store.board.formatFileSize(bytes);
        },

        showSuccess(message) {
            this.$store.board.showSuccess(message);
        },

        showError(message) {
            this.$store.board.showError(message);
        }
    };
}
