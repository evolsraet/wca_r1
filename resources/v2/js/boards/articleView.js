export default function articleView() {
    return {
        // 기본 상태
        boardId: '',
        articleId: null,
        article: null,
        comments: [],
        loading: true,
        commentsLoading: false,

        // 댓글 폼
        commentForm: {
            content: '',
            parent_id: null
        },
        commentErrors: {},
        commentSubmitting: false,

        // 댓글 수정
        editingComment: null,
        editForm: {
            content: ''
        },

        // 초기화
        async init(boardId, articleId) {
            this.boardId = boardId || window.boardConfig?.boardId;
            this.articleId = articleId;

            if (!this.boardId || !this.articleId) {
                this.showError('게시판 정보가 올바르지 않습니다.');
                return;
            }

            await this.loadArticle();
            await this.loadComments();
        },

        // 게시글 조회
        async loadArticle() {
            try {
                this.loading = true;
                const response = await this.$store.api.get(`/api/board/${this.boardId}/articles/${this.articleId}`, {
                    with: 'user,attachments'
                });

                if (response.data.status === 'ok') {
                    this.article = response.data.data;

                    // 조회수 증가 (별도 요청)
                    this.increaseViewCount();
                } else {
                    this.showError(response.data.message || '게시글을 불러올 수 없습니다.');
                }
            } catch (error) {
                console.error('게시글 로딩 오류:', error);
                this.showError('게시글을 불러오는 중 오류가 발생했습니다.');
            } finally {
                this.loading = false;
            }
        },

        // 조회수 증가
        async increaseViewCount() {
            try {
                await this.$store.api.post(`/api/board/${this.boardId}/articles/${this.articleId}/hit`);
            } catch (error) {
                // 조회수 증가 실패는 무시 (중요하지 않음)
                console.warn('조회수 증가 실패:', error);
            }
        },

        // 댓글 목록 조회
        async loadComments() {
            try {
                this.commentsLoading = true;
                const response = await this.$store.api.get(`/api/board/${this.boardId}/articles/${this.articleId}/comments`, {
                    with: 'user',
                    order_column: 'created_at',
                    order_direction: 'asc'
                });

                if (response.data.status === 'ok') {
                    this.comments = response.data.data;
                }
            } catch (error) {
                console.error('댓글 로딩 오류:', error);
            } finally {
                this.commentsLoading = false;
            }
        },

        // 댓글 작성
        async submitComment() {
            if (!this.commentForm.content.trim()) {
                this.commentErrors = { content: ['댓글 내용을 입력하세요.'] };
                return;
            }

            try {
                this.commentSubmitting = true;
                this.commentErrors = {};

                const response = await this.$store.api.post(`/api/board/${this.boardId}/articles/${this.articleId}/comments`, {
                    comment: this.commentForm
                });

                if (response.data.status === 'ok') {
                    this.showSuccess('댓글이 등록되었습니다.');
                    this.commentForm.content = '';
                    this.commentForm.parent_id = null;
                    await this.loadComments();
                } else {
                    if (response.data.errors) {
                        this.commentErrors = response.data.errors;
                    } else {
                        this.showError(response.data.message || '댓글 등록에 실패했습니다.');
                    }
                }
            } catch (error) {
                console.error('댓글 등록 오류:', error);
                this.showError('댓글 등록 중 오류가 발생했습니다.');
            } finally {
                this.commentSubmitting = false;
            }
        },

        // 댓글 수정 시작
        startEditComment(comment) {
            this.editingComment = comment.id;
            this.editForm.content = comment.content;
        },

        // 댓글 수정 취소
        cancelEditComment() {
            this.editingComment = null;
            this.editForm.content = '';
        },

        // 댓글 수정 저장
        async updateComment(commentId) {
            if (!this.editForm.content.trim()) {
                this.showError('댓글 내용을 입력하세요.');
                return;
            }

            try {
                const response = await this.$store.api.put(`/api/board/${this.boardId}/articles/${this.articleId}/comments/${commentId}`, {
                    comment: this.editForm
                });

                if (response.data.status === 'ok') {
                    this.showSuccess('댓글이 수정되었습니다.');
                    this.editingComment = null;
                    this.editForm.content = '';
                    await this.loadComments();
                } else {
                    this.showError(response.data.message || '댓글 수정에 실패했습니다.');
                }
            } catch (error) {
                console.error('댓글 수정 오류:', error);
                this.showError('댓글 수정 중 오류가 발생했습니다.');
            }
        },

        // 댓글 삭제
        async deleteComment(commentId) {
            if (!confirm('댓글을 삭제하시겠습니까?')) {
                return;
            }

            try {
                const response = await this.$store.api.delete(`/api/board/${this.boardId}/articles/${this.articleId}/comments/${commentId}`);

                if (response.data.status === 'ok') {
                    this.showSuccess('댓글이 삭제되었습니다.');
                    await this.loadComments();
                } else {
                    this.showError(response.data.message || '댓글 삭제에 실패했습니다.');
                }
            } catch (error) {
                console.error('댓글 삭제 오류:', error);
                this.showError('댓글 삭제 중 오류가 발생했습니다.');
            }
        },

        // 대댓글 작성
        replyToComment(commentId) {
            this.commentForm.parent_id = commentId;
            this.$refs.commentContent?.focus();
        },

        // 대댓글 취소
        cancelReply() {
            this.commentForm.parent_id = null;
        },

        // 게시글 삭제
        async deleteArticle() {
            if (!confirm('게시글을 삭제하시겠습니까?')) {
                return;
            }

            try {
                const response = await this.$store.api.delete(`/api/board/${this.boardId}/articles/${this.articleId}`);

                if (response.data.status === 'ok') {
                    this.showSuccess('게시글이 삭제되었습니다.');
                    window.location.href = `/v2/board/${this.boardId}`;
                } else {
                    this.showError(response.data.message || '게시글 삭제에 실패했습니다.');
                }
            } catch (error) {
                console.error('게시글 삭제 오류:', error);
                this.showError('게시글 삭제 중 오류가 발생했습니다.');
            }
        },

        // 파일 다운로드
        downloadFile(attachment) {
            window.open(`/api/board/${this.boardId}/articles/${this.articleId}/attachments/${attachment.id}/download`, '_blank');
        },

        // 권한 체크
        canEdit() {
            return this.article?.user_id === this.$store.auth?.user?.id || this.$store.auth?.user?.hasPermissionTo?.(this.$store.board?.write_permission);
        },

        canDelete() {
            return this.article?.user_id === this.$store.auth?.user?.id || this.$store.auth?.user?.hasPermissionTo?.(this.$store.board?.write_permission);
        },

        canComment() {
            return this.$store.auth?.user?.hasPermissionTo?.(this.$store.board?.comment_permission || 'act.login');
        },

        // 유틸리티 메소드
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
    }
}
