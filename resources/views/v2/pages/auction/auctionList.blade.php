@extends('v2.layouts.app')
@section('content')

@include('components.layouts.categoryTab')

<div class="container mt-4">

    <div class="row" x-data="auctionList">
    <div class="col-12">
    @include('components.layouts.searchBar')
        <div class="auction-list row mt-5">
            <template x-if="form.lists && form.lists.length > 0">
                <template x-for="auction in form.lists" :key="auction.id">
                    @include('components.auctions.auctionItem')
                </template>
            </template>
        </div>
    </div>
    </div>

</div>

@endsection