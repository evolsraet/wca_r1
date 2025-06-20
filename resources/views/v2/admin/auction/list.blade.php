@extends('v2.layouts.admin')

@section('title', '관리자 대시보드')

@section('content')
<div class="container table-style1" x-data="auctionList()">

    <div class="d-flex justify-content-between mb-3">
        <div>
            <span class="fw-bold">매물 관리</span><br>
            <small class="text-muted">매물의 상세 관리, 상태 수정이 가능합니다.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm">
                <option>전체</option>
                <option>진행중</option>
                <option>완료</option>
            </select>
            <input type="text" class="form-control form-control-sm" placeholder="매물 검색">
        </div>
    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>경매/공매</th>
                <th>매물번호</th>
                <th>고객코드</th>
                <th>차량번호</th>
                <th>차종</th>
                <th>등록년도</th>
                <th>입찰 종료일</th>
                <th>입찰 건수</th>
                <th>입찰 최고가</th>
                <th>상태</th>
                <th>비고</th>
            </tr>
        </thead>
        <tbody class="text-center">
            {{-- 반복 예시 --}}
            <template x-for="auction in form.lists" :key="auction.id">
            <tr>
                <td class="text-primary">경매</td>
                <td x-text="auction.id"></td>
                <td x-text="auction.hashid"></td>
                <td x-text="auction.car_no"></td>
                <td x-text="auction.car_model"></td>
                <td x-text="auction.car_year"></td>
                <td x-text="auction.final_at ?? '-'"></td>
                <td x-text="auction.bids.length ?? 0"></td>
                <td x-text="auction.max_bid_amount ?? 0"></td>
                <td>
                    <x-auctions.auctionStatusBadges />
                </td>
                <td><a :href="`/v2/admin/auction/form/${auction.hashid}`" class="text-primary">수정</a></td>
            </tr>
            </template>

            <template x-if="form.lists.length === 0">
                <div class="text-center py-5 text-muted w-100">등록된 매물이 없습니다.</div>
            </template>

        </tbody>
    </table>


    <div x-show="form.totalPages > 1" class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination custom-circle-pagination">

                <li class="page-item" :class="{ disabled: form.page === 1 }">
                    <a class="page-link" href="#" @click.prevent="changePage(form.page - 1)">
                    <i class="mdi mdi-chevron-left"></i>
                    </a>
                </li>

                <template x-for="page in form.totalPages" :key="page">
                    <li class="page-item" :class="{ active: form.page === page }">
                    <a class="page-link" href="#" @click.prevent="changePage(page)" x-text="page"></a>
                    </li>
                </template>

                <li class="page-item" :class="{ disabled: form.page === form.totalPages }">
                    <a class="page-link" href="#" @click.prevent="changePage(form.page + 1)">
                    <i class="mdi mdi-chevron-right"></i>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
    
</div>
@endsection
