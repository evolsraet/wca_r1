{{-- 공지사항 최근 5개 게시글 출력 --}}
<div class="notice-wrapper" x-data="notice">
    <div class="notice-header">
        <h5>공지사항</h5>
        <a href="{{ route('board.list', 'notice') }}" class="notice-all-link">전체보기 &gt;</a>
    </div>

    <ul class="notice-list">
        <template x-for="notice in notices" :key="notice.id">
            <li class="notice-item">
                <div class="notice-num" x-text="notice.id"></div>
                <div class="notice-datetime" x-text="notice.created_at"></div>
                <div class="notice-content">
                    <a :href="'/v2/board/notice/view/' + notice.id" x-text="notice.title"></a>
                </div>
            </li>
        </template>
    </ul>
</div>