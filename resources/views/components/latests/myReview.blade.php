@php
    $reviews = [
        ['name' => '홍길동', 'content' => '처음에는 걱정했는데 너무 친절하게 잘 처리해주셨어요!', 'date' => '2025.05.28'],
        ['name' => '김철수', 'content' => '판매 과정이 빠르고 명확해서 좋았습니다.', 'date' => '2025.05.27'],
    ];
    $reviews = [];
@endphp

<div class="review-wrapper">
    <div class="review-header">
        <h5>나의 이용후기</h5>
        <a href="#" class="review-all-link">전체보기 &gt;</a>
    </div>

    @if (empty($reviews))
        <div class="review-empty-box">
            <a href="#" class="btn btn-dark">이용후기 등록 하기</a>
            <p class="text-muted mt-3">내 차 판매 후, 이용후기를 작성해보세요.</p>
        </div>
    @else
        <ul class="review-list">
            @foreach ($reviews as $review)
                <li class="review-item">
                    <div class="review-name">{{ $review['name'] }}</div>
                    <div class="review-content">{{ $review['content'] }}</div>
                    <div class="review-date">{{ $review['date'] }}</div>
                </li>
            @endforeach
        </ul>
    @endif
</div>