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

    <!-- 데스크톱용 테이블 (md 이상) -->
    <div class="d-none d-md-block" style="display:none !important">
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