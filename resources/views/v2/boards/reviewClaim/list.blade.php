@extends('v2.layouts.app')

@section('content')

@php

    switch($board->id) {
        case 'review':
            $boardTitle = '이용후기';
            break;
        case 'claim':
            $boardTitle = '클레임';
            break;
        default:
            $boardTitle = $board->name ?? $board->id;
            break;
    }

@endphp
<script>
    window.boardConfig = {
        boardId: '{{ $board->id }}',
        boardName: '{{ $board->name ?? $board->id }}'
    };
</script>

<div class="board-list board-skin-{{ $board->skin }} testbox mt-5" x-data="articleList()">

    <!-- 게시판 헤더 -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">{{ $boardTitle }}</h4>
            <small class="text-muted">
                <i class="mdi mdi-file-document-outline me-1"></i>
                총 <span x-text="pagination.total || 0"></span>개의 게시글
            </small>
        </div>
        <div class="d-flex gap-2">
            @if(auth()->user()?->hasPermissionTo($board->write_permission ?? 'act.login'))
                <a href="{{ route('board.form', $board->id) }}" class="btn btn-outline-primary rounded-pill">
                    <i class="mdi mdi-pencil me-1"></i>글쓰기
                </a>
            @endif
        </div>
    </div>

    <!-- 검색 및 필터 영역 -->
    <div class="bg-light rounded mb-4 form-custom">
        <div class="row g-3 d-flex justify-content-end">
            @if(!empty($board->categories))
                <div class="col-md-2" >
                    <x-forms.select
                        name="category"
                        model="filters.where.category"
                        :options="collect(json_decode($board->categories))->mapWithKeys(fn($cat) => [$cat => $cat])->toArray()"
                        placeholder="전체 카테고리"
                        @change="loadPage(1)"
                        no-margin
                        :errors="null"
                    />
                </div>
                <!-- whereBuilder 스토어 사용 예시 -->
                <div class="col-md-2" x-data="{ hitMin: '' }">
                    <input type="number"
                           x-model="hitMin"
                           @input="filters.where.hit = '>:' + $event.target.value"
                           class="form-control"
                           placeholder="최소 조회수">
                </div>

                <div class="col-md-8 d-flex justify-content-end">
            @else
                <div class="col-12 d-flex justify-content-end">
            @endif

                    <div class="rounded-search d-flex align-items-center px-3 gap-2">
                        <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="제목, 내용을 검색하세요" x-model="filters.search_text" @keyup.enter="loadPage(1)">
                        
                        <button @click="loadPage(1)" class="btn btn-icon text-dark">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    
                        <button @click="resetSearch()" class="btn btn-icon text-muted">
                            <i class="mdi mdi-refresh"></i>
                        </button>
                    </div>
                    
                </div>
        </div>
    </div>

    <!-- 로딩 상태 -->
    <x-loading />

    <!-- 게시글 목록 -->
    <div x-show="!loading">

        <!-- 데스크톱용 테이블 (md 이상) -->
        <div class="d-none d-md-block">
            <div class="">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="70" class="text-center">
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 sortable-btn"
                                            @click="sort('id')" :class="{ 'text-primary fw-bold': isSorted('id') }">
                                        번호 <i :class="`mdi ${$store.common.getSortIcon('id', filters.order_column, filters.order_direction)}`"></i>
                                    </button>
                                </th>
                                @if(!empty($board->categories))
                                    <th width="100">카테고리</th>
                                @endif
                                <th>
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 sortable-btn"
                                            @click="sort('title')" :class="{ 'text-primary fw-bold': isSorted('title') }">
                                        제목 <i :class="`mdi ${$store.common.getSortIcon('title', filters.order_column, filters.order_direction)}`"></i>
                                    </button>
                                </th>
                                <th width="100" class="text-center">
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 sortable-btn"
                                            @click="sort('user_id')" :class="{ 'text-primary fw-bold': isSorted('user_id') }">
                                        작성자 <i :class="`mdi ${$store.common.getSortIcon('user_id', filters.order_column, filters.order_direction)}`"></i>
                                    </button>
                                </th>
                                <th width="120" class="text-center">
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 sortable-btn"
                                            @click="sort('created_at')" :class="{ 'text-primary fw-bold': isSorted('created_at') }">
                                        작성일 <i :class="`mdi ${$store.common.getSortIcon('created_at', filters.order_column, filters.order_direction)}`"></i>
                                    </button>
                                </th>
                                <th width="70" class="text-center">
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 sortable-btn"
                                            @click="sort('hit')" :class="{ 'text-primary fw-bold': isSorted('hit') }">
                                        조회 <i :class="`mdi ${$store.common.getSortIcon('hit', filters.order_column, filters.order_direction)}`"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(article, index) in articles" :key="article.id">
                                <tr>
                                    <td class="text-center">
                                        <small class="text-muted" x-text="getArticleNumber(index)"></small>
                                    </td>
                                    @if(!empty($board->categories))
                                        <td>
                                            <span x-show="article.category"
                                                  x-text="article.category"
                                                  class="badge bg-primary bg-opacity-25 text-primary"></span>
                                        </td>
                                    @endif
                                    <td>
                                        <a :href="`/v2/board/{{ $board->id }}/view/${article.id}`"
                                           class="text-decoration-none text-dark">
                                            <span x-show="article.is_secret" class="text-warning me-1">
                                                <i class="mdi mdi-lock"></i>
                                            </span>
                                            <span x-text="article.title"></span>
                                            <span x-show="article.comments_count > 0"
                                                  class="badge bg-danger ms-1">
                                                <span x-text="article.comments_count"></span>
                                            </span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted" x-text="article.user?.name || '익명'"></small>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted" x-text="$store.common.formatDate(article.created_at)"></small>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted" x-text="article.hit || 0"></small>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 모바일용 카드 (md 미만) -->
        <div class="d-md-none">
            <!-- 모바일 정렬 버튼 -->
            <div class="btn-group w-100 mb-3" role="group">
                <button @click="sort('created_at')" type="button"
                        class="btn btn-outline-primary btn-sm"
                        :class="{ 'active': isSorted('created_at') }">
                    <i :class="`mdi ${$store.common.getSortIcon('created_at', filters.order_column, filters.order_direction)} me-1`"></i>작성일
                </button>
                <button @click="sort('hit')" type="button"
                        class="btn btn-outline-primary btn-sm"
                        :class="{ 'active': isSorted('hit') }">
                    <i :class="`mdi ${$store.common.getSortIcon('hit', filters.order_column, filters.order_direction)} me-1`"></i>조회수
                </button>
                <button @click="sort('title')" type="button"
                        class="btn btn-outline-primary btn-sm"
                        :class="{ 'active': isSorted('title') }">
                    <i :class="`mdi ${$store.common.getSortIcon('title', filters.order_column, filters.order_direction)} me-1`"></i>제목
                </button>
            </div>

            <!-- 모바일 아티클 리스트 -->
            <template x-for="(article, index) in articles" :key="article.id">
                <div class="card mb-2">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-2">#<span x-text="getArticleNumber(index)"></span></small>
                                <span x-show="article.category"
                                      x-text="article.category"
                                      class="badge bg-primary bg-opacity-25 text-primary me-2"></span>
                            </div>
                            <small class="text-muted" x-text="$store.common.formatDate(article.created_at)"></small>
                        </div>

                        <h6 class="mb-2">
                            <a :href="`/v2/board/{{ $board->id }}/view/${article.id}`"
                               class="text-decoration-none text-dark">
                                <span x-show="article.is_secret" class="text-warning me-1">
                                    <i class="mdi mdi-lock"></i>
                                </span>
                                <span x-text="article.title"></span>
                                <span x-show="article.comments_count > 0"
                                      class="badge bg-danger ms-1">
                                    <span x-text="article.comments_count"></span>
                                </span>
                            </a>
                        </h6>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="mdi mdi-account me-1"></i>
                                <span x-text="article.user?.name || '익명'"></span>
                            </small>
                            <small class="text-muted">
                                <i class="mdi mdi-eye me-1"></i>
                                <span x-text="article.hit || 0"></span>
                            </small>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- 빈 상태 -->
        <div x-show="articles.length === 0" class="text-center py-5">
            <div class="text-muted mb-3">
                <i class="mdi mdi-file-document-outline" style="font-size: 3rem;"></i>
            </div>
            <h6 class="text-muted mb-2">등록된 게시글이 없습니다</h6>
            <p class="text-muted mb-3">첫 번째 게시글을 작성해보세요!</p>
            @if(auth()->user()?->hasPermissionTo($board->write_permission ?? 'act.login'))
                <a href="{{ route('board.form', $board->id) }}" class="btn btn-primary">
                    <i class="mdi mdi-pencil me-1"></i>글쓰기
                </a>
            @endif
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

    <!-- 페이지 정보 -->
    <div x-show="!loading && pagination.last_page > 1" class="text-center mt-2 hidden">
        <small class="text-muted">
            <span x-text="pagination.current_page"></span> / <span x-text="pagination.last_page"></span> 페이지
        </small>
    </div>
</div>

@endsection
