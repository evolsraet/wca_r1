@extends('v2.layouts.app')

@section('content')
<div class="board-view board-skin-{{ $board->skin }}"
     x-data="articleView"
     x-init="init('{{ $board->id }}', {{ $articleId }})">

    <!-- 로딩 상태 -->
    <div x-show="loading" class="text-center py-5">
        <i class="mdi mdi-loading mdi-spin fs-2"></i>
        <p class="mt-2">로딩 중...</p>
    </div>

    <!-- 게시글 내용 -->
    <div x-show="!loading && article" class="board-article">
        <!-- 게시글 헤더 -->
        <div class="article-header bg-light p-4 rounded mb-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span x-show="article.category"
                          x-text="article.category"
                          class="badge bg-secondary me-2"></span>
                    <span x-show="article.is_secret" class="text-warning me-2">
                        <i class="mdi mdi-lock"></i>
                    </span>
                    <h2 x-text="article.title" class="mb-0"></h2>
                </div>
            </div>

            <div class="article-meta text-muted small">
                <span x-text="article.user?.name || '익명'" class="me-3"></span>
                <span x-text="formatDate(article.created_at)" class="me-3"></span>
                <span>조회 <span x-text="article.views || 0"></span></span>
            </div>
        </div>

        <!-- 게시글 본문 -->
        <div class="article-content mb-4">
            <div x-html="article.content" class="content-body"></div>
        </div>

        <!-- 첨부파일 -->
        <div x-show="article.attachments && article.attachments.length > 0" class="article-attachments mb-4">
            <h5>첨부파일</h5>
            <div class="list-group">
                <template x-for="attachment in article.attachments" :key="attachment.id">
                    <a :href="attachment.download_url"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="mdi mdi-file me-2"></i>
                            <span x-text="attachment.original_name"></span>
                        </div>
                        <span x-text="formatFileSize(attachment.size)" class="text-muted small"></span>
                    </a>
                </template>
            </div>
        </div>

        <!-- 액션 버튼 -->
        <div class="article-actions mb-4">
            <div class="btn-group">
                <a href="{{ route('board.list', $board->id) }}" class="btn btn-outline-secondary">
                    <i class="mdi mdi-view-list me-1"></i>목록
                </a>

                <template x-if="canEdit()">
                    <a :href="`{{ route('board.form', $board->id) }}/${article.id}`" class="btn btn-outline-primary">
                        <i class="mdi mdi-pencil me-1"></i>수정
                    </a>
                </template>

                <template x-if="canDelete()">
                    <button @click="deleteArticle()" class="btn btn-outline-danger">
                        <i class="mdi mdi-delete me-1"></i>삭제
                    </button>
                </template>
            </div>
        </div>

        <!-- 댓글 영역 -->
        <div class="article-comments">
            <h5>댓글 <span x-text="article.comments_count || 0"></span>개</h5>

            <!-- 댓글 작성 폼 -->
            @if(auth()->user()?->hasPermissionTo($board->comment_permission ?? 'act.login'))
                <div class="comment-form mb-4">
                    <form @submit.prevent="submitComment()">
                        <div class="mb-3">
                            <textarea x-model="commentForm.content"
                                      class="form-control"
                                      rows="3"
                                      placeholder="댓글을 입력하세요"
                                      required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                    :disabled="commentLoading"
                                    class="btn btn-primary">
                                <span x-show="commentLoading">
                                    <i class="mdi mdi-loading mdi-spin me-1"></i>
                                </span>
                                댓글 등록
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- 댓글 목록 -->
            <div class="comments-list">
                <template x-for="comment in comments" :key="comment.id">
                    <div class="comment-item border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong x-text="comment.user?.name || '익명'"></strong>
                                <small x-text="formatDate(comment.created_at)" class="text-muted ms-2"></small>
                            </div>
                            <div x-show="canEditComment(comment)" class="comment-actions">
                                <button @click="deleteComment(comment.id)"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                        <div x-text="comment.content" class="comment-content"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- 에러 메시지 -->
    <div x-show="!loading && !article" class="text-center py-5">
        <p class="text-danger">게시글을 찾을 수 없습니다.</p>
        <a href="{{ route('board.list', $board->id) }}" class="btn btn-outline-secondary">목록으로 돌아가기</a>
    </div>
</div>
@endsection
