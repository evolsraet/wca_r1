@extends('v2.layouts.app')

@section('content')
<script>
    window.boardConfig = {
        boardId: '{{ $board->id }}',
        boardName: '{{ $board->name ?? $board->id }}'
    };
</script>

<div class="board-view board-skin-{{ $board->skin }}"
     x-data="articleView"
     x-init="init('{{ $board->id }}', {{ $articleId }})">

    <!-- 로딩 상태 -->
    <div x-show="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2 mb-0">게시글을 불러오는 중...</p>
    </div>

    <!-- 게시글 내용 -->
    <div x-show="!loading && article" x-cloak>

        <!-- 게시글 헤더 -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h4 class="mb-2 fw-bold">
                            <span x-show="article && article.is_secret" class="text-warning me-2">
                                <i class="mdi mdi-lock"></i>
                            </span>
                            <span x-text="article && article.title"></span>
                        </h4>

                        <div class="row text-muted small">
                            <div class="col-md-6">
                                <span x-show="article && article.category"
                                      class="badge bg-primary bg-opacity-25 text-primary me-2"
                                      x-text="article && article.category"></span>
                                <i class="mdi mdi-account me-1"></i>
                                <span x-text="(article && article.user && article.user.name) || '익명'"></span>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <i class="mdi mdi-calendar me-1"></i>
                                <span x-text="formatDate(article && article.created_at)"></span>
                                <span class="ms-3">
                                    <i class="mdi mdi-eye me-1"></i>
                                    <span x-text="(article && article.hit) || 0"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 액션 버튼 -->
                    <div class="ms-3">
                        <div class="btn-group" role="group">
                            <a href="/v2/board/{{ $board->id }}" class="btn btn-outline-secondary btn-sm">
                                <i class="mdi mdi-format-list-bulleted me-1"></i>
                                <span class="d-none d-sm-inline">목록</span>
                            </a>

                            <template x-if="canEdit()">
                                <a :href="`/v2/board/{{ $board->id }}/form/${article && article.id}`"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="mdi mdi-pencil me-1"></i>
                                    <span class="d-none d-sm-inline">수정</span>
                                </a>
                            </template>

                            <template x-if="canDelete()">
                                <button @click="deleteArticle()"
                                        class="btn btn-outline-danger btn-sm">
                                    <i class="mdi mdi-delete me-1"></i>
                                    <span class="d-none d-sm-inline">삭제</span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 게시글 본문 -->
            <div class="card-body">
                <div class="article-content" x-html="(article && article.content) || ''"></div>
            </div>

            <!-- 첨부파일 -->
            <div x-show="article && article.attachments && article.attachments.length > 0" class="card-footer bg-light">
                <h6 class="mb-2">
                    <i class="mdi mdi-paperclip me-1"></i>
                    첨부파일
                </h6>
                <div class="list-group list-group-flush">
                    <template x-for="attachment in (article && article.attachments)" :key="attachment.id">
                        <button @click="downloadFile(attachment)"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="mdi mdi-file me-2 text-muted"></i>
                                <span x-text="attachment.original_name"></span>
                            </div>
                            <div class="text-muted small">
                                <span x-text="formatFileSize(attachment.file_size)"></span>
                                <i class="mdi mdi-download ms-2"></i>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- 댓글 영역 -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="mdi mdi-comment-multiple me-2"></i>
                    댓글 <span x-text="comments.length" class="badge bg-secondary"></span>
                </h5>
                <div x-show="commentsLoading">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <!-- 댓글 목록 -->
            <div class="card-body">
                <!-- 댓글 없음 -->
                <div x-show="!commentsLoading && comments.length === 0" class="text-center text-muted py-4">
                    <i class="mdi mdi-comment-outline" style="font-size: 2rem;"></i>
                    <p class="mb-0 mt-2">첫 번째 댓글을 작성해보세요!</p>
                </div>

                <!-- 댓글 리스트 -->
                <template x-for="comment in comments" :key="comment.id">
                    <div class="border-bottom pb-3 mb-3">
                        <!-- 댓글 헤더 -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center">
                                <strong x-text="(comment.user && comment.user.name) || '익명'" class="me-2"></strong>
                                <small class="text-muted" x-text="formatDate(comment.created_at)"></small>
                                <span x-show="comment.parent_id" class="badge bg-info ms-2">답글</span>
                            </div>

                            <!-- 댓글 액션 -->
                            <div class="btn-group btn-group-sm" x-show="comment.user_id === ($store.auth && $store.auth.user && $store.auth.user.id)">
                                <button @click="startEditComment(comment)"
                                        x-show="editingComment !== comment.id"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <button @click="deleteComment(comment.id)"
                                        class="btn btn-outline-danger btn-sm">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>

                        <!-- 댓글 내용 (일반 모드) -->
                        <div x-show="editingComment !== comment.id">
                            <p class="mb-2" x-text="comment.content"></p>

                            <div x-show="canComment() && !comment.parent_id">
                                <button @click="replyToComment(comment.id)"
                                        class="btn btn-link btn-sm p-0 text-decoration-none">
                                    <i class="mdi mdi-reply me-1"></i>답글
                                </button>
                            </div>
                        </div>

                        <!-- 댓글 수정 모드 -->
                        <div x-show="editingComment === comment.id">
                            <div class="mb-2">
                                <x-forms.textarea
                                    name="edit_content"
                                    model="editForm.content"
                                    rows="3"
                                    no-margin
                                />
                            </div>
                            <div class="d-flex gap-2">
                                <button @click="updateComment(comment.id)"
                                        class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-content-save me-1"></i>저장
                                </button>
                                <button @click="cancelEditComment()"
                                        class="btn btn-secondary btn-sm">
                                    취소
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- 댓글 작성 폼 -->
            <div x-show="canComment()" class="card-footer">
                <form @submit.prevent="submitComment()">
                    <!-- 대댓글 알림 -->
                    <div x-show="commentForm.parent_id" class="alert alert-info d-flex justify-content-between align-items-center">
                        <span>
                            <i class="mdi mdi-reply me-1"></i>
                            답글을 작성하고 있습니다.
                        </span>
                        <button @click="cancelReply()" type="button" class="btn-close"></button>
                    </div>

                    <div class="mb-3">
                        <x-forms.textarea
                            name="comment_content"
                            model="commentForm.content"
                            placeholder="댓글을 입력하세요..."
                            rows="3"
                            :errors="'commentErrors'"
                            x-ref="commentContent"
                            no-margin
                        />
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit"
                                :disabled="commentSubmitting || !commentForm.content.trim()"
                                class="btn btn-primary">
                            <i x-show="!commentSubmitting" class="mdi mdi-comment-plus me-1"></i>
                            <i x-show="commentSubmitting" class="mdi mdi-loading mdi-spin me-1"></i>
                            <span x-show="!commentSubmitting">댓글 등록</span>
                            <span x-show="commentSubmitting">등록 중...</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- 로그인 안내 -->
            <div x-show="!canComment()" class="card-footer text-center text-muted">
                <i class="mdi mdi-account-alert me-1"></i>
                댓글을 작성하려면 로그인이 필요합니다.
                <a href="/login" class="btn btn-outline-primary btn-sm ms-2">로그인</a>
            </div>
        </div>
    </div>

    <!-- 에러 상태 -->
    <div x-show="!loading && !article" class="text-center py-5">
        <div class="text-muted mb-3">
            <i class="mdi mdi-alert-circle-outline" style="font-size: 3rem;"></i>
        </div>
        <h6 class="text-muted mb-2">게시글을 찾을 수 없습니다</h6>
        <p class="text-muted mb-3">삭제되었거나 권한이 없는 게시글입니다.</p>
        <a href="/v2/board/{{ $board->id }}" class="btn btn-primary">
            <i class="mdi mdi-format-list-bulleted me-1"></i>목록으로
        </a>
    </div>
</div>

<style>
.article-content {
    line-height: 1.8;
    word-break: break-word;
}

.article-content img {
    max-width: 100%;
    height: auto;
}

.article-content pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    overflow-x: auto;
}

.list-group-item-action:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

@media (max-width: 767.98px) {
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }

    .btn-group .btn .d-none {
        display: none !important;
    }
}
</style>
@endsection
