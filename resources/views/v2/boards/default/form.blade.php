@extends('v2.layouts.app')

@section('content')
<script>
    window.boardConfig = {
        boardId: '{{ $board->id }}',
        boardName: '{{ $board->name ?? $board->id }}',
        articleId: '{{ $articleId }}'
    };
</script>

<div class="board-form board-skin-{{ $board->skin }}"
     x-data="articleForm">
     {{-- x-init="init('{{ $board->id }}', '{{ $articleId }}')" --}}

    <!-- 폼 헤더 -->
    <div class="form-header mb-4">
        <h2 x-text="isEdit ? '게시글 수정' : '게시글 작성'"></h2>
        <p class="text-muted">{{ $board->name ?? $board->id }}</p>
    </div>

    <!-- 로딩 상태 -->
    <div x-show="loading" class="text-center py-5">
        <i class="mdi mdi-loading mdi-spin fs-2"></i>
        <p class="mt-2">로딩 중...</p>
    </div>

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
                required
                :errors="true"
            />

            <!-- 내용 -->
            <x-forms.textarea
                name="article.content"
                label="내용"
                rows="15"
                placeholder="내용을 입력하세요"
                required
                :errors="true"
            />

            <!-- 첨부파일 -->
            @if(auth()->user()?->hasPermissionTo($board->attach_permission ?? 'act.admin'))
            @php
                $existingFiles = [
                    [
                        'uuid' => '123',
                        'file_name' => 'test.txt',
                        'original_url' => 'https://www.google.com'
                    ]
                ];
            @endphp
                <x-forms.fileUpload
                    label="첨부파일"
                    name="board_attach[]"
                    accept="*/*"
                    :errors="true"
                    button-text="파일 선택"
                    {{-- x-bind:existingFilesAlpine="files" --}}
                    {{-- :existingFiles="$existingFiles" --}}
                    {{-- existingFilesAlpine="files" --}}
                />
            @endif

            <!-- 에러 메시지 -->
            <div x-show="errors && Object.keys(errors).length > 0" class="alert alert-danger mb-3">
                <ul class="mb-0">
                    <template x-for="(error, field) in errors" :key="field">
                        <li x-text="error"></li>
                    </template>
                </ul>
            </div>

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
</div>
@endsection
