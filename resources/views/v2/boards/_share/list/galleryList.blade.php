<div x-show="!loading">

    <div class="row">
        <template x-for="(article, index) in articles" :key="article.id">
        <div class="col-md-3 mb-4">
            <a :href="`/v2/board/{{ $board->id }}/view/${article.id}`" class="auction-item" 
                x-data="(() => {
                    const src = article.auction;
                    return {
                        auction: src,
                    };
                })()"
                >
                <x-auctions.auctionItem :boardId="$board->id" :isReviewStar="true" />
            </a>
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