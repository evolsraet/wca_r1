export default () => ({
    // 기본 설정
    commentableType: '',
    commentableId: '',
    boardId: null,
    options: {
        allowReply: false,  // 테이블에 parent_id가 없으므로 답글 비활성화
        maxDepth: 1,        // 댓글만 지원
        pageSize: 10
    },

    // 데이터 상태
    comments: [],
    totalCount: 0,
    currentPage: 1,
    hasNextPage: false,
    loading: false,

    // 정렬 및 필터
    sortBy: 'latest',

    // 권한
    canWrite: false,
    canRead: true,

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
    editErrors: {},

    /**
     * 컴포넌트 초기화
     */
    init(targetType, targetId, boardId = null, options = {}) {
        console.log('Initializing comments component...', { targetType, targetId, boardId, options });

        this.commentableType = targetType;
        this.commentableId = targetId;
        this.boardId = boardId;
        this.options = { ...this.options, ...options };

        // 정렬 변경 감지
        this.$watch('sortBy', () => {
            this.loadComments(true);
        });

        // 권한 체크 및 댓글 로드
        this.checkPermissions();
        this.loadComments();
    },

    /**
     * 권한 체크
     */
    async checkPermissions() {
        try {
            // Laravel의 polymorphic 구조에 맞는 API 호출
            const response = await this.$store.api.get(`/api/v2/${this.commentableType}/${this.commentableId}/comments/permissions`, {
                params: {
                    board_id: this.boardId
                }
            });

            this.canWrite = response.data.can_write || false;
            this.canRead = response.data.can_read || true;
        } catch (error) {
            console.error('권한 체크 실패:', error);
            // 기본값으로 설정
            this.canWrite = false;
            this.canRead = true;
        }
    },

    /**
     * 댓글 목록 로드
     */
    async loadComments(reset = false) {
        if (this.loading) return;

        this.loading = true;

        try {
            if (reset) {
                this.currentPage = 1;
                this.comments = [];
            }

            // Laravel의 polymorphic 구조에 맞는 API 호출
            const response = await this.$store.api.get(`/api/v2/${this.commentableType}/${this.commentableId}/comments`, {
                params: {
                    page: this.currentPage,
                    per_page: this.options.pageSize,
                    sort: this.sortBy,
                    board_id: this.boardId
                }
            });

            const data = response.data;

            if (reset) {
                this.comments = data.data || data;
            } else {
                this.comments.push(...(data.data || data));
            }

            this.totalCount = data.total || this.comments.length;
            this.hasNextPage = data.current_page < data.last_page;

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
        this.currentPage++;
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
            const response = await this.$store.api.post(`/api/v2/${this.commentableType}/${this.commentableId}/comments`, {
                content: this.form.content.trim(),
                board_id: this.boardId
            });

            // 새 댓글을 목록에 추가
            if (this.sortBy === 'latest') {
                this.comments.unshift(response.data);
            } else {
                this.comments.push(response.data);
            }

            this.totalCount++;
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
        this.editErrors = {};
    },

    /**
     * 댓글 수정 저장
     */
    async updateComment(commentId) {
        if (this.loading || !this.editForm.content.trim()) return;

        this.loading = true;
        this.editErrors = {};

        try {
            const response = await this.$store.api.put(`/api/v2/comments/${commentId}`, {
                content: this.editForm.content.trim()
            });

            // 댓글 목록에서 해당 댓글 업데이트
            const index = this.comments.findIndex(c => c.id === commentId);
            if (index !== -1) {
                this.comments[index] = response.data;
            }

            this.cancelEdit();
            this.$store.toastr.success('댓글이 수정되었습니다.');

        } catch (error) {
            console.error('댓글 수정 실패:', error);

            if (error.response?.status === 422) {
                this.editErrors = error.response.data.errors || {};
            } else {
                this.$store.toastr.error('댓글 수정에 실패했습니다.');
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
        this.editErrors = {};
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

        this.loading = true;

        try {
            await this.$store.api.delete(`/api/v2/comments/${commentId}`);

            // 댓글 목록에서 제거
            this.comments = this.comments.filter(c => c.id !== commentId);
            this.totalCount--;

            this.$store.toastr.success('댓글이 삭제되었습니다.');

        } catch (error) {
            console.error('댓글 삭제 실패:', error);
            this.$store.toastr.error('댓글 삭제에 실패했습니다.');
        } finally {
            this.loading = false;
        }
    },

    /**
     * 댓글 좋아요 토글 (별도 구현 필요)
     */
    async toggleLike(commentId) {
        try {
            const response = await this.$store.api.post(`/api/v2/comments/${commentId}/like`);

            // 댓글 목록에서 해당 댓글의 좋아요 정보 업데이트
            const index = this.comments.findIndex(c => c.id === commentId);
            if (index !== -1) {
                this.comments[index].is_liked = response.data.is_liked;
                this.comments[index].likes_count = response.data.likes_count || 0;
            }

        } catch (error) {
            console.error('좋아요 처리 실패:', error);
            // 좋아요 기능이 구현되지 않았을 수 있으므로 에러 메시지는 표시하지 않음
            console.log('좋아요 기능이 구현되지 않았습니다.');
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
