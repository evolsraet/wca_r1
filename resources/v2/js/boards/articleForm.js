// 게시글 작성/수정 폼 컴포넌트
export default () => ({
    // 기본 상태
    boardId: null,
    articleId: null,
    loading: false,
    submitting: false,
    isEdit: false,
    errors: {},

    // 폼 데이터
    form: {
        article: {
            title: '',
            content: '',
            category: '',
            is_secret: false
        }
    },

    // 기존 첨부파일 (수정시)
    existingAttachments: [],

    // 초기화
    async init(boardId, articleId = null) {
        this.boardId = boardId;
        this.articleId = articleId;
        this.isEdit = !!articleId;

        // board 스토어 초기화
        this.$store.board.init(boardId);

        // 수정 모드일 때 기존 데이터 로드
        if (this.isEdit) {
            await this.loadArticle();
        }
    },

    // 기존 게시글 로드 (수정모드)
    async loadArticle() {
        if (!this.articleId) return;

        this.loading = true;

        try {
            const response = await this.$store.api.get(`/api/board/${this.boardId}/articles/${this.articleId}`, {
                with: 'attachments'
            });

            if (response.data.status === 'ok') {
                const article = response.data.data;
                this.form.article = {
                    title: article.title || '',
                    content: article.content || '',
                    category: article.category || '',
                    is_secret: !!article.is_secret
                };

                this.existingAttachments = article.attachments || [];
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

    // 폼 제출
    async submit() {
        this.errors = {};
        this.submitting = true;

        try {
            const formData = new FormData();

            // 게시글 데이터
            Object.keys(this.form.article).forEach(key => {
                if (this.form.article[key] !== null && this.form.article[key] !== undefined) {
                    formData.append(`article[${key}]`, this.form.article[key]);
                }
            });

            // 첨부파일
            const fileInput = document.querySelector('input[type="file"][name="attachments[]"]');
            if (fileInput?.files) {
                Array.from(fileInput.files).forEach(file => {
                    formData.append('attachments[]', file);
                });
            }

            const url = this.isEdit
                ? `/api/board/${this.boardId}/articles/${this.articleId}`
                : `/api/board/${this.boardId}/articles`;

            const method = this.isEdit ? 'put' : 'post';

            const response = await this.$store.api[method](url, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.data.status === 'ok') {
                this.showSuccess(this.isEdit ? '게시글이 수정되었습니다.' : '게시글이 등록되었습니다.');

                // 상세보기 페이지로 이동
                const articleId = response.data.data?.id || this.articleId;
                setTimeout(() => {
                    window.location.href = `/v2/board/${this.boardId}/view/${articleId}`;
                }, 1000);
            } else {
                if (response.data.errors) {
                    this.errors = response.data.errors;
                } else {
                    this.showError(response.data.message || '저장에 실패했습니다.');
                }
            }
        } catch (error) {
            console.error('폼 제출 실패:', error);

            if (error.response?.data?.errors) {
                this.errors = error.response.data.errors;
            } else {
                this.showError('저장 중 오류가 발생했습니다.');
            }
        } finally {
            this.submitting = false;
        }
    },

    // 임시저장
    async saveDraft() {
        this.showError('임시저장 기능은 아직 구현되지 않았습니다.');
    },

    // 기존 첨부파일 삭제
    async removeAttachment(attachmentId) {
        const confirmed = await this.$store.board.confirm(
            '첨부파일 삭제',
            '이 첨부파일을 삭제하시겠습니까?'
        );

        if (!confirmed) return;

        try {
            const response = await this.$store.api.delete(`/api/board/${this.boardId}/articles/${this.articleId}/attachments/${attachmentId}`);

            if (response.data.status === 'ok') {
                this.existingAttachments = this.existingAttachments.filter(att => att.id !== attachmentId);
                this.showSuccess('첨부파일이 삭제되었습니다.');
            } else {
                this.showError('첨부파일 삭제에 실패했습니다.');
            }
        } catch (error) {
            console.error('첨부파일 삭제 실패:', error);
            this.showError('첨부파일 삭제 중 오류가 발생했습니다.');
        }
    },

    // 유틸리티 메소드들
    showSuccess(message) {
        this.$store.board.showSuccess(message);
    },

    showError(message) {
        this.$store.board.showError(message);
    }
});
