@extends('v2.layouts.app')

@php
if(request()->query('id')) {
    $id = Hashids::decode(request()->query('id'));
    $id = $id[0] ?? 0;
} else {
    $id = 0;
}

$article = \App\Models\Article::select('id', 'extra2')->where('id', $articleId)->with('media')->first();
if ($article) {
    $existingFiles = $article->media->map(function ($file) {
        return [
            'uuid' => $file->uuid,
            'file_name' => $file->file_name,
            'original_url' => $file->original_url,
            'collection_name' => $file->collection_name,
        ];
    });

    $reviewData = json_decode($article->extra2 ?? '{}');
    $initialRating = $reviewData->rating ?? 0;
} else {
    $existingFiles = [];
    $initialRating = 0;
}

if($articleId) {
    $articleData = \App\Models\Article::select( 'extra1')->where('id', $articleId)->first();
    $auction = \App\Models\Auction::where('id', $articleData->extra1)->first();
    $isUser = auth()->user()->hasRole('user') ? 'user' : 'dealer';
    $bid = \App\Models\Bid::where('auction_id', $auction->id)->first();
    $dealerId = $bid?->user_id ?? 0;
} else {
    if($id) {
    $auction = \App\Models\Auction::where('id', $id)->first();
    $bid = \App\Models\Bid::where('auction_id', $auction->id)->first();
    $dealerId = $bid?->user_id ?? 0;
    $isUser = auth()->user()->hasRole('user') ? 'user' : 'dealer';
    }
}

switch($board->id) {
    case 'claim':
        $articleTitle = '클레임';
        break;

    case 'review':
        $articleTitle = '이용후기';
        break;
    default:
        $articleTitle = '게시판';
}

@endphp

@section('content')
<script>
    window.boardConfig = {
        boardId: '{{ $board->id }}',
        boardName: '{{ $board->name ?? $board->id }}',
        articleId: '{{ $articleId }}',
        extra1: '{{ $articleData->extra1 ?? $id }}'
    };
</script>

<div class="board-form board-skin-{{ $board->skin }}"
     x-data="articleForm()"
     x-init="
    setup('{{ $board->id }}', '{{ $articleId }}');
    setTimeout(() => form.article.extra1 = window.boardConfig?.extra1, 200);
    ">


    <!-- 로딩 상태 -->
    <x-loading />


    <div class="container pt-4">
        <x-layouts.split
            leftClass="col-lg-7"
            rightClass="col-lg-5"
            leftContainerClass=""
            rightContainerClass=""
            :initialRightPanelOpen="true">
    
            {{-- 왼쪽 컨텐츠 --}}
            <x-slot:leftContent>

                <!-- 폼 헤더 -->
                <div class="form-header mb-4">
                    <h4 class="fw-bold" x-text="isEdit ? '{{ $articleTitle }} 수정' : '{{ $articleTitle }} 작성'"></h4>
                    <p class="text-muted" style="display: none;">{{ $board->name ?? $board->id }}</p>
                </div>

                @if($articleId || $id)
                <div>
                    <x-auctions.auctionThumbnail :status="$auction->status" :auction="$auction" :isUser="$isUser" />
                    <x-auctions.auctionCarInfo :status="$auction->status" :auction="$auction" />
                </div>
                @endif

            </x-slot:leftContent>


            <x-slot:rightContent>


                <!-- 폼 -->
                <div x-show="!loading">
                    <form @submit.prevent="submit()">
                        <!-- 비밀글 설정 (맨 위로 이동) -->
                        @if($board->use_secret == 1)
                            <x-forms.checkbox
                                name="article.is_secret"
                                label="비밀글로 설정"
                                :errors="true"
                            />
                        @endif

                        <!-- 카테고리 선택 -->
                        @if(!empty($board->categories))
                            <x-forms.select
                                name="article.category"
                                label="카테고리"
                                :options="collect(json_decode($board->categories))->mapWithKeys(fn($cat) => [$cat => $cat])->toArray()"
                                placeholder="카테고리를 선택하세요"
                                :errors="true"
                            />
                        @endif

                        @if($board->id == 'review')
                        <div class="mb-4 text-center" x-data="{
                            dealerId: '{{ $dealerId ?? 0 }}',
                            rating: {{ $initialRating ?? 0 }},
                            labels: ['별로예요', '괜찮아요', '좋아요', '만족해요', '최고예요!'],
                            selectRating(value) {
                                this.rating = value;
                                const input = document.querySelector('input[name=\'article.extra2\']');
                                if (input) {
                                    input.value = JSON.stringify({ dealer_id: this.dealerId, rating: this.rating });
                                }
                            }
                        }">
                            <h5 class="fw-bold mb-3">거래는 어떠셨나요?</h5>
                            <div class="d-flex justify-content-center gap-3">
                                <template x-for="i in 5">
                                    <div class="text-center cursor-pointer" @click="selectRating(i)">
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
                            <input type="hidden" name="article.extra2" value="">
                        </div>
                        @endif

                        <x-forms.input
                            type="hidden"
                            name="article.extra1"
                            label=""
                            placeholder="매물 번호를 입력하세요"
                            {{-- required --}}
                            :errors="true"
                        />

                        <!-- 제목 -->
                        <x-forms.input
                            type="text"
                            name="article.title"
                            label=""
                            placeholder="제목을 입력하세요"
                            {{-- required --}}
                            :errors="true"
                        />

                        <!-- 내용 -->
                        <x-forms.textarea
                            name="article.content"
                            label=""
                            rows="15"
                            placeholder="내용을 입력하세요"
                            {{-- required --}}
                            :errors="true"
                        />

                        <!-- 첨부파일 -->
                        @if(auth()->user()?->hasPermissionTo($board->attach_permission ?? 'act.admin'))
                            <x-forms.fileUpload
                                label="첨부파일"
                                name="board_attach[]"
                                accept="*/*"
                                :errors="true"
                                button-text="파일 선택"
                                :existingFiles="$existingFiles"
                            />
                        @endif

                        <!-- 액션 버튼 -->
                        <div class="form-actions d-flex justify-content-between">
                            {{-- <button type="button" @click="goToList()" class="btn btn-outline-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i>취소
                            </button> --}}

                            <button type="submit"
                                    class="btn btn-primary rounded-pill w-100 border-0"
                                    :disabled="submitting">
                                <span x-show="submitting">
                                    <i class="mdi mdi-loading mdi-spin me-1"></i>
                                </span>
                                {{-- <i x-show="!submitting" class="mdi mdi-content-save me-1"></i> --}}
                                <span x-text="isEdit ? '수정' : '등록'"></span>
                            </button>
                        </div>


                        @if($article)
                        <div class="mt-4">
                            {{-- 댓글 --}}
                            <x-comments
                            commentable-type="Article"
                            commentable-id="{{ $articleId }}"
                            title="댓글" />
                        </div>
                        @endif
                        
                    </form>
                </div>
            

            </x-slot:rightContent>


        </x-layouts.split>
    </div>


</div>

@endsection
