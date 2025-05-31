@extends('v2.layouts.app')

@section('content')
<script>
    window.boardConfig = {
        boardId: '{{ $board->id }}',
        boardName: '{{ $board->name ?? $board->id }}',
        articleId: '{{ $articleId }}',
        writePermission: '{{ $board->write_permission }}',
        permissions: {
            write: {{ auth()->user()?->hasPermissionTo($board->write_permission ?? 'act.login') ? 'true' : 'false' }},
            admin: {{ auth()->user()?->hasPermissionTo('act.admin') ? 'true' : 'false' }}
        }
    };
    window.user = {!! auth()->user() ? auth()->user()->toJson() : 'null' !!};

</script>

<div class="board-view board-skin-{{ $board->skin }}"
     x-data="articleView"
     x-init="setup('{{ $board->id }}', '{{ $articleId }}')">

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
                            <button @click="goToList()" class="btn btn-outline-secondary btn-sm">
                                <i class="mdi mdi-format-list-bulleted me-1"></i>
                                <span class="d-none d-sm-inline">목록</span>
                            </button>

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
            <div x-show="article?.board_attach?.length > 0" class="card-footer bg-light">
                <x-forms.fileList
                    label="첨부파일"
                    data-path="article.board_attach"
                    show-download="true"
                    download-action="downloadFile"
                />
            </div>
        </div>

        <!-- 댓글 섹션 -->
        <div class="mt-4">
            <x-comments
                commentable-type="Article"
                commentable-id="{{ $articleId }}"
                title="댓글" />
        </div>
    </div>

    <!-- 에러 상태 -->
    <div x-show="!loading && !article" class="text-center py-5">
        <div class="text-muted mb-3">
            <i class="mdi mdi-alert-circle-outline" style="font-size: 3rem;"></i>
        </div>
        <h6 class="text-muted mb-2">게시글을 찾을 수 없습니다</h6>
        <p class="text-muted mb-3">삭제되었거나 권한이 없는 게시글입니다.</p>
        <button @click="goToList()" class="btn btn-primary">
            <i class="mdi mdi-format-list-bulleted me-1"></i>목록으로
        </button>
    </div>
</div>

<style>
.article-content {
    line-height: 1.8;
    word-break: break-word;
    white-space: pre-line;
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
