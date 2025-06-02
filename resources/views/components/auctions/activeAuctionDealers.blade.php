<div class="bid-status-box">

    {{-- 상단 상태 박스 --}}
    <div class="bg-primary text-white d-flex justify-content-between align-items-center px-3 py-3 rounded-top">
        <div class="fw-semibold">현재 0명이 입찰했어요.</div>
        <button class="btn btn-sm btn-outline-light text-white rounded-pill">경매취소</button>
    </div>

    {{-- 입찰한 딜러 안내 --}}
    <div class="bg-white p-3 border-bottom">
        <div class="fw-bold mb-1">입찰한 딜러</div>
        <div class="text-muted small">※ 입찰 금액이 가장 높은 순으로 5명까지 표시돼요.</div>
    </div>

    {{-- 딜러 리스트 --}}
    <div class="bg-light py-2 px-3">
        {{-- @foreach ($bidders as $bidder) --}}
        <div class="dealer-card bg-white rounded shadow-sm p-3 mb-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                {{-- 딜러 프로필 이미지 --}}
                <div class="dealer-avatar rounded-circle bg-light d-flex justify-content-center align-items-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-person fs-4 text-muted"></i>
                </div>
                {{-- 이름 및 금액 --}}
                <div>
                    <div class="fw-semibold">딜러명</div>
                    <div class="text-danger fw-bold">124 <span class="text-dark">만원</span></div>
                </div>
            </div>

            {{-- 평점 및 이동 아이콘 --}}
            <div class="text-end">
                <div class="text-muted small">
                    <i class="bi bi-star-fill me-1 text-warning"></i> 4.5점
                </div>
                <div><i class="bi bi-chevron-right text-muted"></i></div>
            </div>
        </div>
        {{-- @endforeach --}}
    </div>

</div>