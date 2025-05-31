export default () => ({
    // 기본 설정
    commentableType: '',
    commentableId: '',
    options: {
        allowReply: false,  // 테이블에 parent_id가 없으므로 답글 비활성화
        maxDepth: 1,        // 댓글만 지원
        pageSize: 10
    },

    // 데이터 상태
    comments: [],
    total: 0,
    current_page: 1,
    last_page: 1,
    hasNextPage: false,
    loading: false,

    // 정렬 및 필터
    sortBy: 'latest',

    // 폼 데이터
    form: {
        content: ''
    },
    errors: {},

    // 수정 관련
    editingCommentId: null,
    editForm: {
        content: ''
    },

    // 삭제 관련
    deletingCommentId: null,

    /**
     * 컴포넌트 초기화
     */
    setup(commentableType, commentableId, boardId = null, options = {}) {
        // commentableType이나 commentableId가 undefined인 경우 초기화 중단
        console.log('코멘트 초기화 : ', { commentableType, commentableId, options });
        if (!commentableType || !commentableId) {
            // console.warn('Invalid commentableType or commentableId, initialization skipped.');
            return;
        }

        this.commentableType = commentableType;
        this.commentableId = commentableId;
        this.options = { ...this.options, ...options };

        // 정렬 변경 감지
        this.$watch('sortBy', () => {
            this.loadComments(true);
        });

        // 댓글 로드
        this.loadComments();
    },

    /**
     * 댓글 목록 로드
     */
    async loadComments(reset = false) {
        if (this.loading) return;

        this.loading = true;

        try {
            if (reset) {
                this.current_page = 1;
                this.comments = [];
            }

            const orderDirection = this.sortBy === 'latest' ? 'desc' : 'asc';

            const response = await this.$store.api.get(`/api/comments`, {
                where: "comments.commentable_type:" + this.commentableType + "|comments.commentable_id:" + this.commentableId,
                order_column: 'created_at',
                order_direction: orderDirection,
                // paginate: 2,
                page: this.current_page
            });

            const data = response.data;

            const commentsData = data.data || data;

            if (reset) {
                this.comments = commentsData;
            } else {
                this.comments.push(...commentsData);
            }

            this.total = data.meta.total || this.comments.length;
            this.current_page = data.meta.current_page;
            this.last_page = data.meta.last_page;
            this.hasNextPage = data.meta.current_page < data.meta.last_page;

        } catch (error) {
            console.error('댓글 로드 실패:', error);
            this.$store.toastr.error('댓글을 불러오는데 실패했습니다.');
        } finally {
            this.loading = false;
        }
    },

    /**
     * 더 많은 댓글 로드
     */
    async loadMore() {
        this.current_page++;
        await this.loadComments();
    },

    /**
     * 댓글 작성
     */
    async submitComment() {
        if (this.loading || !this.form.content.trim()) return;

        this.loading = true;
        this.errors = {};

        try {
            const form = {
                comment: {
                    commentable_type: this.commentableType,
                    commentable_id: this.commentableId,
                    content: this.form.content.trim()
                }
            }
            const response = await this.$store.api.post(`/api/comments`, form);

            // 새 댓글을 목록에 추가
            const newComment = response.data.data;
            // console.log('New comment added:', newComment);

            if (this.sortBy === 'latest') {
                this.comments.unshift(newComment);
            } else {
                this.comments.push(newComment);
            }

            this.total++;
            this.form.content = '';

            this.$store.toastr.success('댓글이 등록되었습니다.');

        } catch (error) {
            console.error('댓글 작성 실패:', error);

            if (error.response?.status === 422) {
                this.errors = error.response.data.errors || {};
            } else {
                this.$store.toastr.error('댓글 작성에 실패했습니다.');
            }
        } finally {
            this.loading = false;
        }
    },

    /**
     * 댓글 수정 시작
     */
    editComment(comment) {
        this.editingCommentId = comment.id;
        this.editForm.content = comment.content;
        this.errors = {};
    },

    /**
     * 댓글 수정 저장
     */
    async updateComment(commentId) {
        if (this.loading || !this.editForm.content.trim()) return;

        this.loading = true;
        this.errors = {};

        try {
            const response = await this.$store.api.put(`/api/comments/${commentId}`, {
                comment: {
                    content: this.editForm.content.trim()
                },
            });

            // 댓글 목록에서 해당 댓글 업데이트
            const updatedComment = response.data.data;

            const index = this.comments.findIndex(c => c.id === commentId);
            if (index !== -1) {
                this.comments[index] = updatedComment;
            }

            this.cancelEdit();
            this.$store.toastr.success(response.data.message);

        } catch (error) {
            console.error('댓글 수정 실패:', error);

            if (error.response?.status === 422) {
                this.errors = error.response.data.errors || {};
            } else {
                this.$store.toastr.error(response.data.message);
            }
        } finally {
            this.loading = false;
        }
    },

    /**
     * 댓글 수정 취소
     */
    cancelEdit() {
        this.editingCommentId = null;
        this.editForm.content = '';
        this.errors = {};
    },

    /**
     * 댓글 삭제
     */
    async deleteComment(commentId) {
        const result = await this.$store.swal.fire({
            title: '댓글 삭제',
            text: '정말로 이 댓글을 삭제하시겠습니까?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        });

        if (!result.isConfirmed) return;

        this.deletingCommentId = commentId;
        this.loading = true;

        try {
            await this.$store.api.delete(`/api/comments/${commentId}`);

            // 댓글 목록에서 제거
            this.comments = this.comments.filter(c => c.id !== commentId);
            this.total--;

            // 테스트로 1초 기다리기
            await new Promise(resolve => setTimeout(resolve, 1000));

            this.$store.toastr.success('댓글이 삭제되었습니다.');

        } catch (error) {
            this.$store.toastr.error('댓글 삭제에 실패했습니다.');
        } finally {
            this.loading = false;
            this.deletingCommentId = null;
        }
    },

    /**
     * 날짜 포맷팅
     */
    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

        if (diffDays === 0) {
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
            if (diffHours === 0) {
                const diffMinutes = Math.floor(diffMs / (1000 * 60));
                return diffMinutes < 1 ? '방금 전' : `${diffMinutes}분 전`;
            }
            return `${diffHours}시간 전`;
        } else if (diffDays < 7) {
            return `${diffDays}일 전`;
        } else {
            return date.toLocaleDateString('ko-KR', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    },

    /**
     * 댓글 내용 포맷팅 (줄바꿈 처리)
     */
    formatContent(content) {
        if (!content) return '';

        return content
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\n/g, '<br>');
    }
});
