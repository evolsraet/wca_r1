@php
    $notices = [
        ['id' => 1, 'datetime' => '2025-04-22 15:10:49', 'content' => 'Y박사는 해쑥한.'],
        ['id' => 2, 'datetime' => '2025-04-22 15:10:49', 'content' => 'B호텔에서 미스.'],
        ['id' => 3, 'datetime' => '2025-04-22 15:10:49', 'content' => 'F역에 내리기는.'],
        ['id' => 4, 'datetime' => '2025-04-22 15:10:49', 'content' => 'R의 말을 듣던.'],
        ['id' => 5, 'datetime' => '2025-04-22 15:10:49', 'content' => 'Y박사는 해쑥한.'],
    ];
@endphp

<div class="notice-wrapper">
    <div class="notice-header">
        <h5>공지사항</h5>
        <a href="#" class="notice-all-link">전체보기 &gt;</a>
    </div>

    <ul class="notice-list">
        @foreach ($notices as $notice)
            <li class="notice-item">
                <div class="notice-num">{{ $notice['id'] }}</div>
                <div class="notice-datetime">{{ $notice['datetime'] }}</div>
                <div class="notice-content">{{ $notice['content'] }}</div>
            </li>
        @endforeach
    </ul>
</div>