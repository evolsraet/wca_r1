@extends('v2.layouts.app')

@section('content')

@php
$articleData = \App\Models\Article::select( 'extra1', 'extra2')->where('id', $articleId)->first();
//dd($articleData->extra1);
$auction = \App\Models\Auction::where('id', $articleData->extra1)->first();
$isUser = auth()?->user()?->hasRole('user') ? 'user' : 'dealer';
$hashid = Hashids::encode($auction->id);
@endphp

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
    window.hashid = '{{ $hashid }}';

    // 경매 API 호출
    fetch(`/api/auctions/${window.hashid}?with=bids,reviews,likes&paginate=12`)
    .then(res => res.json())
    .then(response => {
        //console.log('auction', response);
        window.auction = response.data;
    });

    // 진단 API 호출
    fetch(`/api/diagRequest?diag_car_no={{ $auction->car_no }}`)
    .then(res => res.json())
    .then(response => {
        //console.log('diag', response);
        window.diag = response.data;
    });

</script>

<div class="board-view board-skin-{{ $board->skin }}"
     x-data="articleView"
     x-init="setup('{{ $board->id }}', '{{ $articleId }}')">

     <div x-data="{auction: null, diag: null}" x-init="
     (async () => {
        const auctionRes = await fetch(`/api/auctions/${window.hashid}?with=bids,reviews,likes&paginate=12`).then(r => r.json());
        const diagRes = await fetch(`/api/diagRequest?diag_car_no={{ $auction->car_no }}`).then(r => r.json());

        auction = auctionRes?.data ?? null;
        diag = diagRes?.data ?? null;
      })();
     ">

    <!-- 로딩 상태 -->
    <x-loading />


    <!-- 게시글 내용 -->
    <div x-show="!loading && article" x-cloak>

    <div class="container pt-4">
        <x-layouts.split
            leftClass="col-lg-7"
            rightClass="col-lg-5"
            leftContainerClass=""
            rightContainerClass=""
            :initialRightPanelOpen="true">
    
            {{-- 왼쪽 컨텐츠 --}}
            <x-slot:leftContent>

                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h4 class="review-title fw-bold">
                                <span x-show="article && article.is_secret" class="text-warning me-2">
                                    <i class="mdi mdi-lock"></i>
                                </span>
                                <span x-text="article && article.title"></span>
                            </h4>

                            <div class="review-meta text-muted small d-flex flex-wrap align-items-center gap-3 mt-2 mb-2">
                                <div>
                                    <i class="mdi mdi-account me-1"></i>
                                    <span x-text="(article && article.user && article.user.name) || '익명'"></span>
                                </div>
                                <div>
                                    <i class="mdi mdi-calendar me-1"></i>
                                    <span x-text="$store.common.formatDate(article && article.created_at)"></span>
                                </div>
                                <div>
                                    <i class="mdi mdi-eye me-1"></i>
                                    <span x-text="(article && article.hit) || 0"></span>
                                </div>
                            </div>
                        </div>

                        <!-- 액션 버튼 -->
                        <div class="btn-group-wrapper ms-3">
                            <div class="btn-group" role="group">
                                <button @click="goToList()" class="btn btn-outline-secondary btn-sm">
                                    <i class="mdi mdi-format-list-bulleted me-1"></i>
                                    <span class="d-none d-sm-inline">목록</span>
                                </button>

                                <template x-if="canEdit()">
                                    <a :href="`/v2/board/{{ $board->id }}/form/${article && article.id}`"
                                    class="btn btn-outline-danger btn-sm btn-soft-red">
                                        <i class="mdi mdi-pencil me-1"></i>
                                        <span class="d-none d-sm-inline">수정</span>
                                    </a>
                                </template>

                                <template x-if="canDelete()">
                                    <button @click="deleteArticle()"
                                            class="btn btn-outline-danger btn-sm btn-soft-red">
                                        <i class="mdi mdi-delete me-1"></i>
                                        <span class="d-none d-sm-inline">삭제</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                @if($auction?->status)
                    <div class="mb-3">
                        <x-auctions.auctionThumbnail :status="$auction->status" :auction="$auction" :isUser="$isUser" />
                        <x-auctions.auctionCarInfo :status="$auction->status" :auction="$auction" />
                    </div>
                @endif

            </x-slot:leftContent>

            {{-- 오른쪽 컨텐츠 --}}
            <x-slot:rightContent>


                <!-- 게시글 헤더 -->
                <div class="card mb-4">
                    

                    <!-- 게시글 본문 -->
                    <div class="card-body">
                        @if($board->id == 'review')
                        @php
                            $reviewData = json_decode($articleData->extra2 ?? '{}');
                            $initialRating = $reviewData->rating ?? 0;
                        @endphp

                        <div class="mb-4 text-center" x-data="{
                            rating: {{ $initialRating }},
                            labels: ['별로예요', '괜찮아요', '좋아요', '만족해요', '최고예요!']
                        }">

                            <div class="d-flex justify-content-center gap-3">
                                <template x-for="i in 5">
                                    <div class="text-center">
                                        <svg :class="rating >= i ? 'text-danger' : 'text-secondary'" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.63.283.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                        <div class="mt-1 text-sm" x-text="labels[i - 1]"></div>
                                    </div>
                                </template>
                            </div>

                            <template x-if="rating > 0">
                                <div class="mt-2 text-danger fw-bold" x-text="rating + '점'"></div>
                            </template>
                        </div>
                        @endif
                        <div class="article-content" x-html="(article && article.content) || ''"></div>
                    </div>

                    <!-- 첨부파일 -->
                    <div x-show="article?.files?.board_attach?.length > 0" class="card-footer bg-light">
                        <x-forms.fileList
                            label="첨부파일"
                            data-path="article.files.board_attach"
                            show-download="true"
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
                

            </x-slot:rightContent>

        </x-layouts.split>
    </div>

        
    </div>

    <!-- 에러 상태 -->
    <div x-show="!loading && !article" class="text-center py-5" x-cloak>
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
</div>


@endsection
