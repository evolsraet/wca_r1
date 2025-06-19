@extends('v2.layouts.app')

@section('content')

@php
    use App\Helpers\Board;
    // 작성가능한 차량리스트
    $auctionList = Board::getWriteableAuctionList(auth()->user()?->id, $board->id);
@endphp

<script>
    window.boardConfig = {
        boardId: '{{ $board->id }}',
        boardName: '{{ $board->name ?? $board->id }}'
    };

    const auctionList = {!! json_encode($auctionList) !!};
    console.log('작성가능한 차량리스트', auctionList);

</script>

<div class="board-list board-skin-{{ $board->skin }} testbox mt-4" x-data="articleList()">

    <!-- 게시판 헤더 -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">{{ $board->name }}</h4>
            <small class="text-muted">
                <i class="mdi mdi-file-document-outline me-1"></i>
                총 <span x-text="pagination.total || 0"></span>개의 게시글
            </small>
        </div>
        <div class="d-flex gap-2">
            @if(auth()->user()?->hasPermissionTo($board->write_permission ?? 'act.login'))
                {{-- <a href="{{ route('board.form', $board->id) }}" class="btn btn-outline-primary rounded-pill">
                    <i class="mdi mdi-pencil me-1"></i>글쓰기
                </a> --}}
                <button class="btn btn-outline-primary rounded-pill" 
                @click="Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/carInfoModalContent', {
                    id: 'carInfoModalContent',
                    title: '차량 정보',
                    showFooter: false,
                },{
                    content: {
                        cars: auctionList.data
                    }
                })"

                :disabled="loading"
                >
                    <i class="mdi mdi-car-info me-1"></i>등록하기
                </button>
            @endif
        </div>
    </div>

    <!-- 검색 및 필터 영역 -->
    <div class="bg-light rounded mb-4">
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
    @if($board->id == 'review')
        @include('v2.boards._share.list.galleryList')
    @else
        @include('v2.boards._share.list.basicList')
    @endif 


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
