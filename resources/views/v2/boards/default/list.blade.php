@extends('v2.layouts.app')

@section('content')
<script>
    window.boardConfig = {
        boardId: '{{ $board->id }}',
        boardName: '{{ $board->name ?? $board->id }}'
    };
</script>

<div class="board-list board-skin-{{ $board->skin }}"
     x-data="articleList">

    <!-- 게시판 헤더 -->
    <div class="board-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="board-title mb-0">{{ $board->name ?? $board->id }}</h2>
            @if(auth()->user()?->hasPermissionTo($board->write_permission ?? 'act.login'))
                <a href="{{ route('board.form', $board->id) }}" class="btn btn-primary">
                    <i class="mdi mdi-pencil me-1"></i>글쓰기
                </a>
            @endif
        </div>
    </div>

    <!-- 검색 및 필터 영역 -->
    <div class="board-search mb-3">
        <div class="row">
            <div class="col-md-6">
                @if(!empty($board->categories))
                    <select x-model="filters.category" @change="onCategoryChange()" class="form-select">
                        <option value="">전체 카테고리</option>
                        @foreach(json_decode($board->categories) as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" x-model="filters.search_text" @keyup.enter="search()"
                           class="form-control" placeholder="검색어를 입력하세요">
                    <button @click="search()" class="btn btn-outline-secondary" type="button">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 로딩 상태 -->
    <div x-show="loading" class="text-center py-5">
        <i class="mdi mdi-loading mdi-spin fs-2"></i>
        <p class="mt-2">로딩 중...</p>
    </div>

    <!-- 게시글 목록 -->
    <div x-show="!loading" class="board-articles">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="60">번호</th>
                        @if(!empty($board->categories))
                            <th width="100">카테고리</th>
                        @endif
                        <th>제목</th>
                        <th width="100">작성자</th>
                        <th width="120">작성일</th>
                        <th width="60">조회</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="article in articles" :key="article.id">
                        <tr class="board-article-row">
                            <td x-text="article.id"></td>
                            @if(!empty($board->categories))
                                <td>
                                    <span x-show="article.category"
                                          x-text="article.category"
                                          class="badge bg-secondary"></span>
                                </td>
                            @endif
                            <td>
                                <a :href="`/v2/board/{{ $board->id }}/view/${article.id}`"
                                   class="text-decoration-none">
                                    <span x-show="article.is_secret" class="text-warning me-1">
                                        <i class="mdi mdi-lock"></i>
                                    </span>
                                    <span x-text="article.title"></span>
                                    <span x-show="article.comments_count > 0"
                                          class="text-primary ms-1">
                                        [<span x-text="article.comments_count"></span>]
                                    </span>
                                </a>
                            </td>
                            <td x-text="article.user?.name || '익명'"></td>
                            <td x-text="formatDate(article.created_at)"></td>
                            <td x-text="article.views || 0"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- 빈 목록 메시지 -->
        <div x-show="articles.length === 0" class="text-center py-5">
            <p class="text-muted">등록된 게시글이 없습니다.</p>
        </div>
    </div>

    <!-- 페이지네이션 -->
    <div x-show="!loading && pagination.last_page > 1" class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: !pagination.prev }">
                    <a class="page-link" @click="loadPage(pagination.current_page - 1)" href="#">
                        <i class="mdi mdi-chevron-left"></i>
                    </a>
                </li>

                <template x-for="page in paginationPages" :key="page">
                    <li class="page-item" :class="{ active: page === pagination.current_page }">
                        <a class="page-link" @click="loadPage(page)" href="#" x-text="page"></a>
                    </li>
                </template>

                <li class="page-item" :class="{ disabled: !pagination.next }">
                    <a class="page-link" @click="loadPage(pagination.current_page + 1)" href="#">
                        <i class="mdi mdi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
