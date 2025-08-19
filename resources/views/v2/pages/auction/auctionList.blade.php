@extends('v2.layouts.app')

@section('content')

@php
    $container = false;
@endphp

@include('components.layouts.categoryTab')


<div class="container mt-5 auction-content">
  <div class="row" x-data="auctionList()" x-init="init()">

    <!-- 로딩 상태 -->
    <x-loading />

    {{-- 필터 사이드바 --}}
    <div class="col-md-12 col-lg-3 mb-4" x-show="!loading">
      <div class="sider-content mov-info02">
        <div class="sub-side">

          @foreach($carMakers as $key => $value)
            <div class="mt-2">
              <div class="dropdown-section slide-gray my-1" @click="toggleDropdown('{{$key}}')">
                <h5 class="tc-primary line-heigh-0">{{$key == 'Y' ? '수입차' : '국산차'}}</h5>
                <span class="dropdown-icon">
                  <i class="mdi" :class="dropdownStates.{{$key}} ? 'mdi-chevron-up' : 'mdi-chevron-down'"></i>
                </span>
              </div>
              <div class="dropdown-content" :class="{ show: dropdownStates.{{$key}} }">
                @foreach($value as $item)
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="car_filter" id="{{$item['id']}}" value="maker_{{$item['id']}}" @click="handleCategorySelection('maker_{{$item['id']}}', $event)" />
                    <label class="form-check-label d-flex justify-content-between" for="{{$item['id']}}">
                      {{$item['name']}} <span class="text-secondary mx-2">{{number_format($item['count'])}}</span>
                    </label>
                  </div>
                  
                  {{-- 하위 카테고리: 동적 생성 --}}
                  <div class="sub-category" :class="{ show: subCategoryStates?.['maker_{{$item['id']}}'] || false }">
                    <div x-html="renderSubCategory('maker_{{$item['id']}}')"></div>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach

        </div>
      </div>
    </div>

    {{-- 메인 컨텐츠 영역 --}}
    <div class="col-md-12 col-lg-9 mb-5" x-show="!loading">

      {{-- 검색바 --}}
      <div class="search-bar">
        <input
            type="text"
            class="search-input"
            placeholder="모델명, 차량번호, 지역"
            x-model="form.search_text"
            @keyup.enter="handleSearch()"
        />
        <i class="mdi mdi-magnify search-icon clickable" @click="handleSearch()"></i>
      </div>

      {{-- 리스트 영역 --}}
      <div class="auction-list row mt-5">
        <template x-if="form.lists && form.lists.length > 0">
          <template x-for="auction in form.lists" :key="auction.id">

            <div class="col-lg-4 col-md-6 mb-4">
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