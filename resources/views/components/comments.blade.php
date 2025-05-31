@props([
    'commentableType' => 'Article',  // 댓글 대상 타입 (Article, Product, Event 등)
    'commentableId' => '',           // 댓글 대상 ID
    'title' => '댓글',                // 섹션 제목
    'showTitle' => true,             // 제목 표시 여부
    'allowReply' => false,           // 대댓글 허용 안함 (테이블 구조상)
    'maxDepth' => 1,                 // 댓글만 지원
    'pageSize' => 10,                // 페이지당 댓글 수
    'class' => ''                    // 추가 CSS 클래스
])

@php
    $is_admin = (auth()->check() && auth()->user()->hasPermissionTo('act.admin')) ? 1 : 0;
@endphp

<script>
    window.user = {!! auth()->user() ? auth()->user()->toJson() : 'null' !!};
</script>

<style>
    .comment-item.loading, .comment-form-container.loading {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

<div class="comments-container {{ $class }}"
     x-data="comments()"
     x-init="setup('{{ $commentableType }}', '{{ $commentableId }}', null, {
         allowReply: false,
         maxDepth: 1,
         pageSize: {{ $pageSize }}
     })">

    <!-- 댓글 섹션 헤더 -->
    @if($showTitle)
    <div class="comments-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">
                    <i class="mdi mdi-comment-text-outline me-2"></i>
                    {{ $title }}
                    <span class="badge bg-secondary ms-2" x-text="total"></span>
                </h5>
            </div>
            <div class="col-auto">
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="commentSort" id="sort-latest" value="latest" x-model="sortBy">
                    <label class="btn btn-outline-primary" for="sort-latest">최신순</label>

                    <input type="radio" class="btn-check" name="commentSort" id="sort-oldest" value="oldest" x-model="sortBy">
                    <label class="btn btn-outline-primary" for="sort-oldest">등록순</label>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 댓글 작성 폼 -->
    <div class="comment-form-container mb-4"
        :class="{ 'loading': loading }"
        x-show="1"
        >
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submitComment()">
                    <div class="mb-3">
                        <label class="form-label">댓글 작성</label>
                        <textarea
                            class="form-control"
                            rows="4"
                            placeholder="댓글을 입력해주세요..."
                            x-model="form.content"
                            :class="{ 'is-invalid': errors?.content?.length > 0 }"
                            required></textarea>
                        <div class="invalid-feedback" x-text="errors?.content?.[0]"></div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col">
                            <small class="text-muted">
                                <i class="mdi mdi-information-outline me-1"></i>
                                욕설, 비방, 스팸성 댓글은 삭제될 수 있습니다.
                            </small>
                        </div>
                        <div class="col-auto">
                            <button type="submit"
                                    class="btn btn-primary"
                                    :disabled="loading || !form.content.trim()"
                                    x-text="loading ? '등록 중...' : '댓글 등록'">
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- 댓글 없음 -->
    <div class="text-center py-5" x-show="!loading && !comments.length">
        <div class="text-muted">
            <i class="mdi mdi-comment-outline display-4 d-block mb-3 opacity-50"></i>
            <p class="mb-0">아직 댓글이 없습니다.</p>
            <small>첫 번째 댓글을 작성해보세요!</small>
        </div>
    </div>

    <!-- 댓글 목록 -->
    <div class="comments-list" x-show="comments.length > 0">
        <template x-for="comment in comments" :key="comment.id">
            <div class="comment-item" :class="{ 'loading': loading && (editingCommentId === comment.id || deletingCommentId === comment.id) }">

                <!-- 댓글 내용 -->
                <div class="card mb-3">
                    <div class="card-body">
                        <!-- 댓글 헤더 -->
                        <div class="comment-header mb-2">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="d-flex align-items-center">
                                        <!-- 작성자 정보 -->
                                        <div class="user-info">
                                            <span class="fw-bold" x-text="comment.user?.name || '익명'"></span>
                                            <small class="text-muted ms-2" x-text="formatDate(comment.created_at)"></small>
                                        </div>

                                        <!-- 작성자 배지 -->
                                        <template x-if="comment.is_author">
                                            <span class="badge bg-primary ms-2">작성자</span>
                                        </template>
                                        <template x-if="comment.is_admin">
                                            <span class="badge bg-warning ms-2">관리자</span>
                                        </template>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <!-- 댓글 액션 버튼 -->
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-link btn-sm text-muted"
                                                x-show="comment.user_id==window.user.id||{{ $is_admin }}"
                                                @click.prevent="editComment(comment)">
                                            <i class="mdi mdi-pencil"></i> 수정
                                        </button>
                                        <button class="btn btn-link btn-sm text-danger"
                                                x-show="comment.user_id==window.user.id||{{ $is_admin }}"
                                                @click.prevent="deleteComment(comment.id)">
                                            <i class="mdi mdi-delete"></i> 삭제
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 댓글 본문 -->
                        <div class="comment-content" x-show="editingCommentId !== comment.id">
                            <p class="mb-2" x-html="formatContent(comment.content)"></p>
                        </div>

                        <!-- 댓글 수정 폼 -->
                        <div class="comment-edit-form" x-show="editingCommentId === comment.id">
                            <form @submit.prevent="updateComment(comment.id)">
                                <div class="mb-3">
                                    <textarea
                                        class="form-control"
                                        rows="3"
                                        x-model="editForm.content"
                                        :class="{ 'is-invalid': errors?.content?.length > 0 }"></textarea>
                                    <div class="invalid-feedback" x-text="errors?.content?.[0]"></div>
                                </div>
                                <div class="text-end">
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm me-2"
                                            @click="cancelEdit()">취소</button>
                                    <button type="submit"
                                            class="btn btn-primary btn-sm"
                                            :disabled="loading"
                                            x-text="loading ? '저장 중...' : '저장'"></button>
                                </div>
                            </form>
                        </div>

                        <!-- 댓글 액션 버튼 -->
                        <div class="comment-actions mt-2" x-show="editingCommentId !== comment.id">
                            <div class="d-flex align-items-center gap-3">
                                <!-- 좋아요 버튼 (선택사항 - 백엔드 구현 필요) -->
                                <button class="btn btn-link btn-sm text-muted p-0"
                                        @click="toggleLike(comment.id)"
                                        :class="{ 'text-primary': comment.is_liked }"
                                        x-show="comment.likes_count !== undefined">
                                    <i class="mdi" :class="comment.is_liked ? 'mdi-heart' : 'mdi-heart-outline'"></i>
                                    <span x-text="comment.likes_count || 0"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- 더보기 버튼 -->
    <div class="text-center mt-4" x-show="hasNextPage && !loading">
        <button class="btn btn-outline-primary" @click="loadMore()">
            <i class="mdi mdi-chevron-down me-1"></i>
            댓글 더보기
        </button>
    </div>

    <!-- 페이지네이션 로딩 -->
    <div class="text-center mt-4" x-show="loading && comments.length > 0">
        <div class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">더 불러오는 중...</span>
        </div>
    </div>
</div>
