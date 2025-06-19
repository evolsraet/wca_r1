@extends('v2.layouts.app')

@section('content')

@php
    $container = false;
@endphp

@include('components.layouts.categoryTab')

<div class="container mt-5">
  <div class="row" x-data="auctionList" x-init="init()">
    <div class="col-12 mb-5">

      {{-- 검색바 --}}
      <div class="search-bar">
        <i class="mdi mdi-magnify search-icon"></i>
        <input
            type="text"
            class="search-input"
            placeholder="모델명, 차량번호, 지역"
            x-model="form.search_text"
            @input="handleSearch()"
        />
      </div>

      {{-- 리스트 영역 --}}
      <div class="auction-list row mt-5">
        <template x-if="form.lists && form.lists.length > 0">
          <template x-for="auction in form.lists" :key="auction.id">

            <div class="col-md-4 mb-4">
              <a :href="`auction/${auction.hashid}`" class="auction-item" >
                <x-auctions.auctionItem />
              </a>
            </div>

          </template>
        </template>

        {{-- 비어있는 경우 --}}
        <template x-if="form.lists.length === 0">
          <div class="text-center py-5 text-muted w-100">등록된 매물이 없습니다.</div>
        </template>
      </div>

      <!-- 페이지네이션 -->
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
  </div>
</div>

@endsection