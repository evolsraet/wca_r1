@props(['font' => false])

@if($font)
    <i class="mdi mdi-loading mdi-spin" x-show="loading" ></i>
@else
    <div x-show="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2 mb-0">불러오는 중...</p>
    </div>
@endif