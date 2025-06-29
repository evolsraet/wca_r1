@extends('v2.layouts.app')

@php
$article = \App\Models\Article::select('id')->where('id', $articleId)->with('media')->first();
if ($article) {
    $existingFiles = $article->media->map(function ($file) {
        return [
            'uuid' => $file->uuid,
            'file_name' => $file->file_name,
            'original_url' => $file->original_url,
            'collection_name' => $file->collection_name,
        ];
    });
} else {
    $existingFiles = [];
}
@endphp

@section('content')
<script>
    window.boardConfig = {
        boardId: '{{ $board->id }}',
        boardName: '{{ $board->name ?? $board->id }}',
        articleId: '{{ $articleId }}'
    };
</script>

<div class="board-form board-skin-{{ $board->skin }} mt-4"
     x-data="articleForm()"
     x-init="setup('{{ $board->id }}', '{{ $articleId }}')">

    <!-- 폼 헤더 -->
    <div class="form-header mb-4">
        <h2 x-text="isEdit ? '{{ $board->name }} 수정' : '{{ $board->name }} 작성'"></h2>
        <p class="text-muted">{{ $board->name ?? $board->id }}</p>
    </div>

    <!-- 로딩 상태 -->
    <x-loading />

    <!-- 폼 -->
    <div x-show="!loading">
        <form @submit.prevent="submit()">
            <!-- 비밀글 설정 (맨 위로 이동) -->
            @if($board->use_secret == 1)
                <x-forms.checkbox
                    name="article.is_secret"
                    label="비밀글로 설정"
                    :errors="true"
                />
            @endif

            <!-- 카테고리 선택 -->
            @if(!empty($board->categories))
                <x-forms.select
                    name="article.category"
                    label="카테고리"
                    :options="collect(json_decode($board->categories))->mapWithKeys(fn($cat) => [$cat => $cat])->toArray()"
                    placeholder="카테고리를 선택하세요"
                    :errors="true"
                />
            @endif

            <!-- 제목 -->
            <x-forms.input
                type="text"
                name="article.title"
                label="제목"
                placeholder="제목을 입력하세요"
                {{-- required --}}
                :errors="true"
            />

            <!-- 내용 -->
            <x-forms.textarea
                name="article.content"
                label="내용"
                rows="15"
                placeholder="내용을 입력하세요"
                {{-- required --}}
                :errors="true"
            />

            <!-- 첨부파일 -->
            @if(auth()->user()?->hasPermissionTo($board->attach_permission ?? 'act.admin'))
                <x-forms.fileUpload
                    label="첨부파일"
                    name="board_attach[]"
                    accept="*/*"
                    :errors="true"
                    button-text="파일 선택"
                    :existingFiles="$existingFiles"
                />
            @endif

            <!-- 액션 버튼 -->
            <div class="form-actions d-flex justify-content-between">
                <button type="button" @click="goToList()" class="btn btn-outline-secondary">
                    <i class="mdi mdi-arrow-left me-1"></i>취소
                </button>

                <button type="submit"
                        class="btn btn-primary"
                        :disabled="submitting">
                    <span x-show="submitting">
                        <i class="mdi mdi-loading mdi-spin me-1"></i>
                    </span>
                    <i x-show="!submitting" class="mdi mdi-content-save me-1"></i>
                    <span x-text="isEdit ? '수정' : '등록'"></span>
                </button>
            </div>
        </form>
    </div>

    @if($article)
    <div class="mt-4">
        {{-- 댓글 --}}
        <x-comments
        commentable-type="Article"
        commentable-id="{{ $articleId }}"
        title="댓글" />
    </div>
    @endif

</div>
@endsection
